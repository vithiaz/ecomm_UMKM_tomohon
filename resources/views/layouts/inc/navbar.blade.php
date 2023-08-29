<nav id="navbar">
    <div class="container">
        <div class="logo-wrapper">
            <div class="logo-img-container">
                <img src="{{ asset('img\koperasi_dan_UMKM_RI_logo.png') }}" alt="logo_DISKOPUKM">
            </div>
            <div class="logo-text-container">
                <span class="logo-text-sub">MARKET PLACE</span>
                <span class="logo-text-main">KOTA TOMOHON</span>
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
                ><a href="{{ route('product-page', [0]) }}">Produk</a></li>
                <li
                    @if (\Request::route()->getName() == 'umkm-page')
                        class="active"
                    @endif
                ><a href="{{ route('umkm-page') }}">UMKM</a></li>
                <li id="navbar-menu-dropdown-btn" class="menu-dropdown">
                    <i class="fa-solid fa-angle-down"></i>
                </li>
            </ul>
        </div>
        <div class="auth-wrapper">
            <div id="navbar-search-dropdown-btn" class="search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <div id="navbar-hidden-menu" class="hidden-menu">
                <i class="fa-solid fa-bars"></i>
            </div>
            
            @auth
                <div class="user">
                    <span class="username">{{ Auth::user()->username }}</span>
                    <div id="user-dropdown-btn" class="btn btn-dropdown"><i class="fa-solid fa-angle-down"></i></div>
                </div>
            @else
                <div class="auth">
                    <button class="btn auth-secondary" data-bs-toggle="modal" data-bs-target="#auth_modal">Masuk</button>
                    <a href="{{ route('register') }}" class="btn auth-primary">Daftar</a>
                </div>            
            @endauth

        </div>
    </div>
</nav>

<livewire:components.login />

<div class="navbar-placeholder"></div>

<div class="navbar-auth-dropdown">
    <div class="container">

        @auth
            <div class="user">
                <div class="img-container">
                    @if (Auth::user()->profile_img)
                        <img src="{{ asset('storage/'.Auth::user()->profile_img) }}" alt="{{ Auth::user()->first_name }}_profile">
                    @else
                        <div class="no-image">
                            <i class="fa-solid fa-user"></i>
                        </div>                        
                    @endif
                </div>
                <div class="username-container">
                    <span class="username">
                        {{ Auth::user()->username }}
                    </span>
                    <a href="{{ route('account-settings') }}" class="btn"><i class="fa-solid fa-gear"></i></a>
                </div>
            </div>
            <ul>
                @if (Auth::user()->user_type == 1)
                    <li><a href="{{ route('admin.products', ['status' => 'active']) }}">Admin Panel</a></li>
                @endif
                <li><a href="{{ route('account-settings') }}">Pengaturan Akun</a></li>
                <li><a href="{{ route('cart-page') }}">Pesanan Saya</a></li>
                <li><a href="{{ route('umkm.profile') }}">Zona UMKM</a></li>
                {{-- <li><a href="#">Keluar</a></li> --}}
                <li><a href="{{ route('logout') }}">Keluar</a></li>
            </ul>           
        @else
            <div class="auth">
                <button class="btn auth-secondary" data-bs-toggle="modal" data-bs-target="#auth_modal">Masuk</button>
                <a href="{{ route('register') }}" class="btn auth-primary">Daftar</a>
            </div> 
        @endauth

    </div>
</div>

<div class="navbar-menu-dropdown">
    <div class="container">
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
            ><a href="{{ route('product-page', [0]) }}">Produk</a></li>
            <li
                @if (\Request::route()->getName() == 'umkm-page')
                    class="active"
                @endif
            ><a href="{{ route('umkm-page') }}">UMKM</a></li>
    
        </ul>
    </div>
</div>

<div class="navbar-search-dropdown">
    <div class="container">
        <form id="navbar-search-form" action="{{ route('search-page', ['search_key' => '__SEARCH_KEY__']) }}" class="input-group">
            @csrf
            <input type="text" class="form-control" name="search_key" id="navbar-search-input" placeholder="Cari Produk atau UMKM">
            <button class="btn btn-outline-secondary" type="submit" id="navbar-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
</div>

@push('script')
<script>

    document.getElementById('navbar-search-form').addEventListener('submit', function(event) {
        event.preventDefault();

        var searchKey = document.getElementById('navbar-search-input').value;
        var formAction = this.action.replace('__SEARCH_KEY__', encodeURIComponent(searchKey));

        // Redirect to the generated form action
        window.location.href = formAction;
    });

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