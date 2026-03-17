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
    
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0ea5e9; 
            --secondary-color: #f0f9ff;
            --accent-color: #dc2626;
            --field-bg: #f8fafc;
            --field-border: #e2e8f0;
        }

        body {
            background-color: #fdfdfd;
            background-image: 
                radial-gradient(at 0% 0%, rgba(14, 165, 233, 0.05) 0, transparent 50%), 
                radial-gradient(at 50% 0%, rgba(14, 165, 233, 0.03) 0, transparent 50%), 
                radial-gradient(at 100% 0%, rgba(14, 165, 233, 0.05) 0, transparent 50%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, sans-serif;
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            padding: 3rem;
            max-width: 440px;
            width: 100%;
            transition: transform 0.3s ease;
        }

        .logo-container {
            margin-bottom: 2.5rem;
        }

        .logo-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            letter-spacing: -0.025em;
        }

        .welcome-text {
            color: #64748b;
            font-size: 0.935rem;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .form-control, .form-select {
            background-color: var(--field-bg);
            border: 1px solid var(--field-border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
            outline: none;
        }

        .input-group-modern {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            z-index: 10;
        }

        .btn-modern {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }

        .btn-modern:hover {
            background: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2);
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        .btn-google {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.935rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: #94a3b8;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f1f5f9;
        }

        .divider span {
            padding: 0 1rem;
        }

        .footer-text {
            color: #94a3b8;
            font-size: 0.8125rem;
            margin-top: 2rem;
            text-align: center;
        }

        .signup-text {
            font-size: 0.875rem;
            color: #64748b;
            text-align: center;
            margin-top: 1.5rem;
        }

        .signup-text a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        #togglePassword {
            cursor: pointer;
            pointer-events: all;
            border: none;
            background: none;
            padding: 0;
            outline: none;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    
    <div class="login-card">
        <!-- Logo Section -->
        <div class="logo-container text-center">
            <div class="logo-title">AquaTrack</div>
            <div class="welcome-text">Management System</div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <!-- Google Sign In -->
        <button type="button" class="btn-google" onclick="handleGoogleSignIn()">
            <svg width="20" height="20" viewBox="0 0 18 18">
                <path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z"/>
                <path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2.01a4.8 4.8 0 0 1-2.7.73c-2.09 0-3.86-1.4-4.49-3.28H1.83v2.07A8 8 0 0 0 8.98 17z"/>
                <path fill="#FBBC05" d="M4.49 10.52A4.77 4.77 0 0 1 4.24 9c0-.52.09-1.02.25-1.52V5.41H1.83A8 8 0 0 0 .98 9c0 1.3.31 2.52.85 3.59l2.66-2.07z"/>
                <path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 8.98 1 8 8 0 0 0 1.83 5.41L4.49 7.48C5.12 5.6 6.89 4.18 8.98 4.18z"/>
            </svg>
            Continue with Google
        </button>

        <div class="divider">
            <span>Or email</span>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <!-- Station Selection -->
            <div class="mb-3">
                <label for="tenant_id" class="form-label">Station</label>
                <select name="tenant_id" id="tenant_id" class="form-select" required>
                    <option value="">Choose station</option>
                    @foreach(\App\Models\Tenant::where('is_active', true)->get() as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group-modern">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="email"
                        value="{{ old('email') }}"
                        required
                    >
                    <i class="bi bi-envelope input-icon"></i>
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group-modern">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="password"
                        required
                    >
                    <button type="button" id="togglePassword" class="input-icon">
                        <i class="bi bi-eye" id="passwordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-modern">Sign In</button>
        </form>

        <div class="signup-text">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>

        <div class="footer-text">
            Water Refilling Station Management v1.0
        </div>
    </div>

    <script>
        function handleGoogleSignIn() {
            alert('Google authentication integration would be triggered here.');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const passwordIcon = document.getElementById('passwordIcon');

            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    passwordIcon.classList.remove('bi-eye');
                    passwordIcon.classList.add('bi-eye-slash');
                } else {
                    passwordIcon.classList.remove('bi-eye-slash');
                    passwordIcon.classList.add('bi-eye');
                }
            });
        });
    </script>
</body>
</html>
