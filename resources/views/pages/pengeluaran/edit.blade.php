@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')

<div class="container-fluid px-4 keluar-form-elite">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="modern-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">

            <div class="header-icon-elite">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <h1 class="page-title-elite">Edit Pengeluaran Manual</h1>
                <p class="page-subtitle-elite">Pengeluaran ini dicatat secara manual oleh admin</p>
            </div>

        </div>


        <div class="header-actions position-relative">

            <a href="{{ route('pengeluaran.index') }}"
               class="btn btn-outline-elite btn-modern-elite">

                <i class="fas fa-arrow-left mr-2"></i>
                Kembali

            </a>

        </div>

    </div>


    @if($errors->any())
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Periksa kembali data:</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif


    {{-- =========================================================
        CARD FORM
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-body-elite">

            <form method="POST" action="{{ route('pengeluaran.update', $data->id) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ========================= --}}
                    {{-- KOLOM KIRI --}}
                    {{-- ========================= --}}
                    <div class="col-lg-8">

                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Informasi Pengeluaran</div>
                                <small class="section-label-subtitle-elite">Lengkapi data pengeluaran manual</small>
                            </div>
                        </div>


                        <div class="row">

                            {{-- TANGGAL --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="tanggal" class="modern-label-elite">
                                        Tanggal <span class="text-danger">*</span>
                                    </label>

                                    <div class="modern-input-group-elite">

                                        <i class="fas fa-calendar-alt input-icon-elite"></i>

                                        <input type="date"
                                               name="tanggal"
                                               id="tanggal"
                                               class="form-control modern-input-elite @error('tanggal') is-invalid @enderror"
                                               value="{{ old('tanggal', $data->tanggal?->format('Y-m-d') ?: now()->format('Y-m-d')) }}"
                                               required>

                                    </div>

                                    @error('tanggal')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- KATEGORI --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="kategori" class="modern-label-elite">
                                        Kategori <span class="text-danger">*</span>
                                    </label>

                                    <div class="modern-input-group-elite">

                                        <i class="fas fa-tags input-icon-elite"></i>

                                        <input type="text"
                                               name="kategori"
                                               id="kategori"
                                               class="form-control modern-input-elite @error('kategori') is-invalid @enderror"
                                               value="{{ old('kategori', $data->kategori ?? '') }}"
                                               placeholder="Contoh: Operasional, Pembelian Produk"
                                               required>

                                    </div>

                                    @error('kategori')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- SUMBER --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="sumber" class="modern-label-elite">Sumber</label>

                                    <div class="modern-input-group-elite">

                                        <i class="fas fa-project-diagram input-icon-elite"></i>

                                        <input type="text"
                                               name="sumber"
                                               id="sumber"
                                               class="form-control modern-input-elite @error('sumber') is-invalid @enderror"
                                               value="{{ old('sumber', $data->sumber ?? '') }}"
                                               placeholder="Contoh: Pembelian perlengkapan salon">

                                    </div>

                                    @error('sumber')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- NOMINAL --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="nominal_display" class="modern-label-elite">
                                        Nominal <span class="text-danger">*</span>
                                    </label>

                                    <div class="modern-value-group-elite">

                                        <span class="value-symbol-elite value-symbol-warning-elite">Rp</span>

                                        <input type="text"
                                               id="nominal_display"
                                               class="form-control modern-input-elite value-input-elite @error('nominal') is-invalid @enderror"
                                               value="{{ (int) old('nominal', $data->nominal ?? 0) > 0 ? number_format((int) old('nominal', $data->nominal ?? 0), 0, ',', '.') : '' }}"
                                               placeholder="0"
                                               inputmode="numeric"
                                               autocomplete="off"
                                               required>

                                        <input type="hidden" name="nominal" id="nominal" value="{{ old('nominal', $data->nominal ?? 0) }}">

                                    </div>

                                    @error('nominal')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- METODE PEMBAYARAN --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="metode_pembayaran" class="modern-label-elite">Metode Pembayaran</label>

                                    <select name="metode_pembayaran"
                                            id="metode_pembayaran"
                                            class="form-control modern-input-elite @error('metode_pembayaran') is-invalid @enderror">

                                        <option value="">-- Pilih --</option>

                                        @foreach(['Cash', 'Transfer', 'E-Wallet'] as $metode)
                                            <option value="{{ $metode }}" {{ old('metode_pembayaran', $data->metode_pembayaran ?? '') == $metode ? 'selected' : '' }}>
                                                {{ $metode }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('metode_pembayaran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- STATUS --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="status" class="modern-label-elite">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status"
                                            id="status"
                                            class="form-control modern-input-elite @error('status') is-invalid @enderror"
                                            required>

                                        <option value="Diterima" {{ old('status', $data->status ?? 'Diterima') == 'Diterima' ? 'selected' : '' }}>
                                            Diterima
                                        </option>

                                        <option value="Dibatalkan" {{ old('status', $data->status ?? 'Diterima') == 'Dibatalkan' ? 'selected' : '' }}>
                                            Dibatalkan
                                        </option>

                                    </select>

                                    @error('status')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- KETERANGAN --}}
                            <div class="col-12">

                                <div class="form-group-elite mb-3">

                                    <label for="keterangan" class="modern-label-elite">Keterangan</label>

                                    <textarea name="keterangan"
                                              id="keterangan"
                                              rows="4"
                                              class="form-control modern-input-elite @error('keterangan') is-invalid @enderror"
                                              placeholder="Keterangan tambahan...">{{ old('keterangan', $data->keterangan ?? '') }}</textarea>

                                    @error('keterangan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- KOLOM KANAN --}}
                    {{-- ========================= --}}
                    <div class="col-lg-4">

                        <div class="summary-panel-elite h-100">

                            <div class="text-center mb-4">

                                <div class="summary-panel-icon-elite mx-auto mb-3">
                                    <i class="fas fa-wallet"></i>
                                </div>

                                <h6 class="summary-panel-title-elite">
                                    Pengeluaran Manual
                                </h6>

                                <small class="summary-panel-subtitle-elite">
                                    Perbarui data pengeluaran manual.
                                </small>

                            </div>


                            <div class="tips-box-elite mb-3">

                                <div class="d-flex">
                                    <i class="fas fa-info-circle tips-icon-elite mr-2 mt-1"></i>
                                    <div class="small tips-text-elite">
                                        Data pada form ini merupakan <strong>pengeluaran manual</strong>.
                                        Pengeluaran yang berasal dari Gaji dan Pajak dibuat otomatis dari masing-masing modul.
                                    </div>
                                </div>

                            </div>

                            <div class="tips-box-elite">

                                <div class="d-flex">
                                    <i class="fas fa-lightbulb tips-icon-elite mr-2 mt-1"></i>
                                    <div class="small tips-text-elite">
                                        Nominal ditampilkan dalam format Rupiah, sedangkan nilai yang disimpan berupa angka.
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="elite-divider my-4">


                <div class="d-flex justify-content-end">

                    <a href="{{ route('pengeluaran.index') }}"
                       class="btn btn-light-elite btn-modern-elite mr-2">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-gradient-elite btn-modern-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    STYLE
========================================================= --}}
<style>
/* =========================================================
   DESIGN TOKENS
========================================================== */

.keluar-form-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    padding-bottom: 20px;
}


/* =========================================================
   HEADER
========================================================== */

.modern-header-elite {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 26px 30px;
    border-radius: 18px;
    background:
        radial-gradient(circle at 12% 0%, rgba(217, 172, 79, .18) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
    box-shadow: 0 12px 32px rgba(124, 16, 19, 0.18);
}

.header-ornament-1 {
    position: absolute;
    width: 240px; height: 240px;
    top: -150px; right: -60px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 172, 79, .16) 0%, transparent 70%);
    pointer-events: none;
}

.header-ornament-2 {
    position: absolute;
    width: 160px; height: 160px;
    bottom: -100px; right: 60px;
    border-radius: 50%;
    border: 1px solid rgba(217, 172, 79, .18);
    pointer-events: none;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon-elite {
    width: 54px; height: 54px;
    border-radius: 14px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(4px);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.page-title-elite {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #ffffff;
}

.page-subtitle-elite {
    margin: 4px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 12.5px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-modern-elite {
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 13px;
    transition: all .25s ease;
    display: inline-flex;
    align-items: center;
}

.btn-gradient-elite {
    background: linear-gradient(135deg, #ec3b3c, #b91316);
    color: #fff !important;
    border: none;
    box-shadow: 0 6px 16px rgba(224,28,29,.28);
}
.btn-gradient-elite:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(224,28,29,.34);
    color: #fff !important;
}

.btn-outline-elite {
    color: #fff;
    border: 1px solid rgba(255,255,255,.5);
    background: rgba(255,255,255,.08);
}
.btn-outline-elite:hover {
    background: rgba(255,255,255,.18);
    color: #fff;
}

.btn-light-elite {
    background: #f6f3ee;
    border: 1px solid #ecd9bf;
    color: #6b5750;
}
.btn-light-elite:hover {
    background: #efe8de;
    color: #3a2a26;
}


/* =========================================================
   CARD
========================================================== */

.modern-card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 16px;
    box-shadow: 0 6px 22px rgba(124,16,19,.05);
    overflow: hidden;
}

.modern-card-body-elite { padding: 28px; }


/* =========================================================
   SECTION LABEL
========================================================== */

.section-label-elite {
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-label-icon-elite {
    width: 44px; height: 44px;
    min-width: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    font-size: 16px;
}

.section-label-title-elite {
    font-weight: 700;
    color: #3a2a26;
    font-size: 14.5px;
}

.section-label-subtitle-elite {
    color: #a09a94;
    font-size: 11.5px;
}


/* =========================================================
   FORM FIELD
========================================================== */

.modern-label-elite {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #6b5750;
    margin-bottom: 7px;
}

.modern-input-elite,
select.modern-input-elite,
textarea.modern-input-elite {
    height: 44px;
    border: 1px solid #ecd9bf;
    border-radius: 9px;
    font-size: 12.5px;
    background: #fffdf9;
    box-shadow: none !important;
    transition: all .2s ease;
    color: #3a2a26;
    width: 100%;
}

textarea.modern-input-elite {
    height: auto;
    padding: 12px 14px;
}

.modern-input-elite:focus {
    border-color: var(--sb-gold);
    box-shadow: 0 0 0 3px rgba(217,172,79,.18) !important;
    background: #fff;
    outline: none;
}

.modern-input-elite.is-invalid {
    border-color: var(--sb-red);
}

.modern-input-group-elite {
    position: relative;
}

.input-icon-elite {
    position: absolute;
    left: 15px;
    top: 15px;
    color: #c9b48a;
    font-size: 13px;
    z-index: 2;
    pointer-events: none;
}

.modern-input-group-elite .modern-input-elite {
    padding-left: 39px;
}


/* =========================================================
   VALUE GROUP (simbol + input)
========================================================== */

.modern-value-group-elite {
    display: flex;
    align-items: stretch;
    border: 1px solid #ecd9bf;
    border-radius: 9px;
    overflow: hidden;
    background: #fffdf9;
    transition: all .2s ease;
}

.modern-value-group-elite:focus-within {
    border-color: var(--sb-gold);
    box-shadow: 0 0 0 3px rgba(217,172,79,.18);
    background: #fff;
}

.modern-value-group-readonly-elite {
    background: #faf6ee;
}

.value-symbol-elite {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 46px;
    padding: 0 10px;
    background: #fdf3e2;
    color: #a9781f;
    font-weight: 700;
    font-size: 13px;
    border-right: 1px solid #ecd9bf;
}

.value-symbol-success-elite {
    background: #e8f8f1;
    color: #1cc88a;
}

.value-symbol-warning-elite {
    background: #fff0f0;
    color: var(--sb-red);
}

.value-input-elite {
    border: none !important;
    border-radius: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
}

.modern-value-group-elite .value-input-elite:focus {
    box-shadow: none !important;
}


/* =========================================================
   TOTAL BOX
========================================================== */

.total-box-elite {
    display: block;
    padding: 16px 18px;
    border-radius: 14px;
    background:
        radial-gradient(circle at 100% 0%, rgba(217,172,79,.18) 0%, transparent 60%),
        linear-gradient(135deg, #fff0f0, #ffe2e2);
    border: 1px solid rgba(224,28,29,.18);
}

.total-box-label-elite {
    display: block;
    color: #a9645f;
    font-size: 11px;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.total-box-value-elite {
    font-weight: 800;
    font-size: 25px;
    color: var(--sb-red-dark);
}


/* =========================================================
   SUMMARY PANEL (kolom kanan)
========================================================== */

.summary-panel-elite {
    background: #fffdf9;
    border: 1px solid rgba(217,172,79,.2);
    border-radius: 16px;
    padding: 26px;
}

.summary-panel-icon-elite {
    width: 72px; height: 72px;
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    font-size: 30px;
    box-shadow: 0 10px 24px rgba(224,28,29,.25);
}

.summary-panel-title-elite {
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 4px;
}

.summary-panel-subtitle-elite {
    color: #a09a94;
    font-size: 12px;
}

.calc-list-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.25);
    border-radius: 12px;
    padding: 6px 16px;
    margin-bottom: 14px;
}

.calc-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 11px 0;
    border-bottom: 1px dashed rgba(217,172,79,.35);
    font-size: 12.5px;
}
.calc-row-elite:last-child { border-bottom: none; }
.calc-row-elite span { color: #a09a94; }
.calc-row-elite strong { color: #3a2a26; text-align: right; }


/* =========================================================
   TIPS BOX
========================================================== */

.tips-box-elite {
    padding: 15px 16px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.25);
    border-left: 3px solid var(--sb-gold);
}

.tips-icon-elite {
    color: var(--sb-red);
}

.tips-text-elite {
    color: #6b5750;
    font-size: 12px;
    line-height: 1.6;
}


/* =========================================================
   DIVIDER
========================================================== */

.elite-divider {
    border-top: 1px solid rgba(217,172,79,.2);
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .modern-header-elite { align-items: flex-start; flex-direction: column; }
    .header-actions { width: 100%; }
    .summary-panel-elite { margin-top: 20px; }
}

@media (max-width: 767px) {
    .page-title-elite { font-size: 20px; }
    .header-icon-elite { width: 45px; height: 45px; font-size: 18px; }
    .modern-card-body-elite { padding: 18px; }
    .total-box-value-elite { font-size: 21px; }
}

/* =========================================================
   ALERT
========================================================== */

.alert-elite {
    display: flex;
    align-items: flex-start;
    padding: 14px 16px;
    border-radius: 12px;
    font-size: 12.5px;
    color: #5a4a44;
}
.alert-elite strong { color: #3a2a26; font-size: 13px; }
.alert-elite .alert-body-elite { flex: 1; }

.alert-success-elite { background: #eefaf3; border: 1px solid rgba(28,200,138,.25); }
.alert-success-elite > i { color: #1cc88a; }
.alert-danger-elite  { background: #fff0f0; border: 1px solid rgba(224,28,29,.2); }
.alert-danger-elite > i { color: var(--sb-red); }
.alert-warning-elite { background: #fdf6ea; border: 1px solid rgba(217,172,79,.35); color: #8a6a2f; }
.alert-warning-elite > i { color: var(--sb-gold); }
.alert-info-elite    { background: #eef4ff; border: 1px solid rgba(59,111,212,.25); }
.alert-info-elite > i { color: #3b6fd4; }

.alert-close-elite {
    background: none;
    border: none;
    font-size: 18px;
    line-height: 1;
    color: #a09a94;
    cursor: pointer;
    padding: 0 0 0 10px;
}
.alert-close-elite:hover { color: var(--sb-red-dark); }

.btn-success-elite {
    background: linear-gradient(135deg, #1cc88a, #12865d);
    color: #fff !important;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    box-shadow: 0 6px 16px rgba(28,200,138,.28);
    transition: all .25s ease;
}
.btn-success-elite:hover { filter: brightness(1.06); transform: translateY(-1px); }
</style>

@endsection


@push('scripts')
<script>

const nominalDisplay = document.getElementById('nominal_display');
const nominalHidden  = document.getElementById('nominal');

function formatNominal() {

    let angka = nominalDisplay.value.replace(/[^0-9]/g, '');

    if (!angka) {
        nominalHidden.value = 0;
        nominalDisplay.value = '';
        return;
    }

    nominalHidden.value = angka;
    nominalDisplay.value = Number(angka).toLocaleString('id-ID');
}

nominalDisplay.addEventListener('input', formatNominal);

</script>
@endpush