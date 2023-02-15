<div class="app">
    <div class="row-container">
        <div class="menu-container">
            <div class="shadow-placeholder"></div>
            <div id="side-menu-toggle-btn" class="toggle-btn">
                <i class="fa-solid fa-angle-left"></i>
            </div>

            <div class="navigation card">
                <div class="nav-header">
                    <h1>Kategori</h1>
                    
                </div>
                <div class="nav-content">
                    <ul>
                        <li class="nav-item active">
                            <span class="nav-item-main">Makanan</span>
                            <span class="nav-item-secondary">(123)</span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-item-main">Elektronik</span>
                            <span class="nav-item-secondary">(110)</span>
                        </li>
                        <li class="nav-item">
                            <span class="nav-item-main">Perabotan</span>
                            <span class="nav-item-secondary">(96)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-section content-container">
            <div class="container">
                <div class="section-header wrapped">
                    <h1 class="section-header-title">Daftar <span>Produk</span></h1>
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