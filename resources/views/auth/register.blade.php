<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COOP Ehub- Member Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ss-primary: #0d6efd;
            --ss-primary-dark: #0b5ed7;
            --ss-light: #f8f9fa;
            --ss-text-primary: #212529;
            --ss-border-color: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--ss-primary) 0%, var(--ss-primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 700px;
            width: 100%;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h1 {
            font-size: 2rem;
            color: var(--ss-primary);
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #6c757d;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--ss-text-primary);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--ss-border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--ss-primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        /* Member ID Validation Styles */
        .member-id-input {
            position: relative;
        }

        .member-verification {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 6px;
            display: none;
        }

        .member-verification.valid {
            display: block;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .member-verification.invalid {
            display: block;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Password Strength Meter */
        .password-strength-box {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 6px;
            background-color: var(--ss-light);
            border: 1px solid var(--ss-border-color);
        }

        .strength-meter {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            background-color: #dc3545;
        }

        .strength-bar.fair {
            background-color: #ffc107;
            width: 40%;
        }

        .strength-bar.good {
            background-color: #0dcaf0;
            width: 75%;
        }

        .strength-bar.strong {
            background-color: #198754;
            width: 100%;
        }

        .strength-text {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .strength-text.weak {
            color: #dc3545;
        }

        .strength-text.fair {
            color: #ffc107;
        }

        .strength-text.good {
            color: #0dcaf0;
        }

        .strength-text.strong {
            color: #198754;
        }

        .requirement {
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .requirement i {
            width: 16px;
            text-align: center;
        }

        .requirement.met {
            color: #198754;
        }

        .requirement.unmet {
            color: #6c757d;
        }

        .register-btn {
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
        }

        .register-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-link a {
            color: var(--ss-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }

        .loading-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(13, 110, 253, 0.3);
            border-top-color: var(--ss-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-seedling"></i> COOP Ehub</h1>
            <p>Member Registration</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <strong>Registration Error!</strong>
                <ul style="margin-bottom: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- Member ID Field -->
            <div class="form-group member-id-input">
                <label for="member_id">Member ID * <span id="memberIdStatus"></span></label>
                <input 
                    type="text" 
                    id="member_id" 
                    name="member_id" 
                    placeholder="MM-001"
                    value="{{ old('member_id') }}"
                    required
                    pattern="MM-\d{3}"
                >
                <small class="text-muted">Format: MM-XXX (e.g., MM-001)</small>
                @error('member_id')<small class="text-danger d-block mt-2">{{ $message }}</small>@enderror
                
                <div id="memberVerification" class="member-verification">
                    <div id="memberVerificationContent"></div>
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="john@example.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password * <span id="passwordIndicator"></span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter password (8-12 chars)"
                    required
                >
                <small class="text-muted">Must be 8-12 characters with uppercase, lowercase, numbers, and special characters</small>
                @error('password')<small class="text-danger d-block mt-2">{{ $message }}</small>@enderror

                <!-- Password Strength Meter -->
                <div id="passwordStrengthBox" class="password-strength-box" style="display: none;">
                    <div class="strength-meter">
                        <div id="strengthBar" class="strength-bar"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Weak</div>
                    
                    <div id="passwordRequirements">
                        <div class="requirement unmet" id="reqLength">
                            <i class="fas fa-circle"></i>
                            <span>8-12 characters</span>
                        </div>
                        <div class="requirement unmet" id="reqUppercase">
                            <i class="fas fa-circle"></i>
                            <span>Uppercase letter (A-Z)</span>
                        </div>
                        <div class="requirement unmet" id="reqLowercase">
                            <i class="fas fa-circle"></i>
                            <span>Lowercase letter (a-z)</span>
                        </div>
                        <div class="requirement unmet" id="reqNumbers">
                            <i class="fas fa-circle"></i>
                            <span>Number (0-9)</span>
                        </div>
                        <div class="requirement unmet" id="reqSpecial">
                            <i class="fas fa-circle"></i>
                            <span>Special character (!@#$%^&*)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Confirmation Field -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password *</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Confirm your password"
                    required
                >
                <small id="passwordMatchStatus" class="text-muted"></small>
            </div>

            <button type="submit" class="register-btn" id="submitBtn" disabled>
                <i class="fas fa-user-plus"></i> Register as Member
            </button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Back to Login</a>
        </div>
    </div>

    <script>
        const memberIdInput = document.getElementById('member_id');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');
        const memberVerification = document.getElementById('memberVerification');
        const memberVerificationContent = document.getElementById('memberVerificationContent');

        // Validate Member ID on input
        memberIdInput.addEventListener('blur', async function() {
            const memberId = this.value.trim();
            
            if (!memberId) {
                memberVerification.className = 'member-verification';
                return;
            }

            try {
                const response = await fetch('{{ route("validate-member-id") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ member_id: memberId })
                });

                const data = await response.json();

                if (data.valid) {
                    memberVerification.className = 'member-verification valid';
                    memberVerificationContent.innerHTML = `
                        <i class="fas fa-check-circle"></i> 
                        <strong>${data.member.name}</strong> - Member ID verified!
                    `;
                    emailInput.value = data.member.email;
                } else {
                    memberVerification.className = 'member-verification invalid';
                    memberVerificationContent.innerHTML = `
                        <i class="fas fa-times-circle"></i> 
                        <strong>Verification Failed:</strong> ${data.message}
                    `;
                }

                validateForm();
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Check password strength on input
        passwordInput.addEventListener('input', async function() {
            const password = this.value;

            if (!password) {
                document.getElementById('passwordStrengthBox').style.display = 'none';
                validateForm();
                return;
            }

            document.getElementById('passwordStrengthBox').style.display = 'block';

            try {
                const response = await fetch('{{ route("check-password-strength") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();

                // Update strength meter
                const bar = document.getElementById('strengthBar');
                bar.className = `strength-bar ${data.strength}`;
                
                // Update strength text
                const text = document.getElementById('strengthText');
                text.textContent = data.strength.charAt(0).toUpperCase() + data.strength.slice(1);
                text.className = `strength-text ${data.strength}`;

                // Update requirements
                const requirements = {
                    length: data.requirements.length,
                    uppercase: data.requirements.uppercase,
                    lowercase: data.requirements.lowercase,
                    numbers: data.requirements.numbers,
                    special: data.requirements.special,
                };

                const reqElements = {
                    'length': 'reqLength',
                    'uppercase': 'reqUppercase',
                    'lowercase': 'reqLowercase',
                    'numbers': 'reqNumbers',
                    'special': 'reqSpecial',
                };

                for (const [key, value] of Object.entries(requirements)) {
                    const element = document.getElementById(reqElements[key]);
                    if (value) {
                        element.classList.remove('unmet');
                        element.classList.add('met');
                        element.querySelector('i').className = 'fas fa-check-circle';
                    } else {
                        element.classList.remove('met');
                        element.classList.add('unmet');
                        element.querySelector('i').className = 'fas fa-circle';
                    }
                }

                validateForm();
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Check password confirmation
        passwordConfirmInput.addEventListener('input', function() {
            const status = document.getElementById('passwordMatchStatus');
            if (this.value && passwordInput.value) {
                if (passwordInput.value === this.value) {
                    status.innerHTML = '<i class="fas fa-check" style="color: #198754;"></i> Passwords match';
                    status.className = 'text-success';
                } else {
                    status.innerHTML = '<i class="fas fa-times" style="color: #dc3545;"></i> Passwords do not match';
                    status.className = 'text-danger';
                }
            } else {
                status.textContent = '';
            }
            validateForm();
        });

        // Validate entire form
        function validateForm() {
            const memberId = memberIdInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value;
            const passwordConfirm = passwordConfirmInput.value;

            const isValidMemberId = /^MM-\d{3}$/.test(memberId) && 
                                   memberVerification.classList.contains('valid');
            const isValidEmail = email && email.includes('@');
            const isValidPassword = password && /[A-Z]/.test(password) && 
                                   /[a-z]/.test(password) && 
                                   /[0-9]/.test(password) && 
                                   /[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/.test(password) &&
                                   password.length >= 8 && password.length <= 12;
            const isPasswordMatch = password && password === passwordConfirm;

            submitBtn.disabled = !(isValidMemberId && isValidEmail && isValidPassword && isPasswordMatch);
        }

        // Prevent form submission if validation fails
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                alert('Please complete all required fields with valid information.');
            }
        });
    </script>
</body>
</html>

