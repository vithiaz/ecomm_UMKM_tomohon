@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
@endpush

<div class="cart-page">
    <div class="container">
        <div class="page-title">
            <h1>Transaksi Saya</h1>
        </div>
        <div class="page-nav-menu-wrapper">
            <ul>
                <li class="@if($status == 'pending') active @endif">
                    <a href="{{ route('transaction-page', ['status' => 'pending']) }}">Belum bayar</a></li>
                <li class="@if($status == 'progress') active @endif"> 
                    <a href="{{ route('transaction-page', ['status' => 'progress']) }}">Dalam Proses</a>
                </li>
                <li class="@if($status == 'settlement') active @endif">
                    <a href="{{ route('transaction-page', ['status' => 'settlement']) }}">Selesai</a></li>
                <li class="@if($status == 'abort') active @endif">
                    <a href="{{ route('transaction-page', ['status' => 'abort']) }}">Dibatalkan</a></li>
            </ul>
        </div>
        
        @foreach ($UserOrder as $order_detail)
            <div class="page-content-card">
                <div class="card-id-wrapper">
                    <span class="id">{{ $order_detail->umkm->name }}</span>
                    <div class="status">{{ $order_detail->payment_status }}</div>
                </div>
                <div class="address-wrapper">
                    <div class="label">Alamat pengiriman</div>
                    <p>{{ $order_detail->order_address }}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <td>{{ $this->get_date($order_detail->created_at) }}</td>
                                <td colspan="4">Daftar Produk</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_detail->order_item as $item)
                                <tr>
                                    <td>
                                        {{-- <div class="image-container">
                                            <img src="#" alt="FILL THIS">
                                        </div> --}}
                                        {{ $item->delivery_status }}
                                    </td>
                                    <td>
                                        <a href="{{ route('product-details', ['product_id' => $item->product->id, 'name_slug' => $item->product->name_slug]) }}">{{ $item->product->name }}</a>
                                    </td>
                                    <td>
                                        <div class="prices">
                                            <span class="final-price">{{ format_rupiah($item->amount) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="qty-wrapper">
                                            <span>x{{ $item->qty }}</span>
                                        </div>
                                    </td>
                                    <td class="amount">
                                        {{ format_rupiah( $this->calculate_amount($item->amount, $item->qty) ) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    <div class="sub-total-wrapper">
                                        <span class="sub-total-label">Sub Total</span> 
                                        <div class="sub-total">{{ format_rupiah($order_detail->payment_amount) }}</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if ($status == 'pending')
                    <div class="button-wrapper">
                        <button wire:click='remove_transaction({{ $order_detail }})' type="button" class="btn btn-default-red">Batalkan</button>
                        <button type="button" class="btn checkout-btn snap-btn" data-token='{{ $order_detail->payment_token }}'>Bayar</button>
                    </div>
                @endif
                @if ($status == 'progress')
                    @if (!$this->is_delivery($order_detail))
                        <div class="button-wrapper">
                            <button style="display: none" id="refoundModalToggleBtn" type="button" data-bs-toggle="modal" data-bs-target="#refoundModal"></button>
                            <button wire:click='set_refound_state({{ $order_detail }})' type="button" class="btn btn-default-red">Batalkan Pesanan</button>
                        </div>                        
                    @endif
                @endif
                @if ($status == 'abort' && $order_detail->refound_order)
                    <div class="content-card-title">
                        <span>Detail Refund</span>
                    </div>
                    <div class="refound-details-wrapper">
                        <div class="row-wrapper">
                            <span class="title">Status Pembayaran</span>
                            <span class="content">{{ $order_detail->refound_order->payment_status }}</span>
                        </div>
                        <div class="row-wrapper">
                            <span class="title">Nama BANK</span>
                            <span class="content">{{ $order_detail->refound_order->bank_name }}</span>
                        </div>
                        <div class="row-wrapper">
                            <span class="title">Nomor Rekening</span>
                            <span class="content">{{ $order_detail->refound_order->account_number }}</span>
                        </div>
                        <div class="row-wrapper">
                            <span class="title">Pemilik Rekening</span>
                            <span class="content">{{ $order_detail->refound_order->account_name }}</span>
                        </div>
                        <div class="row-wrapper">
                            <span class="title">Keterangan Refund</span>
                            <span class="content">{{ $order_detail->refound_order->refound_description }}</span>
                        </div>


                    </div>
                @endif
            </div>
        @endforeach

    </div>


    {{-- Confirmation Modal --}}
    <div wire:ignore.self class="modal fade" id="refoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="close-modal-button" onclick="hide_refound_modal()" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="title">Informasi</div>

                <div class="card-content-wrapper">
                    <p class="indent">Pesanan dapat dibatalkan apabila penjual belum melakukan pengiriman produk ke alamat tujuan.</p>
                    <p class="indent">Anda akan diminta untuk mengisi form pembatalan transaksi serta mencantumkan informasi rekening Bank yang valid untuk dilakukan refund. Admin akan melakukan refund pada hari kerja.</p>
                </div>

                <div class="card-content-wrapper">
                    <span class="card-content-wrapper-title">Konfirmasi Pembatalan Pesanan</span>
                    <div class="buttons-container">
                        <button onclick="redirect_to_refound_page()" class="btn btn-danger">Batalkan Pesanan</button>
                        <button onclick="hide_refound_modal()" data-bs-dismiss="modal" class="btn btn-secondary">Kembali</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>

@push('script')
<script>

    function open_snap_popup(token) {
        alert(token);
    }

    $('.snap-btn').click(function() {
        let token = $( this ).data('token');
        if (token) {
            window.snap.pay(token, {
                onSuccess: function(result){
                    alert("pembayaran berhasil!");
                    window.location.href = '/transactions/progress';
                },
                onPending: function(result){
                    alert("menunggu pembayaran!");
                    window.location.href = '/transactions/progress';
                },
                onError: function(result){
                    alert("pembayaran gagal!");
                    window.location.href = '/transactions/pending';
                },
                onClose: function(){
                    alert('selesaikan pembayaran anda ...');
                    window.location.href = '/transactions/pending';
                }
            })
        }
    })


    // Modal
    function hide_refound_modal() {
        $('#refoundModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }
    $(window).on('toggleRefoundModal', function () {
        $('#refoundModalToggleBtn').click()
    })


    // Redirect to refound page
    function redirect_to_refound_page() {
        if (@this.refoundId) {
            window.location.href = '/refound-transaction/' + @this.refoundId;
        }
    }

</script>
@endpush