@extends('layouts.app')

@section('content')

<div class="container">
    <div class="my-4 profile-card-elite">

        <!-- Header -->
        <div class="profile-header-elite">

            <div class="header-ornament-1"></div>
            <div class="header-ornament-2"></div>

            <div class="d-flex align-items-center gap-3 position-relative">
                <div class="header-icon-badge">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 class="h4 mb-0 fw-bold text-white ml-2">Ubah Password</h1>
                    <p class="mb-0 text-white-50 small ml-2">Perbarui kata sandi untuk menjaga keamanan akun Anda</p>
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">

            <!-- Notifikasi SweetAlert -->
            @if (session('success'))
                <script>
                    Swal.fire({
                        title: "Berhasil!",
                        text: "{{ session('success') }}",
                        icon: "success"
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    Swal.fire({
                        title: "Error!",
                        text: "{{ session('error') }}",
                        icon: "error"
                    });
                </script>
            @endif

            <!-- Form Ubah Password -->
            <form action="/change/{{ auth()->user()->id }}" method="post">
                @csrf

                <!-- Password Lama -->
                <div class="mb-4 row">
                    <label for="old_password" class="col-sm-3 col-form-label fw-semibold label-elite">Password Lama</label>
                    <div class="col-sm-9">
                        <div class="input-elite-group">
                            <i class="fas fa-lock field-icon"></i>
                            <input 
                                type="password" 
                                class="form-control form-control-elite @error('old_password') is-invalid @enderror" 
                                name="old_password" 
                                id="old_password">
                            <i class="fas fa-eye toggle-eye" data-target="old_password"></i>
                        </div>

                        @error('old_password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-4 row">
                    <label for="new_password" class="col-sm-3 col-form-label fw-semibold label-elite">Password Baru</label>
                    <div class="col-sm-9">
                        <div class="input-elite-group">
                            <i class="fas fa-key field-icon"></i>
                            <input 
                                type="password" 
                                class="form-control form-control-elite @error('new_password') is-invalid @enderror" 
                                name="new_password" 
                                id="new_password">
                            <i class="fas fa-eye toggle-eye" data-target="new_password"></i>
                        </div>

                        @error('new_password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-5 row">
                    <label for="new_password_confirmation" class="col-sm-3 col-form-label fw-semibold label-elite">Konfirmasi Password</label>
                    <div class="col-sm-9">
                        <div class="input-elite-group">
                            <i class="fas fa-key field-icon"></i>
                            <input 
                                type="password" 
                                class="form-control form-control-elite @error('new_password_confirmation') is-invalid @enderror" 
                                name="new_password_confirmation" 
                                id="new_password_confirmation">
                            <i class="fas fa-eye toggle-eye" data-target="new_password_confirmation"></i>
                        </div>

                        @error('new_password_confirmation')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Tips Keamanan -->
                <div class="security-tip mb-4">
                    <i class="fas fa-info-circle"></i>
                    <span>Gunakan kombinasi huruf besar, kecil, angka, dan simbol minimal 8 karakter.</span>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2 pt-4 border-top-elite">
                    <a href="/dashboard" class="btn btn-outline-elite">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-gradient-elite ml-2">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>

/* =========================================================
   DESIGN TOKENS
========================================================= */

.profile-card-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;

    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(124, 16, 19, 0.12);
    border: 1px solid rgba(217, 172, 79, 0.15);
}

/* =========================================================
   HEADER
========================================================= */

.profile-header-elite {
    position: relative;
    overflow: hidden;
    padding: 1.85rem 2rem;
    background:
        radial-gradient(circle at 15% 0%, rgba(217, 172, 79, .18) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
}

.header-ornament-1 {
    position: absolute;
    width: 220px;
    height: 220px;
    top: -140px;
    right: -60px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 172, 79, .18) 0%, transparent 70%);
    pointer-events: none;
}

.header-ornament-2 {
    position: absolute;
    width: 150px;
    height: 150px;
    bottom: -90px;
    right: 40px;
    border-radius: 50%;
    border: 1px solid rgba(217, 172, 79, .18);
    pointer-events: none;
}

.header-icon-badge {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 1.5rem;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}

/* =========================================================
   LABEL
========================================================= */

.label-elite {
    color: #5a3730;
    font-size: 0.9rem;
}

/* =========================================================
   INPUT GROUP DENGAN ICON
========================================================= */

.input-elite-group {
    position: relative;
}

.input-elite-group .field-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #c9b48a;
    font-size: 0.85rem;
    pointer-events: none;
}

.input-elite-group .toggle-eye {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #b8a284;
    font-size: 0.85rem;
    cursor: pointer;
    transition: color 0.2s ease;
}
.input-elite-group .toggle-eye:hover {
    color: #7c1013;
}

.form-control-elite {
    border-radius: 10px;
    border: 1px solid #ecd9bf;
    padding: 0.65rem 2.6rem;
    background: #fffdf9;
    transition: all 0.2s ease;
}
.form-control-elite:focus {
    border-color: #d9ac4f;
    box-shadow: 0 0 0 0.2rem rgba(217, 172, 79, 0.18);
    background: #fff;
}
.form-control-elite.is-invalid {
    padding-right: 2.6rem;
}

/* =========================================================
   TIPS KEAMANAN
========================================================= */

.security-tip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 10px;
    background: #fdf6ea;
    border: 1px solid rgba(217, 172, 79, 0.3);
    color: #8a6a2f;
    font-size: 0.82rem;
}
.security-tip i {
    color: #d9ac4f;
    font-size: 1rem;
}

/* =========================================================
   BORDER & BUTTON
========================================================= */

.border-top-elite {
    border-top: 1px solid rgba(217, 172, 79, 0.25) !important;
}

.btn-outline-elite {
    border-radius: 10px;
    padding: 0.55rem 1.25rem;
    font-weight: 500;
    border: 1px solid #d9ac4f;
    color: #7c1013;
    background: transparent;
    transition: all 0.25s ease;
}
.btn-outline-elite:hover {
    background: rgba(217, 172, 79, 0.1);
    color: #7c1013;
    border-color: #d9ac4f;
}

.btn-gradient-elite {
    background: linear-gradient(135deg, #e01c1d, #7c1013);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.55rem 1.6rem;
    font-weight: 600;
    letter-spacing: .2px;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(224, 28, 29, 0.3);
}
.btn-gradient-elite:hover {
    filter: brightness(1.06);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(224, 28, 29, 0.4);
}
.btn-gradient-elite:active {
    transform: translateY(0);
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-eye').forEach(function (eye) {
        eye.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);

            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
});
</script>

@endsection