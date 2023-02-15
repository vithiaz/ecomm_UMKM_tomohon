<div class="swiper-slide UMKM-card">
    <div class="image-container">
        <img src="{{ $image }}" alt="">
    </div>
    <div class="card-content">
        <div class="card-title">
            <a href="{{ $link }}">{{ $name }}</a>
        </div>
        <div class="info-wrapper">
            <div class="location-wrapper">
                <i class="fa-solid fa-location-dot"></i>
                <span>{{ $location }}</span>
            </div>
            <span class="sold">{{ $sold }} Penjualan</span>
        </div>
    </div>
</div>