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
            <div class="section-header dashed">
                <h1 class="section-header-title">Produk <span>Unggulan</span></h1>
            </div>
            <div class="section-content">
                <div class="swiper HeroProductSwiper">
                    <div class="swiper-wrapper">
                        @forelse ($hero_product as $product)
                            <x-card.product-card 
                                productId='{{ $product["id"] }}'
                                basePrice='{{ $product["price"] }}'
                                discount='{{ $product["discount"] }}'
                                umkm='{{ $product["umkm"]["name"] }}'
                                sold='{{ $product["sales_qty"] }}'
                                stock='{{ $product["stock"] }}'
                                productName='{{ $product["name"] }}'
                                productNameSlug='{{ $product["name_slug"] }}'
                                Location='{{ $product["umkm"]["district"] }}'
                                img='{{ $product["profile_image"]["image"] }}'
                            />
                            {{-- @dump($this->get_product_model($product_id)) --}}
                            {{-- <livewire:component.product-card :product='$this->get_product_model($product_id)' /> --}}
                        @empty
                            <div class="empty-card">
                                <i class="fa-solid fa-exclamation"></i>
                                <span>tidak ada produk . . .</span>
                            </div>
                        @endforelse
                    </div>
                    <div class="swiper-pagination product-swiper-pagination"></div>
                </div>
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
                            {{-- <x-card.umkm-card
                                 image='{{ asset("img\aziz-acharki-boIJluEJEPM-unsplash.jpg") }}'
                                 name='Lorem ipsum dolor siLorem ipsum dolor sit amet Lipsums.' 
                                 location='Location'
                                 sold='2340'
                                 link='#'
                            /> --}}
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


    <section class="page-section lighten">
        <div class="container">
            <div class="section-header">
                <h1 class="section-header-title">Produk Lainnya</h1>
            </div>
            <div class="section-content">
                <div wire:ignore.self class="product-wrapper">
                    @forelse ($other_product as $product)
                    <div class="item">
                        <x-card.product-card 
                            productId='{{ $product->id }}'
                            basePrice='{{ $product->price }}'
                            discount='{{ $product->discount }}'
                            umkm='{{ $product->umkm->name }}'
                            sold='{{ $product->sales_qty }}'
                            stock='{{ $product->stock }}'
                            productName='{{ $product->name }}'
                            productNameSlug='{{ $product->name_slug }}'
                            Location='{{ $product->umkm->district }}'
                            img='{{ $product->profile_image ? $product->profile_image->image : "" }}'
                        />
                    </div>
                    @empty
                        <div class="empty-card full-basis">
                            <i class="fa-solid fa-exclamation"></i>
                            <span>tidak ada produk . . .</span>
                        </div>
                    @endforelse
                </div>
                @if (!$all_loaded_state)
                    <button wire:click='load_more' class="section-content-button">Lebih Banyak</button>
                @else
                    @if (count($other_product) > 0)
                        <a href="{{ route('product-page', ['category_slug' => 0]) }}" class="section-content-button" style="text-decoration: none">Lihat Selengkapnya</a>
                    @endif
                @endif
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