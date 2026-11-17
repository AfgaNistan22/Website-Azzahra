@extends('layouts.app')

@section('title', 'Tambah Kategori Perawatan')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- HEADER --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Tambah Kategori Perawatan</h4>
                <p class="text-white-50 mb-0 small ml-2">Tambahkan kategori baru untuk mengelompokkan perawatan</p>
            </div>
        </div>

        <a href="{{ route('kategori.index') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert-box-elite alert-box-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif


    {{-- FORM CARD --}}
    <div class="card-elite mb-4">

        <div class="card-body p-4 p-md-5">

            <div class="section-title-elite mb-4">
                <h6>Form Kategori</h6>
                <p>Lengkapi informasi kategori perawatan baru.</p>
            </div>

            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                {{-- NAMA --}}
                <div class="mb-4">

                    <label for="nama" class="form-label-elite">
                        Nama Kategori <span class="text-maroon-elite">*</span>
                    </label>

                    <div class="input-group-elite">
                        <span class="input-icon-box-elite">
                            <i class="fas fa-tag"></i>
                        </span>

                        <input type="text"
                               name="nama"
                               id="nama"
                               class="form-control-elite @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}"
                               maxlength="100"
                               required
                               placeholder="Contoh: Facial, Body Care, Hair Care">
                    </div>

                    @error('nama')
                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                    @enderror

                </div>


                {{-- DESKRIPSI --}}
                <div class="mb-4">

                    <label for="deskripsi" class="form-label-elite">Deskripsi</label>

                    <div class="input-group-elite">
                        <span class="input-icon-box-elite align-items-start pt-3">
                            <i class="fas fa-align-left"></i>
                        </span>

                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="5"
                                  class="form-control-elite @error('deskripsi') is-invalid @enderror"
                                  placeholder="Jelaskan kategori perawatan ini...">{{ old('deskripsi') }}</textarea>
                    </div>

                    @error('deskripsi')
                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                    @enderror

                </div>


                {{-- STATUS --}}
                <div class="mb-4">

                    <label for="status" class="form-label-elite">
                        Status <span class="text-maroon-elite">*</span>
                    </label>

                    <div class="input-group-elite">
                        <span class="input-icon-box-elite">
                            <i class="fas fa-toggle-on"></i>
                        </span>

                        <select name="status"
                                id="status"
                                class="form-control-elite @error('status') is-invalid @enderror"
                                required>
                            <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    @error('status')
                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                    @enderror

                </div>


                {{-- BUTTON --}}
                <div class="d-flex justify-content-end pt-4 divider-elite">
                    <a href="{{ route('kategori.index') }}" class="btn btn-outline-elite mr-2 px-4">Batal</a>
                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Kategori
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.customer-form-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.text-maroon-elite { color: var(--sb-red) !important; }


/* =========================================================
   HEADER
========================================================== */

.form-header-elite {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 28px;
    border-radius: 18px;
    background:
        radial-gradient(circle at 12% 0%, rgba(217, 172, 79, .18) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
    box-shadow: 0 12px 32px rgba(124, 16, 19, 0.18);
}

.header-ornament-1 {
    position: absolute;
    width: 220px; height: 220px;
    top: -140px; right: -50px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 172, 79, .16) 0%, transparent 70%);
    pointer-events: none;
}

.header-ornament-2 {
    position: absolute;
    width: 150px; height: 150px;
    bottom: -90px; right: 80px;
    border-radius: 50%;
    border: 1px solid rgba(217, 172, 79, .18);
    pointer-events: none;
}

.header-icon-elite {
    width: 50px; height: 50px;
    border-radius: 14px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(4px);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}


/* =========================================================
   ERROR ALERT
========================================================== */

.alert-box-elite {
    border-radius: 12px;
    padding: 14px 18px;
    border: 1px solid transparent;
}
.alert-box-danger-elite {
    background: #fff0f0;
    border-color: rgba(224,28,29,.2);
    color: var(--sb-red-dark);
}


/* =========================================================
   CARD
========================================================== */

.card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 18px;
    box-shadow: 0 6px 26px rgba(124,16,19,.06);
    overflow: hidden;
}


/* =========================================================
   SECTION TITLE
========================================================== */

.section-title-elite h6 {
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 3px;
    position: relative;
    padding-left: 12px;
}
.section-title-elite h6::before {
    content: "";
    position: absolute;
    left: 0; top: 2px;
    width: 4px; height: 16px;
    border-radius: 3px;
    background: linear-gradient(180deg, var(--sb-red), var(--sb-gold));
}
.section-title-elite p {
    color: #a09a94;
    font-size: 12.5px;
    margin: 0 0 0 12px;
}


/* =========================================================
   LABEL
========================================================== */

.form-label-elite {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #5a4a44;
    margin-bottom: 6px;
}


/* =========================================================
   INPUT GROUP
========================================================== */

.input-group-elite {
    display: flex;
    align-items: stretch;
    border: 1px solid #ecd9bf;
    border-radius: 10px;
    overflow: hidden;
    background: #fffdf9;
    transition: all .2s ease;
}
.input-group-elite:focus-within {
    border-color: var(--sb-gold);
    box-shadow: 0 0 0 3px rgba(217,172,79,.18);
    background: #fff;
}

.input-icon-box-elite {
    width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 13px;
    flex-shrink: 0;
}

.form-control-elite {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    padding: 0.65rem 0.85rem;
    font-size: 13.5px;
    color: #3a2a26;
    min-width: 0;
}
.form-control-elite::placeholder { color: #c9b9a8; }

.form-control-elite.is-invalid {
    background: rgba(224,28,29,.04);
}


/* =========================================================
   DIVIDER
========================================================== */

.divider-elite {
    border-top: 1px solid rgba(217,172,79,.2);
}


/* =========================================================
   BUTTONS
========================================================== */

.btn-outline-elite {
    border-radius: 10px;
    font-weight: 500;
    border: 1px solid var(--sb-gold);
    color: var(--sb-red-dark);
    background: transparent;
    transition: all .25s ease;
}
.btn-outline-elite:hover {
    background: rgba(217,172,79,.1);
    color: var(--sb-red-dark);
    border-color: var(--sb-gold);
}

.btn-outline-elite-onheader {
    border-radius: 10px;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,.5);
    color: #ffffff;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(2px);
    transition: all .25s ease;
    padding: .5rem 1rem;
}
.btn-outline-elite-onheader:hover {
    background: rgba(255,255,255,.2);
    color: #ffffff;
    border-color: #fff;
}

.btn-gradient-elite {
    background: linear-gradient(135deg, #e01c1d, #7c1013);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: .2px;
    transition: all .25s ease;
    box-shadow: 0 6px 18px rgba(224,28,29,.3);
}
.btn-gradient-elite:hover {
    filter: brightness(1.06);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(224,28,29,.4);
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 767px) {
    .form-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }
    .form-header-elite .btn-outline-elite-onheader {
        width: 100%;
        text-align: center;
    }
}

</style>

@endsection