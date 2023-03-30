@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-registration.css') }}">
@endpush

<div class="admin-umkm-registration-page">
    <div class="container">
        <div class="page-title">
            <h1>Verifikasi Pendaftar UMKM</h1>
        </div>
        <div class="content-menu-wrapper">
            <a href="{{ route('admin.umkm-registration', ['status' => 'request']) }}" class="menu-item @if($status == 'request') active @endif">Permintaan</a>
            <a href="{{ route('admin.umkm-registration', ['status' => 'rejected']) }}" class="menu-item @if($status == 'rejected') active @endif">Ditolak</a>
            <a href="{{ route('admin.umkm-registration', ['status' => 'revoked']) }}" class="menu-item @if($status == 'revoked') active @endif">Dicabut</a>
            <a href="{{ route('admin.umkm-registration', ['status' => 'acc']) }}" class="menu-item @if($status == 'acc') active @endif">Diterima</a>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Pemohon</span>
                {{-- <input type="text" class="card-search-input" placeholder="Cari Pemohon ..."> --}}
            </div>

            <livewire:admin.user-umkm-registration-table status='{{ $status }}' />

            {{-- <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>Tanggal</td>
                            <td>Username</td>
                            <td>Nama Lengkap</td>
                            <td>Alamat</td>
                            <td>Tindakan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(0,9) as $item)
                            <tr>
                                <td>01-01-2023</td>
                                <td>JohnDoe</td>
                                <td>John, Doe</td>
                                <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit earum vel asperiores?</td>
                                <td>
                                    <a href="#" class="table-a">Verifikasi</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
    
            <div class="pagination-wrapper">
    
            </div>
        </div>
    </div>
</div>
