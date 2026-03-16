<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $request->get('tenant');
        
        if (!$tenant) {
            return redirect()->route('tenant.select');
        }

        // Get current user settings with defaults
        $user = Auth::user();
        $defaultSettings = [
            'auto_refresh' => true,
            'refresh_interval' => 30, // seconds
            'show_refresh_status' => false,
            'theme' => 'light',
            'notifications' => true
        ];
        
        $settings = array_merge($defaultSettings, session('user_settings', []));

        return view('settings.index', compact('tenant', 'user', 'settings'));
    }

    public function update(Request $request)
    {
        $tenant = $request->get('tenant');
        
        if (!$tenant) {
            return redirect()->route('tenant.select');
        }

        $validated = $request->validate([
            'auto_refresh' => 'boolean',
            'refresh_interval' => 'integer|in:15,30,60,300',
            'show_refresh_status' => 'boolean',
            'theme' => 'string|in:light,dark',
            'notifications' => 'boolean',
        ]);

        // Store settings in session (for now, later we'll use database)
        session(['user_settings' => $validated]);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $tenant = $request->get('tenant');
        
        if (!$tenant) {
            return redirect()->route('tenant.select');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $tenant = $request->get('tenant');
        
        if (!$tenant) {
            return redirect()->route('tenant.select');
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => bcrypt($validated['password'])
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Password updated successfully!');
    }
}
