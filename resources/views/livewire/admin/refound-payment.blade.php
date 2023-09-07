@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-umkm-payment.css') }}">
@endpush

<div class="admin-umkm-payment-page">
    <div class="container">
        <div class="page-title">
            <h1>Refound ke Pembeli</h1>
        </div>
        <div class="content-menu-wrapper">
            <a href="{{ route('admin.refound-payment', ['status' => 'pending']) }}" class="menu-item @if($status == 'pending') active @endif ">Belum dibayarkan</a>
            <a href="{{ route('admin.refound-payment', ['status' => 'settlement']) }}" class="menu-item @if($status == 'settlement') active @endif ">Selesai dibayarkan</a>
        </div>
        <div class="page-content-card">
            <div class="card-title-wrapper">
                <span class="card-title">Daftar Permintaan Pembayaran</span>
                <button style="display: none" id="refoundBankAccountModalToggleBtn" type="button" class="btn auth-secondary" data-bs-toggle="modal" data-bs-target="#refound_bank_account_modal"></button>
            </div>
            <livewire:admin.refound-payment-table status='{{ $status }}' />
        </div>
    </div>

<livewire:components.refound-bank-account-modal />

</div>

@push('script')
<script>

    $( window ).on('toggleRefoundAccountModal', function (event) {
        $('#refoundBankAccountModalToggleBtn').click()
    })

</script>
@endpush