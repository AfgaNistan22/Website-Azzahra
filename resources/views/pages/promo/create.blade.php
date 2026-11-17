@extends('layouts.app')

@section('title', 'Tambah Promo')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Tambah Promo</h4>
                <p class="text-white-50 mb-0 small ml-2">Tambahkan promo baru ke dalam sistem</p>
            </div>
        </div>

        <a href="{{ url('promo') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ================= FORM CARD ================= --}}
    <div class="card-elite mb-5">

        <div class="card-body p-4 p-md-5">

            {{-- ERROR VALIDASI --}}
            @if($errors->any())
                <div class="alert-elite alert-danger-elite mb-4">
                    <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                    <div>
                        <strong>Periksa kembali data yang dimasukkan</strong>
                        <ul class="mb-0 mt-1 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert-elite alert-success-elite mb-4">
                    <i class="fas fa-check-circle mr-2 mt-1"></i>
                    <div>
                        <strong>Berhasil</strong>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            {{-- ERROR SESSION --}}
            @if(session('error'))
                <div class="alert-elite alert-danger-elite mb-4">
                    <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                    <div>
                        <strong>Terjadi kesalahan</strong>
                        <div>{{ session('error') }}</div>
                    </div>
                </div>
            @endif


            <form action="{{ route('promo.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- ================= LEFT ================= --}}
                    <div class="col-lg-8">

                        <div class="section-title-elite mb-4">
                            <h6>Informasi Promo</h6>
                            <p>Masukkan informasi promo yang akan berlaku.</p>
                        </div>


                        {{-- NAMA PROMO --}}
                        <div class="mb-4">

                            <label for="nama" class="form-label-elite">
                                Nama Promo <span class="text-maroon-elite">*</span>
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
                                       placeholder="Contoh: Promo Member Baru"
                                       required>
                            </div>

                            @error('nama')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- PERAWATAN --}}
                        <div class="mb-4">

                            <label for="perawatan_id" class="form-label-elite">
                                Berlaku Untuk Perawatan
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-spa"></i>
                                </span>

                                <select name="perawatan_id"
                                        id="perawatan_id"
                                        class="form-control-elite @error('perawatan_id') is-invalid @enderror">

                                    {{-- value kosong = berlaku untuk semua perawatan --}}
                                    <option value="" {{ old('perawatan_id') ? '' : 'selected' }}>
                                        Semua Perawatan
                                    </option>

                                    @foreach($perawatan as $item)
                                        <option value="{{ $item->id }}" {{ old('perawatan_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @error('perawatan_id')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small class="text-muted-elite d-block mt-1">
                                Pilih "Semua Perawatan" agar promo berlaku untuk seluruh perawatan,
                                atau pilih satu perawatan tertentu.
                            </small>

                        </div>

                        {{-- BERLAKU UNTUK PELANGGAN --}}
                        <div class="mb-4">

                            <label for="khusus_member" class="form-label-elite">
                                Berlaku Untuk Pelanggan <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-crown"></i>
                                </span>

                                <select name="khusus_member"
                                        id="khusus_member"
                                        class="form-control-elite @error('khusus_member') is-invalid @enderror"
                                        required>

                                    <option value="0" {{ old('khusus_member', '0') == '0' ? 'selected' : '' }}>
                                        Semua Pelanggan
                                    </option>

                                    <option value="1" {{ old('khusus_member') == '1' ? 'selected' : '' }}>
                                        Khusus Member
                                    </option>

                                </select>
                            </div>

                            @error('khusus_member')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small class="text-muted-elite d-block mt-1">
                                Pilih "Khusus Member" agar promo hanya muncul untuk pelanggan berstatus member.
                            </small>

                        </div>


                        {{-- JENIS & NILAI --}}
                        <div class="row">

                            {{-- JENIS PROMO --}}
                            <div class="col-md-6 mb-4">

                                <label for="jenis" class="form-label-elite">
                                    Jenis Promo <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-percent"></i>
                                    </span>

                                    <select name="jenis"
                                            id="jenis"
                                            class="form-control-elite @error('jenis') is-invalid @enderror"
                                            required>

                                        <option value="">-- Pilih Jenis Promo --</option>

                                        <option value="Persentase" {{ old('jenis') === 'Persentase' ? 'selected' : '' }}>
                                            Persentase
                                        </option>

                                        <option value="Nominal" {{ old('jenis') === 'Nominal' ? 'selected' : '' }}>
                                            Nominal
                                        </option>

                                    </select>
                                </div>

                                @error('jenis')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- NILAI PROMO --}}
                            <div class="col-md-6 mb-4">

                                <label for="nilai_display" class="form-label-elite">
                                    Nilai Promo <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite input-symbol-elite" id="simbolNilai">%</span>

                                    <input type="text"
                                           id="nilai_display"
                                           class="form-control-elite @error('nilai') is-invalid @enderror"
                                           value="{{ old('nilai') }}"
                                           placeholder="Contoh: 20"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           required>

                                    <input type="hidden"
                                           name="nilai"
                                           id="nilai"
                                           value="{{ old('nilai') }}">
                                </div>

                                @error('nilai')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                                <small id="nilaiKeterangan" class="text-muted-elite d-block mt-1">
                                    Pilih jenis promo terlebih dahulu.
                                </small>

                            </div>

                        </div>


                        {{-- TANGGAL --}}
                        <div class="row">

                            {{-- TANGGAL MULAI --}}
                            <div class="col-md-6 mb-4">

                                <label for="tanggal_mulai" class="form-label-elite">
                                    Tanggal Mulai <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>

                                    <input type="date"
                                           name="tanggal_mulai"
                                           id="tanggal_mulai"
                                           class="form-control-elite @error('tanggal_mulai') is-invalid @enderror"
                                           value="{{ old('tanggal_mulai') }}"
                                           required>
                                </div>

                                @error('tanggal_mulai')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- TANGGAL SELESAI --}}
                            <div class="col-md-6 mb-4">

                                <label for="tanggal_selesai" class="form-label-elite">
                                    Tanggal Selesai <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>

                                    <input type="date"
                                           name="tanggal_selesai"
                                           id="tanggal_selesai"
                                           class="form-control-elite @error('tanggal_selesai') is-invalid @enderror"
                                           value="{{ old('tanggal_selesai') }}"
                                           min="{{ old('tanggal_mulai') }}"
                                           required>
                                </div>

                                @error('tanggal_selesai')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>

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

                                    <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="Tidak Aktif" {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}>
                                        Tidak Aktif
                                    </option>

                                </select>
                            </div>

                            @error('status')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small id="statusKeterangan" class="text-muted-elite d-block mt-1">
                                Promo otomatis menjadi Tidak Aktif setelah melewati tanggal selesai.
                            </small>

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
                                          rows="6"
                                          class="form-control-elite @error('deskripsi') is-invalid @enderror"
                                          placeholder="Masukkan deskripsi promo...">{{ old('deskripsi') }}</textarea>
                            </div>

                            @error('deskripsi')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>


                    {{-- ================= RIGHT ================= --}}
                    <div class="col-lg-4">

                        <div class="section-title-elite mb-4">
                            <h6>Foto Promo</h6>
                            <p>Tambahkan foto untuk menampilkan promo.</p>
                        </div>


                        {{-- FOTO UPLOAD --}}
                        <div class="text-center">

                            <div id="previewContainer" class="photo-upload-square-elite mx-auto mb-3">

                                <div id="defaultPhoto">
                                    <i class="fas fa-tags"></i>
                                    <div class="default-photo-text-elite">Belum ada foto</div>
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
                                   accept=".jpg,.jpeg,.png,.webp">

                            <label for="foto" class="btn btn-outline-elite btn-sm px-4">
                                <i class="fas fa-camera mr-1"></i>
                                Pilih Foto
                            </label>

                            <div class="mt-2">
                                <small class="text-muted-elite">JPG, JPEG, PNG, WEBP · Maks. 2 MB</small>
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
                                    Pastikan nama promo, perawatan, jenis dan nilai promo,
                                    periode, status, deskripsi, dan foto yang dimasukkan
                                    sudah sesuai sebelum disimpan.
                                </small>
                            </div>
                        </div>

                        {{-- INFO OTOMATIS --}}
                        <div class="info-box-elite mt-3">
                            <div class="d-flex">
                                <i class="fas fa-clock mr-2 mt-1"></i>
                                <small>
                                    Setelah melewati tanggal selesai, status promo akan
                                    berubah otomatis menjadi <strong>Tidak Aktif</strong>.
                                </small>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= DIVIDER ================= --}}
                <hr class="divider-elite my-4">


                {{-- ================= BUTTON ================= --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('promo') }}" class="btn btn-outline-elite mr-2 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Promo
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
   ALERT
