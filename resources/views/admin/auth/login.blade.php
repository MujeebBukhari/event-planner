<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — RK Vibes</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0f17;
            --bg-surface: #131b26;
            --border-subtle: #202d3e;
            --border-focus: #375fac;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --accent-primary: #017b45;
            --accent-primary-hover: #029d58;
            --accent-secondary: #375fac;
            --accent-cyan: #66cbe6;
            --accent-rose: #f43f5e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            font-weight: 800;
            font-size: 18px;
            margin-bottom: 16px;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .login-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background-color: rgba(11, 15, 23, 0.6);
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background-color: rgba(11, 15, 23, 0.9);
        }

        .form-input.is-invalid {
            border-color: var(--accent-rose);
        }

        .error-message {
            margin-top: 6px;
            font-size: 12px;
            color: #fb7185;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox {
            accent-color: var(--accent-primary);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--accent-cyan);
            text-decoration: none;
            transition: color 0.15s ease;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .alert-status {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            background-color: rgba(1, 123, 69, 0.15);
            border: 1px solid var(--accent-primary);
            color: var(--accent-cyan);
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: none;
            background: linear-gradient(135deg, #017b45, #375fac);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 14px rgba(1, 123, 69, 0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #029d58, #4875cc);
            box-shadow: 0 6px 18px rgba(55, 95, 172, 0.4);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.15s;
        }

        .login-footer a:hover {
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <a href="{{ route('home') }}" style="display:inline-block; margin-bottom: 16px;">
                <img src="{{ asset('assets/images/logo.png') }}" alt="RK Vibes" style="height: 52px; width: auto; object-fit: contain;">
            </a>
            <h1>Administrator Access</h1>
            <p>Sign in to access the RK Vibes management portal</p>
        </div>

        @if (session('status'))
            <div class="alert-status">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any() && ! $errors->has('email') && ! $errors->has('password'))
            <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background-color: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3); color: #fb7185; font-size: 13px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    class="form-input @error('email') is-invalid @enderror" 
                    required 
                    autofocus 
                    autocomplete="email"
                    placeholder="admin@example.com"
                >
                @error('email')
                    <div class="error-message">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') is-invalid @enderror" 
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <div class="error-message">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-options">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="remember-checkbox" {{ old('remember') ? 'checked' : '' }}>
                    <span>Keep me signed in</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-submit">
                Sign In to Admin
            </button>
        </form>

        <div class="login-footer">
            <a href="{{ route('home') }}">← Return to public website</a>
        </div>
    </div>
</body>
</html>
