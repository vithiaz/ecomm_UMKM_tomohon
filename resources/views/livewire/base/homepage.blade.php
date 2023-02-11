<div class="app">
    <section class="app-message">
        <div class="container">
            <div class="header-wrapper">
                <span class="header-title">
                    E-Commerce Kementrian Koperasi dan UKM
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
                <a href="#" class="link-href">DAFTAR SEKARANG</a>
            </div>
        </div>
    </section>
    <section class="page-section">
        <div class="container">
            <div class="section-header">
                <h1 class="section-header-title">Produk <span>Unggulan</span></h1>
            </div>
            <div class="section-content">
                <x-card.product-card />
            </div>
        </div>
    </section>
</div>

