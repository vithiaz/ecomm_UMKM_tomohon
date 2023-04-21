<div id="side-menu" class="menu-container lighten">
    <div class="shadow-placeholder"></div>
    <div id="side-menu-toggle-btn" class="toggle-btn">
        <i class="fa-solid fa-angle-left"></i>
    </div>
    <div class="navigation navigation-menu">
        <div class="nav-header">
            <h1>Menu</h1>
        </div>
        <div class="nav-content">
            <ul id="side-menu-options" class="sub-menu-expand">
                <li
                    @if ( \Request::route()->getName() == 'account-settings')
                        class="nav-item active"
                    @else
                        class="nav-item"
                    @endif
                    >
                    <a href="{{ route('account-settings') }}" class="main-item">
                        <span class="nav-item-main">Akun dan Privasi</span>
                    </a>
                </li>
                <li
                    @if (   \Request::route()->getName() == 'cart-page' || 
                            \Request::route()->getName() == 'transaction-page')
                        class="nav-item expand-sub active"
                    @else
                        class="nav-item expand-sub"
                    @endif
                    >
                    <div class="main-item">
                        <span class="nav-item-main">Pesanan Saya</span>
                        <span class="nav-item-secondary arrow-btn">
                            <i class="fa-solid fa-angle-down"></i>
                        </span>
                    </div>
                    <div
                        @if (   \Request::route()->getName() == 'cart-page' || 
                                \Request::route()->getName() == 'transaction-page')
                            class="sub-item expand"
                        @else
                            class="sub-item"
                        @endif
                        >
                        <ul>
                            <li><a href="{{ route('cart-page') }}">Keranjang</a></li>
                            <li><a href="{{ route('transaction-page', ['status' => 'pending']) }}">Transaksi</a></li>
                        </ul>
                    </div>
                </li>
                <li
                    @if (   \Request::route()->getName() == 'umkm.profile' ||
                            \Request::route()->getName() == 'umkm.transaction')
                        class="nav-item expand-sub active"
                    @else
                        class="nav-item expand-sub"
                    @endif
                    >
                    <div class="main-item">
                        <span class="nav-item-main">Zona UMKM</span>
                        <span class="nav-item-secondary arrow-btn">
                            <i class="fa-solid fa-angle-down"></i>
                        </span>
                    </div>
                    <div
                        @if (   \Request::route()->getName() == 'umkm.profile' ||
                                \Request::route()->getName() == 'umkm.transaction')
                            class="sub-item expand"
                        @else
                            class="sub-item"
                        @endif
                        >
                        <ul>
                            <li><a href="{{ route('umkm.profile') }}">Profil</a></li>
                            <li><a href="{{ route('umkm.transaction', ['status' => 'pending']) }}">Transaksi</a></li>
                        </ul>
                    </div>
                </li>


                {{-- <li class="nav-item expand-sub">
                    <div class="main-item">
                        <span class="nav-item-main">Zona UMKM</span>
                        <span class="nav-item-secondary arrow-btn">
                            <i class="fa-solid fa-angle-down"></i>
                        </span>
                    </div>
                    <div class="sub-item">

                    </div>
                </li> --}}

            </ul>
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

    // Toggle Side Menu - Sub Menu
    $('#side-menu-options .expand-sub .main-item').click(function() {       
        const parent = $( this ).parents('.expand-sub')
        const subMenu = parent.find('.sub-item')
        subMenu.toggleClass('expand')
        parent.toggleClass('expand')
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