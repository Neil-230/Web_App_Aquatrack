<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AquaTrack</title>
    
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

        .auth-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            padding: 2.5rem 3rem;
            max-width: 500px;
            width: 100%;
            margin: 2rem 0;
            transition: transform 0.3s ease;
        }

        .logo-container {
            margin-bottom: 2rem;
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
            margin-bottom: 1.5rem;
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
            padding: 0.8rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.2s ease;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-modern:hover {
            background: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2);
        }

        .btn-modern:active {
            transform: translateY(0);
        }

        .footer-text {
            color: #94a3b8;
            font-size: 0.8125rem;
            margin-top: 1.5rem;
            text-align: center;
        }

        .signin-text {
            font-size: 0.875rem;
            color: #64748b;
            text-align: center;
            margin-top: 1.5rem;
        }

        .signin-text a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .signin-text a:hover {
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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    
    <div class="auth-card">
        <!-- Logo Section -->
        <div class="logo-container text-center">
            <div class="logo-title">AquaTrack</div>
            <div class="welcome-text">Create your management account</div>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <!-- Register Form -->
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Station Selection -->
                <div class="col-12 mb-3">
                    <label for="tenant_id" class="form-label">Station</label>
                    <select name="tenant_id" id="tenant_id" class="form-select" required>
                        <option value="">Choose your station</option>
                        @foreach(\App\Models\Tenant::where('is_active', true)->get() as $tenant)
                            <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Full Name -->
                <div class="col-12 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group-modern">
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name') }}" placeholder="Full Name" required autocomplete="name">
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group-modern">
                        <input type="email" name="email" id="email" class="form-control" 
                               value="{{ old('email') }}" placeholder="email" required autocomplete="email">
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <div class="input-group-modern">
                        <input type="tel" name="phone" id="phone" class="form-control" 
                               value="{{ old('phone') }}" placeholder="phone" required>
                        <i class="bi bi-phone input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group-modern">
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="password" required>
                        <i class="bi bi-shield-lock input-icon"></i>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-4">
                    <label for="password_confirmation" class="form-label">Confirm</label>
                    <div class="input-group-modern">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" placeholder="confirm" required>
                        <i class="bi bi-shield-check input-icon"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-modern">
                <i class="bi bi-person-plus-fill"></i>
                Create Account
            </button>
        </form>

        <div class="signin-text">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>

        <div class="footer-text">
            Water Refilling Station Management v1.0
        </div>
    </div>

</body>
</html>
