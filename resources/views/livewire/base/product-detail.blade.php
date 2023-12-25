<div class="app">
    <section class="page-section lighten">
        <div class="container">
            <div class="product-detail-wrapper card">
                <div class="image-container">
                    @if ($product->profile_image)
                        <img src="{{ asset('storage/'.$product->profile_image->image) }}" alt="{{ $product->name_slug }}_profile">
                    @else
                        <div class="no-image">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    @endif
                </div>
                <div class="content-container">
                    <div class="content-head-wrapper">
                        <span class="title">{{ $product->name }}</span>
                    </div>
                    <div class="content-neck-wrapper">
                        <div class="main">
                            <span class="umkm-name"><a href="{{ route('umkm-products', ['umkm_id' => $product->umkm->id]) }}">{{ $product->umkm->name }}</a></span>
                            <div class="location-container">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $product->umkm->district }}</span>
                            </div>
                        </div>
                        <div class="sold-container">
                            <span>{{ $this->success_transaction_count }} Terjual</span>
                        </div>
                    </div>
                    <div class="price-wrapper">
                        @if ($product->discount > 0)
                            <div class="discount-box">
                                {{ $product->discount }} %
                            </div>
                        @endif
                        <span class="main-price">
                            {{ format_rupiah($final_price) }}
                        </span>
                        @if ($product->discount > 0)
                            <span class="secondary-price">
                                {{ format_rupiah($product->price) }}
                            </span>
                        @endif
                    </div>
                    <div class="description-wrapper">
                        <div class="title">
                            <span>Deskripsi Produk</span>
                        </div>
                        <div class="description">
                            {{ $product->description }}
                        </div>
                    </div>
                </div>
                @if ($product->stock > 0)
                    @if ($product->status == 'active' && $product->umkm->status)
                        <div class="cart-container">
                            <span class="title">Atur Jumlah Pembelian</span>
                            <div class="qty-wrapper" >
                                <div class="input-group">
                                    <input wire:model.defer='qty_input' type="number" id='qty-input' class="form-control @error('qty_input') is-invalid @enderror" placeholder="Qty" min="1" data-stock="{{ $product->stock }}" value="1">
                                    @error('qty_input')
                                        {{ $message }}
                                    @enderror
                                </div>
                                <div class="stock-container">
                                    <span>Tersisa {{ $product->stock }}</span>
                                </div>
                            </div>
                            <div class="msg-wrapper hide">
                                <div id="cart-msg-toggle-btn" class="title-container">
                                    <i class="fa-solid fa-pen"></i>
                                    <span>Tambahkan catatan</span>
                                </div>
                                <div class="form-floating">
                                    <textarea wire:model.defer='note_input' class="form-control" placeholder="Catatan" id="cartMsgTextarea"></textarea>
                                    <label for="cartMsgTextarea">Catatan</label>
                                </div>
                            </div>
                            <div class="delivery-price-wrapper">
                                <span class="label">Lokasi Pengiriman</span>
                                <div class="row-item">
                                    <select wire:model='delivery_selected_kec' class="form-select form-select-lg @error('delivery_selected_kec') is-invalid @enderror" id="delivery_selected_kec_select" aria-label="Pilih kecamatan">
                                        <option selected>Pilih kecamatan</option>
                                        @foreach ($this->city_dir["Tomohon"] as $kec)
                                            <option wire:key='delivery_selected_kec_select{{ $kec }}' value="{{ $kec }}">{{ $kec }}</option>                    
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="delivery-price-wrapper">
                                <span class="label">Ongkos Kirim</span>
                                <span class="price">{{ format_rupiah($this->delivery_price) }}</span>
                            </div>
                            <div class="price-wrapper">
                                <span class="label">Sub Total</span>
                                <span class="price"></span>
                            </div>
                            <div class="button-wrapper">
                                <button wire:click='store_user_cart({{ $product->id }}, {{ true }})' class="btn btn-primary">+ Keranjang</button>
                                <button wire:click='direct_buy()' class="btn btn-secondary">Beli</button>
                            </div>
                        </div>
                    @else
                        <div class="cart-container empty">
                            <span class="outstock">Produk Tidak Tersedia</span>
                        </div>
                    @endif
                @else
                    <div class="cart-container empty">
                        <span class="outstock">Produk Habis</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="page-section lighten">
        <div class="container">
            <div class="section-header">
                <h1 class="section-header-title">Produk Lainnya</h1>
            </div>
            <div class="section-content">
                <div class="product-wrapper">
                    @forelse ($other_product as $product)
                        <div class="item">
                            <x-card.product-card 
                                productId='{{ $product->id }}'
                                basePrice='{{ $product->price }}'
                                discount='{{ $product->discount }}'
                                umkm='{{ $product->umkm->name }}'
                                umkmId='{{ $product->umkm->id }}'
                                sold='{{ $this->count_success_transaction($product->order_item) }}'
                                stock='{{ $product->stock }}'
                                productName='{{ $product->name }}'
                                productNameSlug='{{ $product->name_slug }}'
                                Location='{{ $product->umkm->district }}'
                                img='{{ $product->profile_image ? $product->profile_image->image : "" }}'
                            />
                        </div>
                    @empty
                        <div class="empty-card full-basis">
                            <i class="fa-solid fa-exclamation"></i>
                            <span>tidak ada produk . . .</span>
                        </div>
                    @endforelse
                </div>
                {{-- <button class="section-content-button">Lebih Banyak</button> --}}
            </div>
        </div>
    </section>

</div>

@push('script')
<script>
    $(document).ready(function () {
        calculate_subtotal()
    })


    // Toggle Cart Message
    $('#cart-msg-toggle-btn').click(function() {
        $('.cart-container .msg-wrapper').toggleClass('hide')
    })

    const qty_max = $('#qty-input').data('stock')
    $('#qty-input').change(function () {
        if ($(this).val() > qty_max) {
            $(this).val(qty_max)
        }
        else if ($(this).val() <= 0) {
            $(this).val(1)
        }
        calculate_subtotal()
    })

    $(window).on('calculatePrice', function (event) {
        calculate_subtotal()
    });


    

    // Calculate SubTotal
    function calculate_subtotal() {
        let qtyInput = $('#qty-input')
        
        let finalPrice = @this.final_price
        let calculatedPrice = (finalPrice * qtyInput.val()) + @this.delivery_price

        $('.price-wrapper .price').text(formatRupiah(calculatedPrice))
    }    

</script>
@endpush