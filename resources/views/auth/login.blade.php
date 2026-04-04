@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center w-100">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-5">
                <i class="fas fa-shoe-prints fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold" style="font-family: 'Playfair Display', serif; font-style: italic;">Masuk Area Klien</h3>
                <p class="text-muted" style="font-weight: 300; font-size: 0.9rem;">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>
            
            <div class="card p-4">
                <div class="card-body p-0">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nama@email.com">
                            </div>

                            @error('email')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Kata Sandi') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>

                            @error('password')
                                <span class="invalid-feedback d-block mt-2" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="form-check">
                                <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">
                                    {{ __('Ingat Saya') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none text-muted transition" href="{{ route('password.request') }}" style="font-size: 0.85rem; border-bottom: 1px solid transparent;" onmouseover="this.style.color='#f472b6'; this.style.borderColor='#f472b6'" onmouseout="this.style.color=''; this.style.borderColor='transparent'">
                                    {{ __('Lupa Kata Sandi?') }}
                                </a>
                            @endif
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-3">
                                {{ __('Masuk') }}
                            </button>
                        </div>
                        
                        @if (Route::has('register'))
                            <div class="text-center text-muted" style="font-size: 0.9rem;">
                                Belum memiliki akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Daftar Sekarang</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
