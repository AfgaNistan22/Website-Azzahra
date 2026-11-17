@extends('layouts.app')

@section('title', 'Tambah Perawatan')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-spa"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Tambah Perawatan</h4>
                <p class="text-white-50 mb-0 small ml-2">Tambahkan layanan perawatan baru ke dalam sistem</p>
            </div>
        </div>

        <a href="{{ url('perawatan') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ================= FORM CARD ================= --}}
    <div class="card-elite mb-5">

        <div class="card-body p-4 p-md-5">

            <form action="{{ url('perawatan') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- ================= LEFT ================= --}}
                    <div class="col-lg-8">

                        <div class="section-title-elite mb-4">
                            <h6>Informasi Perawatan</h6>
                            <p>Masukkan informasi layanan perawatan yang tersedia.</p>
                        </div>


                        {{-- NAMA PERAWATAN --}}
                        <div class="mb-4">

                            <label for="nama" class="form-label-elite">
                                Nama Perawatan <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-spa"></i>
                                </span>

                                <input type="text"
                                       name="nama"
                                       id="nama"
                                       class="form-control-elite @error('nama') is-invalid @enderror"
                                       value="{{ old('nama') }}"
                                       placeholder="Contoh: Facial Muslimah"
                                       required>
                            </div>

                            @error('nama')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- KATEGORI PERAWATAN --}}
                        <div class="mb-4">

                            <label for="kategori_id" class="form-label-elite">
                                Kategori Perawatan <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-tags"></i>
                                </span>

                                <select name="kategori_id"
                                        id="kategori_id"
                                        class="form-control-elite @error('kategori_id') is-invalid @enderror"
                                        required>

                                    <option value="">-- Pilih Kategori Perawatan --</option>

                                    @foreach($kategori as $item)
                                        <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @error('kategori_id')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small class="text-muted-elite d-block mt-1">Pilih kategori yang sesuai dengan jenis perawatan.</small>

                        </div>


                        {{-- DURASI & HARGA --}}
                        <div class="row">

                            {{-- DURASI --}}
                            <div class="col-md-6 mb-4">

                                <label for="durasi_display" class="form-label-elite">
                                    Durasi <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-clock"></i>
                                    </span>

                                    <input type="text"
                                           id="durasi_display"
                                           class="form-control-elite @error('durasi') is-invalid @enderror"
                                           value="{{ old('durasi') ? old('durasi') . "'" : '' }}"
                                           placeholder="60'"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           required>

                                    <input type="hidden"
                                           name="durasi"
                                           id="durasi"
                                           value="{{ old('durasi') }}">
                                </div>

                                <div id="durasiText"
                                     class="duration-hint-elite mt-2"
                                     style="{{ old('durasi') ? '' : 'display: none;' }}">
                                    <i class="fas fa-clock mr-1"></i>
                                    Durasi perawatan: {{ old('durasi') }} Menit
                                </div>

                                @error('durasi')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                                <small class="text-muted-elite d-block mt-1">Masukkan durasi dalam menit, contoh: 60'</small>

                            </div>


                            {{-- HARGA --}}
                            <div class="col-md-6 mb-4">

                                <label for="harga_display" class="form-label-elite">
                                    Harga <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>

                                    <input type="text"
                                           id="harga_display"
                                           class="form-control-elite @error('harga') is-invalid @enderror"
                                           value="{{ old('harga') ? 'Rp ' . number_format(old('harga'), 0, ',', '.') : '' }}"
                                           placeholder="Rp 150.000"
                                           inputmode="numeric"
                                           autocomplete="off"
                                           required>

                                    <input type="hidden"
                                           name="harga"
                                           id="harga"
                                           value="{{ old('harga') }}">
                                </div>

                                @error('harga')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                                <small class="text-muted-elite d-block mt-1">Masukkan harga, contoh: Rp 150.000</small>

                            </div>

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
                                          placeholder="Masukkan deskripsi perawatan...">{{ old('deskripsi') }}</textarea>
                            </div>

                            @error('deskripsi')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>


                    {{-- ================= RIGHT ================= --}}
                    <div class="col-lg-4">

                        <div class="section-title-elite mb-4">
                            <h6>Foto Perawatan</h6>
                            <p>Tambahkan foto untuk menampilkan layanan perawatan.</p>
                        </div>


                        {{-- FOTO UPLOAD --}}
                        <div class="text-center">

                            <div id="previewContainer" class="photo-upload-square-elite mx-auto mb-3">

                                <div id="defaultPhoto">
                                    <i class="fas fa-spa"></i>
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
                                    Pastikan nama perawatan, kategori, durasi,
                                    harga, deskripsi, dan foto yang dimasukkan
                                    sudah sesuai sebelum disimpan.
                                </small>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= DIVIDER ================= --}}
                <hr class="divider-elite my-4">


                {{-- ================= BUTTON ================= --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('perawatan') }}" class="btn btn-outline-elite mr-2 px-4">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perawatan
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


/* =========================================================
   DURATION HINT
========================================================== */

.duration-hint-elite {
    color: var(--sb-red);
    font-weight: 700;
    font-size: 12px;
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


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       DURASI
    ========================= */

    const durasiDisplay =
        document.getElementById('durasi_display');

    const durasiInput =
        document.getElementById('durasi');

    const durasiText =
        document.getElementById('durasiText');


    function tampilkanDurasi() {

        let durasi =
            durasiDisplay.value.replace(/[^0-9]/g, '');


        if (durasi) {

            durasiDisplay.value =
                durasi + "'";

            durasiInput.value =
                durasi;

            durasiText.innerHTML =
                '<i class="fas fa-clock mr-1"></i>' +
                'Durasi perawatan: ' +
                durasi +
                ' Menit';

            durasiText.style.display =
                'block';

        } else {

            durasiDisplay.value =
                '';

            durasiInput.value =
                '';

            durasiText.style.display =
                'none';

        }

    }


    durasiDisplay.addEventListener(
        'input',
        tampilkanDurasi
    );


    if (durasiInput.value) {

        durasiDisplay.value =
            durasiInput.value + "'";

    }


    tampilkanDurasi();


    /* =========================
       HARGA
    ========================= */

    const hargaDisplay =
        document.getElementById('harga_display');

    const hargaInput =
        document.getElementById('harga');


    function formatHarga() {

        let harga =
            hargaDisplay.value.replace(/[^0-9]/g, '');


        if (harga) {

            hargaInput.value =
                harga;

            hargaDisplay.value =
                'Rp ' +
                Number(harga).toLocaleString('id-ID');

        } else {

            hargaDisplay.value =
                '';

            hargaInput.value =
                '';

        }

    }


    hargaDisplay.addEventListener(
        'input',
        formatHarga
    );


    if (hargaInput.value) {

        hargaDisplay.value =
            'Rp ' +
            Number(hargaInput.value)
                .toLocaleString('id-ID');

    }

});

</script>

@endpush

@endsection