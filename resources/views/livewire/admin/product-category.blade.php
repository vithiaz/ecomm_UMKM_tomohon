@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-product-category.css') }}">
@endpush

<div class="admin-product-category-page">
    <div class="container">
        <div class="page-title">
            <h1>Produk Kategori</h1>
        </div>
        <div class="page-content-card">
            <form wire:submit.prevent='store_category' class="add-category-form">
                <div class="form-floating">
                    <input wire:model='category_name' type="text" class="form-control @error('category_name') is-invalid @enderror" id="category-name-input" placeholder="Nama Kategori">
                    <label for="category-name-input">Nama Kategori</label>
                    @error('category_name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <button class="btn btn-default-orange">Tambahkan</button>
            </form>

            <livewire:admin.product-category-table />

            {{-- <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nama Kategori</td>
                            <td>Jumlah Produk</td>
                            <td>Penjualan</td>
                            <td>Tindakan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                @if ($edit_state_id == $category->id)
                                    <input type="text" name="" id="" placeholder="nama kategori">
                                @else
                                    <td>{{ $category->name }}</td>
                                @endif
                                <td>{{ $category->product->count() }}</td>
                                <td>xxx</td>
                                <td><a wire:click='set_edit_state({{ $category->id }})' href="#" class="table-a">Edit</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrapper">
                {{ $categories->links() }}
            </div> --}}


        </div>
    </div>
</div>

@push('script')
<script>

    $(window).on('reload_page', function() {
        location.reload()  
    })

</script>
@endpush
