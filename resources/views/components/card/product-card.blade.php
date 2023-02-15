<div class="swiper-slide product-card">
    <div class="image-container">
        <img src="{{ $img }}" alt="">
    </div>
    <div class="card-content">
        <div class="price-wrapper">
            <span class="main">Rp. {{ $price }}</span>
            <span class="secondary">Rp. {{ $basePrice }}</span>
        </div>
        <div class="store-wrapper">
            <span class="store">{{ $umkm }}</span>
            <span class="discount">-{{ $discount }}%</span>
        </div>
        <div class="stock-wrapper">
            <div class="sale">Terjual {{ $sold }}</div>
            <div class="stock">Tersisa {{ $stock }}</div>
        </div>
        <a href="{{ $link }}" class="card-title">
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

