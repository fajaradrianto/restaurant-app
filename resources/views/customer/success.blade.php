@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="text-center py-5">
    <div style="width: 80px; height: 80px; border-radius: 50%; background: #D1FAE5; color: #059669; font-size: 2.5rem;" class="d-inline-flex align-items-center justify-content-center mb-4">
        <i class="bi bi-check-lg"></i>
    </div>

    <h3 class="font-display fw-bold mb-2">Pesanan Diterima</h3>
    <p class="text-muted mb-4">Pesanan Anda sedang diproses. Silakan tunggu di meja {{ $table->number }}.</p>

    <div class="bg-white rounded-4 p-4 border text-start mx-auto" style="max-width: 400px; border-color: var(--border) !important;">
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">No. Pesanan</span>
            <span class="fw-700">{{ $order->order_number }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">Nama</span>
            <span class="fw-600">{{ $order->customer->name }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">Meja</span>
            <span class="fw-600">{{ $table->number }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">Pembayaran</span>
            <span class="fw-600">{{ $order->payment_method_label }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted small">Status Bayar</span>
            <span class="badge bg-{{ match($order->payment_status) { 'paid' => 'success', default => 'warning' } }} text-dark">
                {{ $order->payment_status_label }}
            </span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="fw-bold">Total</span>
            <span class="fw-bold" style="color: var(--primary);">{{ $order->formatted_total }}</span>
        </div>
    </div>

    <p class="text-muted small mt-4">
        <i class="bi bi-info-circle me-1"></i>Pesanan Anda telah dikirim ke dapur. Terima kasih!
    </p>
</div>
@endsection