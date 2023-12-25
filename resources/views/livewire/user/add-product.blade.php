@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/add-product.css') }}">
@endpush

<div class="add-product-page">
    <div class="container">
        <div class="page-content-card umkm-card">
            <div class="card-body">
                <div class="umkm-profile-wrapper">
                    <div class="image-container">
                        @if ($umkm->profile_img)
                        <img src="{{ asset('storage/'.$umkm->profile_img) }}" alt="{{ $umkm->name }}-profile">
                        @else
                            <div class="no-image">
                                <i class="fa-solid fa-store"></i>
                            </div>
                        @endif
                    </div>
                    <div class="profile-wrapper">
                        <a href="#" class="umkm-name">{{ $umkm->name }} <i class="fa-solid fa-pen"></i></a>
                        <div class="product-wrapper">
                            <span>xx Produk</span>
                            <span>xx Penjualan</span>
                        </div>
                    </div>
                </div>
                <div class="address-wrapper">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>{{ $umkm->address }}</p>
                </div>
            </div>
        </div>
        <div class="page-title">
            <h1>Daftarkan Produk</h1>
        </div>
        <div class="page-content-card">
            <div class="row">
                <div class="image-section">
                    <div class="image-container">
                        <input wire:model='image' type="file" id="profile_img_input" style="display: none" accept="image/*">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}">
                            <div wire:click='empty_image' class="delete-image">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                        @else
                            <div class="no-image" onclick="$('#profile_img_input').click()">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </div>                            
                        @endif
                    </div>
                </div>
                <form wire:submit.prevent='store_product' class="details-wrapper">
                    <div class="form-floating full-width">
                        <input wire:model='name' type="text" class="form-control" id="product_name_input" placeholder="Nama Produk">
                        <label for="product_name_input">Nama Produk</label>
                        @error('name')
                        <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <select wire:model='category' class="form-select form-select-lg" aria-label="Pilih kategori">
                        <option selected>Pilih kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>                    
                        @endforeach
                    </select> 
                    <div class="categories-wrapper">
                        @foreach ($selected_categories as $cat)
                            <div class="category-card">
                                <span class="name">{{ $cat['name'] }}</span>
                                <i wire:click='remove_selected_category({{ $cat['id'] }})' class="fa-solid fa-xmark"></i>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-floating">
                        <input wire:model='stock' type="number" class="form-control" id="stock_input" placeholder="Stok" min="0">
                        <label for="stock_input">Stok</label>
                        @error('stock')
                        <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input wire:model='discount' type="number" class="form-control" id="discount_input" placeholder="Diskon (%)" min="0" max="100" step="5">
                        <label for="discount_input">Diskon (%)</label>
                        @error('discount')
                        <small class="error">{{ $message }}</small>
                        @enderror

                    </div>
                    <div class="form-floating">
                        <input wire:model='price' type="number" class="form-control" id="price_input" placeholder="Harga">
                        <label for="price_input">Harga</label>
                        @error('price')
                        <small class="error">{{ $message }}</small>
                        @enderror

                    </div>
                    <div class="form-floating full-width">
                        <textarea wire:model='description' type="text" class="form-control" id="description_input" placeholder="Deskripsi"></textarea>
                        <label for="description_input">Deskripsi</label>
                        @error('description')
                        <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="delivery-form">
                        <div class="row-wrapper">
                            <div class="row-item">
                                <span>Ongkos Kirim</span>
                            </div>
                            <div class="row-item grow">
                                <div class="form-floating">
                                    <input wire:model='base_delivery_price' type="number" class="form-control" id="base_delivery_price_input" placeholder="Harga">
                                    <label for="base_delivery_price_input">Harga</label>
                                    @error('base_delivery_price')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <span class="separator">*tambahkan data rentang onkos kirim, detail berdasarkan kecamatan</span>
                        <div class="row-wrapper">
                            <div class="row-item">
                                <select wire:model='selected_kec' class="form-select form-select-lg" aria-label="Pilih kecamatan">
                                    <option selected>Pilih kecamatan</option>
                                    @foreach ($this->city_dir["Tomohon"] as $kec)
                                        <option value="{{ $kec }}">{{ $kec }}</option>                    
                                    @endforeach
                                </select>
                            </div>
                            <div class="row-item">
                                <div class="form-floating">
                                    <input wire:model='kec_delivery_price' type="number" class="form-control" id="kec_delivery_price_input" placeholder="Ongkos kirim">
                                    <label for="kec_delivery_price_input">Ongkos kirim</label>
                                    @error('kec_delivery_price')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row-item">
                                <button type="button" wire:click="add_delivery_detail" class="btn btn-default-orange">+</button>
                            </div>
                        </div>
                        <div class="delivery-price-details">
                            <table class="table">
                                <thead>
                                    <th>Kecamatan</th>
                                    <th>Ongkos Kirim</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($this->kec_delivery_detail as $index=>$kec)
                                        <tr wire:key="delivery_price_detail_{{ $index }}">
                                            <td>{{ $kec["kecamatan"] }}</td>
                                            <td>{{ $kec["price"] }}</td>
                                            <td>
                                                <button wire:click='delete_delivery_detail({{ $index }})' type="button" class="btn btn-default-red">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <button class="btn btn-default-orange">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>

    $(document).ready(function () {
        $('.app .row-container').addClass('light-bg')
    });

</script>
@endpush