<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show login form or user dashboard if already logged in
    public function showLoginForm()
    {
        $user = Auth::user();

        if ($user) {
            // Redirect to appropriate dashboard based on user type
            if ($user->user_type === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return view('login', ['user' => $user]);
            }
        }

        // Show login form if not logged in
        return view('login');
    }

    // Handle the login process
    public function login(Request $request)
    {
        // Validate the login request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        // Attempt to log in the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect based on user type
            if ($user->user_type === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('login');
            }
        }

        // If login fails, redirect back with an error
        return redirect()->route('login')->withErrors(['Invalid credentials!']);
    }

    // Handle logout process
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
