@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-product-verification.css') }}">
@endpush

<div class="admin-product-page">
    <div class="container">
        <div class="page-title">
            <h1>Daftar Produk</h1>
        </div>
        <div class="content-menu-wrapper">
            <a href='{{ route("admin.products", ['status' => 'active']) }}' class='menu-item @if($status == "active") active @endif'>Aktif</a>
            <a href='{{ route("admin.products", ['status' => 'disabled']) }}' class='menu-item @if($status == "disabled") active @endif'>Nonaktif</a>
            <a href='{{ route("admin.products", ['status' => 'revoked']) }}' class='menu-item @if($status == "revoked") active @endif'>Ditarik</a>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Produk</span>
                {{-- <input type="text" class="card-search-input" placeholder="Cari Produk ..."> --}}
            </div>
            
            <livewire:admin.product-table status='{{ $status }}'/>

            
            {{-- <div class="table-responsive">
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
                                    <a href="#" class="table-a">Tinjau</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
            </div> --}}
        </div>


    </div>
    


</div>
