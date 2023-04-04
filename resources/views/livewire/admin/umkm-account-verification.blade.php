@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-account-registration.css') }}">
@endpush

<div class="admin-umkm-account-ver-page">
    <div class="container">
        <div class="page-title">
            <h1>Verifikasi Rekening</h1>
        </div>
        <div class="content-menu-wrapper">
            <a href="{{ route('admin.umkm-account-verification', ['status' => 'request']) }}" class="menu-item @if($status == 'request') active @endif ">Permintaan</a>
            <a href="{{ route('admin.umkm-account-verification', ['status' => 'rejected']) }}" class="menu-item @if($status == 'rejected') active @endif ">Ditolak</a>
            <a href="{{ route('admin.umkm-account-verification', ['status' => 'revoked']) }}" class="menu-item @if($status == 'revoked') active @endif ">Dicabut</a>
            <a href="{{ route('admin.umkm-account-verification', ['status' => 'acc']) }}" class="menu-item @if($status == 'acc') active @endif ">Aktif</a>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Rekening</span>
            </div>

            <livewire:admin.bank-account-table status='{{ $status }}' />

            {{-- <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>Tanggal</td>
                            <td>Nama Lengkap</td>
                            <td>Bank</td>
                            <td>Nomor Rekening</td>
                            <td>Atas Nama</td>
                            <td>Tindakan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (range(0,9) as $item)
                            <tr>
                                <td>01-01-2023</td>
                                <td>John, Doe</td>
                                <td>BRI</td>
                                <td>192282733122</td>
                                <td>JohnDoe</td>
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
