@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-account-registration.css') }}">
@endpush

<div class="admin-umkm-account-ver-page">
    <div class="container">
        <div class="page-title">
            <h1>Verifikasi Rekening</h1>
        </div>
        <div class="content-menu-wrapper">
            <span class="menu-item active">Permintaan</span>
            <span class="menu-item">Ditolak</span>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Pemohon</span>
                <input type="text" class="card-search-input" placeholder="Cari Pemohon ...">
            </div>
            <div class="table-responsive">
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
            </div>
    
            <div class="pagination-wrapper">
    
            </div>
        </div>
    </div>
</div>
