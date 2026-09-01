<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COOP Ehub - Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ss-primary: #0d6efd;
            --ss-primary-dark: #0b5ed7;
            --ss-success: #198754;
            --ss-light: #f8f9fa;
            --ss-text-primary: #212529;
            --ss-border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--ss-primary) 0%, var(--ss-primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--ss-text-primary);
        }

        .login-navbar {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 1rem 0;
            border-bottom: 2px solid var(--ss-border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-container {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .navbar-brand-text {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--ss-primary);
        }

        .navbar-link {
            color: var(--ss-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-link:hover {
            color: var(--ss-primary-dark);
        }

        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .auth-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            width: 100%;
            max-width: 500px;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title h1 {
            font-size: 1.8rem;
            color: var(--ss-primary);
            margin-bottom: 0.5rem;
        }

        .auth-title p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--ss-text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--ss-border-color);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--ss-primary);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--ss-primary);
            border-color: var(--ss-primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: var(--ss-primary-dark);
            border-color: var(--ss-primary-dark);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-link a {
            color: var(--ss-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .password-requirements {
            background-color: var(--ss-light);
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .password-requirements li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="login-navbar">
        <div class="navbar-container">
            <div class="navbar-brand-text">
                <i class="fas fa-credit-card"></i> COOP Ehub
            </div>
            <div>
                <a href="{{ route('login') }}" class="navbar-link">Back to Login</a>
            </div>
        </div>
    </nav>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-title">
                <h1>Reset Password</h1>
                <p>Enter your new password below to reset your account.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ request('email') ?? old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" name="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-lock"></i> Reset Password
                </button>
            </form>

            <div class="login-link">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
