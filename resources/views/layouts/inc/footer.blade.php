<nav id="footer">
    <div class="container">
        <div class="content-wrapper">
            <div class="head-logo-wrapper">
                <div class="head-title">
                    <span>Market Place Dinas Koperasi dan UKM</span>
                </div>
                <div class="logo-wrapper">
                    <div class="logo-img-container">
                        <img src="{{ asset('img\koperasi_dan_UMKM_RI_logo.png') }}" alt="logo_DISKOPUKM">
                    </div>
                    <div class="logo-text-container">
                        <span class="logo-text-main">DISKOPUKM</span>
                        <span class="logo-text-sub">KOTA TOMOHON</span>
                    </div>
                </div>
            </div>
            <div class="description">
                <p>Dinas Koperasi, Usaha Kecil dan Menengah mempunyai tugas pokok melaksanakan urusan pemerintahan daerah bidang koperasi dan usaha kecil dan menengah berdasarkan asas otonomi daerah dan tugas pembantuan.</p>
            </div>
            <div class="menu-wrapper">
                <ul>
                    <li><a href="{{ route('homepage') }}">Market Place</a></li>
                    <li><a href="{{ route('product-page', [0]) }}">Produk</a></li>
                    <li><a href="{{ route('umkm-page') }}">UMKM</a></li>
                    @auth
                        <li><a href="{{ route('umkm.profile') }}">Pendaftaran UMKM</a></li>    
                    @else
                        <li><a href="{{ route('register') }}">Pendaftaran UMKM</a></li>    
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>