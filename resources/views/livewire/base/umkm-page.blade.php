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
            <div class="row-content-wrapper">
                <div class="row-section-header">
                    <h1 class="section-header-title">UMKM <span>Unggulan</span></h1>
                </div>
                <div class="row-section-content">
                    <div class="swiper HeroUMKMSwiper">
                        <div class="swiper-wrapper">

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

    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <div class="section-header-title">
                    UMKM Lainnya
                </div>
            </div>
            <div class="section-content">
                <div class="umkm-wrapper">
                    @foreach (range(0,8) as $item)
                        <div class="item">
                            <x-card.umkm-card
                                image='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                name='Lorem ipsum dolor siLorem ipsum dolor sit amet Lipsums.' 
                                location='Location'
                                sold='2340'
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