<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COOP Ehub - Login</title>
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

        /* Mini Navbar */
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

        .login-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ss-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-logo i {
            font-size: 2rem;
        }

        .login-tabs {
            display: flex;
            gap: 1rem;
        }

        .login-tabs .tab-btn {
            padding: 0.5rem 1.5rem;
            border: none;
            background: transparent;
            color: var(--ss-text-primary);
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .login-tabs .tab-btn:hover {
            color: var(--ss-primary);
        }

        .login-tabs .tab-btn.active {
            color: var(--ss-primary);
            border-bottom-color: var(--ss-primary);
        }

        /* Main Container */
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .login-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            width: 100%;
            max-width: 1000px;
            align-items: center;
        }

        /* System Description */
        .system-description {
            color: white;
        }

        .system-description h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .system-description p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            line-height: 1.8;
            opacity: 0.95;
        }

        .feature-list {
            list-style: none;
            margin-top: 2rem;
        }

        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1rem;
        }

        .feature-list i {
            font-size: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login Forms */
        .login-forms {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .form-section h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--ss-primary);
        }

        .form-section .subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--ss-text-primary);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--ss-border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--ss-primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .form-group input::placeholder {
            color: #adb5bd;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .remember-forgot a {
            color: var(--ss-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .remember-forgot a:hover {
            color: var(--ss-primary-dark);
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 0.875rem 1rem;
            background: linear-gradient(135deg, var(--ss-primary) 0%, var(--ss-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: var(--ss-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: var(--ss-primary-dark);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            gap: 1rem;
            color: #adb5bd;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--ss-border-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }

            .system-description {
                display: none;
            }

            .login-forms {
                padding: 2rem;
            }

            .system-description h1 {
                font-size: 1.8rem;
            }

            .navbar-container {
                padding: 0 1rem;
            }

            .login-logo {
                font-size: 1.2rem;
            }

            .login-logo i {
                font-size: 1.5rem;
            }
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <!-- Mini Navbar -->
    <nav class="login-navbar">
        <div class="navbar-container">
            <div class="login-logo">
                <i class="fas fa-seedling"></i>
                <span>COOP Ehub</span>
            </div>
            <div class="login-tabs">
                <button class="tab-btn active" onclick="switchTab('admin')">
                    <i class="fas fa-shield-alt"></i> Admin Login
                </button>
                <button class="tab-btn" onclick="switchTab('member')">
                    <i class="fas fa-user"></i> Member Login
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="login-container">
        <div class="login-wrapper">
            <!-- System Description -->
            <div class="system-description">
                <h1>Loan, Members and Attendance Management System</h1>
                <p>Empowering communities through accessible and transparent cooperative lending.</p>
                
                <ul class="feature-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Transparent member management and loan tracking</span>
                    </li>
                    <li>
                        <i class="fas fa-chart-line"></i>
                        <span>Real-time financial reports and analytics</span>
                    </li>
                    <li>
                        <i class="fas fa-lock"></i>
                        <span>Secure audit logging and compliance tracking</span>
                    </li>
                    <li>
                        <i class="fas fa-calculator"></i>
                        <span>Automated loan calculations and SOA generation</span>
                    </li>
                    <li>
                        <i class="fas fa-users"></i>
                        <span>Multi-user support with role-based access</span>
                    </li>
                    <li>
                        <i class="fas fa-leaf"></i>
                        <span>Community-focused sustainable lending practices</span>
                    </li>
                </ul>
            </div>

            <!-- Login Forms -->
            <div class="login-forms">
                <!-- Admin Login Form -->
                <form class="form-section active" id="admin-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h2><i class="fas fa-shield-alt"></i> Admin Portal</h2>
                    <p class="subtitle">Manage members, loans, and system operations</p>

                    @if ($errors->any())
                        @if ($errors->first() === 'locked' && session('secondsRemaining') !== null)
                            <div class="error-message">
                                <strong><i class="fas fa-lock"></i> Account Locked!</strong>
                                <p style="margin: 0.5rem 0 0;">
                                    Your account is temporarily locked due to too many failed login attempts.
                                </p>
                                <div style="margin-top: 1rem; padding: 1rem; background: rgba(220, 53, 69, 0.1); border-radius: 8px; text-align: center;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Time remaining:</p>
                                    <div id="timer" style="font-size: 2rem; font-weight: 700; color: #dc3545; font-family: 'Courier New', monospace; margin: 0.5rem 0;">
                                        {{ floor(session('secondsRemaining') / 60) }}:{{ str_pad(session('secondsRemaining') % 60, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <p style="margin: 0; font-size: 0.85rem; color: #999;">Try again when the timer reaches 0:00</p>
                                </div>
                            </div>
                            <script>
                                let secondsRemaining = {{ session('secondsRemaining') }};
                                const timerDisplay = document.getElementById('timer');
                                
                                function updateTimer() {
                                    if (secondsRemaining <= 0) {
                                        timerDisplay.textContent = '0:00';
                                        document.querySelector('#admin-form button[type="submit"]').disabled = false;
                                        document.querySelector('#admin-form button[type="submit"]').style.opacity = '1';
                                        document.querySelector('#admin-form button[type="submit"]').textContent = 'Login Now';
                                        return;
                                    }
                                    
                                    const minutes = Math.floor(secondsRemaining / 60);
                                    const seconds = secondsRemaining % 60;
                                    timerDisplay.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                                    
                                    secondsRemaining--;
                                    setTimeout(updateTimer, 1000);
                                }
                                
                                updateTimer();
                                document.querySelector('#admin-form button[type="submit"]').disabled = true;
                                document.querySelector('#admin-form button[type="submit"]').style.opacity = '0.5';
                            </script>
                        @else
                            <div class="error-message">
                                <strong>Login Failed!</strong>
                                <ul style="margin-bottom: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    @if (session('success'))
                        <div class="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="admin-email">Email Address</label>
                        <input 
                            type="email" 
                            id="admin-email" 
                            name="email" 
                            placeholder="admin@sample.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input 
                            type="password" 
                            id="admin-password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="remember-forgot">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" name="remember" id="admin-remember">
                            Remember me
                        </label>
                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Sign In as Admin
                    </button>
                </form>

                <!-- Member Login Form -->
                <form class="form-section" id="member-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h2><i class="fas fa-user"></i> Member Portal</h2>
                    <p class="subtitle">Access your account and loan information</p>

                    @if ($errors->any())
                        @if ($errors->first() === 'locked' && session('secondsRemaining') !== null)
                            <div class="error-message">
                                <strong><i class="fas fa-lock"></i> Account Locked!</strong>
                                <p style="margin: 0.5rem 0 0;">
                                    Your account is temporarily locked due to too many failed login attempts.
                                </p>
                                <div style="margin-top: 1rem; padding: 1rem; background: rgba(220, 53, 69, 0.1); border-radius: 8px; text-align: center;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #666;">Time remaining:</p>
                                    <div id="timer-member" style="font-size: 2rem; font-weight: 700; color: #dc3545; font-family: 'Courier New', monospace; margin: 0.5rem 0;">
                                        {{ floor(session('secondsRemaining') / 60) }}:{{ str_pad(session('secondsRemaining') % 60, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <p style="margin: 0; font-size: 0.85rem; color: #999;">Try again when the timer reaches 0:00</p>
                                </div>
                            </div>
                            <script>
                                let secondsRemaining = {{ session('secondsRemaining') }};
                                const timerDisplayMember = document.getElementById('timer-member');
                                
                                function updateTimerMember() {
                                    if (secondsRemaining <= 0) {
                                        timerDisplayMember.textContent = '0:00';
                                        document.querySelector('#member-form button[type="submit"]').disabled = false;
                                        document.querySelector('#member-form button[type="submit"]').style.opacity = '1';
                                        document.querySelector('#member-form button[type="submit"]').textContent = 'Login Now';
                                        return;
                                    }
                                    
                                    const minutes = Math.floor(secondsRemaining / 60);
                                    const seconds = secondsRemaining % 60;
                                    timerDisplayMember.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                                    
                                    secondsRemaining--;
                                    setTimeout(updateTimerMember, 1000);
                                }
                                
                                updateTimerMember();
                                document.querySelector('#member-form button[type="submit"]').disabled = true;
                                document.querySelector('#member-form button[type="submit"]').style.opacity = '0.5';
                            </script>
                        @else
                            <div class="error-message">
                                <strong>Login Failed!</strong>
                                <ul style="margin-bottom: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    <div class="form-group">
                        <label for="member-email">Email Address or Member ID</label>
                        <input 
                            type="text" 
                            id="member-email" 
                            name="email" 
                            placeholder="john.smith@example.com or MBR-001"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="member-password">Password</label>
                        <input 
                            type="password" 
                            id="member-password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="remember-forgot">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" name="remember" id="member-remember">
                            Remember me
                        </label>
                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Sign In as Member
                    </button>

                    <div class="divider">or</div>

                    <div class="signup-link">
                        New member? <a href="{{ route('register') }}">Register here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all forms
            document.getElementById('admin-form').classList.remove('active');
            document.getElementById('member-form').classList.remove('active');

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected form
            if (tab === 'admin') {
                document.getElementById('admin-form').classList.add('active');
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
            } else if (tab === 'member') {
                document.getElementById('member-form').classList.add('active');
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
            }
        }
    </script>
</body>
</html>
