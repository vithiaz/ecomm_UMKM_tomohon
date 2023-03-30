@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-product-verification.css') }}">
@endpush

<div class="admin-product-verification-page">
    <div class="container">
        <div class="page-title">
            <h1>Verifikasi Pendaftaran Produk</h1>
        </div>
        <div class="content-menu-wrapper">
            <span class="menu-item active">Permintaan</span>
            <span class="menu-item">Ditolak</span>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Produk</span>
                <input type="text" class="card-search-input" placeholder="Cari Produk ...">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nama Produk</td>
                            <td>Harga</td>
                            <td>UMKM Penjual</td>
                            <td>Nama Penjual</td>
                            <td>Tindakan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(0,9) as $item)
                            <tr>
                                <td>28223</td>
                                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                                <td>Rp. 250.000,00</td>
                                <td>Mitra UMKM</td>
                                <td>John Doe</td>
                                <td>
                                    <a href="#" class="table-a">Verifikasi</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    
            <div class="pagination-wrapper">
    
            </div>
        </div>


    </div>
    


</div>
