@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/checkout-page.css') }}">
@endpush

<div class="checkout-page">
    <div class="container">
        <div class="page-title">
            <h1>Checkout</h1>
        </div>

        <div class="page-content-card">
            <div class="card-title">
                <h1>{{ $umkm->name }}</h1>
            </div>

            <div class="address-wrapper">
                <span>Alamat Pengiriman</span>
                <div class="form-check">
                    <input wire:model='set_delivery_address' class="form-check-input" type="checkbox" id="set-delivery-address">
                    <label class="form-check-label" for="set-delivery-address">
                        Sama seperti alamat saya
                    </label>
                </div>
                <div class="input-area">
                    <textarea wire:model='delivery_address' class="form-control @error('delivery_address') is-invalid @enderror" id="delivery-address-input" rows="3"></textarea>
                    @error('delivery_address')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <span class="card-section-title">
                Daftar Produk
            </span>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                        @foreach ($userCart as $product)
                            <tr>
                                <td>
                                    <div class="image-container">
                                        <img src="{{ 'img\aziz-acharki-boIJluEJEPM-unsplash.jpg' }}" alt="FILL THIS">
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('product-details', [$product['product']['id'], $product['product']['name_slug']]) }}">{{ $product['product']['name'] }}</a>
                                </td>
                                <td>
                                    <div class="prices">
                                        @if ($product['product']['discount'] > 0)
                                            <span class="base-price">{{ format_rupiah($product['product']['price']) }}</span>
                                            <span class="final-price" data-amount="{{ $this->get_final_price($product['product']['price'], $product['product']['discount']) }}">{{ format_rupiah($this->get_final_price($product['product']['price'], $product['product']['discount'])) }}</span>
                                        @else
                                            <span class="final-price" data-amount="{{ $product['product']['price'] }}">{{ format_rupiah($product['product']['price']) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="qty-wrapper">
                                        <span>x</span>
                                        <span>{{ $product['qty'] }}</span>
                                    </div>
                                </td>
                                <td class="amount" data-calcamount="{{                                     
                                        $this->calculate_amount(
                                                    $this->get_final_price($product['product']['price'], $product['product']['discount']),
                                                    $product['qty']
                                                )
                                    }}" >
                                    {{ format_rupiah(
                                        $this->calculate_amount(
                                            $this->get_final_price($product['product']['price'], $product['product']['discount']),
                                            $product['qty']
                                        )
                                    ) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <div class="sub-total-wrapper">
                                    <span class="sub-total-label">Sub Total</span> 
                                    <div class="sub-total" data-subtotal="">{{ format_rupiah($gross_amount) }}</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button-wrapper">
                <button wire:click="init_payment" type="button" class="btn btn-default-red checkout-btn">Bayar</button>
            </div>
            
        </div>
    </div>
</div>

@push('script')
<script>

    function toggle_delivery_address() {
        if (@this.set_delivery_address) {
            $('#delivery-address-input').attr('disabled', 'disabled');
        } else {
            $('#delivery-address-input').removeAttr('disabled');
        }
    }

    $( document ).ready(function () {
        toggle_delivery_address()
    })

    $( window ).on('delivery-address-changed', function() {
        toggle_delivery_address()
    })

    $( window ).on('snap-popup', function (e) {
        window.snap.pay(e.detail.token, {
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
    })
    
</script>
@endpush