========================================================== */

.alert-elite {
    display: flex;
    align-items: flex-start;
    padding: 14px 16px;
    border-radius: 10px;
    font-size: 12.5px;
    color: #5a4a44;
}
.alert-elite strong { color: #3a2a26; font-size: 13px; }
.alert-danger-elite {
    background: #fff0f0;
    border: 1px solid rgba(224,28,29,.2);
}
.alert-danger-elite > i { color: var(--sb-red); }
.alert-success-elite {
    background: #eefaf3;
    border: 1px solid rgba(28,200,138,.25);
}
.alert-success-elite > i { color: #1cc88a; }


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

/* simbol % / Rp pada nilai promo */
.input-symbol-elite {
    font-weight: 700;
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
   PHOTO UPLOAD (SQUARE)
========================================================== */

.photo-upload-square-elite {
    position: relative;
    width: 220px;
    height: 220px;
    border-radius: 16px;
    background: #fdf8ef;
    border: 2px dashed rgba(217,172,79,.4);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.photo-upload-square-elite #defaultPhoto {
    text-align: center;
}

.photo-upload-square-elite #defaultPhoto i {
    font-size: 60px;
    color: #e3d3ac;
}

.default-photo-text-elite {
    color: #c9b9a8;
    font-size: 12.5px;
    margin-top: 8px;
}

.photo-upload-square-elite img {
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
    font-size: 1.5rem;
    opacity: 0;
    cursor: pointer;
    transition: opacity .25s ease;
    margin: 0;
}
.photo-upload-square-elite:hover .photo-overlay-elite {
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


@push('scripts')

{{-- ================= PREVIEW FOTO ================= --}}
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


{{-- ================= NILAI PROMO, TANGGAL & STATUS ================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       NILAI PROMO
    ========================= */

    const jenis =
        document.getElementById('jenis');

    const nilaiDisplay =
        document.getElementById('nilai_display');

    const nilaiInput =
        document.getElementById('nilai');

    const simbolNilai =
        document.getElementById('simbolNilai');

    const nilaiKeterangan =
        document.getElementById('nilaiKeterangan');


    function formatNilai() {

        let nilai =
            nilaiDisplay.value.replace(/[^0-9]/g, '');

        const jenisPromo =
            jenis.value;


        if (!nilai || !jenisPromo) {

            nilaiDisplay.value = '';

            nilaiInput.value = '';

            return;

        }


        /* PERSENTASE */

        if (jenisPromo === 'Persentase') {

            nilai = parseInt(nilai);

            if (nilai > 100) {

                nilai = 100;

            }

            nilaiInput.value =
                nilai;

            nilaiDisplay.value =
                nilai + '%';

        }


        /* NOMINAL */

        else if (jenisPromo === 'Nominal') {

            nilaiInput.value =
                nilai;

            nilaiDisplay.value =
                Number(nilai).toLocaleString('id-ID');

        }

    }


    function updateNilaiPromo() {

        const jenisPromo =
            jenis.value;


        if (jenisPromo === 'Persentase') {

            simbolNilai.innerText = '%';

            nilaiDisplay.placeholder = 'Contoh: 20';

            nilaiKeterangan.innerText =
                'Masukkan nilai persentase, maksimal 100%.';

        } else if (jenisPromo === 'Nominal') {

            simbolNilai.innerText = 'Rp';

            nilaiDisplay.placeholder = 'Contoh: 50.000';

            nilaiKeterangan.innerText =
                'Masukkan nilai nominal dalam Rupiah.';

        } else {

            simbolNilai.innerText = '%';

            nilaiDisplay.placeholder = 'Contoh: 20';

            nilaiKeterangan.innerText =
                'Pilih jenis promo terlebih dahulu.';

        }


        formatNilai();

    }


    nilaiDisplay.addEventListener(
        'input',
        formatNilai
    );


    jenis.addEventListener(
        'change',
        function () {

            /* simpan nilai lama agar tidak hilang saat ganti jenis */
            nilaiDisplay.value =
                nilaiInput.value;

            updateNilaiPromo();

        }
    );


    /* tampilkan nilai lama (old input) dengan format yang benar */
    if (nilaiInput.value) {

        nilaiDisplay.value =
            nilaiInput.value;

    }

    updateNilaiPromo();


    /* =========================
       TANGGAL & STATUS
    ========================= */

    const tanggalMulai =
        document.getElementById('tanggal_mulai');

    const tanggalSelesai =
        document.getElementById('tanggal_selesai');

    const status =
        document.getElementById('status');

    const statusKeterangan =
        document.getElementById('statusKeterangan');

    const teksStatusDefault =
        statusKeterangan.innerText;


    /* tanggal hari ini (zona waktu lokal) format YYYY-MM-DD */
    function hariIni() {

        const d = new Date();

        return d.getFullYear() + '-' +
            String(d.getMonth() + 1).padStart(2, '0') + '-' +
            String(d.getDate()).padStart(2, '0');

    }


    /* jika tanggal selesai sudah lewat -> status otomatis Tidak Aktif */
    function cekKedaluwarsa() {

        if (tanggalSelesai.value &&
            tanggalSelesai.value < hariIni()) {

            status.value = 'Tidak Aktif';

            statusKeterangan.innerText =
                'Tanggal selesai sudah lewat, status otomatis Tidak Aktif.';

            statusKeterangan.classList.add('text-maroon-elite');

        } else {

            statusKeterangan.innerText =
                teksStatusDefault;

            statusKeterangan.classList.remove('text-maroon-elite');

        }

    }


    tanggalMulai.addEventListener('change', function () {

        tanggalSelesai.min =
            tanggalMulai.value;

        if (tanggalSelesai.value &&
            tanggalSelesai.value < tanggalMulai.value) {

            tanggalSelesai.value = '';

        }

        cekKedaluwarsa();

    });


    tanggalSelesai.addEventListener('change', cekKedaluwarsa);

    status.addEventListener('change', cekKedaluwarsa);

    cekKedaluwarsa();

});

</script>

@endpush

@endsection