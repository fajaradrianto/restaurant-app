@extends('layouts.admin')

@section('page-title', 'Detail Pesanan')

@section('content')
<div class="row g-4">
    <!-- Info utama -->
    <div class="col-lg-8">
        <div class="section-card mb-4">
            <div class="section-header">
                <h5>{{ $order->order_number }}</h5>
                <div class="d-flex gap-2 no-print">
                    <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-admin-accent btn-admin btn-sm">
                        <i class="bi bi-printer me-1"></i>Print Dapur
                    </a>
                </div>
            </div>
            <div class="section-body">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3">
                        <div class="text-muted small">Pelanggan</div>
                        <div class="fw-bold">{{ $order->customer->name }}</div>
                        <div class="small text-muted">{{ $order->customer->phone }}</div>
                        <div class="small text-muted">{{ $order->customer->email }}</div>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <div class="text-muted small">Meja</div>
                        <div class="fw-bold fs-5" style="color: var(--primary);">{{ $order->table->number }}</div>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <div class="text-muted small">Waktu</div>
                        <div class="fw-600">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="small text-muted">{{ $order->created_at->format('H:i:s') }}</div>
                    </div>
                </div>

                <!-- Item pesanan -->
                <h6 class="fw-bold mb-3">Item Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="fw-600">{{ $item->menu_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ $item->formatted_price }}</td>
                                <td class="text-end fw-600">{{ $item->formatted_subtotal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: var(--cream);">
                                <td colspan="3" class="fw-bold text-end">Total</td>
                                <td class="text-end fw-bold fs-5" style="color: var(--primary);">{{ $order->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->notes)
                <div class="mt-3 p-3 bg-light rounded-3">
                    <div class="small fw-600 text-muted mb-1">Catatan:</div>
                    <div>{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel aksi -->
    <div class="col-lg-4">
        <!-- Info pembayaran -->
        <div class="section-card mb-4">
            <div class="section-header"><h5>Pembayaran</h5></div>
            <div class="section-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Metode</span>
                    <span class="fw-600">{{ $order->payment_method_label }}</span>
                </div>
                @php
                    $payBg = $order->payment_status === 'paid' ? '#D1FAE5' : '#FEF3C7';
                    $payColor = $order->payment_status === 'paid' ? '#065F46' : '#92400E';
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Status Bayar</span>
                    <span class="badge-status" style="background: {{ $payBg }}; color: {{ $payColor }};">
                        {{ $order->payment_status_label }}
                    </span>
                </div>
                @if($order->payment_method === 'virtual_account')
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">No. VA</span>
                    <span class="fw-600" style="font-family: monospace;">{{ $order->generateVirtualAccount() }}</span>
                </div>
                @endif
                @if($order->paid_at)
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Waktu Bayar</span>
                    <span class="small">{{ $order->paid_at->format('d M, H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Update status -->
        <div class="section-card no-print">
            <div class="section-header"><h5>Update Status</h5></div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600">Status Pesanan</label>
                        <select name="status" class="form-select form-control-admin">
                            @foreach(['new' => 'Baru', 'confirmed' => 'Dikonfirmasi', 'preparing' => 'Dimasak', 'ready' => 'Siap Saji', 'served' => 'Disajikan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
                                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-600">Status Pembayaran</label>
                        <select name="payment_status" class="form-select form-control-admin">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                    <button class="btn btn-admin-primary btn-admin w-100">
                        <i class="bi bi-check-lg me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection