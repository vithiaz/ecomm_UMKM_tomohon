@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/account-settings.css') }}">
@endpush

<div class="account-settings-page">
    <div class="container">
        <div class="page-title">
            <h1>Akun dan Privasi</h1>
        </div>
        <div class="page-content-container">
            <div class="row">
                <div class="profile-wrapper">
                    <div class="image-container">
                        <input wire:model='image_upload' type="file" id="profile_img_input" style="display: none" accept="image/*">

                        @if ($image_upload)
                            <img src='{{ $image_upload->temporaryUrl() }}' alt="user_image_temporary">
                            <div wire:click='delete_temporary_img' class="delete-image">
                                <i class="fa-solid fa-trash"></i>
                            </div>
                        @else
                            @if ($profile_img)
                                <img src='{{ asset("storage/".$profile_img) }}' alt="{{ Auth::user()->first_name }}_profile">
                                <div wire:click='delete_state_profile_img' class="delete-image">
                                    <i class="fa-solid fa-trash"></i>
                                </div>
                            @else
                                <div class="no-image" onclick="$('#profile_img_input').click()">
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                </div>                            
                            @endif
                        @endif

                        
                    </div>
                    <div class="username-wrapper">
                        <span class="username">{{ Auth::user()->username }}</span>
                        <span class="user_id">ID {{ Auth::user()->id }}</span>
                    </div>
                </div>
                <form wire:submit.prevent='update_data' class="user-info-form-wrapper">
                    <div class="form-floating">
                        <input wire:model='first_name' type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name_input" placeholder="Nama Depan">
                        <label for="first_name_input">Nama Depan</label>
                        @error('first_name')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input wire:model='last_name' type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name_input" placeholder="Nama Belakang">
                        <label for="last_name_input">Nama Belakang</label>
                        @error('last_name')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input wire:model='email' type="email" class="form-control @error('email') is-invalid @enderror" id="email_input" placeholder="Email">
                        <label for="email_input">Email</label>
                        @error('email')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input wire:model='phone' type="number" class="form-control @error('phone') is-invalid @enderror" id="phone_input" placeholder="Nomor HP">
                        <label for="phone_input">Nomor HP</label>
                        @error('phone')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="form-floating full">
                        <textarea wire:model='address' class="form-control @error('address') is-invalid @enderror" id="address_input" placeholder="Alamat Lengkap"></textarea>
                        <label for="address_input">Alamat Lengkap</label>
                        @error('address')
                            <small class="error">
                                {{ $error }}
                            </small>
                        @enderror
                    </div>
                    <div class="button-wrapper">
                        <button type="submit" class="btn submit-btn">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="content-wrapper">
                <span class="row-title">Ubah Password</span>
                <form wire:submit.prevent='update_password'>
                    <div class="row-label-input-wrapper">
                        <label for="password_input" class="form-label">Password</label>
                        <input wire:model='password' type="password" class="form-control @error('password') is-invalid @enderror" id="password_input" placeholder="Password">
                        @error('password')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="row-label-input-wrapper">
                        <label for="password_conf_input" class="form-label">Konfirmasi Password</label>
                        <input wire:model='password_confirm' type="password" class="form-control @error('password_confirm') is-invalid @enderror" id="password_conf_input" placeholder="Konfirmasi Password">
                        @error('password_confirm')
                            <small class="error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                    <div class="button-wrapper">
                        <button type="submit" class="btn submit-btn">Ubah Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


@push('script')
<script>

</script>
@endpush