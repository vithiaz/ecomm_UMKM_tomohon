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
                ><a href="#">Market Place</a></li>
                <li><a href="#">Produk</a></li>
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

    // Toggle Navbar Dropdown Menu
    $('#user-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-auth-dropdown').toggleClass('active')
        $('#navbar-hidden-menu').toggleClass('active')
        HideNavbarSearch()
    })
    $('#navbar-hidden-menu').click(function() {
        $( this ).toggleClass('active')
        $('#user-dropdown-btn').toggleClass('active')
        $('.navbar-auth-dropdown').toggleClass('active')
        $('#navbar-hidden-menu').toggleClass('active')
        HideNavbarSearch()
    })

    // Toggle Navbar Link Dropdown
    $('#navbar-menu-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-menu-dropdown').toggleClass('active')
        HideNavbarSearch()
    })
    
    // Toggle Navbar Search
    $('#navbar-search-dropdown-btn').click(function() {
        $( this ).toggleClass('active')
        $('.navbar-search-dropdown').toggleClass('active')
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
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

    $( window ).resize(function() {
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
        HideNavbarSearch()
    })

    $( window ).scroll(function() {
        HideNavbarHiddenMenu()
        HideNavbarLinkDropdown()
        HideNavbarSearch()
    })

    

        
</script>
@endpush