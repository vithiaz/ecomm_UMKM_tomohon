<div wire:ignore.self class="modal fade" id="umkm_bank_account_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="close-modal-button" onclick="hide_auth_modal()" data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="title">Informasi UMKM</div>
            <div class="profile-wrapper">
                <div class="profile-head-wrapper">
                    <div class="image-container">
                        @if ($umkmDetail)
                            @if ($umkmDetail['profile_img'])
                                <img src="{{ asset('storage/'.$umkmDetail['profile_img']) }}" alt="{{ $umkmDetail['name'] }}_profile">
                            @else
                                <div class="no-image">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                            @endif                            
                        @endif
                    </div>
                    <span class="profile-name">{{ $umkmDetail ? $umkmDetail['name'] : '' }}</span>
                </div>
                <div class="card-content-wrapper">
                    <span class="card-content-wrapper-title">Informasi Pemilik UMKM</span>
                    @if ($userDetail)
                        <div class="wrapper-col">
                            <span class="col-title">Nama Lengkap</span>
                            <span class="col-body">{{ $userDetail['first_name'] }} {{ $userDetail['last_name'] }}</span>
                        </div>
                        <div class="wrapper-col">
                            <span class="col-title">Email</span>
                            <span class="col-body">{{ $userDetail['email'] }}</span>
                        </div>
                        <div class="wrapper-col">
                            <span class="col-title">Alamat</span>
                            <span class="col-body">{{ $userDetail['address'] }}</span>
                        </div>                                        
                    @endif
                </div>
                @if ($bankAccounts)
                    <div class="card-content-wrapper">
                        <span class="card-content-wrapper-title">Daftar Rekening Aktif</span>
                        <div class="bank-card-wrapper">
                            @foreach ($bankAccounts as $account)
                                <div class="bank-card">
                                    <div class="bank-acc">
                                        <span>{{ $account['bank_name'] }}</span>
                                        <span>{{ $account['account_number'] }}</span>
                                    </div>
                                    <div class="owner-name">
                                        <span>{{ $account['account_name'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('script')
<script>

    $( window ).on('toggleBankAccountModal', function (event) {
        const eventData = event.detail.data
        @this.set('umkmDetail', eventData);
        @this.set('userDetail', eventData['user']);
        @this.set('bankAccounts', event.detail.bank_accounts);
        
    })

    function hide_auth_modal() {
        $('#umkm_bank_account_modal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }

</script>
@endpush