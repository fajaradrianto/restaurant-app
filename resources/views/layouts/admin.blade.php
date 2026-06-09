<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1B4332;
            --sidebar-hover: #2D6A4F;
            --sidebar-active: #40916C;
            --accent: #D4A373;
            --content-bg: #F5F1EC;
            --card-bg: #FFFFFF;
            --text-dark: #2C2C2C;
            --text-muted: #6B7280;
            --border: #E5E0DB;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--content-bg);
            color: var(--text-dark);
            margin: 0;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: var(--sidebar-bg);
            color: white;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .admin-sidebar .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }

        .admin-sidebar .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .sidebar-menu li a:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .admin-sidebar .sidebar-menu li a.active {
            background: var(--sidebar-active);
            color: white;
            border-left-color: var(--accent);
        }

        .admin-sidebar .sidebar-menu li a i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Konten utama */
        .admin-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .admin-topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-body {
            padding: 2rem;
        }

        /* Kartu statistik */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Tabel */
        .table-custom {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .table-custom thead th {
            background: #F8F5F1;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--text-muted);
            border: none;
            padding: 0.85rem 1rem;
        }

        .table-custom tbody td {
            border: none;
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 1rem;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background: rgba(212,163,115,0.05);
        }

        /* Badge */
        .badge-status {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        /* Tombol */
        .btn-admin {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .btn-admin-primary {
            background: var(--sidebar-bg);
            color: white;
            border: none;
        }

        .btn-admin-primary:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .btn-admin-accent {
            background: var(--accent);
            color: var(--sidebar-bg);
            border: none;
        }

        .btn-admin-accent:hover {
            background: #c4935f;
            color: var(--sidebar-bg);
        }

        .btn-admin-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text-dark);
        }

        .btn-admin-outline:hover {
            border-color: var(--sidebar-bg);
            color: var(--sidebar-bg);
        }

        .btn-admin-danger {
            background: #DC3545;
            color: white;
            border: none;
        }

        .btn-admin-danger:hover {
            background: #c82333;
            color: white;
        }

        /* Form */
        .form-control-admin {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-control-admin:focus {
            border-color: var(--sidebar-bg);
            box-shadow: 0 0 0 3px rgba(27,67,50,0.08);
        }

        /* Card section */
        .section-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .section-card .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-card .section-header h5 {
            font-weight: 700;
            margin: 0;
        }

        .section-card .section-body {
            padding: 1.5rem;
        }

        /* Mobile sidebar toggle */
        .sidebar-toggle {
            display: none;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            font-size: 1.3rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: inline-flex;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .admin-body {
                padding: 1rem;
            }
        }

        /* Print */
        @media print {
            .admin-sidebar, .admin-topbar, .no-print { display: none !important; }
            .admin-content { margin-left: 0; }
            .admin-body { padding: 0; }
        }

        /* Chart mini bar */
        .mini-bar {
            height: 6px;
            border-radius: 3px;
            background: #E8E0D8;
            overflow: hidden;
        }
        .mini-bar .mini-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: var(--sidebar-bg);
            transition: width 0.5s ease;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="bi bi-shop me-2"></i>Warung Nusantara
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tables.index') }}" class="{{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                    <i class="bi bi-table"></i> Kelola Meja
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menus.index') }}" class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Kelola Menu
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Pesanan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.kitchen') }}" target="_blank" class="">
                    <i class="bi bi-display"></i> Layar Dapur
                </a>
            </li>
        </ul>
        <div style="padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none text-white-50 w-100 text-start ps-0">
                    <i class="bi bi-box-arrow-left me-2"></i>Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten -->
    <div class="admin-content">
        <div class="admin-topbar no-print">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="text-muted small">
            <i class="bi bi-clock me-1"></i><span id="adminClock">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <div class="admin-body">
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-4">
                    @foreach ($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    // jam real-time
    function updateAdminClock() {
        const now = new Date();
        const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false };
        const element = document.getElementById('adminClock');
        if(element) {
            element.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    setInterval(updateAdminClock, 1000);
    updateAdminClock();
    </script>
    @yield('scripts')
</body>
</html>