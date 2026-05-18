<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validate incoming string request inputs
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Locate the specific record row inside your table
        $user = User::where('email', $request->email)->first();

        // 3. Match credentials directly against the bcrypt hash strings
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our database records.',
            ]);
        }

        // 4. SECURITY STATUS CHECK: Stop pending accounts from logging in
        if ($user->status === 'pending') {
            throw ValidationException::withMessages([
                'email' => 'Your instructor registration is pending institutional domain verification.',
            ]);
        }

        // 5. Establish secure authenticated session state tracking
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // 6. OVERRIDE REDIRECTION PIPELINE: Send them exactly to their role layout
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        // Default fallback route for students
        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}