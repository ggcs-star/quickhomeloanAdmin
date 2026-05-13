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

if (Auth::guard('web')->attempt([
    'email' => $request->email,
    'password' => $request->password,
])) {

    $user = Auth::guard('web')->user();

    if ($user->status !== 'active') {
        Auth::guard('web')->logout();

        return back()->withErrors([
            'email' => 'Account inactive'
        ]);
    }

    return redirect()->route('dashboard');
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
