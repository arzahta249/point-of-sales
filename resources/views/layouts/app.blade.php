<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TitikJual POS')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #0d2137 100%);
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            padding-top: 60px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: .5rem 1rem;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all .2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.15);
        }
        .sidebar .nav-link i { width: 20px; }
        .sidebar .nav-section {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(255,255,255,.4);
            padding: .75rem 1.25rem .25rem;
            text-transform: uppercase;
        }
        .topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            position: fixed; top: 0; right: 0;
            left: 250px;
            z-index: 99;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .main-content {
            margin-left: 250px;
            padding: 76px 1.5rem 1.5rem;
        }
        .brand {
            position: fixed;
            top: 0; left: 0;
            width: 250px;
            height: 56px;
            background: rgba(0,0,0,.2);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            z-index: 101;
        }
        .brand span { color: #fff; font-weight: 700; font-size: 1.1rem; }
        .badge-role { font-size: .65rem; }
        .card { border: 0; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .card-header { border-bottom: 1px solid #f0f0f0; }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Brand / Logo -->
<div class="brand">
    <i class="bi bi-shop me-2 text-warning fs-5"></i>
    <span>TitikJual</span>
</div>

<!-- Sidebar -->
<nav class="sidebar">
    <ul class="nav flex-column pt-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        @if(auth()->user()->role === 'admin')
        <li class="nav-section">Admin</li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link @if(request()->routeIs('users.*')) active @endif">
                <i class="bi bi-people me-2"></i> Pengguna
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categories.index') }}" class="nav-link @if(request()->routeIs('categories.*')) active @endif">
                <i class="bi bi-tags me-2"></i> Kategori
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link @if(request()->routeIs('products.*')) active @endif">
                <i class="bi bi-box-seam me-2"></i> Produk
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('stock.index') }}" class="nav-link @if(request()->routeIs('stock.*')) active @endif">
                <i class="bi bi-clipboard-data me-2"></i> Manajemen Stok
            </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'kasir']))
        <li class="nav-section">Kasir</li>
        <li class="nav-item">
            <a href="{{ route('transactions.create') }}" class="nav-link @if(request()->routeIs('transactions.create')) active @endif">
                <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link @if(request()->routeIs('transactions.index')) active @endif">
                <i class="bi bi-receipt me-2"></i> Riwayat Transaksi
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('customers.index') }}" class="nav-link @if(request()->routeIs('customers.*')) active @endif">
                <i class="bi bi-person-lines-fill me-2"></i> Pelanggan
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'pemilik')
        <li class="nav-section">Laporan</li>
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-graph-up-arrow me-2"></i> Dashboard Analitik
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transactions.index') }}" class="nav-link @if(request()->routeIs('transactions.index')) active @endif">
                <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Penjualan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('stock.logs') }}" class="nav-link @if(request()->routeIs('stock.logs')) active @endif">
                <i class="bi bi-journal-text me-2"></i> Riwayat Stok
            </a>
        </li>
        @endif
    </ul>
</nav>

<!-- Topbar -->
<div class="topbar">
    <span class="fw-semibold text-muted fs-6">@yield('title', 'Dashboard')</span>
    <div class="d-flex align-items-center gap-3">
        <span class="text-secondary small">
            <i class="bi bi-person-circle me-1"></i>
            {{ auth()->user()->nama }}
            <span class="badge bg-primary badge-role ms-1">{{ strtoupper(auth()->user()->role) }}</span>
        </span>
        <form method="POST" action="{{ route('logout') }}" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<main class="main-content">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

