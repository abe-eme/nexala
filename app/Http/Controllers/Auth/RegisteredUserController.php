<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Basic Inputs Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'role' => ['required', 'string', 'in:student,teacher'],
        ]);

        $role = $request->role;
        $status = 'approved';
        $currentIp = $request->ip();

        // 2. SECURITY GATE FOR STUDENTS: Stop creation of 50 accounts from 1 network
        if ($role === 'student') {
            $ipExists = User::where('ip_address', $currentIp)->where('role', 'student')->exists();
            if ($ipExists) {
                throw ValidationException::withMessages([
                    'email' => 'An account has already been registered from this network connection. Multiple profiles are restricted.',
                ]);
            }
        }

        // 3. SECURITY GATE FOR TEACHERS: Domain Verification & Dev Bypass
        if ($role === 'teacher') {
            // Master bypass emails for you and your grader
            $developerBypassList = [
                'developer@gmail.com', 
                'evaluator@gmail.com'
            ];

            if (in_array($request->email, $developerBypassList)) {
                // If it is you, bypass all gates instantly
                $status = 'approved';
            } else {
                // For external users, check if they own an institutional school domain
                $allowedDomains = ['university.edu', 'mit.edu', 'school.org'];
                $userDomain = substr($request->email, strpos($request->email, '@') + 1);

                if (!in_array($userDomain, $allowedDomains)) {
                    throw ValidationException::withMessages([
                        'email' => 'Your school email domain is not authorized. Instructors must use an official institutional account.',
                    ]);
                }
                
                // If the domain is valid, place them in pending until they pass an OTP check
                $status = 'pending';
            }
        }

        // 4. Everything is safe! Create the user in the database
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'role' => $role,
            'status' => $status,
            'ip_address' => $currentIp,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // If a real teacher is pending, don't log them in yet, send them to login with a warning
        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'A verification code has been dispatched to your school inbox. Please verify your account.');
        }

        Auth::login($user);

        // Redirect based on roles
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect(route('dashboard', absolute: false));
    }
}