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
                            <div class="location-container">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $product->umkm->district }}</span>
                            </div>
                            <span>{{ $product->umkm->name }}</span>
                        </div>
                        <div class="sold-container">
                            <span>122 Terjual</span>
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
                <div class="cart-container">
                    <span class="title">Atur Jumlah Pembelian</span>
                    <div class="qty-wrapper" >
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="Qty">
                        </div>
                        <div class="stock-container">
                            <span>Tersisa 999</span>
                        </div>
                    </div>
                    <div class="msg-wrapper hide">
                        <div id="cart-msg-toggle-btn" class="title-container">
                            <i class="fa-solid fa-pen"></i>
                            <span>Tambahkan catatan</span>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Catatan" id="cartMsgTextarea"></textarea>
                            <label for="cartMsgTextarea">Catatan</label>
                        </div>
                    </div>
                    <div class="price-wrapper">
                        <span class="label">Sub Total</span>
                        <span class="price">Rp. 180.000,00</span>
                    </div>
                    <div class="button-wrapper">
                        <button class="btn btn-primary">+ Keranjang</button>
                        <button class="btn btn-secondary">Beli</button>
                    </div>
                </div>
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
                    @foreach (range(0,8) as $i)
                        <div class="item">
                            <x-card.product-card
                                productId='404' 
                                basePrice='220000'
                                discount='50'
                                umkm='Mitra UMKM'
                                sold='2130'
                                stock='999'
                                productName='Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum dolor sit amet consectetur adipisicing elit'
                                productNameSlug='name-slug'
                                Location='Location'
                                img=''
                            />
                        </div>
                    @endforeach
                </div>
                <button class="section-content-button">Lebih Banyak</button>
            </div>
        </div>
    </section>

</div>

@push('script')
<script>

    // Toggle Cart Message
    $('#cart-msg-toggle-btn').click(function() {
        $('.cart-container .msg-wrapper').toggleClass('hide')
    })

</script>
@endpush