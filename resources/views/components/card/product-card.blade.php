<div class="swiper HeroProductSwiper">
    <div class="swiper-wrapper">
        @foreach (range(1,12) as $i)
            <div class="swiper-slide product-card">
                
                <div class="image-container">
                    <img src="{{ asset('img\aziz-acharki-boIJluEJEPM-unsplash.jpg') }}" alt="">
                </div>
                <div class="card-content">
                    <div class="price-wrapper">
                        <span class="main">Rp. 230.000,00</span>
                        <span class="secondary">250.000,00</span>
                    </div>
                    <div class="store-wrapper">
                        <span class="store">Mitra UMKM</span>
                        <span class="discount">-5%</span>
                    </div>
                    <div class="stock-wrapper">
                        <div class="sale">Terjual 1.2rb</div>
                        <div class="stock">Tersisa 999</div>
                    </div>
                    <a href="#" class="card-title">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </a>
                    <div class="bottom-wrapper">
                        <div class="location-wrapper">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Location</span>
                        </div>
                        <button class="btn cart-btn">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
            </div>                        
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
</div>

@push('script')
<script>

    var swiper = new Swiper(".HeroProductSwiper", {
        slidesPerView: 4,
        spaceBetween: 20,
        slidesPerGroup: 4,
        loop: true,
        loopFillGroupWithBlank: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swipe-next-btn",
            prevEl: ".swipe-prev-btn",
        },
        breakpoints: {
                0: {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                },
                430: {
                    slidesPerView: 2,
                    slidesPerGroup: 2,
                },
                768: {
                    slidesPerView: 3,
                    slidesPerGroup: 3,
                },
                1200: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                },
            }
    });

    
</script>
@endpush