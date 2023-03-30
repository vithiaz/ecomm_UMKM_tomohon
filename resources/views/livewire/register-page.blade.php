@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/register-page.css') }}">
@endpush

<div class="register-page">
    <div class="container">
        <div class="register-card">
            <div class="register-card-title card-title">
                <h1>Daftar Akun Baru</h1>
            </div>
            <form wire:submit.prevent='register' class="register-card-content">
                <div class="form-floating">
                    <input wire:model='username' type="text" class="form-control @error('username') is-invalid @enderror" id="username_input" placeholder="Username*">
                    <label for="username_input">Username*</label>
                    @error('username')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='first_name' type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name_input" placeholder="Nama Depan*">
                    <label for="first_name_input">Nama Depan*</label>
                    @error('first_name')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='last_name' type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name_input" placeholder="Nama Belakang">
                    <label for="last_name_input">Nama Belakang</label>
                    @error('last_name')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='email' type="email" class="form-control @error('email') is-invalid @enderror" id="email_input" placeholder="Email*">
                    <label for="email_input">Email*</label>
                    @error('email')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='phone_number' type="number" class="form-control @error('phone_number') is-invalid @enderror" id="phone_input" placeholder="Nomor HP">
                    <label for="phone_input">Nomor HP</label>
                    @error('phone_number')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='password' type="password" class="form-control @error('password') is-invalid @enderror" id="password_input" placeholder="Password*">
                    <label for="password_input">Password*</label>
                    @error('password')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='password_confirmation' type="password" class="form-control @error('password_conf') is-invalid @enderror" id="password_conf_input" placeholder="Konfirmasi Password*">
                    <label for="password_conf_input">Konfirmasi Password*</label>
                    @error('password_confirmation')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror
                </div>
                <div class="button-wrapper">
                    <button type="submit" class="btn btn-default-orange">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>
