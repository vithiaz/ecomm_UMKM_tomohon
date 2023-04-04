<div class="swiper-slide product-card">
    <div class="image-container">
        @if ($product->product_images->first())
            <img src="{{ asset('storage/'.$product->product_images->first()->image) }}" alt="product_{{ $product->name_slug }}_profile">
        @else
            <div class="no-image">
                <i class="fa-solid fa-image"></i>
            </div>
        @endif
    </div>
    <div class="card-content">
        <div class="price-wrapper">
            <span class="main">{{ $this->get_final_price($product->price, $product->discount) }}</span>
            @if ($product->discount > 0)
                <span class="secondary">{{ format_rupiah($product->price) }}</span>
            @endif
        </div>
        <div class="store-wrapper">
            <span class="store">{{ $product->umkm->name }}</span>
            @if ($product->discount > 0)
                <span class="discount">-{{ $product->discount }}%</span>
            @endif
        </div>
        <div class="stock-wrapper">
            <div class="sale">Terjual 99</div>
            <div class="stock">Tersisa {{ $product->stock }}</div>
        </div>
        <a href="{{ route('product-details', [$product->id, $product->name_slug]) }}" class="card-title">
            {{ $product->name }}
        </a>
        <div class="bottom-wrapper">
            <div class="location-wrapper">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $product->umkm->district }}</span>
            </div>
            @auth
                <button wire:click='add_to_cart' class="btn cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
            @endauth
        </div>
    </div>
</div>

