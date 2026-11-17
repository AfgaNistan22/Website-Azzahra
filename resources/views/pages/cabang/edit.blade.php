@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- HEADER --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Edit Cabang</h4>
                <p class="text-white-50 mb-0 small ml-2">Perbarui informasi cabang Azzahra Salon dan Spa Muslimah</p>
            </div>
        </div>

        <a href="{{ url('cabang') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ERROR --}}
    @if($errors->any())
        <div class="alert-box-elite alert-box-danger-elite mb-4">
            <div class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Terdapat kesalahan:
            </div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- FORM --}}
    <div class="card-elite mb-4">

        <div class="card-body p-4 p-md-5">

            <form action="{{ url('cabang/update', $data->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- FORM --}}
                    <div class="col-lg-8">

                        {{-- KODE CABANG --}}
                        <div class="mb-4">

                            <label for="kode_cabang" class="form-label-elite">
                                Kode Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-hashtag"></i>
                                </span>

                                <input type="number"
                                       name="kode_cabang"
                                       id="kode_cabang"
                                       value="{{ old('kode_cabang', $data->kode_cabang) }}"
                                       class="form-control-elite @error('kode_cabang') is-invalid @enderror"
                                       min="1"
                                       max="999"
                                       required>
                            </div>

                            @error('kode_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small class="text-muted-elite d-block mt-1">Masukkan kode unik untuk cabang.</small>

                        </div>


                        {{-- NAMA CABANG --}}
                        <div class="mb-4">

                            <label for="nama_cabang" class="form-label-elite">
                                Nama Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-building"></i>
                                </span>

                                <input type="text"
                                       name="nama_cabang"
                                       id="nama_cabang"
                                       value="{{ old('nama_cabang', $data->nama_cabang) }}"
                                       class="form-control-elite @error('nama_cabang') is-invalid @enderror"
                                       placeholder="Contoh: Azzahra Cabang Sleman"
                                       required>
                            </div>

                            @error('nama_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ALAMAT --}}
                        <div class="mb-4">

                            <label for="alamat_cabang" class="form-label-elite">
                                Alamat Cabang <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite align-items-start pt-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>

                                <textarea name="alamat_cabang"
                                          id="alamat_cabang"
                                          rows="4"
                                          class="form-control-elite @error('alamat_cabang') is-invalid @enderror"
                                          placeholder="Masukkan alamat lengkap cabang"
                                          required>{{ old('alamat_cabang', $data->alamat_cabang) }}</textarea>
                            </div>

                            @error('alamat_cabang')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- TELEPON --}}
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label for="no_telepon" class="form-label-elite">
                                        No. Telepon <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite">
                                            <i class="fas fa-phone"></i>
                                        </span>

                                        <input type="text"
                                               name="no_telepon"
                                               id="no_telepon"
                                               value="{{ old('no_telepon', $data->no_telepon) }}"
                                               class="form-control-elite @error('no_telepon') is-invalid @enderror"
                                               placeholder="08xxxxxxxxxx"
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

                                    <label for="email" class="form-label-elite">Email</label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite">
                                            <i class="fas fa-envelope"></i>
                                        </span>

                                        <input type="email"
                                               name="email"
                                               id="email"
                                               value="{{ old('email', $data->email) }}"
                                               class="form-control-elite @error('email') is-invalid @enderror"
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
                                    <i class="fas fa-building"></i>
                                </div>

                                <h6 class="fw-bold mt-3 mb-1 side-panel-title-elite">Data Cabang</h6>

                                <p class="side-panel-subtitle-elite mb-0">
                                    Perbarui informasi cabang dengan data yang benar
                                </p>

                            </div>


                            <div class="info-box-elite mb-3">
                                <div class="fw-bold mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Informasi
                                </div>
                                <small>
                                    Pastikan kode cabang dan nama cabang sudah sesuai
                                    sebelum menyimpan perubahan.
                                </small>
                            </div>


                            <div class="tips-box-elite">
                                <div class="fw-bold mb-2">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Tips
                                </div>
                                <small>
                                    Gunakan nomor telepon dan email yang masih aktif
                                    agar pelanggan dapat menghubungi cabang.
                                </small>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- DIVIDER --}}
                <hr class="divider-elite my-4">


                {{-- BUTTON --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('cabang') }}" class="btn btn-outline-elite px-4 mr-2">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
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
.text-muted-elite { color: #a09a94; }


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

.form-control-elite.is-invalid {
    background: rgba(224,28,29,.04);
}


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
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 6px 16px rgba(124,16,19,.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
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