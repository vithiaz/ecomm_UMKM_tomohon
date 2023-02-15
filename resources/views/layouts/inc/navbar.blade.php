<nav id="navbar">
    <div class="container">
        <div class="logo-wrapper">
            <div class="logo-img-container">
                <img src="{{ asset('img\koperasi_dan_UMKM_RI_logo.png') }}" alt="logo_KEMENKOPUKM">
            </div>
            <div class="logo-text-container">
                <span class="logo-text-main">KEMENKOPUKM</span>
                <span class="logo-text-sub">KOTA TOMOHON</span>
            </div>
        </div>
        <div class="menu-wrapper">
            <ul>
                <li
                    @if (\Request::route()->getName() == 'homepage')
                        class="active"
                    @endif
                ><a href="{{ route('homepage') }}">Market Place</a></li>
                <li
                    @if (\Request::route()->getName() == 'product-page')
                        class="active"
                    @endif
                ><a href="{{ route('product-page') }}">Produk</a></li>
                <li><a href="#">UMKM</a></li>
                <li id="navbar-menu-dropdown-btn" class="menu-dropdown"><i class="fa-solid fa-angle-down"></i></li>
            </ul>
        </div>
        <div class="auth-wrapper">
            <div id="navbar-search-dropdown-btn" class="search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div id="navbar-hidden-menu" class="hidden-menu">
                <i class="fa-solid fa-bars"></i>
            </div>
            {{-- <div class="auth">
                <button class="btn auth-secondary">Masuk</button>
                <button class="btn auth-primary">Daftar</button>
            </div> --}}
            <div class="user">
                <span class="username">Username</span>
                <div id="user-dropdown-btn" class="btn btn-dropdown"><i class="fa-solid fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</nav>

<div class="navbar-placeholder"></div>

<div class="navbar-auth-dropdown">
    <div class="container">
        {{-- <div class="auth">
            <button class="btn auth-secondary">Masuk</button>
            <button class="btn auth-primary">Daftar</button>
        </div> --}}
        <div class="user">
            <div class="img-container">
                <img src="{{ asset('img\aziz-acharki-boIJluEJEPM-unsplash.jpg') }}" alt="">
            </div>
            <div class="username-container">
                <span class="username">
                    Username
                </span>
                <button class="btn"><i class="fa-solid fa-gear"></i></button>
            </div>

        </div>
        <ul>
            <li><a href="#">Pengaturan Akun</a></li>
            <li><a href="#">Pesanan Saya</a></li>
            <li><a href="#">Zona UMKM</a></li>
            <li><a href="#">Keluar</a></li>
        </ul>
    </div>
</div>

<div class="navbar-menu-dropdown">
    <div class="container">
        <ul>
            <li
            @if (\Request::route()->getName() == 'homepage')
                class="active"
            @endif
            ><a href="#">Market Place</a></li>
            <li><a href="#">Produk</a></li>
            <li><a href="#">UMKM</a></li>
    
        </ul>
    </div>
</div>

<div class="navbar-search-dropdown">
    <div class="container">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari Produk atau UMKM">
            <button class="btn btn-outline-secondary" type="button" id="navbar-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
    </div>
</div>

@push('script')
<script>

    function adaptNavbarDropdown(DropdownClass, top = 0) {
        let navbarOffset = $('#navbar').offset().top
        let navbarHeight = $('#navbar').height()
        DropdownClass.css('top', navbarOffset + navbarHeight + top + 'px')
    }

    // Toggle Navbar Dropdown Menu
    $('#user-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-auth-dropdown').toggleClass('active')
        $('#navbar-hidden-menu').toggleClass('active')
        HideNavbarSearch()
        adaptNavbarDropdown($('.navbar-auth-dropdown'))
    })
    $('#navbar-hidden-menu').click(function() {
        $( this ).toggleClass('active')
        $('#user-dropdown-btn').toggleClass('active')
        $('.navbar-auth-dropdown').toggleClass('active')
        $('#navbar-hidden-menu').toggleClass('active')
        HideNavbarSearch()
        adaptNavbarDropdown($('.navbar-auth-dropdown'))
    })

    // Toggle Navbar Link Dropdown
    $('#navbar-menu-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-menu-dropdown').toggleClass('active')
        HideNavbarSearch()
        adaptNavbarDropdown($('.navbar-menu-dropdown'))
    })
    
    // Toggle Navbar Search
    $('#navbar-search-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-search-dropdown').toggleClass('active')
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
        adaptNavbarDropdown($('.navbar-search-dropdown'), 10)
    })

    function HideNavbarHiddenMenu() {
        $('#user-dropdown-btn').removeClass('active')
        $('.navbar-auth-dropdown').removeClass('active')
        $('#navbar-hidden-menu').removeClass('active')
    }
    
    function HideNavbarLinkDropdown() {
        $('#navbar-menu-dropdown-btn').removeClass('active')
        $('.navbar-menu-dropdown').removeClass('active')
    }

    function HideNavbarSearch() {
        $('#navbar-search-dropdown-btn').removeClass('active')
        $('.navbar-search-dropdown').removeClass('active')
    }

    
    // Navbar Scroll Positioning
    function scroll_up() {
        $('#navbar').addClass('scroll-up');
        $('#navbar').removeClass('scroll-down');
    }
    
    function scroll_down() {
        $('#navbar').addClass('scroll-down');
        $('#navbar').removeClass('scroll-up');
    }

    let lastScroll = 0;
    $(window).scroll(function() {
        if (this.scrollY <= 0) {
            $('#navbar').removeClass('scroll-up')
            $('#navbar').removeClass('floating')
        }
        
        if (this.scrollY >= $('#navbar').height()) {
        $('.navbar-placeholder').height( $('#navbar').height() );

            if (this.scrollY > lastScroll && !$('#navbar').hasClass('scroll-down')) {
                scroll_down()
            }
            if (this.scrollY <= lastScroll && $('#navbar').hasClass('scroll-down')) {
                scroll_up();
            }
        }
        lastScroll = this.scrollY;
    })


    // Window Resize Events
    $( window ).resize(function() {
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
        HideNavbarSearch()

        adaptNavbarDropdown($('.navbar-auth-dropdown'))
        adaptNavbarDropdown($('.navbar-auth-dropdown'))
        adaptNavbarDropdown($('.navbar-menu-dropdown'))
        adaptNavbarDropdown($('.navbar-search-dropdown'), 10)
    })

    // Window Scroll Events
    $( window ).scroll(function() {
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
        HideNavbarSearch()

        adaptNavbarDropdown($('.navbar-auth-dropdown'))
        adaptNavbarDropdown($('.navbar-auth-dropdown'))
        adaptNavbarDropdown($('.navbar-menu-dropdown'))
        adaptNavbarDropdown($('.navbar-search-dropdown'), 10)
    })

    

        
</script>
@endpush