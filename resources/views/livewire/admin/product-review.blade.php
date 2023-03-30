@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-product-verify.css') }}">
@endpush

<div class="admin-product-verify">
    <div class="container">
        <div class="page-title">
            <h1>Review Produk</h1>
        </div>
        <div class="page-content-card">
            <div class="product-detail-wrapper">
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
                        <span>{{ $product->status }}</span>
                        {{-- <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="statusCheckbox">
                            <label class="form-check-label" for="statusCheckbox">Aktif</label>
                        </div> --}}
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
            </div>

            <div class="button-wrapper">
                @if ($product->status != 'revoked')
                    <button wire:click='set_status("revoked")' class="btn btn-default-red">Tarik dari Market</button>                
                @else
                    <button wire:click='set_status("disabled")' class="btn btn-default-orange">Aktifkan</button>                
                @endif
                <button wire:click='redirect_back' class="btn btn-default-dark">Kembali</button>
            </div>
        </div>
    </div>
</div>