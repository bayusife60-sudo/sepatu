@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-5">
                <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold" style="font-family: 'Playfair Display', serif; font-style: italic;">Bergabung Bersama Kami</h3>
                <p class="text-white" style="font-weight: 300; font-size: 0.9rem;">Daftarkan diri Anda untuk merasakan layanan premium</p>
            </div>
            
            <div class="card p-4">
                <div class="card-body p-0">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">
                            </div>

                            @error('name')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                            </div>

                            @error('email')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">{{ __('Nomor WhatsApp') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                <input id="phone" type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="08123456789">
                            </div>

                            @error('phone')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Kata Sandi') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-color: rgba(255,255,255,0.1); background-color: #2a2a2a; color: #a0a0a0;">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>

                            @error('password')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label for="password-confirm" class="form-label">{{ __('Konfirmasi Kata Sandi') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                <input id="password-confirm" type="password" class="form-control border-start-0 ps-0 border-end-0" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword" style="border-color: rgba(255,255,255,0.1); background-color: #2a2a2a; color: #a0a0a0;">
                                    <i class="fas fa-eye" id="toggleConfirmPasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-3">
                                {{ __('Daftar Sekarang') }}
                            </button>
                        </div>
                        
                        @if (Route::has('login'))
                            <div class="text-center text-white" style="font-size: 0.9rem;">
                                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Masuk di sini</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const togglePasswordIcon = document.querySelector('#togglePasswordIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePasswordIcon.classList.toggle('fa-eye');
            togglePasswordIcon.classList.toggle('fa-eye-slash');
        });

        // Toggle Confirm Password
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const passwordConfirm = document.querySelector('#password-confirm');
        const toggleConfirmPasswordIcon = document.querySelector('#toggleConfirmPasswordIcon');

        toggleConfirmPassword.addEventListener('click', function (e) {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            toggleConfirmPasswordIcon.classList.toggle('fa-eye');
            toggleConfirmPasswordIcon.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endsection
