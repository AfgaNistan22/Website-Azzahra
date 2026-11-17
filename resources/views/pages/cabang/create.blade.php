@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- HEADER --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-code-branch"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Tambah Cabang</h4>
                <p class="text-white-50 mb-0 small ml-2">Tambahkan informasi cabang Azzahra Salon dan Spa Muslimah</p>
            </div>
        </div>

        <a href="{{ url('cabang') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ERROR VALIDATION --}}
    @if($errors->any())
        <div class="alert-box-elite alert-box-danger-elite mb-4">
            <div class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Terdapat kesalahan:
            </div>
            <ul class="mb-0 pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ERROR SESSION --}}
    @if(session('error'))
        <div class="alert-box-elite alert-box-danger-elite mb-4">
            <i class="fas fa-times-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- FORM CARD --}}
    <div class="card-elite mb-4">

        <div class="card-body p-4 p-md-5">

            <form action="{{ url('cabang') }}" method="POST">

                @csrf

                <div class="row">

                    {{-- FORM UTAMA --}}
                    <div class="col-lg-8">

                        {{-- KODE CABANG --}}
                        <div class="mb-4">

                            <label class="form-label-elite">
                                Kode Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-hashtag"></i>
                                </span>

                                <input type="text"
                                       name="kode_cabang"
                                       class="form-control-elite"
                                       value="{{ old('kode_cabang') }}"
                                       placeholder="Contoh: CBG001"
                                       required>
                            </div>

                            @error('kode_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- NAMA CABANG --}}
                        <div class="mb-4">

                            <label class="form-label-elite">
                                Nama Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-store"></i>
                                </span>

                                <input type="text"
                                       name="nama_cabang"
                                       class="form-control-elite"
                                       value="{{ old('nama_cabang') }}"
                                       placeholder="Masukkan nama cabang"
                                       required>
                            </div>

                            @error('nama_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ALAMAT --}}
                        <div class="mb-4">

                            <label class="form-label-elite">
                                Alamat Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite align-items-start pt-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>

                                <textarea name="alamat_cabang"
                                          rows="4"
                                          class="form-control-elite"
                                          placeholder="Masukkan alamat lengkap cabang"
                                          required>{{ old('alamat_cabang') }}</textarea>
                            </div>

                            @error('alamat_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- TELEPON --}}
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label-elite">
                                        No. Telepon <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite">
                                            <i class="fas fa-phone"></i>
                                        </span>

                                        <input type="text"
                                               name="no_telepon"
                                               class="form-control-elite"
                                               value="{{ old('no_telepon') }}"
                                               placeholder="Contoh: 08123456789"
                                               required>
                                    </div>

                                    @error('no_telepon')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror

                                </div>

                            </div>


                            {{-- EMAIL --}}
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="form-label-elite">Email</label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite">
                                            <i class="fas fa-envelope"></i>
                                        </span>

                                        <input type="email"
                                               name="email"
                                               class="form-control-elite"
                                               value="{{ old('email') }}"
                                               placeholder="contoh@email.com">
                                    </div>

                                    @error('email')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- INFORMASI SAMPING --}}
                    <div class="col-lg-4">

                        <div class="side-panel-elite h-100">

                            <div class="text-center mb-4">

                                <div class="side-panel-icon-elite">
                                    <i class="fas fa-store"></i>
                                </div>

                                <h6 class="fw-bold mt-3 mb-1 side-panel-title-elite">Data Cabang</h6>

                                <p class="side-panel-subtitle-elite mb-0">
                                    Lengkapi informasi cabang dengan benar
                                </p>

                            </div>


                            {{-- INFO --}}
                            <div class="info-box-elite mb-3">
                                <div class="fw-bold mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Informasi
                                </div>
                                <small>
                                    Data cabang akan digunakan dalam sistem
                                    untuk mengelola informasi operasional
                                    Azzahra Salon dan Spa Muslimah.
                                </small>
                            </div>


                            {{-- TIPS --}}
                            <div class="tips-box-elite">
                                <div class="fw-bold mb-2">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    Tips
                                </div>
                                <small>
                                    Pastikan kode cabang dibuat unik dan
                                    nomor telepon yang dimasukkan masih aktif.
                                </small>
                            </div>


                            {{-- FIELD WAJIB --}}
                            <div class="mt-3">
                                <small class="side-panel-subtitle-elite">
                                    <span class="text-maroon-elite">*</span>
                                    Field wajib diisi
                                </small>
                            </div>

                        </div>

                    </div>

                </div>


                <hr class="divider-elite my-4">


                {{-- BUTTON --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('cabang') }}" class="btn btn-outline-elite px-4 mr-2">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Cabang
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
.alert-box-danger-elite ul { color: var(--sb-red-dark); }


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


/* =========================================================
   SIDE PANEL
========================================================== */

.side-panel-elite {
    padding: 24px;
    border-radius: 16px;
    background: linear-gradient(180deg, #fff5f5, #fffaf5);
    border: 1px solid rgba(224,28,29,.12);
}

.side-panel-icon-elite {
    width: 74px;
    height: 74px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 6px 16px rgba(124,16,19,.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: var(--sb-red);
}

.side-panel-title-elite {
    color: #3a2a26;
}
.side-panel-subtitle-elite {
    color: #a09a94;
    font-size: 12px;
}


/* =========================================================
   INFO BOX
========================================================== */

.info-box-elite {
    padding: 14px 16px;
    border-radius: 10px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.3);
    color: #8a6a2f;
    font-size: 12.5px;
}
.info-box-elite i {
    color: var(--sb-gold);
}


/* =========================================================
   TIPS BOX
========================================================== */

.tips-box-elite {
    padding: 14px 16px;
    border-radius: 10px;
    background: #fffaf0;
    border: 1px solid rgba(240,173,78,.35);
    color: #8a6a2f;
    font-size: 12.5px;
}
.tips-box-elite i {
    color: #f0ad4e;
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