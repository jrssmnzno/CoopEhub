<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COOP Ehub </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav col-md-3 col-lg-2 d-md-block sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-leaf"></i> COOP Ehub
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
                <a class="nav-link {{ Route::is('admin.audit-ledger*') ? 'active' : '' }}" 
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
                <form method="POST" action="{{ route('logout') }}" class="p-3">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link" style="color: rgba(255, 255, 255, 0.85); text-decoration: none; justify-content: flex-start; padding: 1rem 1.5rem;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content flex-grow-1 col-md-9 col-lg-10">
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
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
