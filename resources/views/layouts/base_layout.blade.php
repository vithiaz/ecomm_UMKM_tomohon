<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    {{-- SwiperJS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>

    {{-- Font Awesome --}}
    <link href="{{ asset('/fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('/fontawesome/css/solid.css') }}" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- @stack('name') --}}

    @stack('stylesheet')

    @livewireStyles
    @powerGridStyles
</head>
<body>

    {{-- Content Here --}}
    @yield('base_layout_content')

    {{-- Session Message --}}
    <div id="session-message" class="session-message-card" data-notify='{{ session('message') }}'>
        <div class="content">
            <i class="success fa-solid fa-circle-check"></i>
            <i class="info fa-solid fa-circle-info"></i>
            <i class="danger fa-solid fa-circle-exclamation"></i>
            <span class="message">Message Placeholder</span>
        </div>
        <div class="close-button">
            <i class="success fa-solid fa-xmark"></i>
        </div>
    </div>

    {{-- Dependencies /////////////////////// --}}

    {{-- JQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    {{-- Bootstrap 5 Scripts CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    {{-- SwiperJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    {{-- Session Message Events Scripts--}}
    <script>
        function show_message($msg, $class) {
            $('.session-message-card').addClass($class);
            $('.session-message-card .message').text($msg);
            $('.session-message-card').addClass('active');
            setTimeout(function() {
                $('.session-message-card').removeClass('active');
            }, 3000);
        }

        $(document).ready(function () {
            $('.session-message-card').removeClass('success');
            $('.session-message-card').removeClass('danger');
            $('.session-message-card').removeClass('info');

            if( $('#session-message').data('notify') ) {
                show_message($('#session-message').data('notify'), 'success');
            }
        });

        $('.session-message-card .close-button').click(function () {
            $('.session-message-card').removeClass('active');
        });

        $(window).on('display-message', function (event) {
            $('.session-message-card').removeClass('success');
            $('.session-message-card').removeClass('danger');
            $('.session-message-card').removeClass('info');

            if (event.detail.success) {
                show_message(event.detail.success, 'success');
            }
            else if (event.detail.error) {
                show_message(event.detail.error, 'danger');          
            }
            else {
                show_message(event.detail.info, 'info');          
            }
        });
    </script>

    {{-- Helper Function --}}
    <script>
        function formatRupiah(number) {
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            return formatter.format(number).replace(/(\,00$)|(\.00$)/, '');
        }

    </script>

    {{-- PowerGrid Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" 
            crossorigin="anonymous"></script>
    
    @stack('script')

    {{-- AlpineJS  --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireScripts
    @powerGridScripts
</body>

</html>