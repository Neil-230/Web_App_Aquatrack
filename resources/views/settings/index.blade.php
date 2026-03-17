@extends('layouts.app')

@section('title', 'Settings - AquaTrack')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Modern Header -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="fw-bold tracking-tight text-slate-800" style="font-size: 2.5rem; letter-spacing: -0.025em; margin-bottom: 0.25rem;">
                Settings
            </h1>
            <p class="text-muted mb-0" style="font-size: 1.1rem; font-weight: 400;">
                Personalize your <span class="text-primary fw-medium">experience and account</span>
            </p>
        </div>
        <div class="text-end d-none d-md-block">
            <div class="badge rounded-pill px-3 py-2" style="background: rgba(14, 165, 233, 0.08); color: var(--primary-color); border: 1px solid rgba(14, 165, 233, 0.15); font-weight: 600; font-size: 0.875rem;">
                <i class="bi bi-shield-check me-1"></i> Secure Session
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Modern Sidebar Navigation -->
        <div class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm p-3 sticky-top" style="border-radius: 24px; top: 100px; z-index: 10;">
                <div class="nav flex-column gap-2">
                    <button onclick="showTab('dashboard')" id="tab-dashboard" class="settings-nav-btn active">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </button>
                    <button onclick="showTab('profile')" id="tab-profile" class="settings-nav-btn">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                    </button>
                    <button onclick="showTab('security')" id="tab-security" class="settings-nav-btn">
                        <i class="bi bi-shield-lock"></i>
                        <span>Security</span>
                    </button>
                    <button onclick="showTab('station')" id="tab-station" class="settings-nav-btn">
                        <i class="bi bi-building"></i>
                        <span>Station</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Settings Content Areas -->
        <div class="col-12 col-lg-9">
            <!-- Dashboard Settings -->
            <div id="dashboard-settings" class="settings-section">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                    <div class="mb-4">
                        <h3 class="fw-bold text-slate-800 h4 mb-1">Dashboard Preferences</h3>
                        <p class="text-slate-500 small">Configure how your dashboard behaves and looks.</p>
                    </div>

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        <div class="d-flex flex-column gap-4">
                            <!-- Toggle Item -->
                            <div class="settings-item d-flex justify-content-between align-items-center p-4 rounded-4 bg-slate-50">
                                <div>
                                    <div class="fw-bold text-slate-800 mb-1">Auto Refresh</div>
                                    <div class="text-slate-500 small">Automatically update dashboard data</div>
                                </div>
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_refresh" value="1" {{ $settings['auto_refresh'] ? 'checked' : '' }} style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                </div>
                            </div>

                            <div class="settings-item d-flex justify-content-between align-items-center p-4 rounded-4 bg-slate-50">
                                <div>
                                    <div class="fw-bold text-slate-800 mb-1">Refresh Interval</div>
                                    <div class="text-slate-500 small">How often data updates occur</div>
                                </div>
                                <select name="refresh_interval" class="form-select border-0 bg-white shadow-sm px-4 py-2" style="border-radius: 12px; width: auto; font-weight: 600;">
                                    <option value="15" {{ $settings['refresh_interval'] == 15 ? 'selected' : '' }}>15s</option>
                                    <option value="30" {{ $settings['refresh_interval'] == 30 ? 'selected' : '' }}>30s</option>
                                    <option value="60" {{ $settings['refresh_interval'] == 60 ? 'selected' : '' }}>1m</option>
                                    <option value="300" {{ $settings['refresh_interval'] == 300 ? 'selected' : '' }}>5m</option>
                                </select>
                            </div>

                            <div class="settings-item d-flex justify-content-between align-items-center p-4 rounded-4 bg-slate-50">
                                <div>
                                    <div class="fw-bold text-slate-800 mb-1">Status Indicator</div>
                                    <div class="text-slate-500 small">Show refresh status on dashboard</div>
                                </div>
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="show_refresh_status" value="1" {{ $settings['show_refresh_status'] ? 'checked' : '' }} style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold" style="border-radius: 16px;">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profile Settings -->
            <div id="profile-settings" class="settings-section" style="display: none;">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                    <div class="mb-4">
                        <h3 class="fw-bold text-slate-800 h4 mb-1">Profile Information</h3>
                        <p class="text-slate-500 small">Update your personal account details.</p>
                    </div>

                    <form method="POST" action="{{ route('settings.profile.update') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="label-modern">Full Name</label>
                                <input type="text" name="name" class="form-control input-modern" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-modern">Email Address</label>
                                <input type="email" name="email" class="form-control input-modern" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-modern">Role</label>
                                <input type="text" class="form-control input-modern bg-slate-100 border-0" value="{{ ucfirst($user->role) }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="label-modern">Assigned Station</label>
                                <input type="text" class="form-control input-modern bg-slate-100 border-0" value="{{ $tenant->name }}" readonly>
                            </div>
                        </div>

                        <div class="mt-5 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold" style="border-radius: 16px;">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div id="security-settings" class="settings-section" style="display: none;">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                    <div class="mb-4">
                        <h3 class="fw-bold text-slate-800 h4 mb-1">Security & Access</h3>
                        <p class="text-slate-500 small">Keep your account secure by rotating your password.</p>
                    </div>

                    <form method="POST" action="{{ route('settings.password.update') }}">
                        @csrf
                        <div class="d-flex flex-column gap-4" style="max-width: 500px;">
                            <div>
                                <label class="label-modern">Current Password</label>
                                <input type="password" name="current_password" class="form-control input-modern" required>
                            </div>
                            <div>
                                <label class="label-modern">New Password</label>
                                <input type="password" name="password" class="form-control input-modern" required minlength="8">
                                <p class="text-slate-400 small mt-2">Minimum 8 characters with letters & numbers.</p>
                            </div>
                            <div>
                                <label class="label-modern">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control input-modern" required>
                            </div>
                        </div>

                        <div class="mt-5 text-end">
                            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold" style="border-radius: 16px;">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Station Settings -->
            <div id="station-settings" class="settings-section" style="display: none;">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 24px;">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold text-slate-800 h4 mb-1">Station Details</h3>
                            <p class="text-slate-500 small">Official information for your refilling station.</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            {{ strtoupper($tenant->subscriptionPlan->name) }} PLAN
                        </span>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="label-modern">Station Code/Name</label>
                            <div class="p-3 bg-slate-50 rounded-3 text-slate-700 fw-medium border">{{ $tenant->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="label-modern">Contact Phone</label>
                            <div class="p-3 bg-slate-50 rounded-3 text-slate-700 fw-medium border">{{ $tenant->phone ?? 'Not set' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="label-modern">Registered Address</label>
                            <div class="p-3 bg-slate-50 rounded-3 text-slate-700 fw-medium border">{{ $tenant->address ?? 'Not set' }}</div>
                        </div>
                    </div>

                    <div class="p-4 rounded-4 d-flex gap-3 align-items-start" style="background: #fffbeb; border: 1px solid #fde68a;">
                        <i class="bi bi-info-circle-fill text-warning" style="font-size: 1.25rem;"></i>
                        <div>
                            <div class="fw-bold text-slate-800 mb-1">Administrative Note</div>
                            <p class="text-slate-600 small mb-0">Some station details are locked for administrative purposes. To make changes to your legal business profile, please contact support.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-nav-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        background: transparent;
        border: none;
        border-radius: 14px;
        color: #64748b;
        font-weight: 600;
        transition: all 0.2s ease;
        text-align: left;
    }

    .settings-nav-btn i {
        font-size: 1.2rem;
    }

    .settings-nav-btn:hover {
        background: #f8fafc;
        color: var(--primary-color);
    }

    .settings-nav-btn.active {
        background: #f0f9ff;
        color: var(--primary-color);
    }

    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .text-slate-500 { color: #64748b; }
    .text-slate-400 { color: #94a3b8; }
    
    .label-modern {
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.6rem;
        display: block;
    }

    .input-modern {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .input-modern:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
    }

    .bg-primary-subtle { background-color: #f0f9ff; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .settings-section {
        animation: fadeIn 0.4s ease-out;
    }
</style>

<script>
function showTab(tabName) {
    document.querySelectorAll('.settings-section').forEach(section => section.style.display = 'none');
    document.querySelectorAll('.settings-nav-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabName + '-settings').style.display = 'block';
    document.getElementById('tab-' + tabName).classList.add('active');

    // Smooth scroll to top of content on mobile
    if (window.innerWidth < 992) {
        window.scrollTo({
            top: document.getElementById(tabName + '-settings').offsetTop - 100,
            behavior: 'smooth'
        });
    }
}
</script>
@endsection
