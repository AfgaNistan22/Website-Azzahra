@extends('pages.auth.guest')

@section('title', 'Masuk - Azzahra Salon & Spa Muslimah')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/css/auth-modern.css') }}?v={{ file_exists(public_path('template/css/auth-modern.css')) ? filemtime(public_path('template/css/auth-modern.css')) : time() }}">
@endpush

@section('content')

<main class="au-page">
    <div class="au-shell">

        @include('pages.partials.auth-art', [
            'judul'   => 'Selamat datang',
            'judulEm' => 'kembali.',
            'lead'    => 'Masuk untuk mengatur reservasi, melihat riwayat perawatan, dan menikmati layanan Azzahra.',
        ])

        <section class="au-panel">
            <div class="au-card {{ ($errors->any() || session('error')) ? 'has-alert' : '' }}">

                <div class="au-icon"><i class="fas fa-user-circle" aria-hidden="true"></i></div>

                <h1 class="au-title">Masuk ke Akun</h1>
                <p class="au-sub">Silakan masuk untuk mengakses akun Azzahra Salon &amp; Spa Muslimah.</p>

                @if (session('success'))
                    <div class="au-alert is-success" role="status"><i class="fas fa-check-circle" aria-hidden="true"></i><span>{{ session('success') }}</span></div>
                @endif

                @if (session('error'))
                    <div class="au-alert is-error" role="alert"><i class="fas fa-exclamation-circle" aria-hidden="true"></i><span>{{ session('error') }}</span></div>
                @endif

                {{-- LOGIN SOSIAL --}}
                @if (\Illuminate\Support\Facades\Route::has('auth.social.redirect'))
                    <div class="au-social" style="--i:1">
                        <a href="{{ route('auth.social.redirect', 'google') }}" class="au-social-btn" data-loading-text="Membuka Google...">
                            <svg viewBox="0 0 48 48" width="20" height="20" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/><path fill="#FBBC05" d="M10.5 28.7c-.5-1.5-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.3 0 20 0 24s.9 7.7 2.6 10.8l7.9-6.1z"/><path fill="#34A853" d="M24 48c6.3 0 11.7-2.1 15.6-5.7l-7.5-5.8c-2.1 1.4-4.8 2.3-8.1 2.3-6.3 0-11.6-4.1-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/></svg>
                            <span>Google</span>
                        </a>
                        <a href="{{ route('auth.social.redirect', 'facebook') }}" class="au-social-btn is-fb" data-loading-text="Membuka Facebook...">
                            <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="#1877F2" d="M24 12a12 12 0 1 0-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.7 4.5-4.7 1.3 0 2.7.2 2.7.2v3h-1.5c-1.5 0-2 .9-2 1.9V12h3.4l-.5 3.5h-2.9v8.4A12 12 0 0 0 24 12z"/></svg>
                            <span>Facebook</span>
                        </a>
                    </div>

                    <div class="au-divider" style="--i:2"><span>atau masuk dengan email</span></div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" data-auth-form novalidate>
                    @csrf

                    <div class="au-field @error('email') has-error @enderror" style="--i:3">
                        <label for="email">Alamat Email</label>
                        <div class="au-input">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   placeholder="Masukkan alamat email" autocomplete="email" required autofocus>
                        </div>
                        @error('email')
                            <small class="au-error"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <div class="au-field @error('password') has-error @enderror" style="--i:4">
                        <label for="password">Password</label>
                        <div class="au-input">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <input type="password" id="password" name="password"
                                   placeholder="Masukkan password" autocomplete="current-password" required>
                            <button type="button" class="au-eye" data-toggle-pass="password" aria-label="Tampilkan password"><i class="far fa-eye" aria-hidden="true"></i></button>
                        </div>
                        <small class="au-caps" id="capsHint" hidden><i class="fas fa-arrow-up" aria-hidden="true"></i> Caps Lock aktif</small>
                        @error('password')
                            <small class="au-error"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="au-submit" style="--i:5">
                        <span class="au-submit-label"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Masuk</span>
                        <span class="au-spinner" aria-hidden="true"></span>
                    </button>
                </form>

                @if (\Illuminate\Support\Facades\Route::has('register'))
                    <p class="au-switch" style="--i:6">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}">Daftar sekarang</a>
                    </p>
                @endif

            </div>
        </section>

    </div>
</main>

<script src="{{ asset('template/js/auth-modern.js') }}?v={{ file_exists(public_path('template/js/auth-modern.js')) ? filemtime(public_path('template/js/auth-modern.js')) : time() }}" defer></script>

@endsection