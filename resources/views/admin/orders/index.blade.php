@extends('layouts.admin')

@section('page-title', 'Pesanan')

@section('content')
<!-- Filter -->
<div class="section-card mb-4">
    <div class="section-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-600">Cari</label>
                <input type="text" name="search" class="form-control form-control-admin" placeholder="No. pesanan / nama" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Status</label>
                <select name="status" class="form-select form-control-admin">
                    <option value="">Semua</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Dimasak</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Saji</option>
                    <option value="served" {{ request('status') === 'served' ? 'selected' : '' }}>Disajikan</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Pembayaran</label>
                <select name="payment_status" class="form-select form-control-admin">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Tanggal</label>
                <input type="date" name="date" class="form-control form-control-admin" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button class="btn btn-admin-primary btn-admin flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-admin-outline btn-admin">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel pesanan -->
<div class="section-card">
    <div class="section-header">
        <h5>Daftar Pesanan ({{ $orders->total() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Waktu</th>
                    <th>Pelanggan</th>
                    <th>Meja</th>
                    <th>Metode Bayar</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $statusBg = $order->status === 'new' ? '#FEF3C7' : ($order->status === 'preparing' ? '#DBEAFE' : ($order->status === 'ready' ? '#E0E7FF' : ($order->status === 'completed' ? '#D1FAE5' : ($order->status === 'cancelled' ? '#FEE2E2' : '#F3F4F6'))));
                    $statusColor = $order->status === 'new' ? '#92400E' : ($order->status === 'preparing' ? '#1E40AF' : ($order->status === 'ready' ? '#3730A3' : ($order->status === 'completed' ? '#065F46' : ($order->status === 'cancelled' ? '#991B1B' : '#374151'))));
                    $payBg = $order->payment_status === 'paid' ? '#D1FAE5' : '#FEF3C7';
                    $payColor = $order->payment_status === 'paid' ? '#065F46' : '#92400E';
                @endphp
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td class="small">{{ $order->created_at->format('d M, H:i') }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td><span class="fw-600">Meja {{ $order->table->number }}</span></td>
                    <td class="small">{{ $order->payment_method_label }}</td>
                    <td class="fw-600">{{ $order->formatted_total }}</td>
                    <td>
                        <span class="badge-status" style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status" style="background: {{ $payBg }}; color: {{ $payColor }};">
                            {{ $order->payment_status_label }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-admin-outline btn-admin" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-sm btn-admin-accent btn-admin" title="Print Dapur">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top d-flex justify-content-center" style="border-color: var(--border) !important;">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection