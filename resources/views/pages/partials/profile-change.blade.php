@extends('pages.auth.guest')

@section('title', 'Ubah Password | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap"
    >
    {{-- CSS dasar akun (isi sama dengan akun.css / Detail Profil dan Profil Saya) --}}
    <link rel="stylesheet" href="{{ asset('template/css/profilec.css') }}?v={{ filemtime(public_path('template/css/profilec.css')) }}">

    <style>
        /* =========================================================
           Tambahan khusus halaman Ubah Password
        ========================================================= */

        /* ---- Sidebar: kartu tips keamanan ---- */
        .akun-tips { padding: 18px 24px 20px; border-top: 1px solid var(--ak-line-soft); }
        .akun-tips-title { display: flex; align-items: center; gap: 9px; margin-bottom: 12px; color: var(--ak-ink); font-size: 13px; font-weight: 600; }
        .akun-tips-title i { color: var(--ak-brass-dark); }
        .akun-tips ul { margin: 0; padding: 0; list-style: none; }
        .akun-tips li { position: relative; padding: 5px 0 5px 20px; color: var(--ak-muted); font-size: 12.5px; line-height: 1.6; }
        .akun-tips li::before { content: ""; position: absolute; top: 12px; left: 2px; width: 7px; height: 7px; border-radius: 50%; background: var(--ak-brass); }

        /* ---- Kartu & bagian form ---- */
        .akun-pw-card { overflow: visible; }
        .akun-pw-card .akun-card-head { border-radius: var(--ak-radius) var(--ak-radius) 0 0; }
        .akun-pw-card .akun-form { max-width: none; }

        .akun-section { padding: 28px 0; border-top: 1px solid var(--ak-line-soft); }
        .akun-section:first-of-type { padding-top: 0; border-top: 0; }
        .akun-section-head { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
        .akun-section-ico {
            display: grid; flex-shrink: 0; width: 38px; height: 38px; place-items: center;
            border-radius: 10px; background: var(--ak-tint); color: var(--ak-maroon); font-size: 14px;
        }
        .akun-section-head h3 { margin: 0; color: var(--ak-dark); font-family: var(--ak-display); font-size: 18px; font-weight: 600; }
        .akun-section-head p { margin: 2px 0 0; color: var(--ak-muted); font-size: 13px; }
        .akun-section .akun-field:last-child { margin-bottom: 0; }

        .akun-pw-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 28px; align-items: start; }
        .akun-pw-grid .akun-field { margin-bottom: 0; }

        .akun-gen {
            display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 0;
            border: 0; background: none; color: var(--ak-maroon); font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer;
        }
        .akun-gen:hover { text-decoration: underline; text-underline-offset: 4px; }
        .akun-gen i { font-size: 12px; }

        /* ---- Panel kekuatan (kolom kanan) ---- */
        .akun-meter { padding: 20px; border: 1px solid var(--ak-line-soft); border-radius: 12px; background: #FCFAFA; }
        .akun-meter-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: var(--ak-muted); }
        .akun-meter-top b { color: var(--ak-ink); font-weight: 700; }
        .akun-meter .akun-strength { margin-top: 0; }
        .akun-meter .akun-rules { margin-top: 18px; padding: 0; border: 0; background: transparent; grid-template-columns: 1fr; gap: 10px; }

        /* ---- Bilah simpan ---- */
        .akun-savebar {
            position: sticky; bottom: 0; z-index: 5; justify-content: space-between; align-items: center;
            margin: 8px -36px -36px; padding: 16px 36px;
            border-radius: 0 0 var(--ak-radius) var(--ak-radius);
            background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
            box-shadow: 0 -8px 24px rgba(69,13,29,.06);
        }
        .akun-savestate { display: inline-flex; align-items: center; gap: 9px; color: var(--ak-muted); font-size: 13px; }
        .akun-savestate::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #CDBFC3; }
        .akun-savebar.is-ready .akun-savestate { color: var(--ak-ok); font-weight: 500; }
        .akun-savebar.is-ready .akun-savestate::before { background: var(--ak-ok); box-shadow: 0 0 0 4px rgba(46,139,87,.18); }
        .akun-savebar-btns { display: flex; gap: 12px; }

        @media (max-width: 760px) {
            .akun-pw-grid { grid-template-columns: 1fr; gap: 22px; }
        }
        @media (max-width: 640px) {
            .akun-savebar { flex-direction: column; align-items: stretch; margin: 8px -20px -26px; padding: 14px 20px; }
            .akun-savebar-btns { flex-direction: column-reverse; }
            .akun-savebar-btns .akun-btn { width: 100%; }
            .akun-savestate { justify-content: center; }
            .akun-tips { padding: 16px 20px; }
        }
    </style>
@endpush

@section('content')

@php
    $user = auth()->user();

    $fotoDefault = asset('default/user.png');
    $foto = $user->photo
        ? (\Illuminate\Support\Str::startsWith($user->photo, ['http://', 'https://'])
            ? $user->photo
            : asset('storage/' . $user->photo))
        : $fotoDefault;

    $urlProfil = \Illuminate\Support\Facades\Route::has('profile') ? route('profile') : url('/profile');
    $urlSandi  = \Illuminate\Support\Facades\Route::has('password.change') ? route('password.change') : url('/change');
@endphp

<main class="akun-page">

    {{-- HERO --}}
    <section class="akun-hero">
        <div class="akun-wrap">
            <div class="akun-eyebrow">Akun saya</div>
            <h1>Ubah password</h1>
            <p>Perbarui kata sandi untuk menjaga keamanan akun Anda.</p>
        </div>
    </section>

    <div class="akun-wrap akun-layout">

        {{-- SIDEBAR --}}
        <aside class="akun-side akun-step" style="--d: 0">
            <div class="akun-user">
                <img class="akun-avatar"
                     data-fallback="{{ $fotoDefault }}"
                     src="{{ $foto }}"
                     alt="">
                <div class="akun-user-text">
                    <div class="akun-user-name">{{ $user->name }}</div>
                    <div class="akun-user-email">{{ $user->email }}</div>
                </div>
            </div>

            <div class="akun-tips">
                <div class="akun-tips-title"><i class="fas fa-shield-alt" aria-hidden="true"></i> Tips keamanan</div>
                <ul>
                    <li>Gunakan minimal 8 karakter, makin panjang makin aman.</li>
                    <li>Padukan huruf besar, huruf kecil, angka, dan simbol.</li>
                    <li>Jangan memakai password yang sama dengan akun lain.</li>
                    <li>Jangan bagikan password kepada siapa pun.</li>
                </ul>
            </div>

            <nav class="akun-nav" aria-label="Pengaturan akun">
                <a class="akun-nav-item" href="{{ $urlProfil }}">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    Profil saya
                </a>
                <a class="akun-nav-item is-active" href="{{ $urlSandi }}" aria-current="page">
                    <i class="fas fa-key" aria-hidden="true"></i>
                    Ubah password
                </a>
            </nav>
        </aside>

        {{-- KARTU FORM --}}
        <section class="akun-card akun-pw-card akun-step" style="--d: 1">
            <header class="akun-card-head">
                <h2>Keamanan akun</h2>
                <p>Gunakan password yang kuat dan belum pernah dipakai di tempat lain.</p>
            </header>

            <div class="akun-card-body">

                @if (session('success'))
                    <div class="akun-flash is-success" role="status">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="akun-flash-close" aria-label="Tutup notifikasi">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="akun-flash is-error" role="alert">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="akun-flash-close" aria-label="Tutup notifikasi">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                @elseif ($errors->any())
                    <div class="akun-flash is-error" role="alert">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <span>Password belum dapat diubah. Periksa isian yang ditandai merah.</span>
                    </div>
                @endif

                <form class="akun-form"
                      action="{{ url('change/' . $user->id) }}"
                      method="POST"
                      id="formSandi"
                      novalidate>
                    @csrf

                    {{-- ===== 1. VERIFIKASI ===== --}}
                    <div class="akun-section akun-step" style="--d: 2">
                        <div class="akun-section-head">
                            <span class="akun-section-ico"><i class="fas fa-user-lock" aria-hidden="true"></i></span>
                            <div>
                                <h3>Verifikasi identitas</h3>
                                <p>Masukkan password Anda saat ini untuk melanjutkan.</p>
                            </div>
                        </div>

                        <div class="akun-field">
                            <label for="old_password">Password lama</label>
                            <div class="akun-control">
                                <i class="fas fa-lock akun-field-icon" aria-hidden="true"></i>
                                <input type="password"
                                       id="old_password"
                                       name="old_password"
                                       class="akun-input has-icon has-toggle @error('old_password') is-invalid @enderror"
                                       autocomplete="current-password"
                                       required>
                                <button type="button" class="akun-eye" data-target="old_password"
                                        aria-label="Tampilkan password lama" aria-pressed="false">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p class="akun-caps" data-caps hidden>
                                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                Caps Lock sedang aktif.
                            </p>
                            @error('old_password')
                                <p class="akun-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ===== 2. PASSWORD BARU ===== --}}
                    <div class="akun-section akun-step" style="--d: 3">
                        <div class="akun-section-head">
                            <span class="akun-section-ico"><i class="fas fa-key" aria-hidden="true"></i></span>
                            <div>
                                <h3>Password baru</h3>
                                <p>Buat password yang kuat dan mudah Anda ingat.</p>
                            </div>
                        </div>

                        <div class="akun-pw-grid">

                            <div>
                                <div class="akun-field" style="margin-bottom:24px">
                                    <label for="new_password">Password baru</label>
                                    <div class="akun-control">
                                        <i class="fas fa-key akun-field-icon" aria-hidden="true"></i>
                                        <input type="password"
                                               id="new_password"
                                               name="new_password"
                                               class="akun-input has-icon has-toggle @error('new_password') is-invalid @enderror"
                                               autocomplete="new-password"
                                               aria-describedby="aturanSandi"
                                               required>
                                        <button type="button" class="akun-eye" data-target="new_password"
                                                aria-label="Tampilkan password baru" aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <p class="akun-caps" data-caps hidden>
                                        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                        Caps Lock sedang aktif.
                                    </p>
                                    @error('new_password')
                                        <p class="akun-error">{{ $message }}</p>
                                    @enderror
                                    <p class="akun-error" id="samaError" role="alert" hidden></p>

                                    <button type="button" class="akun-gen" id="btnAcak">
                                        <i class="fas fa-magic" aria-hidden="true"></i>
                                        Buatkan password kuat
                                    </button>
                                </div>

                                <div class="akun-field">
                                    <label for="new_password_confirmation">Konfirmasi password baru</label>
                                    <div class="akun-control">
                                        <i class="fas fa-key akun-field-icon" aria-hidden="true"></i>
                                        <input type="password"
                                               id="new_password_confirmation"
                                               name="new_password_confirmation"
                                               class="akun-input has-icon has-toggle @error('new_password_confirmation') is-invalid @enderror"
                                               autocomplete="new-password"
                                               required>
                                        <button type="button" class="akun-eye" data-target="new_password_confirmation"
                                                aria-label="Tampilkan konfirmasi password" aria-pressed="false">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <p class="akun-caps" data-caps hidden>
                                        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                                        Caps Lock sedang aktif.
                                    </p>
                                    <p class="akun-match" id="kecocokan" role="status" hidden></p>
                                    @error('new_password_confirmation')
                                        <p class="akun-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Panel kekuatan --}}
                            <div class="akun-meter">
                                <div class="akun-meter-top">
                                    <span>Kekuatan password</span>
                                    <b id="kekuatanTeks">belum diisi</b>
                                </div>

                                <div class="akun-strength" id="kekuatan" data-level="0" aria-live="polite">
                                    <div class="akun-strength-bar" aria-hidden="true">
                                        <span></span><span></span><span></span><span></span>
                                    </div>
                                </div>

                                <ul class="akun-rules" id="aturanSandi">
                                    <li data-rule="length"><i class="fas fa-check" aria-hidden="true"></i> Minimal 8 karakter</li>
                                    <li data-rule="upper"><i class="fas fa-check" aria-hidden="true"></i> Huruf besar (A–Z)</li>
                                    <li data-rule="lower"><i class="fas fa-check" aria-hidden="true"></i> Huruf kecil (a–z)</li>
                                    <li data-rule="digit"><i class="fas fa-check" aria-hidden="true"></i> Angka (0–9)</li>
                                    <li data-rule="symbol"><i class="fas fa-check" aria-hidden="true"></i> Simbol (! @ # ...)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ===== TOMBOL ===== --}}
                    <div class="akun-actions akun-savebar akun-step" id="saveBar" style="--d: 4">
                        <span class="akun-savestate" id="saveState" aria-live="polite">Isi semua kolom untuk melanjutkan</span>

                        <div class="akun-savebar-btns">
                            <a href="/" class="akun-btn akun-btn-line">Kembali</a>

                            <button type="submit" class="akun-btn akun-btn-primary" id="btnSimpan">
                                <span class="akun-btn-label">
                                    <i class="fas fa-save" aria-hidden="true"></i>
                                    Simpan perubahan
                                </span>
                                <span class="akun-spinner" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        (function () {
            var page = document.querySelector('.akun-page');
            if (!page) return;

            /* Foto cadangan bila gambar gagal dimuat */
            page.querySelectorAll('img[data-fallback]').forEach(function (img) {
                var pakaiCadangan = function () {
                    img.removeEventListener('error', pakaiCadangan);
                    img.src = img.getAttribute('data-fallback');
                };
                img.addEventListener('error', pakaiCadangan);
                if (img.complete && img.naturalWidth === 0) pakaiCadangan();
            });

            /* Tutup notifikasi */
            page.querySelectorAll('.akun-flash-close').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var box = btn.closest('.akun-flash');
                    box.classList.add('is-leaving');
                    setTimeout(function () { box.remove(); }, 260);
                });
            });

            var lama    = document.getElementById('old_password');
            var baru    = document.getElementById('new_password');
            var konfirm = document.getElementById('new_password_confirmation');
            var kotak   = document.getElementById('kekuatan');
            var teks    = document.getElementById('kekuatanTeks');
            var cocok   = document.getElementById('kecocokan');
            var samaErr = document.getElementById('samaError');
            var saveBar = document.getElementById('saveBar');
            var saveSt  = document.getElementById('saveState');
            var form    = document.getElementById('formSandi');
            var btn     = document.getElementById('btnSimpan');

            /* ---------- Tampilkan / sembunyikan password ---------- */
            function setLihat(input, buka) {
                var tombol = page.querySelector('.akun-eye[data-target="' + input.id + '"]');
                input.type = buka ? 'text' : 'password';
                tombol.querySelector('i').className = buka ? 'fas fa-eye-slash' : 'fas fa-eye';
                tombol.setAttribute('aria-pressed', buka ? 'true' : 'false');
                tombol.setAttribute('aria-label', tombol.getAttribute('aria-label')
                    .replace(buka ? 'Tampilkan' : 'Sembunyikan', buka ? 'Sembunyikan' : 'Tampilkan'));
            }

            page.querySelectorAll('.akun-eye').forEach(function (tombol) {
                tombol.addEventListener('click', function () {
                    var input = document.getElementById(tombol.getAttribute('data-target'));
                    setLihat(input, input.type === 'password');
                });
            });

            /* ---------- Peringatan Caps Lock ---------- */
            page.querySelectorAll('input.akun-input').forEach(function (input) {
                var peringatan = input.closest('.akun-field').querySelector('[data-caps]');
                if (!peringatan) return;

                var cek = function (e) {
                    if (!e.getModifierState) return;
                    peringatan.hidden = !e.getModifierState('CapsLock');
                };
                input.addEventListener('keydown', cek);
                input.addEventListener('keyup', cek);
                input.addEventListener('blur', function () { peringatan.hidden = true; });
            });

            /* ---------- Kekuatan password ---------- */
            var aturan = {
                length: function (v) { return v.length >= 8; },
                upper:  function (v) { return /[A-Z]/.test(v); },
                lower:  function (v) { return /[a-z]/.test(v); },
                digit:  function (v) { return /[0-9]/.test(v); },
                symbol: function (v) { return /[^A-Za-z0-9]/.test(v); }
            };
            var namaLevel = ['belum diisi', 'lemah', 'cukup', 'kuat', 'sangat kuat'];

            function periksaKekuatan() {
                var v = baru.value, terpenuhi = 0;

                Object.keys(aturan).forEach(function (kunci) {
                    var li  = page.querySelector('[data-rule="' + kunci + '"]');
                    var oke = aturan[kunci](v);
                    if (oke) terpenuhi++;
                    li.classList.toggle('is-met', oke);
                });

                var level = 0;
                if (v.length > 0) {
                    level = terpenuhi <= 1 ? 1 : terpenuhi <= 3 ? 2 : terpenuhi === 4 ? 3 : 4;
                    if (v.length < 8 && level > 1) level = 1;      // kurang dari 8 karakter selalu lemah
                }

                kotak.setAttribute('data-level', level);
                teks.textContent = namaLevel[level];
            }

            function periksaKecocokan() {
                if (!konfirm.value) { cocok.hidden = true; return; }

                var sama = konfirm.value === baru.value;
                cocok.hidden = false;
                cocok.className = 'akun-match ' + (sama ? 'is-ok' : 'is-bad');
                cocok.innerHTML = sama
                    ? '<i class="fas fa-check-circle" aria-hidden="true"></i> Password cocok'
                    : '<i class="fas fa-times-circle" aria-hidden="true"></i> Password belum sama';
            }

            /* ---------- Status tombol simpan ---------- */
            function siap() {
                return lama.value && baru.value.length >= 8 && baru.value === konfirm.value;
            }

            function perbaruiStatus() {
                var ok = !!siap();
                saveBar.classList.toggle('is-ready', ok);
                saveSt.textContent = ok ? 'Siap disimpan' : 'Isi semua kolom untuk melanjutkan';
            }

            function bersihkanSama() { samaErr.hidden = true; }

            baru.addEventListener('input', function () {
                periksaKekuatan(); periksaKecocokan(); bersihkanSama(); perbaruiStatus();
            });
            konfirm.addEventListener('input', function () { periksaKecocokan(); perbaruiStatus(); });
            lama.addEventListener('input', function () { bersihkanSama(); perbaruiStatus(); });

            [lama, baru, konfirm].forEach(function (el) {
                el.addEventListener('input', function () { el.classList.remove('is-invalid'); });
            });

            /* ---------- Buatkan password kuat ---------- */
            document.getElementById('btnAcak').addEventListener('click', function () {
                var kelompok = [
                    'ABCDEFGHJKLMNPQRSTUVWXYZ',
                    'abcdefghijkmnopqrstuvwxyz',
                    '23456789',
                    '!@#$%^&*?'
                ];
                var semua = kelompok.join('');
                var PANJANG = 14;

                var acak = function (n) {
                    var a = new Uint32Array(1);
                    window.crypto.getRandomValues(a);
                    return a[0] % n;
                };

                var hasil = kelompok.map(function (g) { return g.charAt(acak(g.length)); });
                while (hasil.length < PANJANG) hasil.push(semua.charAt(acak(semua.length)));

                for (var i = hasil.length - 1; i > 0; i--) {          // kocok urutan
                    var j = acak(i + 1), t = hasil[i]; hasil[i] = hasil[j]; hasil[j] = t;
                }

                baru.value = konfirm.value = hasil.join('');
                setLihat(baru, true);
                setLihat(konfirm, true);
                baru.classList.remove('is-invalid');
                konfirm.classList.remove('is-invalid');
                periksaKekuatan(); periksaKecocokan(); bersihkanSama(); perbaruiStatus();
            });

            /* ---------- Kirim ---------- */
            form.addEventListener('submit', function (e) {
                var kosong = [lama, baru, konfirm].filter(function (el) { return !el.value; });
                if (kosong.length) {
                    e.preventDefault();
                    kosong.forEach(function (el) { el.classList.add('is-invalid'); });
                    kosong[0].focus();
                    return;
                }

                if (baru.value.length < 8) {
                    e.preventDefault();
                    baru.classList.add('is-invalid');
                    samaErr.textContent = 'Password baru minimal 8 karakter.';
                    samaErr.hidden = false;
                    baru.focus();
                    return;
                }

                if (baru.value === lama.value) {
                    e.preventDefault();
                    baru.classList.add('is-invalid');
                    samaErr.textContent = 'Password baru tidak boleh sama dengan password lama.';
                    samaErr.hidden = false;
                    baru.focus();
                    return;
                }

                if (baru.value !== konfirm.value) {
                    e.preventDefault();
                    konfirm.classList.remove('is-invalid');
                    void konfirm.offsetWidth;          // mulai ulang animasi getar
                    konfirm.classList.add('is-invalid');
                    periksaKecocokan();
                    konfirm.focus();
                    return;
                }

                btn.classList.add('is-loading');
            });

            periksaKekuatan();
            perbaruiStatus();
        })();
    </script>
</main>

@endsection