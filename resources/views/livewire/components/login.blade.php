<div wire:ignore.self class="modal fade" id="auth_modal" tabindex="-1">
    <div class="modal-dialog">
        <form wire:submit.prevent='login' class="modal-content" id="auth_modal_form">
            @csrf

            <button class="close-modal-button" onclick="hide_auth_modal()" data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="logo-wrapper">
                <img src="{{ asset('img\koperasi_dan_UMKM_RI_logo.png') }}" alt="DISKOPUKM-logo">
                <div class="brand">
                    <span class="brand-name">DISKOPUKM</span>
                </div>
            </div>

            <div class="title">Masuk</div>
            <div class="form-floating">
                <input wire:model='username' type="text" class="form-control @error('username') is-invalid @enderror @if(Session::has('error')) is-invalid @endif" id="username_input" placeholder="username">
                <label for="username_input">Username</label>
                @error('username')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-floating">
                <input wire:model='password' type="password" class="form-control @error('password') is-invalid @enderror @if(Session::has('error')) is-invalid @endif" id="login_password" placeholder="password">
                <label for="login_password">Password</label>
                @error('password')
                    <small class="error">{{ $message }}</small>
                @enderror
            </div>
            @if (Session::has('error'))
                <small class="error">{{ Session::get('error') }}</small>
            @endif
            <button type="submit" class="btn btn-default-dark">
                Masuk
            </button>
            <div class="register-suggest">
                <span>Belum punya akun?</span>
                <a href="{{ route('register') }}" class="register"> Buat Akun</a>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>

    function hide_auth_modal() {
        $('#auth_modal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }

    $('#username_input').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            @this.check_login();
        }
    });
    $('#login_password').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            @this.check_login();
        }
    });

</script>
@endpush