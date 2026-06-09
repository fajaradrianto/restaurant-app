@extends('layouts.admin')

@section('page-title', 'Kelola Menu')

@section('content')
<div class="row g-4">
    <!-- Form tambah kategori & menu -->
    <div class="col-lg-4">
        <!-- Tambah kategori -->
        <div class="section-card mb-4">
            <div class="section-header"><h5>Tambah Kategori</h5></div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.menus.store-category') }}">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-admin" placeholder="Nama kategori" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="icon" class="form-control form-control-admin" placeholder="Icon (emoji, opsional)">
                    </div>
                    <button class="btn btn-admin-outline btn-admin w-100"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</button>
                </form>
            </div>
        </div>

        <!-- Tambah menu -->
        <div class="section-card">
            <div class="section-header"><h5>Tambah Menu</h5></div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.menus.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small fw-600">Kategori</label>
                        <select name="category_id" class="form-select form-control-admin" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-600">Nama Menu</label>
                        <input type="text" name="name" class="form-control form-control-admin" placeholder="Nama menu" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-600">Deskripsi</label>
                        <textarea name="description" class="form-control form-control-admin" rows="2" placeholder="Deskripsi singkat"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-600">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control form-control-admin" placeholder="25000" min="0" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_available" id="isAvailable" class="form-check-input" checked>
                            <label for="isAvailable" class="form-check-label small">Tersedia</label>
                        </div>
                    </div>
                    <button class="btn btn-admin-primary btn-admin w-100"><i class="bi bi-plus-lg me-1"></i>Tambah Menu</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar menu -->
    <div class="col-lg-8">
        <div class="section-card">
            <div class="section-header">
                <h5>Daftar Menu ({{ $menus->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                        <tr>
                            <td>
                                <div class="fw-600">{{ $menu->name }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $menu->description }}</div>
                            </td>
                            <td>{{ $menu->category->name }}</td>
                            <td class="fw-600">{{ $menu->formatted_price }}</td>
                            <td>
                                <span class="badge-status" style="background: {{ $menu->is_available ? '#D1FAE5' : '#FEE2E2' }}; color: {{ $menu->is_available ? '#065F46' : '#991B1B' }};">
                                    {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('admin.menus.update', $menu->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="category_id" value="{{ $menu->category_id }}">
                                        <input type="hidden" name="name" value="{{ $menu->name }}">
                                        <input type="hidden" name="description" value="{{ $menu->description }}">
                                        <input type="hidden" name="price" value="{{ $menu->price }}">
                                        <input type="hidden" name="is_available" value="{{ $menu->is_available ? '' : '0' }}">
                                        @if($menu->is_available)
                                            <button type="submit" class="btn btn-sm btn-admin-outline btn-admin" title="Nonaktifkan">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-admin-accent btn-admin" title="Aktifkan">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        @endif
                                    </form>
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu->id) }}" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-admin-danger btn-admin"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada menu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection