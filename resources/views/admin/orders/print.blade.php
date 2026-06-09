<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print - {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .border-dashed {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; vertical-align: top; }
        .item-name { width: 60%; }
        .item-qty { width: 15%; text-align: center; }
        .item-sub { width: 25%; text-align: right; }
        .title { font-size: 16px; font-weight: bold; }
        .total-box {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer-note { font-size: 10px; margin-top: 15px; text-align: center; }
        @media print {
            body { width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="text-center mb-2">
        <div class="title">WARUNG NUSANTARA</div>
        <div class="mb-1">================================</div>
    </div>

    <div class="mb-2">
        <div><strong>No:</strong> {{ $order->order_number }}</div>
        <div><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</div>
        <div><strong>Meja:</strong> {{ $order->table->number }}</div>
        <div><strong>Pelanggan:</strong> {{ $order->customer->name }}</div>
    </div>

    @if($order->notes)
    <div class="mb-2">
        <strong>Catatan:</strong> {{ $order->notes }}
    </div>
    @endif

    <div class="border-dashed"></div>

    <table>
        <thead>
            <tr>
                <th class="item-name">Menu</th>
                <th class="item-qty">Qty</th>
                <th class="item-sub">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="item-name">{{ $item->menu_name }}</td>
                <td class="item-qty">{{ $item->quantity }}x</td>
                <td class="item-sub">{{ $item->formatted_subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-dashed"></div>

    <div class="total-box">
        TOTAL: {{ $order->formatted_total }}
    </div>

    <div class="border-dashed"></div>

    <div class="mb-1">
        <strong>Metode Bayar:</strong> {{ $order->payment_method_label }}
    </div>
    <div class="mb-1">
        <strong>Status Bayar:</strong> {{ $order->payment_status_label }}
    </div>

    @if($order->payment_method === 'virtual_account')
    <div class="mb-1">
        <strong>No. VA:</strong> {{ $order->generateVirtualAccount() }}
    </div>
    @endif

    <div class="border-dashed"></div>

    <div class="footer-note">
        Struk ini dicari untuk keperluan dapur.<br>
        Terima kasih telah memesan di Warung Nusantara!
    </div>

    <!-- Tombol print (hanya muncul di layar, hilang saat di-print) -->
    <div class="no-print text-center mt-3">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; border-radius: 5px; border: none; background: #1B4332; color: white;">
            <i class="bi bi-printer"></i> Print Struk
        </button>
    </div>

</body>
</html>