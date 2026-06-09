@extends('layouts.app')

@section('title', 'Pesanan')

@section('content')
<div class="step-indicator mb-3">
    <div class="step-dot done"></div>
    <div class="step-dot done"></div>
    <div class="step-dot active"></div>
    <div class="step-dot"></div>
</div>

<h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

<!-- Info pelanggan -->
<div class="bg-white rounded-4 p-3 mb-3 border" style="border-color: var(--border) !important;">
    <div class="row small">
        <div class="col-6 mb-1"><span class="text-muted">Nama</span><br><strong>{{ $customerData['name'] }}</strong></div>
        <div class="col-6 mb-1"><span class="text-muted">Meja</span><br><strong>{{ $table->number }}</strong></div>
        <div class="col-6"><span class="text-muted">Telepon</span><br><strong>{{ $customerData['phone'] }}</strong></div>
        <div class="col-6"><span class="text-muted">E-mail</span><br><strong>{{ $customerData['email'] }}</strong></div>
    </div>
</div>

<!-- Daftar pesanan -->
<div class="bg-white rounded-4 border overflow-hidden mb-3" style="border-color: var(--border) !important;">
    <div class="p-3 border-bottom" style="border-color: var(--border) !important;">
        <strong>Item Pesanan</strong>
    </div>
    @foreach($cart as $item)
    <div class="d-flex align-items-center p-3 border-bottom" style="border-color: var(--border) !important;">
        <div class="flex-grow-1">
            <div class="fw-600">{{ $item['name'] }}</div>
            <div class="text-muted small">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</div>
        </div>
        <div class="fw-700 text-end" style="min-width: 100px;">
            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
        </div>
    </div>
    @endforeach

    <div class="d-flex align-items-center p-3" style="background: var(--cream);">
        <div class="fw-bold">Total</div>
        <div class="fw-bold ms-auto fs-5" style="color: var(--primary);">
            Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    </div>
</div>

<!-- Catatan -->
<form method="POST" action="{{ route('customer.process', $table->id) }}" id="orderForm">
    @csrf
    <div class="mb-3">
        <label class="form-label fw-600 small">Catatan (opsional)</label>
        <textarea name="notes" class="form-control form-control-custom" rows="2"
                  placeholder="Contoh: tidak pakai sambal, es teh manis, dll."></textarea>
    </div>

    <!-- Metode pembayaran -->
    <h6 class="fw-bold mb-3">Pilih Metode Pembayaran</h6>

    <div class="mb-3">
        <label class="payment-option d-flex align-items-center gap-3 mb-2" onclick="selectPayment('qris')">
            <input type="radio" name="payment_method" value="qris" class="form-check-input m-0" required>
            <div class="flex-grow-1">
                <div class="fw-600"><i class="bi bi-qr-code me-2"></i>QRIS</div>
                <div class="text-muted small">Scan QR code untuk pembayaran instan</div>
            </div>
        </label>

        <label class="payment-option d-flex align-items-center gap-3 mb-2" onclick="selectPayment('virtual_account')">
            <input type="radio" name="payment_method" value="virtual_account" class="form-check-input m-0">
            <div class="flex-grow-1">
                <div class="fw-600"><i class="bi bi-bank me-2"></i>Virtual Account</div>
                <div class="text-muted small">Transfer via bank (BCA, Mandiri, BNI, dll)</div>
            </div>
        </label>

        <label class="payment-option d-flex align-items-center gap-3" onclick="selectPayment('cash')">
            <input type="radio" name="payment_method" value="cash" class="form-check-input m-0">
            <div class="flex-grow-1">
                <div class="fw-600"><i class="bi bi-cash-stack me-2"></i>Tunai di Kasir</div>
                <div class="text-muted small">Bayar langsung ke kasir setelah selesai makan</div>
            </div>
        </label>
    </div>

    <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-5 mt-2" id="btnSubmit" disabled>
        Buat Pesanan <i class="bi bi-arrow-right ms-2"></i>
    </button>
</form>
@endsection

@section('scripts')
<script>
function selectPayment(method) {
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
    event.currentTarget.closest('.payment-option').classList.add('selected');
    document.getElementById('btnSubmit').disabled = false;
}
</script>
@endsection