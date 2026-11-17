@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Tambah Pelanggan</h4>
                <p class="text-white-50 mb-0 small ml-2">Tambahkan data pelanggan baru ke dalam sistem</p>
            </div>
        </div>

        <a href="{{ url('pelanggan') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ================= FORM CARD ================= --}}
    <div class="card-elite">

        <div class="card-body p-4 p-md-5">

            <form action="{{ url('pelanggan') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- ================= LEFT ================= --}}
                    <div class="col-lg-8">

                        <div class="section-title-elite mb-4">
                            <h6>Informasi Pelanggan</h6>
                            <p>Masukkan informasi dasar pelanggan.</p>
                        </div>


                        {{-- NAMA --}}
                        <div class="mb-4">

                            <label for="nama" class="form-label-elite">
                                Nama Pelanggan <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-user"></i>
                                </span>

                                <input type="text"
                                       name="nama"
                                       id="nama"
                                       class="form-control-elite @error('nama') is-invalid @enderror"
                                       value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap pelanggan"
                                       required>
                            </div>

                            @error('nama')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        <div class="row">

                            {{-- JENIS KELAMIN --}}
                            <div class="col-md-6 mb-4">

                                <label class="form-label-elite">Jenis Kelamin</label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-venus"></i>
                                    </span>

                                    <input type="text"
                                           class="form-control-elite bg-locked-elite"
                                           value="Perempuan"
                                           readonly>
                                </div>

                            </div>


                            {{-- TANGGAL LAHIR --}}
                            <div class="col-md-6 mb-4">

                                <label for="tgl_lahir" class="form-label-elite">
                                    Tanggal Lahir <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>

                                    <input type="date"
                                           name="tgl_lahir"
                                           id="tgl_lahir"
                                           class="form-control-elite @error('tgl_lahir') is-invalid @enderror"
                                           value="{{ old('tgl_lahir') }}"
                                           required>
                                </div>

                                @error('tgl_lahir')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>


                        <div class="row">

                            {{-- NO TELEPON --}}
                            <div class="col-md-6 mb-4">

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
                                           class="form-control-elite @error('no_telepon') is-invalid @enderror"
                                           value="{{ old('no_telepon') }}"
                                           placeholder="08xxxxxxxxxx"
                                           required>
                                </div>

                                @error('no_telepon')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- EMAIL --}}
                            <div class="col-md-6 mb-4">

                                <label for="email" class="form-label-elite">Email</label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-envelope"></i>
                                    </span>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control-elite @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="nama@email.com">
                                </div>

                                @error('email')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>


                        {{-- ALAMAT --}}
                        <div class="mb-4">

                            <label for="alamat" class="form-label-elite">
                                Alamat <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite align-items-start pt-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>

                                <textarea name="alamat"
                                          id="alamat"
                                          rows="4"
                                          class="form-control-elite @error('alamat') is-invalid @enderror"
                                          placeholder="Masukkan alamat lengkap pelanggan"
                                          required>{{ old('alamat') }}</textarea>
                            </div>

                            @error('alamat')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ================= DATA MEMBER ================= --}}
                        <div class="section-title-elite mb-4 pt-2">
                            <h6>Informasi Member</h6>
                            <p>Masukkan informasi keanggotaan dan riwayat kedatangan pelanggan.</p>
                        </div>


                        <div class="row">

                            {{-- MEMBER ATAU BUKAN --}}
                            <div class="col-md-4 mb-4">

                                <label for="member" class="form-label-elite">
                                    Member atau Bukan <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-id-card"></i>
                                    </span>

                                    <select name="member"
                                            id="member"
                                            class="form-control-elite @error('member') is-invalid @enderror"
                                            required>

                                        <option value="">Pilih</option>
                                        <option value="Ya" {{ old('member') == 'Ya' ? 'selected' : '' }}>Member</option>
                                        <option value="Tidak" {{ old('member') == 'Tidak' ? 'selected' : '' }}>Bukan Member</option>

                                    </select>
                                </div>

                                @error('member')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- JUMLAH KEDATANGAN --}}
                            <div class="col-md-4 mb-4">

                                <label for="jumlah_kedatangan" class="form-label-elite">
                                    Jumlah Kedatangan <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-history"></i>
                                    </span>

                                    <input type="number"
                                           name="jumlah_kedatangan"
                                           id="jumlah_kedatangan"
                                           class="form-control-elite @error('jumlah_kedatangan') is-invalid @enderror"
                                           value="{{ old('jumlah_kedatangan', 0) }}"
                                           min="0"
                                           required>
                                </div>

                                @error('jumlah_kedatangan')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- TERAKHIR DATANG --}}
                            <div class="col-md-4 mb-4">

                                <label for="terakhir_datang" class="form-label-elite">Terakhir Datang</label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>

                                    <input type="date"
                                           name="terakhir_datang"
                                           id="terakhir_datang"
                                           class="form-control-elite @error('terakhir_datang') is-invalid @enderror"
                                           value="{{ old('terakhir_datang') }}">
                                </div>

                                @error('terakhir_datang')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- ================= RIGHT ================= --}}
                    <div class="col-lg-4">

                        <div class="section-title-elite mb-4">
                            <h6>Foto Pelanggan</h6>
                            <p>Tambahkan foto profil pelanggan.</p>
                        </div>


                        {{-- FOTO UPLOAD --}}
                        <div class="text-center">

                            <div id="previewContainer" class="photo-upload-circle-elite mx-auto mb-3">

                                <div id="defaultPhoto">
                                    <i class="fas fa-user"></i>
                                </div>

                                <img id="previewFoto" src="" style="display:none;">

                                <label for="foto" class="photo-overlay-elite">
                                    <i class="fas fa-camera"></i>
                                </label>

                            </div>

                            <input type="file"
                                   name="foto"
                                   id="foto"
                                   class="d-none"
                                   accept=".jpg,.jpeg,.png">

                            <label for="foto" class="btn btn-outline-elite btn-sm px-4">
                                <i class="fas fa-camera mr-1"></i>
                                Pilih Foto
                            </label>

                            <div class="mt-2">
                                <small class="text-muted-elite">JPG, JPEG, PNG · Maks. 2 MB</small>
                            </div>

                            @error('foto')
                                <div class="text-maroon-elite small mt-2">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- INFO --}}
                        <div class="info-box-elite mt-4">

                            <div class="d-flex">
                                <i class="fas fa-info-circle mr-2 mt-1"></i>
                                <small>
                                    Pastikan data pelanggan yang dimasukkan
                                    sudah benar sebelum disimpan.
                                </small>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================= DIVIDER ================= --}}
                <hr class="divider-elite my-4">


                {{-- ================= BUTTON ================= --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('pelanggan') }}" class="btn btn-outline-elite mr-2 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Pelanggan
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

