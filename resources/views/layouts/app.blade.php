<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restoran') - Warung Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1B4332;
            --primary-light: #2D6A4F;
            --accent: #D4A373;
            --accent-light: #E9C89A;
            --cream: #FFF8F0;
            --warm-white: #FEFCF8;
            --text-dark: #2C2C2C;
            --text-muted: #6B7280;
            --border: #E8E0D8;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--text-dark);
            min-height: 100vh;
            padding-bottom: 100px;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Header pelanggan */
        .customer-header {
            background: var(--primary);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(27,67,50,0.3);
        }

        .customer-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            margin: 0;
            font-weight: 700;
        }

        .customer-header .table-badge {
            background: var(--accent);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
        }

        /* Kartu menu */
        .menu-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .menu-card .menu-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: #f0ebe5;
        }

        .menu-card .menu-body {
            padding: 1rem;
        }

        .menu-card .menu-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .menu-card .menu-desc {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .menu-card .menu-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.05rem;
        }

        /* Tombol aksen */
        .btn-accent {
            background: var(--accent);
            color: var(--primary);
            border: none;
            font-weight: 600;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-accent:hover {
            background: var(--accent-light);
            color: var(--primary);
            transform: translateY(-1px);
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            color: white;
        }

        /* Floating cart bar */
        .cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--primary);
            color: white;
            padding: 1rem 1.5rem;
            z-index: 1001;
            box-shadow: 0 -4px 30px rgba(27,67,50,0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-bar .cart-count {
            background: var(--accent);
            color: var(--primary);
            font-weight: 700;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        /* Form input custom */
        .form-control-custom {
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: border-color 0.2s;
            background: white;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27,67,50,0.1);
        }

        /* Kategori tabs */
        .category-tab {
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
            border: 2px solid var(--border);
            background: white;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s;
        }

        .category-tab.active,
        .category-tab:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--border);
            transition: all 0.3s;
        }

        .step-dot.active {
            background: var(--primary);
            width: 30px;
            border-radius: 5px;
        }

        .step-dot.done {
            background: var(--accent);
        }

        /* Alert custom */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
        }

        /* Payment card */
        .payment-option {
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .payment-option:hover {
            border-color: var(--primary-light);
        }

        .payment-option.selected {
            border-color: var(--primary);
            background: rgba(27,67,50,0.03);
        }

        /* Animasi masuk */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease-out;
        }

        /* Print */
        @media print {
            body { padding: 0; background: white; }
            .no-print { display: none !important; }
        }

        /* Responsif */
        @media (max-width: 576px) {
            .customer-header h1 { font-size: 1.1rem; }
            .menu-card .menu-img { height: 130px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="customer-header no-print">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1>Warung Nusantara</h1>
                @if(isset($table))
                    <span class="table-badge">
                        <i class="bi bi-geo-alt-fill me-1"></i>Meja {{ $table->number }}
                    </span>
                @endif
            </div>
        </div>
    </header>

    <!-- Konten -->
    <main class="container py-4 animate-in">
        @if(session('success'))
            <div class="alert alert-success alert-custom mb-3">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-custom mb-3">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Floating Cart Bar (hanya di halaman menu) -->
    @yield('cart-bar')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>