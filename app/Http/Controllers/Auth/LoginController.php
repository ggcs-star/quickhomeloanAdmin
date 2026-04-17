<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login page
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // MongoDB + Laravel FIX (explicit web guard)
        if (Auth::guard('web')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {

            $user = Auth::guard('web')->user();

            // ❌ block inactive users
            if ($user->status !== 'active') {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Account inactive'
                ]);
            }

            // ✅ ADMIN → DASHBOARD
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }

            // ✅ NORMAL USER (future use)
            return redirect('/login');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
