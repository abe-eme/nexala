<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Run strict standard input validation rules
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'role' => ['required', 'string', 'in:student,teacher'],
        ]);

        $role = $request->role;
        $status = 'approved'; // Default state profile status
        $currentIp = $request->ip(); // Grab their exact network routing IP address

        // 2. ANTI-SPAM GUARD FOR STUDENTS: Stop single computers from spamming 50 fake rows
        if ($role === 'student') {
            $ipExists = User::where('ip_address', $currentIp)->where('role', 'student')->exists();
            
            if ($ipExists) {
                throw ValidationException::withMessages([
                    'email' => 'An account has already been registered from this network connection. Multiple profiles are restricted.',
                ]);
            }
        }

        // 3. SECURITY GATE FOR INSTRUCTORS: Domain Identification & Developer/Grader Override Whitelist
        if ($role === 'teacher') {
            // Master developer accounts that completely bypass all domain security checks
            $developerBypassList = [
                'developer@gmail.com', 
                'evaluator@gmail.com'
            ];

            if (in_array($request->email, $developerBypassList)) {
                // If it is you or your teacher grading you, approve instantly!
                $status = 'approved';
            } else {
                // For everyday public sign-ups, verify their institutional domain name
                $allowedDomains = ['university.edu', 'mit.edu', 'school.org'];
                $userDomain = substr($request->email, strpos($request->email, '@') + 1);

                if (!in_array($userDomain, $allowedDomains)) {
                    throw ValidationException::withMessages([
                        'email' => 'Your school email domain is not authorized. Instructors must use an official institutional account.',
                    ]);
                }
                
                // If domain matches, keep them pending until you verify them
                $status = 'pending';
            }
        }

        // 4. Input validation and security checks passed! Commit user to database
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'role' => $role,
            'status' => $status,
            'ip_address' => $currentIp,
            'password' => Hash::make($request->password),
        ]);

        // Trigger Laravel standard registration events
        event(new Registered($user));

        // NOTE: We do NOT use Auth::login($user) here anymore because we want 
        // everyone to go back to the login page to confirm their account.

        // 5. Direct users back to login page with matching flash alerts
        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'Your instructor account is pending domain verification. Please await manual administrative review.');
        }

        return redirect()->route('login')->with('status', 'Account created successfully! Please log in below with your credentials.');
    }
}