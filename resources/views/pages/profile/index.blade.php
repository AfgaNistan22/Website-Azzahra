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
                    <i class="fas fa-user-circle"></i>
                </div>
                <div>
                    <h1 class="h4 mb-0 ml-2 fw-bold text-white">Detail Profile</h1>
                    <p class="mb-0 ml-2 text-white-50 small">Kelola informasi akun dan foto profil Anda</p>
                </div>
            </div>
        </div>

        <div class="p-4 p-md-5">

            <!-- Form -->
            <form action="{{ url('profile/' . auth()->user()->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">

                @csrf

                {{-- FOTO --}}
                <div class="mb-5 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold label-elite">Foto Profile</label>

                    <div class="col-sm-9">

                        <div class="d-flex align-items-center gap-4 flex-wrap">

                            <!-- Preview dengan overlay upload -->
                            <div class="photo-upload-wrapper">
                                <img 
                                    id="previewImage"
                                    src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('default/user.png') }}"
                                    class="photo-preview"
                                >
                                <label for="photoInput" class="photo-overlay">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input 
                                    id="photoInput"
                                    type="file" 
                                    class="d-none" 
                                    name="photo"
                                    accept="image/*"
                                    onchange="previewPhoto(event)"
                                >
                            </div>

                            <div>
                                <div class="fw-semibold text-elite-dark mb-1 ml-2">Ubah foto profil</div>
                                <small class="text-muted d-block ml-2">Format JPG/JPEG/PNG, max 2MB</small>
                                @error('photo')
                                    <span class="text-danger small d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NAMA --}}
                <div class="mb-4 row">
                    <label class="col-sm-3 col-form-label fw-semibold label-elite">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input 
                            type="text" 
                            name="name" 
                            class="form-control form-control-elite @error('name') is-invalid @enderror"
                            value="{{ old('name', auth()->user()->name) }}"
                        >

                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="mb-5 row">
                    <label class="col-sm-3 col-form-label fw-semibold label-elite">Email / Username</label>
                    <div class="col-sm-9">
                        <div class="input-elite-locked">
                            <input 
                                type="email" 
                                class="form-control form-control-elite"
                                value="{{ auth()->user()->email }}"
                                readonly
                            >
                            <i class="fas fa-lock lock-icon"></i>
                        </div>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end gap-2 pt-4 border-top-elite">
                    <a href="/dashboard" class="btn btn-outline-elite">Kembali</a>
                    <button type="submit" class="btn btn-gradient-elite">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>

/* =========================================================
   DESIGN TOKENS — selaras dengan sidebar
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
    color: #6b3a1f;
    color: #5a3730;
    font-size: 0.9rem;
}

.text-elite-dark {
    color: #5a2a2c;
}

/* =========================================================
   FOTO
========================================================= */

.photo-upload-wrapper {
    position: relative;
    width: 130px;
    height: 130px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(124, 16, 19, 0.18);
    border: 2px solid rgba(217, 172, 79, 0.35);
}

.photo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(224,28,29,0.75), rgba(124,16,19,0.85));
    color: #d9ac4f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    opacity: 0;
    cursor: pointer;
    transition: opacity 0.25s ease;
    margin: 0;
}
.photo-upload-wrapper:hover .photo-overlay {
    opacity: 1;
}

/* =========================================================
   INPUT
========================================================= */

.form-control-elite {
    border-radius: 10px;
    border: 1px solid #ecd9bf;
    padding: 0.65rem 0.9rem;
    background: #fffdf9;
    transition: all 0.2s ease;
}
.form-control-elite:focus {
    border-color: #d9ac4f;
    box-shadow: 0 0 0 0.2rem rgba(217, 172, 79, 0.18);
    background: #fff;
}

.input-elite-locked {
    position: relative;
}
.input-elite-locked .form-control-elite {
    background: #f7f3ec;
    color: #8a7a63;
    padding-right: 2.5rem;
}
.lock-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #c9b48a;
    font-size: 0.8rem;
}

/* =========================================================
   BORDER ATAS TOMBOL
========================================================= */

.border-top-elite {
    border-top: 1px solid rgba(217, 172, 79, 0.25) !important;
}

/* =========================================================
   BUTTON
========================================================= */

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
function previewPhoto(event) {
    const image = document.getElementById('previewImage');
    const file = event.target.files[0];

    if (file) {
        image.src = URL.createObjectURL(file);
    }
}
</script>

@endsection