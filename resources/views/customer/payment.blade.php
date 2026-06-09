@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="step-indicator mb-3">
    <div class="step-dot done"></div>
    <div class="step-dot done"></div>
    <div class="step-dot done"></div>
    <div class="step-dot active"></div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="text-center mb-4">
            <h4 class="font-display fw-bold">Pembayaran</h4>
            <p class="text-muted">Order {{ $order->order_number }}</p>
        </div>

        <!-- Detail total -->
        <div class="bg-white rounded-4 p-4 mb-3 border text-center" style="border-color: var(--border) !important;">
            <div class="text-muted small mb-1">Total yang harus dibayar</div>
            <div class="fw-bold fs-2" style="color: var(--primary);">{{ $order->formatted_total }}</div>
        </div>

        <!-- Instruksi pembayaran sesuai metode -->
        @if($order->payment_method === 'qris')
        <div class="bg-white rounded-4 p-4 mb-3 border" style="border-color: var(--border) !important;">
            <h6 class="fw-bold mb-3 text-center"><i class="bi bi-qr-code me-2"></i>Scan QRIS</h6>
            <div class="text-center mb-3">
                {!! QrCode::size(200)->generate('QRIS-PAYMENT-' . $order->order_number . '-' . $order->total) !!}
            </div>
            <div class="text-center">
                <p class="text-muted small mb-0">Buka aplikasi e-wallet atau mobile banking Anda, lalu scan QR code di atas</p>
            </div>
        </div>

        @elseif($order->payment_method === 'virtual_account')
        <div class="bg-white rounded-4 p-4 mb-3 border" style="border-color: var(--border) !important;">
            <h6 class="fw-bold mb-3"><i class="bi bi-bank me-2"></i>Virtual Account</h6>
            <div class="mb-3">
                <label class="form-label text-muted small">Nomor Virtual Account</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control form-control-custom fw-bold fs-5" value="{{ $order->generateVirtualAccount() }}" readonly id="vaNumber">
                    <button class="btn btn-accent py-2 px-3" onclick="copyVA()">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
            </div>
            <div class="bg-light rounded-3 p-3 small">
                <strong>Cara pembayaran:</strong>
                <ol class="mb-0 mt-2">
                    <li>Buka aplikasi mobile banking Anda</li>
                    <li>Pilih menu Transfer > Transfer ke Virtual Account</li>
                    <li>Masukkan nomor VA di atas</li>
                    <li>Masukkan nominal <strong>{{ $order->formatted_total }}</strong></li>
                    <li>Konfirmasi dan selesaikan pembayaran</li>
                </ol>
            </div>
        </div>

        @elseif($order->payment_method === 'cash')
        <div class="bg-white rounded-4 p-4 mb-3 border" style="border-color: var(--border) !important;">
            <h6 class="fw-bold mb-3"><i class="bi bi-cash-stack me-2"></i>Pembayaran Tunai</h6>
            <div class="text-center py-3">
                <i class="bi bi-cash-coin" style="font-size: 3rem; color: var(--accent);"></i>
                <p class="mt-3 mb-0">Silakan hubungi kasir untuk melakukan pembayaran tunai sebesar</p>
                <p class="fw-bold fs-4 mt-2" style="color: var(--primary);">{{ $order->formatted_total }}</p>
            </div>
        </div>
        @endif

        <!-- Rincian pesanan -->
        <div class="bg-white rounded-4 border overflow-hidden mb-3" style="border-color: var(--border) !important;">
            <div class="p-3 border-bottom" style="border-color: var(--border) !important;">
                <strong class="small text-muted">RINCIAN PESANAN</strong>
            </div>
            @foreach($order->items as $item)
            <div class="d-flex justify-content-between p-3 border-bottom small" style="border-color: var(--border) !important;">
                <span>{{ $item->menu_name }} x{{ $item->quantity }}</span>
                <span class="fw-600">{{ $item->formatted_subtotal }}</span>
            </div>
            @endforeach
            <div class="d-flex justify-content-between p-3 fw-bold" style="background: var(--cream);">
                <span>Total</span>
                <span style="color: var(--primary);">{{ $order->formatted_total }}</span>
            </div>
        </div>

        <!-- Tombol konfirmasi -->
        <form method="POST" action="{{ route('customer.confirm', [$table->id, $order->order_number]) }}">
            @csrf
            <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-5">
                @if($order->payment_method === 'cash')
                    Saya Akan Bayar di Kasir
                @else
                    Saya Sudah Melakukan Pembayaran
                @endif
                <i class="bi bi-check-circle ms-2"></i>
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
function copyVA() {
    const va = document.getElementById('vaNumber').value;
    navigator.clipboard.writeText(va.replace(/\s/g, '')).then(() => {
        const btn = event.currentTarget;
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i>', 2000);
    });
}
</script>
@endsection