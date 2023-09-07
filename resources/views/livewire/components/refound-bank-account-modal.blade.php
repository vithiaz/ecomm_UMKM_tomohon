<div wire:ignore.self class="modal fade" id="refound_bank_account_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="close-modal-button" onclick="hide_auth_modal()" data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="title">Informasi Akun Refound</div>
            <div class="profile-wrapper">
                <div class="profile-head-wrapper">
                    <div class="image-container">
                        @if ($userDetail)
                            @if ($userDetail['profile_img'])
                                <img src="{{ asset('storage/'.$userDetail['profile_img']) }}" alt="{{ $userDetail['username'] }}_profile">
                            @else
                                <div class="no-image">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                            @endif                            
                        @endif
                    </div>
                    <span class="profile-name">{{ $userDetail ? $userDetail['username'] : '' }}</span>
                </div>
                <div class="card-content-wrapper">
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
                        @if ($refoundDetail)
                            <div class="wrapper-col">
                                <span class="col-title">Keterangan Refound</span>
                                <span class="col-body">{{ $refoundDetail['refound_description'] }}</span>
                            </div>                                                                    
                        @endif
                    @endif
                </div>
                <div class="card-content-wrapper">
                    <span class="card-content-wrapper-title">Informasi Rekening Refound</span>
                    @if ($refoundDetail)
                        <div class="wrapper-col">
                            <span class="col-title">Nama Bank</span>
                            <span class="col-body">{{ $refoundDetail['bank_name'] }}</span>
                        </div>
                        <div class="wrapper-col">
                            <span class="col-title">Nomor Rekening</span>
                            <span class="col-body">{{ $refoundDetail['account_number'] }}</span>
                        </div>
                        <div class="wrapper-col">
                            <span class="col-title">Pemilik Rekening</span>
                            <span class="col-body">{{ $refoundDetail['account_name'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('script')
<script>

    $( window ).on('toggleRefoundAccountModal', function (event) {
        const eventData = event.detail.data
        @this.set('userDetail', event.detail.userDetail);
        @this.set('refoundDetail', event.detail.refoundDetail);
        
    })

    function hide_auth_modal() {
        $('#umkm_bank_account_modal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }

</script>
@endpush