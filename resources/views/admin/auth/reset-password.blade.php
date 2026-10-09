<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Reset Password — RK Vibes</title>
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
            max-width: 440px;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .login-header p {
            font-size: 13.5px;
            color: var(--text-muted);
            margin-top: 8px;
            line-height: 1.5;
        }

        .alert-error {
            margin-bottom: 24px;
            padding: 14px 16px;
            border-radius: 8px;
            background-color: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #fb7185;
            font-size: 13.5px;
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
            background-color: #0d121b;
            color: var(--text-primary);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .form-input:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(102, 203, 230, 0.25);
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
            gap: 6px;
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
            font-size: 12.5px;
        }

        .login-footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.15s;
        }

        .login-footer a:hover {
            color: var(--accent-cyan);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <a href="{{ route('home') }}" style="display:inline-block; margin-bottom: 16px;">
                <img src="{{ asset('assets/images/logo.png') }}" alt="RK Vibes" style="height: 52px; width: auto; object-fit: contain;">
            </a>
            <h1>Set New Password</h1>
            <p>Please enter your account email and choose a new secure password.</p>
        </div>

        @if ($errors->any() && ! $errors->has('email') && ! $errors->has('password'))
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $email) }}" 
                    class="form-input @error('email') is-invalid @enderror" 
                    required 
                    autofocus 
                    autocomplete="email"
                    placeholder="admin@rkvibes.com"
                >
                @error('email')
                    <div class="error-message">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') is-invalid @enderror" 
                    required 
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                >
                @error('password')
                    <div class="error-message">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input" 
                    required 
                    autocomplete="new-password"
                    placeholder="Re-enter new password"
                >
            </div>

            <button type="submit" class="btn-submit">
                Reset Password &amp; Continue
            </button>
        </form>

        <div class="login-footer">
            <a href="{{ route('admin.login') }}">← Back to Login</a>
        </div>
    </div>
</body>
</html>
