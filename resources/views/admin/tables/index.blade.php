@extends('layouts.admin')

@section('page-title', 'Kelola Meja')

@section('content')
<div class="section-card">
    <div class="section-header">
        <h5>Daftar Meja & QR Code</h5>
        <button class="btn btn-admin-primary btn-admin" data-bs-toggle="modal" data-bs-target="#addTableModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Meja
        </button>
    </div>
    <div class="section-body">
        <div class="row g-3">
            @forelse($tables as $table)
            @php
                $statusBg = $table->status === 'available' ? '#D1FAE5' : ($table->status === 'occupied' ? '#FEE2E2' : '#FEF3C7');
                $statusColor = $table->status === 'available' ? '#065F46' : ($table->status === 'occupied' ? '#991B1B' : '#92400E');
                $statusLabel = $table->status === 'available' ? 'Tersedia' : ($table->status === 'occupied' ? 'Terisi' : 'Direservasi');
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column" style="border-color: var(--border) !important;">
                    <div class="mb-2">
                        <span class="badge-status" style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: var(--primary);">{{ $table->number }}</h4>
                    <small class="text-muted mb-3">Kapasitas: {{ $table->capacity }} orang</small>

                    <!-- QR Code -->
                    <div class="mb-3 p-2 bg-light rounded-2 d-inline-block mx-auto">
                        {!! QrCode::size(120)->generate($table->qr_code_url) !!}
                    </div>

                    <div class="mt-auto">
                        <small class="text-muted d-block mb-2" style="font-size: 0.7rem; word-break: break-all;">{{ $table->qr_code_url }}</small>
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-admin-outline btn-admin" data-bs-toggle="modal" data-bs-target="#editTableModal{{ $table->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.tables.destroy', $table->id) }}" onsubmit="return confirm('Hapus meja ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-admin-danger btn-admin"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal edit meja -->
                <div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="border-radius: 16px; border: none;">
                            <div class="modal-header border-0 pb-0">
                                <h6 class="fw-bold">Edit Meja {{ $table->number }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.tables.update', $table->id) }}">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label class="form-label small fw-600">Nomor Meja</label>
                                        <input type="text" name="number" value="{{ $table->number }}" class="form-control form-control-admin" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-600">Kapasitas</label>
                                        <input type="number" name="capacity" value="{{ $table->capacity }}" class="form-control form-control-admin" min="1" max="20" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label small fw-600">Status</label>
                                        <select name="status" class="form-select form-control-admin">
                                            <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Terisi</option>
                                            <option value="reserved" {{ $table->status === 'reserved' ? 'selected' : '' }}>Direservasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="submit" class="btn btn-admin-primary btn-admin w-100">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-table" style="font-size: 3rem;"></i>
                    <p class="mt-2">Belum ada meja. Klik "Tambah Meja" untuk memulai.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal tambah meja -->
<div class="modal fade" id="addTableModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold">Tambah Meja Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.tables.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label small fw-600">Nomor Meja</label>
                        <input type="text" name="number" class="form-control form-control-admin" placeholder="Contoh: 5" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-600">Kapasitas</label>
                        <input type="number" name="capacity" value="4" class="form-control form-control-admin" min="1" max="20" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-admin-primary btn-admin w-100">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection