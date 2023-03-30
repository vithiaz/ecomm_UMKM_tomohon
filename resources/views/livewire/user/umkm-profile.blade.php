@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/umkm-profile.css') }}">
@endpush

<div class="umkm-profile-page">
    <div class="container">
        <div class="page-title">
            <h1>UMKM Zone</h1>
        </div>

        {{-- User Profile and Bank Account Card --}}
        <div class="page-content-card">
            <div class="card-title">
                <h1>Profil UMKM</h1>
            </div>
            <div class="card-content">
                <div class="account-wrapper">
                    <div class="profile-container">
                        <div class="image-container">
                            @if (Auth::user()->profile_img)
                                <img src="{{ asset('storage/'.Auth::user()->profile_img) }}" alt="FILL THIS">                            
                            @else
                                <div class="no-image">
                                    <i class="fa-solid fa-user"></i>
                                </div>                                
                            @endif
                        </div>
                        <div class="profile-id-wrapper">
                            <span class="username">{{ Auth::user()->username }}</span>
                            <span class="user-id">ID {{ Auth::user()->id }}</span>
                        </div>
                    </div>
                    <div class="profile-details-wrapper">
                        <div class="row">
                            <span class="label">Nama Lengkap:</span>
                            <span>{{ Auth::user()->first_name }}, {{ Auth::user()->last_name }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Email:</span>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                        <div class="row">
                            <span class="label">Status UMKM:</span>
                            @if (Auth::user()->umkm_status == true)
                                <span>aktif</span>
                            @else
                                <span>nonaktif</span>
                            @endif
                        </div>
                        <div class="address-wrapper">
                            <span class="label">Alamat:</span>
                            <p>{{ Auth::user()->address }}</p>
                        </div>
                    </div>
                </div>
                {{-- Placeholder --}}
                <livewire:components.add-bank-account-umkm-profile-page />
            </div>
        </div>

        {{-- UMKM Profile Card --}}
        <div class="page-section-title">
            <h2>UMKM Saya</h2>
            <a href="{{ route('umkm.register') }}">+Daftarkan UMKM</a>
        </div>

        @forelse ($umkms as $umkm)
            <livewire:components.umkm-profile-card :umkm="$umkm" />
        @empty
            <div class="page-content-card umkm-card">
                <div class="card-body">
                    <span>Anda belum mendaftarkan UMKM</span>
                </div>
            </div>
        @endforelse

    </div>
</div>