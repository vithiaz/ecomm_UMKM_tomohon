<div class="page-content-card umkm-card">
    <div class="card-header">
        <div class="umkm-profile-wrapper">
            <div class="image-container">
                @if ($umkm->profile_img)
                    <img src="{{ asset('storage/'.$umkm->profile_img) }}" alt="{{ $umkm->name }}_profile">
                @else
                    <div class="no-image">
                        <i class="fa-solid fa-store"></i>
                    </div>
                @endif
            </div>
            <div class="profile-wrapper">
                <a href="{{ route('umkm.edit', [$umkm]) }}" class="umkm-name">{{ $umkm->name }} <i class="fa-solid fa-pen"></i></a>
                <div class="product-wrapper">
                    <span>{{ $umkm->products->count() }} Produk</span>
                    <span>xx Penjualan</span>
                </div>
            </div>
        </div>
        <div class="address-wrapper">
            <i class="fa-solid fa-location-dot"></i>
            <p>{{ $umkm->address }}</p>
        </div>
    </div>
    <div class="card-body">
        <div class="body-title-wrapper">
            <span class="label">Daftar Produk</span>
            <a href="{{ route('add-product', $umkm) }}" class="btn btn-add-product">Tambahkan Produk</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Status</th>
                        <th>Diskon</th>
                        <th>Harga Dasar</th>
                        <th>Penjualan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="break">
                                <a href="#">{{ $product->name }}</a>
                            </td>
                            <td class="break">{{ $product->status }}</td>
                            <td>{{ $product->discount }}%</td>
                            <td class="break">{{ format_rupiah($product->price) }}</td>
                            <td>xxx Penjualan</td>
                            <td>
                                <a class="edit-btn" href="{{ route('edit-product', [$umkm, $product]) }}">
                                    edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada produk ...</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            {{ $products->links() }}
        </div>
    </div>
</div>