<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showModernLoginForm()
    {
        return view('auth.login-modern');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function loginModern(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Find user with tenant
        $user = User::where('email', $credentials['email'])
            ->where('tenant_id', $credentials['tenant_id'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        // Check tenant subscription
        $tenant = Tenant::find($credentials['tenant_id']);
        if (!$tenant->is_active || !$tenant->isSubscriptionActive()) {
            return back()->withErrors([
                'tenant_id' => 'The selected tenant is not active or subscription has expired.',
            ]);
        }

        Auth::login($user);
        session(['tenant_id' => $tenant->id]);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear tenant session
        session()->forget('tenant_id');

        return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Check if user already exists for this tenant
        $existingUser = User::where('email', $request->email)
            ->where('tenant_id', $request->tenant_id)
            ->first();

        if ($existingUser) {
            return back()->withErrors([
                'email' => 'A user with this email already exists for this tenant.',
            ]);
        }

        $user = User::create([
            'tenant_id' => $request->tenant_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'staff', // Default role for new registrations
            'is_active' => true,
        ]);

        Auth::login($user);
        session(['tenant_id' => $request->tenant_id]);

        return redirect()->route('dashboard');
    }
}
