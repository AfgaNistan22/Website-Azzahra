@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 customer-form-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white">Edit Pegawai</h4>
                <p class="text-white-50 mb-0 small">Perbarui informasi data pegawai</p>
            </div>
        </div>

        <a href="{{ url('pegawai') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ================= FORM CARD ================= --}}
    <div class="card-elite">

        <div class="card-body p-4 p-md-5">

            <form action="{{ url('pegawai/'.$data->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- ================= LEFT ================= --}}
                    <div class="col-lg-8">

                        <div class="section-title-elite mb-4">
                            <h6>Informasi Pegawai</h6>
                            <p>Perbarui informasi pegawai sesuai kebutuhan.</p>
                        </div>


                        {{-- ================= NAMA ================= --}}
                        <div class="mb-4">

                            <label for="nama" class="form-label-elite">
                                Nama Pegawai <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-user-tie"></i>
                                </span>

                                <input type="text"
                                       name="nama"
                                       id="nama"
                                       class="form-control-elite @error('nama') is-invalid @enderror"
                                       value="{{ old('nama', $data->nama) }}"
                                       placeholder="Masukkan nama lengkap pegawai"
                                       required>
                            </div>

                            @error('nama')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ================= TELEPON & EMAIL ================= --}}
                        <div class="row">

                            {{-- NO TELEPON --}}
                            <div class="col-md-6 mb-4">

                                <label for="no_telepon" class="form-label-elite">No. Telepon</label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-phone"></i>
                                    </span>

                                    <input type="text"
                                           name="no_telepon"
                                           id="no_telepon"
                                           class="form-control-elite @error('no_telepon') is-invalid @enderror"
                                           value="{{ old('no_telepon', $data->no_telepon) }}"
                                           placeholder="08xxxxxxxxxx">
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
                                           value="{{ old('email', $data->email) }}"
                                           placeholder="nama@email.com">
                                </div>

                                @error('email')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>

                        </div>


                        {{-- ================= STATUS ================= --}}
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

                                    <option value="Aktif" {{ old('status', $data->status) == 'Aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="Tidak Aktif" {{ old('status', $data->status) == 'Tidak Aktif' ? 'selected' : '' }}>
                                        Tidak Aktif
                                    </option>

                                </select>
                            </div>

                            @error('status')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        {{-- ================= ALAMAT ================= --}}
                        <div class="mb-4">

                            <label for="alamat" class="form-label-elite">Alamat</label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite align-items-start pt-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>

                                <textarea name="alamat"
                                          id="alamat"
                                          rows="4"
                                          class="form-control-elite @error('alamat') is-invalid @enderror"
                                          placeholder="Masukkan alamat lengkap pegawai">{{ old('alamat', $data->alamat) }}</textarea>
                            </div>

                            @error('alamat')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>


                    {{-- ================= RIGHT ================= --}}
                    <div class="col-lg-4">

                        <div class="section-title-elite mb-4">
                            <h6>Foto Pegawai</h6>
                            <p>Perbarui foto profil pegawai jika diperlukan.</p>
                        </div>


                        {{-- ================= FOTO ================= --}}
                        <div class="text-center">

                            <div id="previewContainer" class="photo-upload-circle-elite mx-auto mb-3">

                                @if ($data->foto)

                                    <img id="previewFoto"
                                         src="{{ asset('storage/foto_pegawai/'.$data->foto) }}"
                                         alt="Foto {{ $data->nama }}">

                                @else

                                    <div id="defaultPhoto">
                                        <i class="fas fa-user-tie"></i>
                                    </div>

                                    <img id="previewFoto" src="" alt="Preview Foto" style="display:none;">

                                @endif

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
                                Ganti Foto
                            </label>

                            <div class="mt-2">
                                <small class="text-muted-elite">JPG, JPEG, PNG · Maks. 2 MB</small>
                            </div>

                            @error('foto')
                                <div class="text-maroon-elite small mt-2">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- ================= INFO ================= --}}
                        <div class="info-box-elite mt-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle mr-2 mt-1"></i>
                                <small>
                                    Kosongkan pilihan foto jika tidak ingin
                                    mengganti foto pegawai.
                                </small>
                            </div>
                        </div>

                    </div>

                </div>


                {{-- ================= DIVIDER ================= --}}
                <hr class="divider-elite my-4">


                {{-- ================= BUTTON ================= --}}
                <div class="d-flex justify-content-end">

                    <a href="{{ url('pegawai') }}" class="btn btn-outline-elite mr-2 px-4">
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
    object-position: center top;
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

document.addEventListener('DOMContentLoaded', function () {

    const fotoInput = document.getElementById('foto');

    const preview = document.getElementById('previewFoto');

    const defaultPhoto = document.getElementById('defaultPhoto');


    if (fotoInput) {

        fotoInput.addEventListener('change', function(event) {

            const file = event.target.files[0];


            if (!file) {

                return;

            }


            // ==========================
            // VALIDASI FORMAT
            // ==========================

            const allowedTypes = [
                'image/jpeg',
                'image/png'
            ];


            if (!allowedTypes.includes(file.type)) {

                alert(
                    'Foto harus berformat JPG, JPEG, atau PNG.'
                );

                fotoInput.value = '';

                return;

            }


            // ==========================
            // VALIDASI UKURAN
            // ==========================

            if (file.size > 2 * 1024 * 1024) {

                alert(
                    'Ukuran foto maksimal 2 MB.'
                );

                fotoInput.value = '';

                return;

            }


            // ==========================
            // PREVIEW FOTO BARU
            // ==========================

            const reader = new FileReader();


            reader.onload = function(e) {

                if (preview) {

                    preview.src = e.target.result;

                    preview.style.display = 'block';

                }


                if (defaultPhoto) {

                    defaultPhoto.style.display = 'none';

                }

            };


            reader.readAsDataURL(file);

        });

    }

});

</script>

@endpush

@endsection