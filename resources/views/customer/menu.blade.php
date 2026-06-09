@extends('layouts.app')

@section('title', 'Menu')

@section('styles')
<style>
    .category-scroll {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        -webkit-overflow-scrolling: touch;
    }
    .category-scroll::-webkit-scrollbar { display: none; }

    .qty-control {
        display: inline-flex;
        align-items: center;
        gap: 0;
        border: 2px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }
    .qty-control button {
        background: none;
        border: none;
        width: 36px;
        height: 36px;
        font-size: 1.1rem;
        cursor: pointer;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .qty-control button:hover { background: var(--cream); }
    .qty-control .qty-val {
        width: 36px;
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        background: none;
    }
</style>
@endsection

@section('content')
<!-- Step indicator -->
<div class="step-indicator mb-3">
    <div class="step-dot done"></div>
    <div class="step-dot active"></div>
    <div class="step-dot"></div>
    <div class="step-dot"></div>
</div>

<h5 class="fw-bold mb-3">Pilih Menu</h5>

<!-- Kategori tabs -->
<div class="category-scroll mb-4">
    <button class="category-tab active" data-category="all">Semua</button>
    @foreach($categories as $cat)
        <button class="category-tab" data-category="{{ $cat->id }}">
            {{ $cat->icon ? $cat->icon . ' ' : '' }}{{ $cat->name }}
        </button>
    @endforeach
</div>

<!-- Daftar menu -->
<div class="row g-3" id="menuGrid">
    @foreach($categories as $category)
        @foreach($category->menus as $menu)
            <div class="col-6 col-md-4 menu-item" data-category-id="{{ $category->id }}">
                <div class="menu-card h-100 d-flex flex-column">
                    <img src="https://picsum.photos/seed/menu{{ $menu->id }}/400/300" alt="{{ $menu->name }}" class="menu-img" loading="lazy">
                    <div class="menu-body d-flex flex-column flex-grow-1">
                        <div class="menu-name">{{ $menu->name }}</div>
                        <div class="menu-desc">{{ $menu->description }}</div>
                        <div class="mt-auto d-flex align-items-center justify-content-between">
                            <div class="menu-price">{{ $menu->formatted_price }}</div>
                            @php
                                $inCart = isset($cart[$menu->id]) ? $cart[$menu->id]['quantity'] : 0;
                            @endphp
                            @if($inCart > 0)
                                <div class="qty-control">
                                    <button type="button" onclick="updateQty({{ $menu->id }}, 'decrease')">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <span class="qty-val" id="qty-{{ $menu->id }}">{{ $inCart }}</span>
                                    <button type="button" onclick="updateQty({{ $menu->id }}, 'increase')">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            @else
                                <form method="POST" action="{{ route('customer.add-to-cart', $table->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-accent btn-sm py-1 px-3">
                                        <i class="bi bi-plus-lg"></i> Tambah
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
</div>
@endsection

@section('cart-bar')
@php
    $cartCount = array_sum(array_map(fn($item) => $item['quantity'], $cart));
    $cartTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
@endphp
@if($cartCount > 0)
<div class="cart-bar no-print">
    <div class="d-flex align-items-center gap-3">
        <span class="cart-count">{{ $cartCount }}</span>
        <div>
            <div class="fw-600">{{ $cartCount }} item</div>
            <div class="small opacity-75">Rp {{ number_format($cartTotal, 0, ',', '.') }}</div>
        </div>
    </div>
    <a href="{{ route('customer.checkout', $table->id) }}" class="btn btn-accent py-2 px-4">
        Lihat Pesanan <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>
@endif
@endsection

@section('scripts')
<script>
// Filter kategori
document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const cat = this.dataset.category;
        document.querySelectorAll('.menu-item').forEach(item => {
            if (cat === 'all' || item.dataset.categoryId === cat) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Update quantity via fetch
function updateQty(menuId, action) {
    const tableId = {{ $table->id }};
    fetch(`{{ route('customer.update-cart', $table->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: `menu_id=${menuId}&action=${action}`
    }).then(() => location.reload());
}
</script>
@endsection