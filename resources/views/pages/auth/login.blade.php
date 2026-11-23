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
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label for="password" style="margin-bottom:0;">Password</label>
                            <a href="javascript:void(0)" onclick="bukaModalLupaPassword()" style="font-size:12px; color:#C2415D; font-weight:700; text-decoration:none;">
                                Lupa password?
                            </a>
                        </div>
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

{{-- MODAL BANTUAN LUPA PASSWORD --}}
<div id="modalLupaPassword" class="au-modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(42, 10, 11, 0.72); backdrop-filter:blur(6px); z-index:99999; justify-content:center; align-items:center; padding:16px;">
    <div class="au-modal-card" style="background:#fff; width:100%; max-width:440px; border-radius:24px; padding:32px 24px; box-shadow:0 24px 60px rgba(100,23,45,0.45); text-align:center; position:relative; animation:au-rise .35s ease;">
        <button type="button" onclick="tutupModalLupaPassword()" style="position:absolute; top:18px; right:18px; background:none; border:none; font-size:24px; color:#999; cursor:pointer; line-height:1;" aria-label="Tutup">&times;</button>
        
        <div style="width:68px; height:68px; margin:0 auto 16px; border-radius:50%; background:#fdf2f4; display:grid; place-items:center; color:#C2415D; font-size:28px;">
            <i class="fas fa-key"></i>
        </div>

        <h3 style="font-size:1.3rem; font-weight:800; color:#3a1214; margin-bottom:8px;">Bantuan Lupa Kata Sandi</h3>
        <p style="font-size:0.875rem; color:#666; margin-bottom:22px; line-height:1.5;">
            Untuk menjaga keamanan akun Anda, silakan gunakan opsi pemulihan akun di bawah ini:
        </p>

        {{-- OPSI 1: CHAT WHATSAPP ADMIN --}}
        @php
            $waAdmin = '6281246771771';
            $pesanWa = rawurlencode("Halo Admin Azzahra Salon & Spa,\nSaya lupa kata sandi akun saya.\nMohon bantuan untuk melakukan reset password akun saya. Terima kasih.");
            $linkWa = 'https://wa.me/' . $waAdmin . '?text=' . $pesanWa;
        @endphp
        <a href="{{ $linkWa }}" target="_blank" rel="noopener" style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:14px; margin-bottom:12px; background:linear-gradient(135deg, #25D366, #128C7E); color:#fff; border-radius:14px; text-decoration:none; font-weight:700; font-size:0.95rem; box-shadow:0 8px 20px -6px rgba(18,140,126,0.6);">
            <i class="fab fa-whatsapp fa-lg"></i>
            <span>Bantuan Reset via WhatsApp Admin</span>
        </a>

        {{-- OPSI 2: LOGIN VIA GOOGLE --}}
        @if (\Illuminate\Support\Facades\Route::has('auth.social.redirect'))
            <a href="{{ route('auth.social.redirect', 'google') }}" style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:12px; margin-bottom:14px; background:#fff; color:#3c4043; border:1px solid #dadce0; border-radius:14px; text-decoration:none; font-weight:600; font-size:0.9rem;">
                <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z"/><path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.836.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/><path fill="#FBBC05" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707 0-.59.102-1.167.282-1.707V4.961H.957C.347 6.175 0 7.55 0 9s.347 2.825.957 4.039l3.007-2.332z"/><path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z"/></svg>
                <span>Masuk Tanpa Password via Google</span>
            </a>
        @endif

        <div style="font-size:0.75rem; color:#888; margin-bottom:14px; background:#faf7f8; padding:8px 12px; border-radius:10px;">
            <i class="fas fa-info-circle mr-1"></i> Admin akan meresetkan kata sandi sementara setelah memverifikasi nomor telepon akun Anda.
        </div>

        <button type="button" onclick="tutupModalLupaPassword()" style="background:none; border:none; color:#777; font-size:0.85rem; cursor:pointer; text-decoration:underline;">
            Kembali ke halaman login
        </button>
    </div>
</div>

<script>
function bukaModalLupaPassword() {
    var modal = document.getElementById('modalLupaPassword');
    if (modal) {
        modal.style.display = 'flex';
    }
}
function tutupModalLupaPassword() {
    var modal = document.getElementById('modalLupaPassword');
    if (modal) {
        modal.style.display = 'none';
    }
}
window.addEventListener('click', function(e) {
    var modal = document.getElementById('modalLupaPassword');
    if (e.target === modal) {
        tutupModalLupaPassword();
    }
});
</script>

<script src="{{ asset('template/js/auth-modern.js') }}?v={{ file_exists(public_path('template/js/auth-modern.js')) ? filemtime(public_path('template/js/auth-modern.js')) : time() }}" defer></script>

@endsection