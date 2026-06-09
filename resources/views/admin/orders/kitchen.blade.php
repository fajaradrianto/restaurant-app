<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display - Warung Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1a1a2e;
            color: white;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Top Bar */
        .kitchen-topbar {
            background: #16213e;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #e94560;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .kitchen-topbar h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
        }

        .kitchen-topbar .clock {
            font-size: 1.8rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color: #e94560;
        }

        .kitchen-topbar .ready-badge {
            background: #059669;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            display: none;
        }

        .kitchen-topbar .ready-badge.show {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Statistik mini */
        .kitchen-stats {
            display: flex;
            gap: 1.5rem;
            padding: 0.75rem 2rem;
            background: #0f3460;
            font-size: 0.9rem;
        }

        .kitchen-stats span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            opacity: 0.8;
        }

        .kitchen-stats .num {
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Grid kartu pesanan */
        .kitchen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.25rem;
            padding: 1.5rem 2rem;
        }

        /* Kartu pesanan */
        .order-card {
            background: #16213e;
            border-radius: 16px;
            overflow: hidden;
            border-left: 6px solid transparent;
            transition: transform 0.2s;
        }

        .order-card:hover {
            transform: translateY(-2px);
        }

        .order-card.status-new {
            border-left-color: #f59e0b;
        }

        .order-card.status-confirmed {
            border-left-color: #3b82f6;
        }

        .order-card.status-preparing {
            border-left-color: #e94560;
            animation: cardPulse 2s infinite;
        }

        @keyframes cardPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(233, 69, 96, 0); }
            50% { box-shadow: 0 0 20px 5px rgba(233, 69, 96, 0.3); }
        }

        .order-card-header {
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .order-card-header .order-num {
            font-weight: 700;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.7);
        }

        .order-card-header .table-num {
            background: #e94560;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .order-card-status {
            padding: 0.4rem 1.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .order-card-status.status-new { background: #f59e0b; color: #000; }
        .order-card-status.status-confirmed { background: #3b82f6; color: white; }
        .order-card-status.status-preparing { background: #e94560; color: white; }

        .order-card-body {
            padding: 1rem 1.25rem;
        }

        .order-card-body .customer-name {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 0.75rem;
        }

        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .menu-item:last-child {
            border-bottom: none;
        }

        .menu-item .menu-name {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .menu-item .menu-qty {
            background: rgba(255,255,255,0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            min-width: 40px;
            text-align: center;
        }

        .order-card-notes {
            padding: 0.5rem 1.25rem;
            font-size: 0.85rem;
            color: #fbbf24;
            font-style: italic;
        }

        .order-card-time {
            padding: 0.5rem 1.25rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* Tombol aksi */
        .order-card-actions {
            padding: 1rem 1.25rem;
            display: flex;
            gap: 0.75rem;
        }

        .btn-kitchen {
            flex: 1;
            padding: 0.85rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-kitchen:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .btn-kitchen:active {
            transform: translateY(0);
        }

        .btn-cook {
            background: #e94560;
            color: white;
        }

        .btn-done {
            background: #059669;
            color: white;
        }

        /* Kosong */
        .kitchen-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            color: rgba(255,255,255,0.3);
        }

        .kitchen-empty i {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        .kitchen-empty h3 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Auto refresh indicator */
        .refresh-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255,255,255,0.1);
            z-index: 100;
        }

        .refresh-bar .fill {
            height: 100%;
            background: #e94560;
            width: 0%;
            animation: refreshAnim 10s linear infinite;
        }

        @keyframes refreshAnim {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* Tombol fullscreen */
        .btn-fullscreen {
            background: none;
            border: 2px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .btn-fullscreen:hover {
            border-color: #e94560;
            color: #e94560;
        }
    </style>
</head>
<body>

    <!-- Top Bar -->
    <div class="kitchen-topbar">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-fire" style="font-size: 1.8rem; color: #e94560;"></i>
            <h1>KITCHEN DISPLAY</h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="ready-badge" id="readyBadge">
                <i class="bi bi-bell-fill"></i>
                <span id="readyCount">0</span> SIAP SAJI
            </div>
            <div class="clock" id="clock">00:00:00</div>
            <button class="btn-fullscreen" onclick="toggleFullscreen()">
                <i class="bi bi-arrows-fullscreen"></i> Fullscreen
            </button>
        </div>
    </div>

    <!-- Statistik -->
    <div class="kitchen-stats">
        <span><i class="bi bi-clock-history"></i> Antrian: <span class="num" id="queueCount">{{ $orders->count() }}</span></span>
        <span><i class="bi bi-hourglass-split"></i> Baru: <span class="num" id="newCount">{{ $orders->where('status', 'new')->count() }}</span></span>
        <span><i class="bi bi-fire"></i> Dimasak: <span class="num" id="cookingCount">{{ $orders->where('status', 'preparing')->count() }}</span></span>
        <span><i class="bi bi-check-circle"></i> Siap Saji: <span class="num" id="readyCountStat">{{ $readyCount }}</span></span>
    </div>

    <!-- Grid Pesanan -->
    <div class="kitchen-grid" id="orderGrid">
        @forelse($orders as $order)
        <div class="order-card status-{{ $order->status }}" id="card-{{ $order->id }}">
            <div class="order-card-header">
                <span class="order-num">{{ $order->order_number }}</span>
                <span class="table-num">MEJA {{ $order->table->number }}</span>
            </div>
            <div class="order-card-status status-{{ $order->status }}">
                @if($order->status === 'new')
                    <i class="bi bi-bell-fill"></i> Pesanan Baru
                @elseif($order->status === 'confirmed')
                    <i class="bi bi-check2-circle"></i> Dikonfirmasi
                @else
                    <i class="bi bi-fire"></i> Sedang Dimasak
                @endif
            </div>
            <div class="order-card-body">
                <div class="customer-name"><i class="bi bi-person-fill me-1"></i>{{ $order->customer->name }}</div>
                @foreach($order->items as $item)
                <div class="menu-item">
                    <span class="menu-name">{{ $item->menu_name }}</span>
                    <span class="menu-qty">x{{ $item->quantity }}</span>
                </div>
                @endforeach
            </div>
            @if($order->notes)
            <div class="order-card-notes">
                <i class="bi bi-chat-left-quote-fill me-1"></i>{{ $order->notes }}
            </div>
            @endif
            <div class="order-card-time">
                <i class="bi bi-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
            </div>
            <div class="order-card-actions">
                @if(in_array($order->status, ['new', 'confirmed']))
                <form method="POST" action="{{ route('admin.orders.kitchen-update', $order->id) }}">
                    @csrf
                    <button type="submit" class="btn-kitchen btn-cook w-100">
                        <i class="bi bi-fire"></i> Mulai Masak
                    </button>
                </form>
                @elseif($order->status === 'preparing')
                <form method="POST" action="{{ route('admin.orders.kitchen-update', $order->id) }}">
                    @csrf
                    <button type="submit" class="btn-kitchen btn-done w-100">
                        <i class="bi bi-check-circle-fill"></i> Siap Saji!
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="kitchen-empty">
            <i class="bi bi-emoji-smile"></i>
            <h3>Tidak Ada Pesanan</h3>
            <p style="font-size: 1.2rem;">Semua pesanan sudah selesai. Tunggu pesanan baru...</p>
        </div>
        @endforelse
    </div>

    <!-- Auto refresh bar -->
    <div class="refresh-bar">
        <div class="fill"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Jam real-time
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Auto refresh setiap 10 detik
        setInterval(() => {
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newGrid = doc.getElementById('orderGrid').innerHTML;
                    document.getElementById('orderGrid').innerHTML = newGrid;

                    // Update statistik
                    document.getElementById('queueCount').textContent = doc.getElementById('queueCount').textContent;
                    document.getElementById('newCount').textContent = doc.getElementById('newCount').textContent;
                    document.getElementById('cookingCount').textContent = doc.getElementById('cookingCount').textContent;
                    document.getElementById('readyCountStat').textContent = doc.getElementById('readyCountStat').textContent;

                    // Update ready badge
                    const readyStat = parseInt(doc.getElementById('readyCountStat').textContent);
                    const badge = document.getElementById('readyBadge');
                    if (readyStat > 0) {
                        badge.classList.add('show');
                        document.getElementById('readyCount').textContent = readyStat;
                    } else {
                        badge.classList.remove('show');
                    }
                });
        }, 10000);

        // Cek ready badge saat load
        const initReady = parseInt(document.getElementById('readyCountStat').textContent);
        if (initReady > 0) {
            document.getElementById('readyBadge').classList.add('show');
            document.getElementById('readyCount').textContent = initReady;
        }

        // Fullscreen toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    </script>
</body>
</html>