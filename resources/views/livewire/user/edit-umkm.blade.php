@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/umkm-edit.css') }}">
@endpush

<div class="edit-umkm-page">
    <div class="container">
        <div class="page-title">
            <h1>Edit UMKM</h1>
        </div>

        <div class="page-content-card">
            <div class="row">
                <div class="image-wrapper">
                    <div class="image-container">
                        <input wire:model='upload_image' type="file" id="profile_img_input" style="display: none" accept="image/*">
                        @if ($upload_image)
                            <img src="{{ $upload_image->temporaryUrl() }}">
                            <div wire:click='empty_image' class="delete-image">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                        @else
                            @if ($image)
                                <img src="{{ asset('storage/'.$image) }}" alt="{{ $umkm->name }}_profile">
                                <div wire:click='empty_image' class="delete-image">
                                    <i class="fa-solid fa-trash"></i>
                                </div>
                            @else
                                <div class="no-image" onclick="$('#profile_img_input').click()">
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                </div>
                            @endif
                        @endif
    

                    </div>
                </div>

                <form wire:submit.prevent='update_umkm'>
                    <div class="form-check form-switch">
                        <input wire:model='status' class="form-check-input" type="checkbox" id="product_status" @if($status) checked @endif>
                        @if ($status)
                            <label class="form-check-label active" for="product_status">Aktif</label>
                        @else
                            <label class="form-check-label inactive" for="product_status">Tidak Aktif</label>
                        @endif
                    </div>
                    <div class="form-floating">
                        <input wire:model='name' type="text" class="form-control" id="umkm_name_input" placeholder="Nama UMKM">
                        <label for="umkm_name_input">Nama UMKM</label>
                        @error('name')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <select wire:model='city' class="form-select form-select-lg" aria-label="Pilih kota">
                        <option value="" selected>Pilih Kota</option>
                        <option value="Tomohon">Tomohon</option>
                    </select> 
                    @error('city')
                        <small class="error">{{ $message }}</small>
                    @enderror
                    <select wire:model='district' class="form-select form-select-lg" aria-label="Pilih kelurahan">
                        <option value="" selected>Pilih Kelurahan</option>
                        @foreach ($districts as $dst)
                            <option value="{{ $dst }}">{{ $dst }}</option>                    
                        @endforeach
                    </select>
                    @error('district')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    <div class="form-floating">
                        <textarea wire:model='address' type="text" class="form-control" id="umkm_address_input" placeholder="Alamat UMKM"></textarea>
                        <label for="umkm_address_input">Alamat UMKM</label>
                        @error('address')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="button-wrapper">
                        <button onclick="window.history.back()" type="button" class="btn btn-default-dark">Batalkan</button>
                        <button type="submit" class="btn btn-default-orange">Simpan</button>
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