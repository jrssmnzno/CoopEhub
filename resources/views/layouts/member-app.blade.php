<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COOP Ehub - Member</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .member-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .member-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .member-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .member-brand i {
            font-size: 1.5rem;
        }

        .member-logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .member-logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .member-logout-btn:active {
            transform: translateY(1px);
        }

        .member-content {
            margin-left: 0;
            padding-top: 0;
        }

        p {
            font-family: 'Inter', 'Poppins', sans-serif;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body style="background-color: #ffffff;">
    <!-- Beautiful Background Watermark -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: url('{{ asset('images/logo.png') }}'); background-repeat: no-repeat; background-size: 35%; background-position: center center; opacity: 0.12; pointer-events: none; z-index: 0;"></div>
    
    <!-- Member Header -->
    <header class="member-header" style="position: relative; z-index: 2; border-bottom: 2px solid rgba(255, 255, 255, 0.1);">
        <div class="member-header-content">
            <div class="member-brand">
                <img src="{{ asset('images/logo.png') }}" alt="COOP Ehub Logo" class="logo-img-header">
                <span style="font-family: 'Poppins', sans-serif; letter-spacing: -0.3px;">COOP Ehub Member</span>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <form method="POST" action="{{ route('logout') }}" class="d-flex m-0" id="logoutForm">
                    @csrf
                    <button type="button" class="member-logout-btn" id="logoutBtn" style="font-family: 'Poppins', sans-serif; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.3px; font-weight: 700;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal" id="logoutModal">
        <div class="logout-modal-dialog">
            <div class="logout-modal-content">
                <div class="logout-modal-header">
                    <h5 class="logout-modal-title">
                        <i class="fas fa-sign-out-alt"></i>
                        Confirm Logout
                    </h5>
                </div>
                <div class="logout-modal-body">
                    <p>Are you sure you want to logout?</p>
                    <p class="text-muted small">You will be redirected to the login page.</p>
                </div>
                <div class="logout-modal-footer">
                    <button type="button" class="btn btn-secondary" id="logoutCancelBtn">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="logoutConfirmBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="member-content flex-grow-1">
        <!-- Alerts -->
        @if($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-bottom">
            <p class="m-0">&copy; 2026 COOP Ehub Ranget Multipurpose Cooperative. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Logout confirmation modal
        const logoutBtn = document.getElementById('logoutBtn');
        const logoutForm = document.getElementById('logoutForm');
        const logoutModal = document.getElementById('logoutModal');
        const logoutCancelBtn = document.getElementById('logoutCancelBtn');
        const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
        
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.add('show');
            });
        }
        
        if (logoutCancelBtn) {
            logoutCancelBtn.addEventListener('click', () => {
                logoutModal.classList.remove('show');
            });
        }
        
        if (logoutConfirmBtn) {
            logoutConfirmBtn.addEventListener('click', () => {
                logoutForm.submit();
            });
        }
        
        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && logoutModal.classList.contains('show')) {
                logoutModal.classList.remove('show');
            }
        });
        
        // Close modal on outside click
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('show');
            }
        });
    </script>
    
    @yield('scripts')
    </div>
</body>
</html>
