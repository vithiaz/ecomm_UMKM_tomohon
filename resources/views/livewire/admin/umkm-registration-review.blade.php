@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-registration-review.css') }}">
@endpush

<div class="admin-umkm-registration-review">
    <div class="container">
        <div class="page-title">
            <h1>Review Pendaftar UMKM</h1>
        </div>
        <div class="page-content-card">
            <div class="card-title">
                <h1>Profil</h1>
            </div>
            <div class="card-content">
                <div class="account-wrapper">
                    <div class="profile-container">
                        <div class="image-container">
                            @if ($user->profile_img)
                                <img src="{{ asset('storage/'.$user->profile_img) }}" alt="{{ $user->username }}_profile">                            
                            @else
                                <div class="no-image">
                                    <i class="fa-solid fa-user"></i>
                                </div>                                
                            @endif
                        </div>
                        <div class="profile-id-wrapper">
                            <span class="username">{{ $user->username }}</span>
                            <span class="user-id">ID {{ $user->id }}</span>
                        </div>
                    </div>
                    <div class="profile-details-wrapper">
                        <div class="row">
                            <span class="label">Nama Lengkap:</span>
                            <span>{{ $user->first_name }} @if ($user->last_name) {{ ', ' . $user->last_name }} @endif
                            </span>
                        </div>
                        <div class="row">
                            <span class="label">Email:</span>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Status UMKM:</span>
                            @if ($user->umkm_status == true)
                                <span>aktif</span>
                            @else
                                <span>nonaktif</span>
                            @endif
                        </div>
                        <div class="address-wrapper">
                            <span class="label">Alamat:</span>
                            <p>{{ $user->address }}</p>
                        </div>
                    </div>
                </div>
                <div class="response-wrapper">
                    @if ($umkm_registration->status == 'request')
                        <div class="request-wrapper">
                            <span class="info">User <strong>ID {{ $user->id }}</strong>, <strong>{{ $user->first_name }} {{ $user->last_name }}</strong> mengajukan aktivasi status UMKM</span>
                            <span>pesan: {{ $umkm_registration->message }}</span>
                            <form wire:submit.prevent='decline_umkm_status_request' class="decline-form">
                                <div class="form-floating">
                                    <textarea wire:model.defer='decline_message' class="form-control" placeholder="Tambahkan pesan" id="decline-message-input" style="height: 120px"></textarea>
                                    <label for="decline-message-input">Pesan</label>
                                    @error('decline_message')
                                        <small class="error">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="button" id="decline-form-hide-btn" class="btn btn-default-dark">Kembali</button>
                                <button type="submit" class="btn btn-default-red">Tolak Pengajuan</button>
                            </form>
                            <div class="button-wrapper">
                                <button wire:click='set_active_umkm_status' class="btn btn-default-orange">Aktivasi UMKM</button>
                                <button id="request-reject-btn" class="btn btn-default-red">Tolak Pengajuan</button>
                            </div>
                        </div>
                    @else
                        <form wire:submit.prevent='update_umkm_status' class="edit-status">
                            <span class="form-label">Ubah Status Aktivasi UMKM</span>
                            <select wire:model='status' class="form-select" aria-label="Status UMKM">
                                <option value="" selected>Pilih status UMKM</option>
                                <option value="acc">Aktif</option>
                                <option value="revoked">Nonaktif</option>
                            </select>
                            @error('status')
                                <small class="error">{{ $message }}</small>
                            @enderror
                            <div class="form-floating">
                                <textarea wire:model='message' class="form-control" placeholder="Tambahkan pesan" id="message-input" style="height: 120px"></textarea>
                                <label for="message-input">Pesan</label>
                            </div>
                            <div class="button-wrapper">
                                <button class="btn btn-default-red">Ubah Status</button>
                            </div>
                        </form>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>

@push('script')
<script>

    $('#request-reject-btn').click(function() {
        $('.decline-form').addClass('active')
        $( this ).parent().addClass('hide')
    })

    $('#decline-form-hide-btn').click(function () {
        $( this ).parent().removeClass('active')
        $('#request-reject-btn').parent().removeClass('hide')
    })

</script>
@endpush