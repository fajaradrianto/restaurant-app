@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<!-- Kartu statistik -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="stat-icon" style="background: #D1FAE5; color: #059669;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalOrdersToday }}</div>
            <div class="stat-label">Pesanan Hari Ini</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-value">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Hari Ini</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="stat-icon" style="background: #FEE2E2; color: #DC2626;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="stat-icon" style="background: #DBEAFE; color: #2563EB;">
                    <i class="bi bi-fire"></i>
                </div>
            </div>
            <div class="stat-value">{{ $preparingOrders }}</div>
            <div class="stat-label">Sedang Dimasak</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Grafik 7 hari -->
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-header">
                <h5>Pesanan 7 Hari Terakhir</h5>
            </div>
            <div class="section-body">
                @php $maxOrders = $last7Days->pluck('orders')->max() ?? 1; @endphp
                <div class="d-flex align-items-end gap-2" style="height: 180px;">
                    @foreach($last7Days as $day)
                        <div class="flex-fill text-center">
                            <div class="d-flex flex-column align-items-center justify-content-end h-100">
                                <div class="small fw-600 mb-1">{{ $day['orders'] }}</div>
                                <div style="width: 100%; max-width: 50px; height: {{ ($day['orders'] / $maxOrders) * 130 }}px; background: #1B4332; border-radius: 6px 6px 0 0; transition: height 0.5s;"></div>
                            </div>
                            <div class="text-muted mt-2" style="font-size: 0.75rem;">{{ $day['date'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Meja -->
    <div class="col-lg-4">
        <div class="section-card">
            <div class="section-header">
                <h5>Status Meja</h5>
            </div>
            <div class="section-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Terisi</span>
                    <span class="fw-bold">{{ $tablesOccupied }} / {{ $tablesTotal }}</span>
                </div>
                <div class="mini-bar mb-4">
                    <div class="mini-bar-fill" style="width: {{ $tablesTotal > 0 ? ($tablesOccupied / $tablesTotal) * 100 : 0 }}%;"></div>
                </div>
                <a href="{{ route('admin.tables.index') }}" class="btn btn-admin-outline btn-admin w-100">
                    <i class="bi bi-table me-1"></i>Kelola Meja
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pesanan terbaru -->
<div class="section-card mt-4">
    <div class="section-header">
        <h5>Pesanan Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-admin-outline btn-admin btn-sm">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Meja</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bayar</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                @php
                    $statusBg = $order->status === 'new' ? '#FEF3C7' : ($order->status === 'preparing' ? '#DBEAFE' : ($order->status === 'completed' ? '#D1FAE5' : '#F3F4F6'));
                    $statusColor = $order->status === 'new' ? '#92400E' : ($order->status === 'preparing' ? '#1E40AF' : ($order->status === 'completed' ? '#065F46' : '#374151'));
                    $payBg = $order->payment_status === 'paid' ? '#D1FAE5' : '#FEF3C7';
                    $payColor = $order->payment_status === 'paid' ? '#065F46' : '#92400E';
                @endphp
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->table->number }}</td>
                    <td>{{ $order->formatted_total }}</td>
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
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-admin-outline btn-admin">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection