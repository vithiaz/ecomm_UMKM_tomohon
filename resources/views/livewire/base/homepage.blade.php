<div class="app">
    <section class="app-message">
        <div class="container">
            <div class="header-wrapper">
                <span class="header-title">
                    E-Commerce Kementrian Koperasi dan UKM
                </span>
                <div class="logo-wrapper">
                    <div class="logo-container">
                        <img src="{{ asset('img\logo_tomohon.png') }}" alt="Logo Tomohon">
                    </div>
                    <span>KOTA TOMOHON</span>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="content">
                    <p>Aplikasi ini membantu pengusaha UMKM di Kota Tomohon untuk mengenalkan produk yang dijual kepada masyarakat umum dengan media internet sehingga dapat meningkatkan penjualan.</p>
                </div>
                <a href="#" class="link-href">DAFTAR SEKARANG</a>
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="container">
            <div class="section-header dashed">
                <h1 class="section-header-title">Produk <span>Unggulan</span></h1>
            </div>
            <div class="section-content">
                <div class="swiper HeroProductSwiper">
                    <div class="swiper-wrapper">
                        <x-card.product-card 
                            basePrice='220000'
                            discount='50'
                            umkm='Mitra UMKM'
                            sold='2130'
                            stock='999'
                            productName='Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur adipisicing elit'
                            Location='Location'
                            img='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                            link='#'
                        />
                        @foreach (range(1,8) as $i)
                            <x-card.product-card 
                                basePrice='220000'
                                discount='50'
                                umkm='Mitra UMKM'
                                sold='2130'
                                stock='999'
                                productName='{{ $i }} - Lorem ipsum dolor sit amet consectetur adipisicing elit.'
                                Location='Location'
                                img='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                link='#'
                            />
                        @endforeach
                    </div>
                    <div class="swiper-pagination product-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>


    <section class="page-section">
        <div class="container">
            <div class="row-content-wrapper">
                <div class="row-section-header">
                    <h1 class="section-header-title">UMKM <span>Unggulan</span></h1>
                </div>
                <div class="row-section-content">
                    <div class="swiper HeroUMKMSwiper">
                        <div class="swiper-wrapper">

                            <x-card.umkm-card
                                 image='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                 name='Lorem ipsum dolor siLorem ipsum dolor sit amet Lipsums.' 
                                 location='Location'
                                 sold='2340'
                                 link='#'
                            />
                            @foreach (range(0,12) as $i)
                               <x-card.umkm-card
                                    image='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                    name='Lorem ipsum dolor sit amet.' 
                                    location='Location'
                                    sold='2340'
                                    link='#'
                               />
                            @endforeach
                        
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination umkm-swiper-pagination"></div>
            </div>
        </div>
    </section>


    <section class="page-section lighten">
        <div class="container">
            <div class="section-header">
                <h1 class="section-header-title">Produk Lainnya</h1>
            </div>
            <div class="section-content">
                <div class="product-wrapper">
                    <div class="item">
                        <x-card.product-card 
                            basePrice='220000'
                            discount='50'
                            umkm='Mitra UMKM'
                            sold='2130'
                            stock='999'
                            productName='Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur adipisicing elit'
                            Location='Location'
                            img='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                            link='#'
                        />
                    </div>
                    @foreach (range(0,8) as $i)
                        <div class="item">
                            <x-card.product-card 
                                basePrice='220000'
                                discount='50'
                                umkm='Mitra UMKM'
                                sold='2130'
                                stock='999'
                                productName='{{ $i }} - Lorem ipsum dolor sit amet consectetur adipisicing elit.'
                                Location='Location'
                                img='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                link='#'
                            />
                        </div>
                    @endforeach
                </div>
                <button class="section-content-button">Lebih Banyak</button>
            </div>
        </div>
    </section>
</div>

@push('script')
<script>

    var HeroProductSwiper = new Swiper(".HeroProductSwiper",
    {
        slidesPerView: 4,
        spaceBetween: 20,
        slidesPerGroup: 4,
        loop: false,
        loopFillGroupWithBlank: true,
        pagination: {
            el: ".product-swiper-pagination",
            clickable: true,
        },
        // navigation: {
        //     nextEl: ".swipe-next-btn",
        //     prevEl: ".swipe-prev-btn",
        // },
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

    var HeroUMKMSwiper = new Swiper('.HeroUMKMSwiper', 
    {
        slidesPerView: 3,
        spaceBetween: 10,
        slidesPerGroup: 3,
        loop: false,
        loopFillGroupWithBlank: true,
        pagination: {
            el: ".umkm-swiper-pagination",
            clickable: true,
        },
        // navigation: {
        //     nextEl: ".swipe-next-btn",
        //     prevEl: ".swipe-prev-btn",
        // },
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
            }
    });

    
</script>
@endpush