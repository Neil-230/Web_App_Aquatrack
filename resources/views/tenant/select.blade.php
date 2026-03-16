<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Tenant - AquaTrack</title>
    
    <style>
        :root {
            --primary-color: #0ea5e9; /* 60% - Sky Blue */
            --secondary-color: #f0f9ff; /* 40% - Light Blue */
            --accent-color: #dc2626; /* 10% - Red for alerts */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
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
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #1e293b;
        }

        .tenant-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tenant-card {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .tenant-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .tenant-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .tenant-address {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .tenant-plan {
            display: inline-block;
            background: var(--secondary-color);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #0284c7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>💧 AquaTrack</h1>
            <p>Water Refilling Station Management</p>
        </div>

        <h2 class="form-title">Select Your Station</h2>

        <form action="{{ route('tenant.set') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Choose your water refilling station:</label>
                <select name="tenant_id" class="form-select" required>
                    <option value="">Select a station...</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">
                            {{ $tenant->name }} - {{ $tenant->address }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Continue to Dashboard
            </button>
        </form>

        <div class="tenant-list" style="margin-top: 2rem;">
            @foreach($tenants as $tenant)
                <div class="tenant-card">
                    <div class="tenant-name">{{ $tenant->name }}</div>
                    <div class="tenant-address">{{ $tenant->address }}</div>
                    <div class="tenant-plan">{{ $tenant->subscriptionPlan->name }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
