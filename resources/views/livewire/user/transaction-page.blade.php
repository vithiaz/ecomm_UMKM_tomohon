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
            </div>
        @endforeach

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
                alert("payment success!"); console.log(result);
                },
                onPending: function(result){
                alert("wating your payment!"); console.log(result);
                },
                onError: function(result){
                alert("payment failed!"); console.log(result);
                },
                onClose: function(){
                alert('you closed the popup without finishing the payment');
                }
            })
        }
    })

</script>
@endpush