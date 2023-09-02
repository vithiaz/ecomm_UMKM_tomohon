<div class="app">
    <div class="row-container">
        <div id="side-menu" class="menu-container">
            <div class="shadow-placeholder"></div>
            <div id="side-menu-toggle-btn" class="toggle-btn">
                <i class="fa-solid fa-angle-left"></i>
            </div>
            <div class="navigation card">
                <div class="nav-header">
                    <h1>Kategori</h1>
                </div>
                <div class="nav-content">
                    <ul class="category-list">
                        @foreach ($categories as $category)
                            <li class="nav-item">
                                <a href="{{ route('product-page', [$category->name_slug]) }}">
                                    <span class="nav-item-main">{{ $category->name }}</span>
                                    <span class="nav-item-secondary">{{ $this->count_filter_product_active_umkm($category->product_active) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-section content-container">
            <div class="container">
                <div class="section-header wrapped">
                    <h1 class="section-header-title">Daftar <span>Produk</span></h1>
                </div>
                <div class="section-content fill-height">
                    <div class="product-wrapper">
                        @forelse ($products as $product)
                            <div class="item">
                                <x-card.product-card 
                                    productId='{{ $product->id }}'
                                    basePrice='{{ $product->price }}'
                                    discount='{{ $product->discount }}'
                                    umkm='{{ $product->umkm->name }}'
                                    umkmId='{{ $product->umkm->id }}'
                                    sold='{{ $this->count_success_transaction($product->order_item) }}'
                                    stock='{{ $product->stock }}'
                                    productName='{{ $product->name }}'
                                    productNameSlug='{{ $product->name_slug }}'
                                    Location='{{ $product->umkm->district }}'
                                    img='{{ $product->profile_image ? $product->profile_image->image : "" }}'
                                />
                                {{-- <livewire:component.product-card :product='$product' /> --}}
                                {{-- @livewire('component.product-card', ['product' => $product]) --}}

                            </div>
                        @empty
                            <div class="empty-card full-basis fill-height">
                                <i class="fa-solid fa-exclamation"></i>
                                <span>tidak ada produk . . .</span>
                            </div>
                        @endforelse
                    </div>
                    @if (!$all_loaded_state)
                        <button wire:click='load_more' class="section-content-button">Lebih Banyak</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>

    function AdaptSideMenuPositioning() {   
        if ( !$('#navbar').hasClass('scroll-down') ) {
            $('.menu-container .shadow-placeholder').addClass('fill')
            $('#side-menu-toggle-btn').removeClass('higher')
        } else {
            $('.menu-container .shadow-placeholder').removeClass('fill')
            $('#side-menu-toggle-btn').addClass('higher')
        }
    }

    // Toggle Side Menu
    $('#side-menu-toggle-btn').click(function() {
        $('#side-menu').toggleClass('hide')
        $('#side-menu-toggle-btn').toggleClass('hide')
    })

    // Document Mount
    $(document).ready(function() {
        AdaptSideMenuPositioning()
    })

    // Window Scrolling Events
    $(window).scroll(function() {
        AdaptSideMenuPositioning()
    })


</script>
@endpush