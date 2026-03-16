<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AquaTrack</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-color: #0ea5e9; /* 60% - Sky Blue */
            --secondary-color: #f0f9ff; /* 40% - Light Blue */
            --accent-color: #dc2626; /* 10% - Red for alerts */
            --bs-primary: #0ea5e9;
            --bs-secondary: #64748b;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            padding: 2rem 3rem;
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .logo p {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .form-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .btn-google {
            background: #ffffff;
            border: 2px solid #d1d5db;
            color: #64748b;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #9ca3af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px -5px rgba(0, 0, 0, 0.1);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
            color: #64748b;
            font-size: 0.875rem;
        }

        .google-signin {
            margin-bottom: 1.5rem;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .logo h1 {
                font-size: 2rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                max-width: 100%;
                margin: 1rem auto;
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient">
    <div class="container-fluid px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="login-container card border-0 shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <!-- Logo Section -->
                        <div class="text-center mb-4">
                            <h1 class="display-5 fw-bold text-primary mb-1">AquaTrack</h1>
                        </div>

                        <!-- Form Title -->
                        <h2 class="h4 fw-semibold text-center text-primary mb-4">Sign In</h2>

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>{{ $errors->first() }}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('login') }}" method="POST" class="mb-3">
                            @csrf
                            
                            <!-- Station Selection -->
                            <div class="mb-3">
                                <label for="tenant_id" class="form-label fw-medium">Station</label>
                                <select name="tenant_id" id="tenant_id" class="form-select form-control-lg border-2" required>
                                    <option value="">Select your station...</option>
                                    @foreach(\App\Models\Tenant::where('is_active', true)->get() as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tenant_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Email Address</label>
                                <div class="input-group input-group-lg">
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control border-2" 
                                        placeholder="Enter your email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                    >
                                    <span class="input-group-text bg-transparent border-2 border-start-0 rounded-3">
                                        <i class="bi bi-envelope-fill text-muted"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="input-group input-group-lg">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        class="form-control border-2" 
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                    >
                                    <span class="input-group-text bg-transparent border-2 border-start-0">
                                        <i class="bi bi-lock-fill text-white"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sign In Button -->
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-1 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Sign In
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="divider">
                            <span>or</span>
                        </div>

                        <!-- Google Sign In -->
                        <div class="google-signin">
                            <button type="button" class="btn btn-google btn-lg w-100" onclick="handleGoogleSignIn()">
                                <svg class="me-2" width="18" height="18" viewBox="0 0 18 18">
                                    <path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z"/>
                                    <path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2.01a4.8 4.8 0 0 1-2.7.73c-2.09 0-3.86-1.4-4.49-3.28H1.83v2.07A8 8 0 0 0 8.98 17z"/>
                                    <path fill="#FBBC05" d="M4.49 10.52A4.77 4.77 0 0 1 4.24 9c0-.52.09-1.02.25-1.52V5.41H1.83A8 8 0 0 0 .98 9c0 1.3.31 2.52.85 3.59l2.66-2.07z"/>
                                    <path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 8.98 1 8 8 0 0 0 1.83 5.41L4.49 7.48C5.12 5.6 6.89 4.18 8.98 4.18z"/>
                                </svg>
                                Sign in with Google
                            </button>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mt-4">
                            <small class="text-muted">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none">Sign up</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted fs-6 mb-0 text-center">Water Refilling Station Management</p>
    </div>
    

    <script>
        function handleGoogleSignIn() {
            // Google OAuth implementation
            const googleAuthUrl = 'https://accounts.google.com/oauth/authorize?' + new URLSearchParams({
                client_id: 'YOUR_GOOGLE_CLIENT_ID', // Replace with your Google OAuth client ID
                redirect_uri: encodeURIComponent(window.location.origin + '/auth/google/callback'),
                response_type: 'code',
                scope: 'email profile openid',
                access_type: 'offline',
                prompt: 'consent'
            }).toString();
            
            // For demo purposes, show an alert
            alert('Google OAuth would open here: ' + googleAuthUrl);
            
            // In production, redirect to Google OAuth
            // window.location.href = googleAuthUrl;
        }

        // Add form validation and interactions
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const tenantSelect = document.getElementById('tenant_id');
            
            // Add input animations
            emailInput.addEventListener('focus', function() {
                this.style.borderColor = '#0ea5e9';
                this.style.boxShadow = '0 0 0 3px rgba(14, 165, 233, 0.1)';
            });
            
            emailInput.addEventListener('blur', function() {
                this.style.borderColor = '#e5e7eb';
                this.style.boxShadow = 'none';
            });
            
            passwordInput.addEventListener('focus', function() {
                this.style.borderColor = '#0ea5e9';
                this.style.boxShadow = '0 0 0 3px rgba(14, 165, 233, 0.1)';
            });
            
            passwordInput.addEventListener('blur', function() {
                this.style.borderColor = '#e5e7eb';
                this.style.boxShadow = 'none';
            });
            
            // Add password visibility toggle
            const passwordWrapper = passwordInput.parentElement;
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
            toggleBtn.style.cssText = `
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                font-size: 1.125rem;
                padding: 0.25rem;
            `;
            
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
                } else {
                    passwordInput.type = 'password';
                    toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
                }
            });
            
            passwordWrapper.appendChild(toggleBtn);
        });
    </script>
</body>
</html>
