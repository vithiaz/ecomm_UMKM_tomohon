<div class="umkm-details-wrapper">
    @if ($umkm_status == 'acc')
        <span class="content-label">Opsi Pembayaran</span>
        <p>Tambahkan Rekening Bank di opsi pembayaran untuk mencairkan dana hasil transaksi ke rekening tujuan.<br>Admin akan melakukan verifikasi terhadap rekening bank yang diinput. Rekening yang ditambahkan dapat digunakan setelah proses verifikasi.</p>
        <div class="table-section">
            <table class="table table-striped table-hover">
                <tbody>
                    @forelse ($bank_accounts as $account)
                        <tr>
                            <td>{{ $account->bank_name }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td>{{ $account->account_name }}</td>
                            <td>{{ $account->status }}</td>
                            <td><i wire:click='bank_acc_state_delete({{ $account->id }})' class="fa-solid fa-trash delete-ic"></i></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada Rekening didaftarkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <span class="content-label">Tambahkan Rekening</span>
        <form wire:submit.prevent='register_bank_acc' class="account-input-form">
            <select wire:model='bank_name' class="form-select" aria-label="Pilih Bank">
                <option selected>Pilih Bank</option>
                <option value="BRI">BRI</option>
                <option value="BNI">BNI</option>
                <option value="MANDIRI">Mandiri</option>
            </select>
            @error('bank_name')
                <small class="error">{{ $message }}</small>
            @enderror
            <div class="form-floating">
                <input wire:model='account_number' type="text" class="form-control" id="account-number-input" placeholder="Nomor Rekening">
                <label for="account-number-input">Nomor Rekening</label>
                @error('account_number')
                    <small class="error">{{ $message }}</small>
                @enderror                            </div>
            <div class="form-floating">
                <input wire:model='account_name' type="text" class="form-control" id="full-name-input" placeholder="Nama Lengkap Pemilik Rekening">
                <label for="full-name-input">Nama Lengkap Pemilik Rekening</label>
                @error('account_name')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>
            <div class="button-wrapper">
                <button class="btn btn-add-account">Tambahan Rekening</button>
            </div>
        </form>
    @else 
        <div class="message-section">
            <span class="content-label">Informasi</span>
            @if ($umkm_status == 'request')
                <p class="message">
                    Anda telah mengajukan aktivasi UMKM, silahkan menunggu admin untuk memverifikasi data anda
                </p>                    
            @endif
            @if ($umkm_status == 'rejected')
                <p class="message">
                    Pengajuan aktivasi UMKM anda ditolak, {{ $umkm_message }} <br>lakukan pengajuan kembali
                </p>
                <button wire:click='request_umkm_status' class="btn btn-default-red">Aktivasi UMKM</button>
            @endif
            @if ($umkm_status == 'revoked')
                <p class="message">
                    Status aktivasi UMKM anda dicabut, {{ $umkm_message }} <br>lakukan pengajuan kembali
                </p>
                <button wire:click='request_umkm_status' class="btn btn-default-red">Aktivasi UMKM</button>
            @endif
            @if ($umkm_status == null)
                <p class="message">
                    Status UMKM anda belum aktif, pastikan melengkapi data akun anda pada menu <a class="paragraph-redirect" href="{{ route('account-settings') }}">Akun dan Privasi</a> kemudian ajukan pengaktifan UMKM anda pada tombol berikut: 
                </p>
                <button wire:click='request_umkm_status' class="btn btn-default-red">Aktivasi UMKM</button>
            @endif
        </div>
    @endif

    {{-- Delete Bank Account Confirmation Modal --}}
    <div class="confirmation-modal modal fade" id="deleteBankAccountModal" tabindex="-1" aria-labelledby="deleteBankAccountModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span><b>Ingin menghapus akun bank?</b></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn modal-button abort-button" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn modal-button confirm-button" wire:click='delete_bank_account'>Simpan</button>
                </div>
            </div>
        </div>
    </div>
    
</div>

@push('script')
<script>

    $(document).ready(function () {
        $('.app .row-container').addClass('light-bg')
    });

    // Open Add Post Modal
    $(window).on('toggle-delete-acc-modal', function () {        
        $('#deleteBankAccountModal').modal('toggle');
    });

</script>
@endpush