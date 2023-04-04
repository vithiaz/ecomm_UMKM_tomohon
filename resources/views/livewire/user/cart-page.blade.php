@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
@endpush

<div class="cart-page">
    <div class="container">
        <div class="page-title">
            <h1>Keranjang</h1>
        </div>
        {{-- <div class="page-nav-menu-wrapper">
            <ul>
                <li
                    @if (\Request::route()->getName() == 'cart-page')
                        class='active'
                    @endif
                ><a 
                    href="{{ route('cart-page') }}">Keranjang</a></li>
                <li><a href="#">Dalam Proses</a></li>
                <li><a href="#">Selesai</a></li>
                <li><a href="#">Dibatalkan</a></li>
            </ul>
        </div> --}}
        @forelse ($Umkm as $umkm_cart)
            <div class="page-content-card">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <td colspan="5">{{ $umkm_cart->name }}</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Cart[$umkm_cart->id] as $product)
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
                                            <input wire:change="collect_modify_cart({{ $product['id'] }}, $event.target.value, {{ $umkm_cart->id }})" class="form-control" type="number" placeholder="qty" value="{{ $product['qty'] }}" min="1" max="{{ $product['product']['stock'] }}">
                                            <i class="fa-solid fa-trash delete-ic"></i>
                                            {{-- <button type="submit" class="fa-solid fa-trash delete-ic"></button> --}}
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
                                    <td>
                                        <div class="form-check">
                                            <input wire:model.defer='checked_cart' class="form-check-input checked_cart" type="checkbox" value="{{ $umkm_cart->id }}-{{ $product['id'] }}" checked>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">
                                    <div class="sub-total-wrapper">
                                        <span class="sub-total-label">Sub Total</span> 
                                        <div class="sub-total" data-subtotal="">Rp. 0,00</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="button-wrapper">
                    <button wire:click='item_checkout({{ $umkm_cart->id }})' type="button" class="btn checkout-btn">Checkout</button>
                </div>
            </div>
        @empty
            <div class="page-content-card">
                <span>Tidak ada produk di keranjang</span>
            </div>
        @endforelse

    </div>
</div>


@push('script')
<script>
    
    // Handle Sub Total
    function calculate_subtotal() {
        let subtotalsElem = $('.sub-total-wrapper .sub-total')
        
        subtotalsElem.each(function() {
            let amounts = $( this ).parentsUntil('table').find('.amount')
            
            let calculated_amount = 0;
            amounts.each(function() {
                amount = $( this ).attr('data-calcamount')
                let isChecked = $(this).parent().find('.checked_cart').prop('checked')

                if (isChecked) {
                    calculated_amount += parseInt(amount)
                }
            })

            $( this ).text( formatRupiah(calculated_amount) )
            $( this ).attr('data-subtotal', calculated_amount )
        })
    }

    function calculate_item_amount( qty ) {
        if (parseInt(qty.val()) >= parseInt(qty.attr('max'))) {
            qty.val(qty.attr('max'))
        }
        else if (parseInt(qty.val()) < 1) {
            qty.val(1)
        }
    
        // Handle Calculated Item Price
        let itemAmount = qty.parentsUntil('tbody').find('.final-price').data('amount')
        
        // Set item calculated price adapt on qty input
        let itemCalcPriceElem = qty.parentsUntil('tbody').find('.amount')
    
        itemCalcPriceElem.text(formatRupiah(itemAmount * qty.val()))
        itemCalcPriceElem.attr('data-calcamount', itemAmount * qty.val())
    }

    // Fix product stock issues
    $('.qty-wrapper input').on('change', function() {
        calculate_item_amount( $( this ) )
        calculate_subtotal()
    })
    
    $('.checked_cart').on('change', function () {
        calculate_subtotal()
    })

    // OnLoad
    $( document ).ready(function() {
        calculate_subtotal()
    })
    
    $( window ).on('refreshScript', function() {
        $('.qty-wrapper input').each(function() {
            calculate_item_amount( $( this ) )
        })
        calculate_subtotal()
    })


</script>
@endpush