<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Check if username or email is provided
        $loginField = $request->input('username') ?? $request->input('email');
        $fieldType = $request->has('username') ? 'username' : 'email';
        
        $credentials = [
            $fieldType => $loginField,
            'password' => $request->input('password')
        ];

        $request->validate([
            $fieldType => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Login successful! Welcome back.');
        }

        return back()->withErrors([
            $fieldType => 'The provided credentials do not match our records.',
        ])->withInput($request->only($fieldType));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('kiosk.map')->with('success', 'You have been logged out successfully.');
    }
}