.bg-locked-elite {
    color: #a09a94;
}


/* =========================================================
   PHOTO UPLOAD
========================================================== */

.photo-upload-circle-elite {
    position: relative;
    width: 170px;
    height: 170px;
    border-radius: 50%;
    background: #fdf8ef;
    border: 2px dashed rgba(217,172,79,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.photo-upload-circle-elite #defaultPhoto i {
    font-size: 60px;
    color: #e3d3ac;
}

.photo-upload-circle-elite img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-overlay-elite {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(224,28,29,0.55), rgba(124,16,19,0.65));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    opacity: 0;
    cursor: pointer;
    transition: opacity .25s ease;
    margin: 0;
}
.photo-upload-circle-elite:hover .photo-overlay-elite {
    opacity: 1;
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
    .form-header-elite .btn-outline-elite {
        width: 100%;
        text-align: center;
    }
}

</style>


{{-- ================= PREVIEW FOTO ================= --}}
@push('scripts')

<script>

document.getElementById('foto').addEventListener('change', function(event) {

    const file = event.target.files[0];

    const preview = document.getElementById('previewFoto');
    const defaultPhoto = document.getElementById('defaultPhoto');

    if (file) {

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;
            preview.style.display = 'block';

            defaultPhoto.style.display = 'none';

        };

        reader.readAsDataURL(file);

    }

});

</script>

@endpush

@endsection