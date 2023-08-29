<div class="swiper-slide UMKM-card">
    <div class="image-container">
        @if ($image)
            <img src="{{ asset("storage/". $image) }}" alt="">
        @else
            <div class="no-image">
                <i class="fa-solid fa-image"></i>
            </div>        
        @endif
    </div>
    <div class="card-content">
        <div class="card-title">
            <a href="{{ route('umkm-products', ['umkm_id' => $link]) }}">{{ $name }}</a>
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