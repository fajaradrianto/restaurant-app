@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-5">
        <div class="text-center mb-4 mt-3">
            <div class="step-indicator">
                <div class="step-dot active"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
                <div class="step-dot"></div>
            </div>
            <h4 class="font-display fw-bold mt-3">Selamat Datang</h4>
            <p class="text-muted">Meja {{ $table->number }} &mdash; Silakan isi data diri Anda untuk memulai pemesanan</p>
        </div>

        <div class="bg-white rounded-4 p-4 border" style="border-color: var(--border) !important;">
            <form method="POST" action="{{ route('customer.store-customer', $table->id) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-custom"
                           placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600">Nomor Handphone <span class="text-danger">*</span></label>
                    <input type="tel" name="phone" class="form-control form-control-custom"
                           placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-600">E-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-custom"
                           placeholder="email@contoh.com" value="{{ old('email') }}" required>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-5">
                    Lihat Menu <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-muted small mt-3">
            <i class="bi bi-shield-lock me-1"></i>Data Anda aman dan hanya digunakan untuk pemesanan
        </p>
    </div>
</div>
@endsection