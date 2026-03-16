@extends('layouts.app')

@section('title', 'Settings - AquaTrack')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h1 style="font-size: 2.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                ⚙️ Settings
            </h1>
            <p style="color: var(--text-secondary); font-size: 1rem;">
                Manage your account and application preferences.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 0.625rem 1.25rem; border-radius: var(--radius); font-weight: 600; box-shadow: var(--shadow-sm);">
                🏢 {{ $tenant->name }}
            </span>
        </div>
    </div>

    <!-- Settings Navigation -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
        <button onclick="showTab('dashboard')" id="tab-dashboard" class="settings-tab active">
            📊 Dashboard
        </button>
        <button onclick="showTab('profile')" id="tab-profile" class="settings-tab">
            👤 Profile
        </button>
        <button onclick="showTab('security')" id="tab-security" class="settings-tab">
            🔒 Security
        </button>
        <button onclick="showTab('station')" id="tab-station" class="settings-tab">
            🏢 Station
        </button>
    </div>

    <!-- Dashboard Settings -->
    <div id="dashboard-settings" class="settings-section">
        <div class="card">
            <div class="card-header">
                <span>📊</span>
                <span>Dashboard Preferences</span>
            </div>
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <div style="space-y: 2rem;">
                    <!-- Auto Refresh Toggle -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: var(--secondary-color); border-radius: var(--radius);">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">
                                Auto Refresh
                            </h3>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                                Automatically update dashboard data without manual refresh
                            </p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="auto_refresh" value="1" {{ $settings['auto_refresh'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <!-- Refresh Interval -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: var(--secondary-color); border-radius: var(--radius);">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">
                                Refresh Interval
                            </h3>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                                How often to refresh dashboard data
                            </p>
                        </div>
                        <select name="refresh_interval" class="form-select" style="width: auto;">
                            <option value="15" {{ $settings['refresh_interval'] == 15 ? 'selected' : '' }}>15 seconds</option>
                            <option value="30" {{ $settings['refresh_interval'] == 30 ? 'selected' : '' }}>30 seconds</option>
                            <option value="60" {{ $settings['refresh_interval'] == 60 ? 'selected' : '' }}>1 minute</option>
                            <option value="300" {{ $settings['refresh_interval'] == 300 ? 'selected' : '' }}>5 minutes</option>
                        </select>
                    </div>

                    <!-- Show Refresh Status -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: var(--secondary-color); border-radius: var(--radius);">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">
                                Show Refresh Status
                            </h3>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                                Display auto-refresh indicator in dashboard header
                            </p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_refresh_status" value="1" {{ $settings['show_refresh_status'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <!-- Theme Settings -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; background: var(--secondary-color); border-radius: var(--radius);">
                        <div>
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">
                                Theme
                            </h3>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">
                                Choose your preferred color theme
                            </p>
                        </div>
                        <select name="theme" class="form-select" style="width: auto;">
                            <option value="light" {{ $settings['theme'] == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ $settings['theme'] == 'dark' ? 'selected' : '' }}>Dark (Coming Soon)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span>
                        <span>Save Dashboard Settings</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Settings -->
    <div id="profile-settings" class="settings-section" style="display: none;">
        <div class="card">
            <div class="card-header">
                <span>👤</span>
                <span>Profile Information</span>
            </div>
            <form method="POST" action="{{ route('settings.profile.update') }}">
                @csrf
                <div style="space-y: 1.5rem;">
                    <div>
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                    </div>
                    
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" value="{{ $user->email }}" required>
                    </div>
                    
                    <div>
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" readonly style="background: var(--secondary-color);">
                    </div>
                    
                    <div>
                        <label class="form-label">Station</label>
                        <input type="text" class="form-input" value="{{ $tenant->name }}" readonly style="background: var(--secondary-color);">
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span>
                        <span>Update Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings -->
    <div id="security-settings" class="settings-section" style="display: none;">
        <div class="card">
            <div class="card-header">
                <span>🔒</span>
                <span>Security Settings</span>
            </div>
            <form method="POST" action="{{ route('settings.password.update') }}">
                @csrf
                <div style="space-y: 1.5rem;">
                    <div>
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-input" required>
                    </div>
                    
                    <div>
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required minlength="8">
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
                            Must be at least 8 characters long
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <span>🔐</span>
                        <span>Change Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Station Settings -->
    <div id="station-settings" class="settings-section" style="display: none;">
        <div class="card">
            <div class="card-header">
                <span>🏢</span>
                <span>Station Information</span>
            </div>
            <div style="space-y: 1.5rem;">
                <div>
                    <label class="form-label">Station Name</label>
                    <input type="text" class="form-input" value="{{ $tenant->name }}" readonly style="background: var(--secondary-color);">
                </div>
                
                <div>
                    <label class="form-label">Subscription Plan</label>
                    <input type="text" class="form-input" value="{{ $tenant->subscriptionPlan->name }}" readonly style="background: var(--secondary-color);">
                </div>
                
                <div>
                    <label class="form-label">Station Address</label>
                    <input type="text" class="form-input" value="{{ $tenant->address ?? 'Not set' }}" readonly style="background: var(--secondary-color);">
                </div>
                
                <div>
                    <label class="form-label">Contact Phone</label>
                    <input type="text" class="form-input" value="{{ $tenant->phone ?? 'Not set' }}" readonly style="background: var(--secondary-color);">
                </div>
                
                <div>
                    <label class="form-label">Contact Email</label>
                    <input type="email" class="form-input" value="{{ $tenant->email ?? 'Not set' }}" readonly style="background: var(--secondary-color);">
                </div>
                
                <div style="padding: 1rem; background: linear-gradient(135deg, #fef2f2, #fee2e2); border-radius: var(--radius); border-left: 4px solid var(--accent-color);">
                    <div style="font-weight: 600; color: var(--accent-color); margin-bottom: 0.5rem;">
                        📝 Note
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                        Station information can only be modified by the system administrator. Contact support if you need to update these details.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-tab {
    padding: 0.75rem 1.5rem;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--text-secondary);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    border-radius: var(--radius) var(--radius) 0 0;
}

.settings-tab:hover {
    color: var(--primary-color);
    background: var(--secondary-color);
}

.settings-tab.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: var(--secondary-color);
}

.settings-section {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ccc;
    transition: 0.4s;
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background: var(--primary-color);
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
</style>

<script>
function showTab(tabName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(tabName + '-settings').style.display = 'block';
    
    // Add active class to selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
}
</script>
@endsection
