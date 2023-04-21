@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/umkm-transaction.css') }}">
@endpush

<div class="umkm-transaction-page">
    <div class="container">
        <div class="page-title">
            <h1>Transaksi Saya</h1>
        </div>
        <div class="page-nav-menu-wrapper">
            <ul>
                <li @if($status == 'pending') class="active" @endif>
                    <a href="{{ route('umkm.transaction', ['status' => 'pending']) }}">Order</a>
                </li>
                <li @if($status == 'processed') class="active" @endif>
                    <a href="{{ route('umkm.transaction', ['status' => 'processed']) }}">Dalam Pengiriman</a></li>
                <li @if($status == 'onsite') class="active" @endif>
                    <a href="{{ route('umkm.transaction', ['status' => 'onsite']) }}">Selesai</a></li>
                <li @if($status == 'return') class="active" @endif>
                    <a href="{{ route('umkm.transaction', ['status' => 'return']) }}">Dibatalkan</a></li>
            </ul>
        </div>
        <div class="page-content-card">
            <livewire:user.user-order-item-table status='{{ $status }}' />
        </div>
        {{-- <div class="page-content-card">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>Tanggal</td>
                            <td>Produk</td>
                            <td>Qty</td>
                            <td>Dibayarkan</td>
                            <td>Catatan</td>
                            <td>Penerima</td>
                            <td>Alamat</td>
                            <td>Tindakan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(0,9) as $item)
                            <tr>
                                <td>01-01-2023</td>
                                <td>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</td>
                                <td>4</td>
                                <td>Rp. 120.000,00</td>
                                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias at ipsum veritatis?</td>
                                <td>Lorem, ipsum.</td>
                                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni veritatis quos itaque enim deleniti.</td>
                                <td><a href="#">Proses Pengiriman</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                
            </div>
        </div> --}}
    </div>
</div>

@push('script')
<script>

    $(document).ready(function () {
        $('.app .row-container').addClass('light-bg')
    });

</script>
@endpush