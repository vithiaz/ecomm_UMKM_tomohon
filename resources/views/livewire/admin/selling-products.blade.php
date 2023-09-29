@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-product-verification.css') }}">
@endpush

<div class="admin-product-page">
    <div class="container">
        <div class="page-title">
            <h1>Penjualan Produk</h1>
        </div>
        <div class="content-menu-wrapper">
            {{-- <span href='#' wire:click='set_date_filter("weekly")' class='menu-item @if($this->date_filter == "weekly") active @endif'>Mingguan</span>
            <span href='#' wire:click='set_date_filter("monthly")' class='menu-item @if($this->date_filter == "monthly") active @endif'>Bulanan</span>
            <span href='#' wire:click='set_date_filter("alltime")' class='menu-item @if($this->date_filter == "alltime") active @endif'>Semua</span> --}}
            {{-- <a href='{{ route("admin.products", ['status' => 'active']) }}' class='menu-item @if($status == "active") active @endif'>Aktif</a>
            <a href='{{ route("admin.products", ['status' => 'disabled']) }}' class='menu-item @if($status == "disabled") active @endif'>Nonaktif</a>
            <a href='{{ route("admin.products", ['status' => 'revoked']) }}' class='menu-item @if($status == "revoked") active @endif'>Ditarik</a> --}}
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Penjualan Produk</span>
                {{-- <input type="text" class="card-search-input" placeholder="Cari Produk ..."> --}}
            </div>

            <div class="filter-form">
                <div class="form-floating">
                    <input wire:model='date_filter_start' type="date" class="form-control" id="date-filter-start-input" placeholder="Nama Kategori">
                    <label for="date-filter-start-input">Rentang Awal</label>
                    @error('category_name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='date_filter_end' type="date" class="form-control" id="date-filter-end-input" placeholder="Nama Kategori">
                    <label for="date-filter-end-input">Rentang Akhir</label>
                    @error('category_name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <button wire:click='reset_date_filter' class="btn btn-default-orange">reset</button>
            </div>
            
            {{-- <livewire:admin.selling-report-table /> --}}

            

            <div class="table-responsive">
                <div class="result-count-wrapper">
                    <span>menampilkan {{ $this->load_products }} dari {{ count($this->Products) }} hasil</span>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nama Produk</td>
                            <td>Harga</td>
                            <td>UMKM Penjual</td>
                            <td>Penjualan</td>
                            {{-- <td>Tindakan</td> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product['id'] }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ format_rupiah($product['price']) }}</td>
                                <td>{{ $product['umkm']['name'] }}</td>
                                <td>{{ simplify_number_id($product['success_order_count']) }}</td>
                                {{-- <td>
                                    <a href="#" class="table-a">Tinjau</a>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Tidak ada penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                @if (!$this->all_loaded_state)
                    <button wire:click='load_more' class="btn btn-default-dark">Muat Lebih</button>
                @endif
            </div>


        </div>


    </div>
    


</div>
