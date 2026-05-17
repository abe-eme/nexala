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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],
            'role' => ['required', 'string', 'in:student,teacher'],
            'invitation_code' => ['nullable', 'string'],
        ]);

        $role = 'student';
        $codeUsed = null;

        if ($request->role === 'teacher') {
            $secretMasterKey = 'NEXALA-TEACH-2026'; // Master verification key
            
            if (empty($request->invitation_code) || $request->invitation_code !== $secretMasterKey) {
                throw ValidationException::withMessages([
                    'invitation_code' => 'The Teacher Activation Code you entered is invalid or expired.',
                ]);
            }
            
            $role = 'teacher';
            $codeUsed = $request->invitation_code;
        }

        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'role' => $role,
            'used_invitation_code' => $codeUsed,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Dynamic Role Routing Filter
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect(route('dashboard', absolute: false));
    }
}