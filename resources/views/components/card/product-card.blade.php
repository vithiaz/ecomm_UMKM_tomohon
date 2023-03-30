<div class="swiper-slide product-card">
    <div class="image-container">
        @if ($img)
            <img src="{{ asset('storage/'.$img) }}" alt="">
        @else
            <div class="no-image">
                <i class="fa-solid fa-image"></i>
            </div>
        @endif
    </div>
    <div class="card-content">
        <div class="price-wrapper">
            <span class="main">Rp. {{ $price }}</span>
            @if ($discount > 0)
                <span class="secondary">Rp. {{ $basePrice }}</span>
            @endif
        </div>
        <div class="store-wrapper">
            <span class="store">{{ $umkm }}</span>
            @if ($discount > 0)
                <span class="discount">-{{ $discount }}%</span>
            @endif
        </div>
        <div class="stock-wrapper">
            <div class="sale">Terjual {{ $sold }}</div>
            <div class="stock">Tersisa {{ $stock }}</div>
        </div>
        <a href="{{ route('product-details', [$productId, $productNameSlug]) }}" class="card-title">
            {{ $productName }}
        </a>
        <div class="bottom-wrapper">
            <div class="location-wrapper">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $location }}</span>
            </div>
            <button class="btn cart-btn">
                <i class="fa-solid fa-cart-shopping"></i>
            </button>
        </div>
    </div>
</div>

