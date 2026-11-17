@extends('pages.auth.guest')

@section('title', 'Profil Saya | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap"
    >
    {{-- CSS dasar akun (isi sama dengan akun.css / Detail Profil dan Ubah Password) --}}
    <link rel="stylesheet" href="{{ asset('template/css/profilec.css') }}?v={{ filemtime(public_path('template/css/profilec.css')) }}">

    <style>
        /* =========================================================
           Tambahan khusus halaman Profil Saya
           (memakai variabel warna .akun-page dari CSS dasar)
        ========================================================= */

        /* ---- Sidebar: lencana & kelengkapan profil ---- */
        .akun-badge {
            display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 3px 10px;
            border-radius: 50px; background: var(--ak-tint); color: var(--ak-maroon);
            font-size: 11px; font-weight: 600;
        }
        .akun-badge.is-member { background: #FBF3DF; color: var(--ak-brass-dark); }
        .akun-badge i { font-size: 10px; }

        .akun-complete { padding: 18px 24px 20px; border-top: 1px solid var(--ak-line-soft); }
        .akun-complete-top { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 10px; font-size: 13px; font-weight: 600; color: var(--ak-ink); }
        .akun-complete-top b { color: var(--ak-maroon); font-size: 15px; }
        .akun-progress { height: 7px; overflow: hidden; border-radius: 7px; background: var(--ak-line-soft); }
        .akun-progress span {
            display: block; height: 100%; border-radius: 7px;
            background: linear-gradient(90deg, var(--ak-brass), var(--ak-maroon));
            transition: width .5s var(--ak-ease);
        }
        .akun-complete-hint { margin: 10px 0 0; color: var(--ak-muted); font-size: 12px; line-height: 1.6; }

        /* ---- Kartu: bagian-bagian form ---- */
        .akun-profile-card { overflow: visible; }
        .akun-profile-card .akun-card-head { border-radius: var(--ak-radius) var(--ak-radius) 0 0; }
        .akun-profile-card .akun-form { max-width: none; }

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

        .akun-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
        .akun-grid-2 > .akun-field { min-width: 0; }

        .akun-req { margin-left: 3px; color: var(--ak-danger); }
        textarea.akun-input { min-height: 112px; padding-top: 13px; padding-bottom: 13px; line-height: 1.7; resize: vertical; }
        .akun-input.is-locked { border-style: dashed; background: #FBF9F9; color: var(--ak-muted); cursor: not-allowed; }
        .akun-field-foot { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .akun-counter { margin: 8px 0 0; color: var(--ak-muted); font-size: 12px; white-space: nowrap; }

        /* ---- Foto profil ---- */
        .akun-photo-box { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
        .akun-photo-circle {
            position: relative; flex-shrink: 0; width: 112px; height: 112px;
            border: 3px solid #fff; border-radius: 50%; background: var(--ak-tint);
            box-shadow: 0 0 0 2px var(--ak-brass), 0 10px 24px rgba(69,13,29,.14);
        }
        .akun-photo-circle img { display: block; width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .akun-photo-circle img.is-changed { animation: akun-pop .45s var(--ak-ease); }
        .akun-photo-badge {
            position: absolute; right: -2px; bottom: -2px; display: grid; width: 34px; height: 34px; place-items: center;
            border: 3px solid #fff; border-radius: 50%; background: var(--ak-maroon); color: #fff; font-size: 12px; cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }
        .akun-photo-badge:hover { background: var(--ak-dark); transform: scale(1.06); }
        .akun-photo-side { flex: 1 1 240px; min-width: 0; }
        .akun-photo-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; margin-top: 14px; }
        .akun-photo-hint { margin: 6px 0 0; color: var(--ak-muted); font-size: 13px; line-height: 1.7; }

        /* ---- Info keanggotaan ---- */
        .akun-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .akun-stat { padding: 16px 18px; border: 1px solid var(--ak-line-soft); border-radius: 12px; background: #FCFAFA; }
        .akun-stat small { display: block; margin-bottom: 6px; color: var(--ak-muted); font-size: 12px; }
        .akun-stat b { color: var(--ak-dark); font-size: 17px; font-weight: 700; }
        .akun-stat.is-member { border-color: rgba(169,139,79,.4); background: #FBF6EA; }
        .akun-stat.is-member b { color: var(--ak-brass-dark); }

        /* ---- Bilah simpan (menempel di bawah layar) ---- */
        .akun-savebar {
            position: sticky; bottom: 0; z-index: 5; justify-content: space-between; align-items: center;
            margin: 8px -36px -36px; padding: 16px 36px;
            border-radius: 0 0 var(--ak-radius) var(--ak-radius);
            background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
            box-shadow: 0 -8px 24px rgba(69,13,29,.06);
        }
        .akun-savestate { display: inline-flex; align-items: center; gap: 9px; color: var(--ak-muted); font-size: 13px; }
        .akun-savestate::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--ak-ok); }
        .akun-savebar.is-dirty .akun-savestate { color: #8A5A12; font-weight: 500; }
        .akun-savebar.is-dirty .akun-savestate::before { background: #D99A2B; box-shadow: 0 0 0 4px rgba(217,154,43,.2); }
        .akun-savebar-btns { display: flex; gap: 12px; }

        @media (max-width: 640px) {
            .akun-grid-2, .akun-stats { grid-template-columns: 1fr; }
            .akun-photo-box { gap: 18px; }
            .akun-photo-circle { width: 96px; height: 96px; }
            .akun-savebar { flex-direction: column; align-items: stretch; margin: 8px -20px -26px; padding: 14px 20px; }
            .akun-savebar-btns { flex-direction: column-reverse; }
            .akun-savebar-btns .akun-btn { width: 100%; }
            .akun-savestate { justify-content: center; }
            .akun-complete { padding: 16px 20px; }
        }
    </style>
@endpush

@section('content')

@php
    $user      = auth()->user();
    $pelanggan = $pelanggan ?? null;   // dikirim dari controller (boleh null)

    $fotoDefault = asset('default/user.png');

    if ($pelanggan && $pelanggan->foto) {
        $foto = asset('storage/foto_pelanggan/' . $pelanggan->foto);
    } elseif ($user->photo) {
        $foto = \Illuminate\Support\Str::startsWith($user->photo, ['http://', 'https://'])
            ? $user->photo
            : asset('storage/' . $user->photo);
    } else {
        $foto = $fotoDefault;
    }

    $adaFoto = ($pelanggan && $pelanggan->foto) || $user->photo;

    $urlProfil = \Illuminate\Support\Facades\Route::has('profile') ? route('profile') : url('/profile');
    $urlSandi  = \Illuminate\Support\Facades\Route::has('password.change') ? route('password.change') : url('/change');

    $tglLahir  = $pelanggan && $pelanggan->tgl_lahir ? \Carbon\Carbon::parse($pelanggan->tgl_lahir)->format('Y-m-d') : '';
    $terakhir  = $pelanggan && $pelanggan->terakhir_datang ? \Carbon\Carbon::parse($pelanggan->terakhir_datang)->translatedFormat('d M Y') : 'Belum ada';
    $isMember  = $pelanggan && ($pelanggan->member ?? 'Tidak') === 'Ya';

    $nilaiNama   = old('nama', $pelanggan->nama ?? $user->name);
    $nilaiTelp   = old('no_telepon', $pelanggan->no_telepon ?? '');
    $nilaiAlamat = old('alamat', $pelanggan->alamat ?? '');
    $nilaiEmail  = old('email', $user->email);
    $nilaiTgl    = old('tgl_lahir', $tglLahir);

    // kelengkapan profil untuk tampilan awal (dihitung ulang oleh skrip saat mengetik)
    $kelengkapan = collect([$adaFoto, $nilaiNama, $nilaiTgl, $nilaiTelp, $nilaiEmail, $nilaiAlamat])->filter()->count();
    $persen      = (int) round($kelengkapan / 6 * 100);
@endphp

<main class="akun-page">

    {{-- HERO --}}
    <section class="akun-hero">
        <div class="akun-wrap">
            <div class="akun-eyebrow">Akun saya</div>
            <h1>Profil saya</h1>
            <p>Perbarui data diri Anda agar reservasi dan komunikasi dengan salon berjalan lancar.</p>
        </div>
    </section>

    <div class="akun-wrap akun-layout">

        {{-- SIDEBAR --}}
        <aside class="akun-side akun-step" style="--d: 0">
            <div class="akun-user">
                <img class="akun-avatar"
                     id="avatarSamping"
                     data-fallback="{{ $fotoDefault }}"
                     src="{{ $foto }}"
                     alt="">
                <div class="akun-user-text">
                    <div class="akun-user-name">{{ $pelanggan->nama ?? $user->name }}</div>
                    <div class="akun-user-email">{{ $user->email }}</div>
                    @if ($pelanggan)
                        <span class="akun-badge {{ $isMember ? 'is-member' : '' }}">
                            <i class="fas {{ $isMember ? 'fa-crown' : 'fa-user' }}" aria-hidden="true"></i>
                            {{ $isMember ? 'Member' : 'Pelanggan' }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="akun-complete">
                <div class="akun-complete-top">
                    <span>Kelengkapan profil</span>
                    <b id="pctText">{{ $persen }}%</b>
                </div>
                <div class="akun-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $persen }}" id="pctWrap">
                    <span id="pctBar" style="width: {{ $persen }}%"></span>
                </div>
                <p class="akun-complete-hint" id="pctHint" aria-live="polite">
                    {{ $persen === 100 ? 'Profil Anda sudah lengkap.' : 'Lengkapi data agar proses reservasi lebih cepat.' }}
                </p>
            </div>

            <nav class="akun-nav" aria-label="Pengaturan akun">
                <a class="akun-nav-item is-active" href="{{ $urlProfil }}" aria-current="page">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    Profil saya
                </a>
                <a class="akun-nav-item" href="{{ $urlSandi }}">
                    <i class="fas fa-key" aria-hidden="true"></i>
                    Ubah password
                </a>
            </nav>
        </aside>

        {{-- KARTU FORM --}}
        <section class="akun-card akun-profile-card akun-step" style="--d: 1">
            <header class="akun-card-head">
                <h2>Informasi pelanggan</h2>
                <p>Perbarui informasi data diri Anda sesuai kebutuhan.</p>
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
                        <span>Profil belum dapat disimpan. Periksa isian yang ditandai merah.</span>
                    </div>
                @endif

                <form class="akun-form"
                      action="{{ \Illuminate\Support\Facades\Route::has('profile.update') ? route('profile.update', $user->id) : url('profile/' . $user->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="formProfil"
                      novalidate>
                    @csrf

                    {{-- ===== 1. FOTO ===== --}}
                    <div class="akun-section akun-step" style="--d: 2">
                        <div class="akun-section-head">
                            <span class="akun-section-ico"><i class="fas fa-camera" aria-hidden="true"></i></span>
                            <div>
                                <h3>Foto profil</h3>
                                <p>Foto membantu terapis mengenali Anda.</p>
                            </div>
                        </div>

                        <div class="akun-photo-box">
                            <div class="akun-photo-circle">
                                <img id="previewFoto"
                                     src="{{ $foto }}"
                                     data-fallback="{{ $fotoDefault }}"
                                     alt="Foto profil">
                                <label for="foto" class="akun-photo-badge" title="Ganti foto">
                                    <i class="fas fa-pen" aria-hidden="true"></i>
                                    <span class="akun-sr">Ganti foto</span>
                                </label>
                            </div>

                            <div class="akun-photo-side">
                                <input type="file" name="foto" id="foto" class="akun-file" accept=".jpg,.jpeg,.png">
                                <div class="akun-photo-actions" style="margin-top:0">
                                    <label for="foto" class="akun-btn akun-btn-line akun-btn-sm" style="cursor:pointer">
                                        <i class="fas fa-upload" aria-hidden="true"></i>
                                        Pilih foto
                                    </label>
                                    <button type="button" class="akun-link" id="fotoBatal" hidden>Batalkan perubahan foto</button>
                                </div>
                                <p class="akun-photo-hint">JPG, JPEG, atau PNG dengan ukuran maksimal 2 MB. Kosongkan bila tidak ingin mengganti.</p>
                                <p class="akun-error" id="fotoError" role="alert" hidden></p>
                                @error('foto')
                                    <p class="akun-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ===== 2. DATA DIRI ===== --}}
                    <div class="akun-section akun-step" style="--d: 3">
                        <div class="akun-section-head">
                            <span class="akun-section-ico"><i class="fas fa-id-card" aria-hidden="true"></i></span>
                            <div>
                                <h3>Data diri</h3>
                                <p>Nama dan tanggal lahir sesuai identitas Anda.</p>
                            </div>
                        </div>

                        <div class="akun-field">
                            <label for="nama">Nama lengkap<span class="akun-req" aria-hidden="true">*</span></label>
                            <div class="akun-control">
                                <i class="fas fa-user akun-field-icon" aria-hidden="true"></i>
                                <input type="text"
                                       id="nama"
                                       name="nama"
                                       class="akun-input has-icon @error('nama') is-invalid @enderror"
                                       value="{{ $nilaiNama }}"
                                       placeholder="Masukkan nama lengkap"
                                       autocomplete="name"
                                       required>
                            </div>
                            @error('nama')
                                <p class="akun-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="akun-grid-2">
                            <div class="akun-field">
                                <label for="jk">Jenis kelamin</label>
                                <div class="akun-control">
                                    <i class="fas fa-venus akun-field-icon" aria-hidden="true"></i>
                                    <input type="text" id="jk" class="akun-input has-icon is-locked" value="Perempuan" readonly>
                                </div>
                                <p class="akun-hint">Layanan khusus muslimah.</p>
                            </div>

                            <div class="akun-field">
                                <label for="tgl_lahir">Tanggal lahir<span class="akun-req" aria-hidden="true">*</span></label>
                                <div class="akun-control">
                                    <i class="fas fa-calendar-alt akun-field-icon" aria-hidden="true"></i>
                                    <input type="date"
                                           id="tgl_lahir"
                                           name="tgl_lahir"
                                           max="{{ now('Asia/Jakarta')->toDateString() }}"
                                           class="akun-input has-icon @error('tgl_lahir') is-invalid @enderror"
                                           value="{{ $nilaiTgl }}"
                                           required>
                                </div>
                                @error('tgl_lahir')
                                    <p class="akun-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ===== 3. KONTAK ===== --}}
                    <div class="akun-section akun-step" style="--d: 4">
                        <div class="akun-section-head">
                            <span class="akun-section-ico"><i class="fas fa-address-book" aria-hidden="true"></i></span>
                            <div>
                                <h3>Kontak &amp; alamat</h3>
                                <p>Dipakai untuk konfirmasi reservasi.</p>
                            </div>
                        </div>

                        <div class="akun-grid-2">
                            <div class="akun-field">
                                <label for="no_telepon">No. telepon<span class="akun-req" aria-hidden="true">*</span></label>
                                <div class="akun-control">
                                    <i class="fas fa-phone akun-field-icon" aria-hidden="true"></i>
                                    <input type="text"
                                           id="no_telepon"
                                           name="no_telepon"
                                           inputmode="tel"
                                           class="akun-input has-icon @error('no_telepon') is-invalid @enderror"
                                           value="{{ $nilaiTelp }}"
                                           placeholder="08xxxxxxxxxx"
                                           autocomplete="tel"
                                           required>
                                </div>
                                <p class="akun-error" id="telError" role="alert" hidden></p>
                                @error('no_telepon')
                                    <p class="akun-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="akun-field">
                                <label for="email">Email</label>
                                <div class="akun-control">
                                    <i class="fas fa-envelope akun-field-icon" aria-hidden="true"></i>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="akun-input has-icon @error('email') is-invalid @enderror"
                                           value="{{ $nilaiEmail }}"
                                           placeholder="nama@email.com"
                                           autocomplete="email">
                                </div>
                                @error('email')
                                    <p class="akun-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="akun-field">
                            <label for="alamat">Alamat<span class="akun-req" aria-hidden="true">*</span></label>
                            <div class="akun-control">
                                <textarea id="alamat"
                                          name="alamat"
                                          rows="4"
                                          class="akun-input @error('alamat') is-invalid @enderror"
                                          placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota"
                                          autocomplete="street-address"
                                          required>{{ $nilaiAlamat }}</textarea>
                            </div>
                            <div class="akun-field-foot">
                                <div>
                                    @error('alamat')
                                        <p class="akun-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="akun-counter" id="alamatCounter" aria-hidden="true">0 karakter</p>
                            </div>
                        </div>
                    </div>

                    {{-- ===== 4. KEANGGOTAAN (hanya baca) ===== --}}
                    @if ($pelanggan)
                        <div class="akun-section akun-step" style="--d: 5">
                            <div class="akun-section-head">
                                <span class="akun-section-ico"><i class="fas fa-award" aria-hidden="true"></i></span>
                                <div>
                                    <h3>Keanggotaan</h3>
                                    <p>Informasi ini diperbarui otomatis oleh salon.</p>
                                </div>
                            </div>

                            <div class="akun-stats">
                                <div class="akun-stat {{ $isMember ? 'is-member' : '' }}">
                                    <small>Status</small>
                                    <b>{{ $isMember ? 'Member' : 'Bukan member' }}</b>
                                </div>
                                <div class="akun-stat">
                                    <small>Jumlah kedatangan</small>
                                    <b>{{ (int) ($pelanggan->jumlah_kedatangan ?? 0) }} kali</b>
                                </div>
                                <div class="akun-stat">
                                    <small>Terakhir datang</small>
                                    <b>{{ $terakhir }}</b>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ===== TOMBOL ===== --}}
                    <div class="akun-actions akun-savebar akun-step" id="saveBar" style="--d: 6">
                        <span class="akun-savestate" id="saveState" aria-live="polite">Belum ada perubahan</span>

                        <div class="akun-savebar-btns">
                            <a href="/" class="akun-btn akun-btn-line">Batal</a>

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

            var form      = document.getElementById('formProfil');
            var btn       = document.getElementById('btnSimpan');
            var saveBar   = document.getElementById('saveBar');
            var saveState = document.getElementById('saveState');

            /* ---------- Foto: validasi, pratinjau, batalkan ---------- */
            var inputFoto = document.getElementById('foto');
            var preview   = document.getElementById('previewFoto');
            var avatar    = document.getElementById('avatarSamping');
            var fotoBatal = document.getElementById('fotoBatal');
            var fotoError = document.getElementById('fotoError');
            var fotoAsli  = preview.getAttribute('src');
            var adaFoto   = {{ $adaFoto ? 'true' : 'false' }};
            var fotoBaru  = false;
            var MAKS      = 2 * 1024 * 1024;

            function pesanFoto(teks) {
                fotoError.textContent = teks || '';
                fotoError.hidden = !teks;
            }

            function setFoto(src, berubah) {
                preview.src = src;
                if (avatar) avatar.src = src;
                preview.classList.remove('is-changed');
                if (berubah) { void preview.offsetWidth; preview.classList.add('is-changed'); }
                fotoBaru = berubah;
                fotoBatal.hidden = !berubah;
                refresh();
            }

            inputFoto.addEventListener('change', function (e) {
                var file = e.target.files[0];
                pesanFoto('');
                if (!file) return;

                if (['image/jpeg', 'image/png'].indexOf(file.type) === -1) {
                    inputFoto.value = '';
                    pesanFoto('Format foto harus JPG, JPEG, atau PNG.');
                    return;
                }
                if (file.size > MAKS) {
                    inputFoto.value = '';
                    pesanFoto('Ukuran foto melebihi 2 MB. Pilih foto yang lebih kecil.');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (ev) { setFoto(ev.target.result, true); };
                reader.readAsDataURL(file);
            });

            fotoBatal.addEventListener('click', function () {
                inputFoto.value = '';
                pesanFoto('');
                setFoto(fotoAsli, false);
            });

            /* ---------- Hitungan alamat ---------- */
            var alamat  = document.getElementById('alamat');
            var counter = document.getElementById('alamatCounter');

            function hitungAlamat() {
                counter.textContent = alamat.value.length + ' karakter';
            }
            alamat.addEventListener('input', hitungAlamat);
            hitungAlamat();

            /* ---------- Kelengkapan profil ---------- */
            var KOLOM = [
                { id: 'nama',       label: 'nama' },
                { id: 'tgl_lahir',  label: 'tanggal lahir' },
                { id: 'no_telepon', label: 'nomor telepon' },
                { id: 'email',      label: 'email' },
                { id: 'alamat',     label: 'alamat' }
            ];
            var pctText = document.getElementById('pctText');
            var pctBar  = document.getElementById('pctBar');
            var pctWrap = document.getElementById('pctWrap');
            var pctHint = document.getElementById('pctHint');

            function hitungKelengkapan() {
                var terisi = (adaFoto || fotoBaru) ? 1 : 0;
                var kurang = [];
                if (!(adaFoto || fotoBaru)) kurang.push('foto');

                KOLOM.forEach(function (k) {
                    if (document.getElementById(k.id).value.trim()) terisi++;
                    else kurang.push(k.label);
                });

                var persen = Math.round(terisi / (KOLOM.length + 1) * 100);
                pctText.textContent = persen + '%';
                pctBar.style.width = persen + '%';
                pctWrap.setAttribute('aria-valuenow', persen);
                pctHint.textContent = kurang.length
                    ? 'Lengkapi: ' + kurang.join(', ') + '.'
                    : 'Profil Anda sudah lengkap.';
            }

            /* ---------- Deteksi perubahan belum disimpan ---------- */
            function ambilNilai() {
                var data = {};
                form.querySelectorAll('input[name], textarea[name]').forEach(function (el) {
                    if (el.type === 'file' || el.name === '_token' || el.name === '_method') return;
                    data[el.name] = el.value;
                });
                return JSON.stringify(data);
            }
            var nilaiAwal = ambilNilai();
            var kotor = false;
            var kirim = false;

            function refresh() {
                kotor = ambilNilai() !== nilaiAwal || fotoBaru;
                saveBar.classList.toggle('is-dirty', kotor);
                saveState.textContent = kotor ? 'Ada perubahan yang belum disimpan' : 'Belum ada perubahan';
                hitungKelengkapan();
            }

            form.addEventListener('input', function (e) {
                if (e.target.classList.contains('akun-input')) e.target.classList.remove('is-invalid');
                if (e.target.id === 'no_telepon') { telError.hidden = true; }
                refresh();
            });

            window.addEventListener('beforeunload', function (e) {
                if (kotor && !kirim) { e.preventDefault(); e.returnValue = ''; }
            });

            /* ---------- Validasi ringan sebelum kirim (server tetap menjadi acuan) ---------- */
            var telepon  = document.getElementById('no_telepon');
            var telError = document.getElementById('telError');

            form.addEventListener('submit', function (e) {
                var kosong = ['nama', 'tgl_lahir', 'no_telepon', 'alamat']
                    .map(function (id) { return document.getElementById(id); })
                    .filter(function (el) { return !el.value.trim(); });

                if (kosong.length) {
                    e.preventDefault();
                    kosong.forEach(function (el) { el.classList.add('is-invalid'); });
                    kosong[0].focus();
                    return;
                }

                var digit = telepon.value.replace(/\D/g, '');
                if (digit.length < 9 || digit.length > 15) {
                    e.preventDefault();
                    telepon.classList.add('is-invalid');
                    telError.textContent = 'Nomor telepon harus 9 sampai 15 digit.';
                    telError.hidden = false;
                    telepon.focus();
                    return;
                }

                kirim = true;
                btn.classList.add('is-loading');
            });

            refresh();
            saveBar.classList.remove('is-dirty');
            saveState.textContent = 'Belum ada perubahan';
        })();
    </script>
</main>

@endsection