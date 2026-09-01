<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COOP Ehub </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #ffffff;">
    <!-- Beautiful Background Watermark -->
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: url('{{ asset('images/logo.png') }}'); background-repeat: no-repeat; background-size: 35%; background-position: center center; opacity: 0.12; pointer-events: none; z-index: 0;"></div>
    
    <div class="d-flex app-container" style="position: relative; z-index: 1;">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav" id="sidebarNav">
            <div class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="COOP Ehub Logo" class="logo-img">
                <span class="brand-text">COOP Ehub</span>
            </div>
            <div class="nav flex-column nav-pills">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                @if(Auth::user()->isAdmin())
                <a class="nav-link {{ Route::is('members*') ? 'active' : '' }}" 
                   href="{{ route('members.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Members</span>
                </a>
                <a class="nav-link {{ Route::is('admin.loan-requests*') ? 'active' : '' }}" 
                   href="/admin/loan-requests">
                    <i class="fas fa-file-contract"></i>
                    <span>Loan Requests</span>
                </a>
                <a class="nav-link {{ Route::is('receipt-log*') ? 'active' : '' }}" 
                   href="{{ route('receipt-log.index') }}">
                    <i class="fas fa-receipt"></i>
                    <span>Receipt Log</span>
                </a>
                <a class="nav-link {{ Route::is('loan-portfolio*') ? 'active' : '' }}" 
                   href="{{ route('loan-portfolio.index') }}">
                    <i class="fas fa-book"></i>
                    <span>Loan Portfolio</span>
                </a>
                <a class="nav-link {{ Route::is('audit-ledger*') ? 'active' : '' }}" 
                   href="{{ route('audit-ledger.index') }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Audit Ledger</span>
                </a>
                <a class="nav-link {{ Route::is('admin.meetings*') ? 'active' : '' }}" 
                   href="{{ route('admin.meetings.index') }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Meetings</span>
                </a>
                @endif
            </div>

            <div class="mt-5 pt-4 border-top" style="border-top-color: rgba(255, 255, 255, 0.1) !important;">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.change-password.show') }}" class="nav-link btn btn-link" style="color: rgba(255, 255, 255, 0.85); text-decoration: none; justify-content: flex-start; padding: 1rem 1.5rem;">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>
                @else
                    <a href="{{ route('password.request') }}" class="nav-link btn btn-link" style="color: rgba(255, 255, 255, 0.85); text-decoration: none; justify-content: flex-start; padding: 1rem 1.5rem;">
                        <i class="fas fa-key"></i>
                        <span>Forgot Password</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="p-3" id="logoutForm">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link" style="color: rgba(255, 255, 255, 0.85); text-decoration: none; justify-content: flex-start; padding: 1rem 1.5rem;" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

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
        <main class="main-content flex-grow-1">
            <!-- Header -->
            <div class="app-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                    <p class="text-muted mb-0">@yield('subtitle')</p>
                </div>
                <div class="user-info">
                    <span class="text-muted">{{ Auth::user()->name ?? 'User' }}</span>
                </div>
            </div>

            <!-- Alerts -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Responsive Sidebar Navigation with Drag Support
        (function() {
            const sidebar = document.getElementById('sidebarNav');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');
            const overlay = document.getElementById('sidebarOverlay');
            
            let startX = 0;
            let currentX = 0;
            let isDragging = false;
            const dragThreshold = 50;

            // Toggle sidebar on button click
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            // Close sidebar on close button click
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Close sidebar when clicking a nav link
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    // Only close on mobile
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            });

            // Drag functionality for sidebar
            sidebar.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
            }, { passive: true });

            sidebar.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                
                currentX = e.touches[0].clientX - startX;
                
                // Only allow dragging to the left (closing)
                if (currentX < 0) {
                    const dragAmount = Math.abs(currentX);
                    sidebar.style.transform = `translateX(calc(-100% + ${dragAmount}px))`;
                }
            }, { passive: true });

            sidebar.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;
                
                const dragAmount = Math.abs(currentX);
                
                // Close sidebar if dragged more than threshold
                if (dragAmount > dragThreshold) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    sidebar.style.transform = '';
                } else {
                    // Snap back to open position
                    sidebar.style.transform = '';
                }
                
                currentX = 0;
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    // On larger screens, ensure sidebar is visible
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    sidebar.style.transform = '';
                }
            });

            // Optional: Keyboard support
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && window.innerWidth < 992) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });

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
        })();
    </script>
    
    @yield('scripts')
</body>
</html>
