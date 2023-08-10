<div class="app">
    <section class="app-message">
        <div class="container">
            <div class="header-wrapper">
                <span class="header-title">
                    E-Commerce Dinas Koperasi dan UKM
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
                @auth
                    <a href="{{ route('umkm.profile') }}" class="link-href">DAFTAR SEKARANG</a>
                @else
                    <a href="{{ route('register') }}" class="link-href">DAFTAR SEKARANG</a>
                @endauth
            </div>
        </div>
    </section>

    <section wire:ignore class="page-section">
        <div class="container">
            <div class="row-content-wrapper">
                <div class="row-section-header">
                    <h1 class="section-header-title">UMKM <span>Unggulan</span></h1>
                </div>
                <div class="row-section-content">
                    <div class="swiper HeroUMKMSwiper">
                        <div class="swiper-wrapper">

                            @forelse ($popularUmkm as $umkm)
                                <x-card.umkm-card
                                    image='{{ $umkm->profile_img }}'
                                    name='{{ $umkm->name }}' 
                                    location='{{ $umkm->district }}'
                                    sold='{{ $umkm->success_transaction_count }}'
                                    link='#'
                                />
                            @empty
                                <div class="swiper-slide UMKM-card empty">
                                    <i class="fa-solid fa-exclamation"></i>
                                    <span>Tidak ada UMKM</span>
                                </div>
                            @endforelse
                        
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
            <div class="section-content fill-height">
                <div class="umkm-wrapper">
                    @forelse ($other_umkm as $umkm)
                        <div class="item">
                            <x-card.umkm-card
                                image='{{ $umkm->profile_img }}'
                                name='{{ $umkm->name }}' 
                                location='{{ $umkm->district }}'
                                sold='{{ $umkm->success_transaction_count }}'
                                link='#'
                            />
                        </div>
                    @empty
                        <div class="empty-card full-basis fill-height">
                            <i class="fa-solid fa-exclamation"></i>
                            <span>tidak ada UMKM . . .</span>
                        </div>
                    @endforelse
                </div>
                @if (!$all_loaded_state)
                    <button wire:click='load_more' class="section-content-button">Lebih Banyak</button>
                @endif
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