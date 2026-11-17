@extends('layouts.app')

@section('title', 'Edit Pajak')

@section('content')

@php
    $bulanNama = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp

<div class="container-fluid px-4 pajak-form-elite">

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
                <h1 class="page-title-elite">Edit Data Pajak</h1>
                <p class="page-subtitle-elite">Perbarui data pajak pegawai</p>
            </div>

        </div>


        <div class="header-actions position-relative">

            <a href="{{ route('pajak.index') }}"
               class="btn btn-outline-elite btn-modern-elite">

                <i class="fas fa-arrow-left mr-2"></i>
                Kembali

            </a>

        </div>

    </div>


    {{-- =========================================================
        CARD FORM
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-body-elite">

            <form action="{{ route('pajak.update', ['pajak' => $data->id]) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ========================= --}}
                    {{-- KOLOM KIRI --}}
                    {{-- ========================= --}}
                    <div class="col-lg-7">

                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Informasi Pajak</div>
                                <small class="section-label-subtitle-elite">Masukkan informasi pajak pegawai</small>
                            </div>
                        </div>


                        {{-- PEGAWAI --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                Pegawai <span class="text-danger">*</span>
                            </label>

                            <select name="pegawai_id"
                                    class="form-control modern-input-elite @error('pegawai_id') is-invalid @enderror"
                                    required>

                                <option value="">-- Pilih Pegawai --</option>

                                @foreach($pegawai as $item)
                                    <option value="{{ $item->id }}" {{ old('pegawai_id', $data->pegawai_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>

                            @error('pegawai_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- BULAN + TAHUN --}}
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label class="modern-label-elite">
                                        Bulan <span class="text-danger">*</span>
                                    </label>

                                    <select name="bulan"
                                            class="form-control modern-input-elite @error('bulan') is-invalid @enderror"
                                            required>

                                        <option value="">-- Pilih Bulan --</option>

                                        @foreach($bulanNama as $key => $nama)
                                            <option value="{{ $key }}" {{ old('bulan', $data->bulan) == $key ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('bulan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label class="modern-label-elite">
                                        Tahun <span class="text-danger">*</span>
                                    </label>

                                    <input type="number"
                                           name="tahun"
                                           class="form-control modern-input-elite @error('tahun') is-invalid @enderror"
                                           value="{{ old('tahun', $data->tahun) }}"
                                           min="2020"
                                           max="2100"
                                           required>

                                    @error('tahun')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- PENGHASILAN BRUTO --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                Penghasilan Bruto <span class="text-danger">*</span>
                            </label>

                            <div class="modern-value-group-elite">

                                <span class="value-symbol-elite">Rp</span>

                                <input type="text"
                                       id="penghasilan_bruto_display"
                                       class="form-control modern-input-elite value-input-elite @error('penghasilan_bruto') is-invalid @enderror"
                                       placeholder="0"
                                       inputmode="numeric"
                                       autocomplete="off">

                                <input type="hidden" name="penghasilan_bruto" id="penghasilan_bruto"
                                       value="{{ old('penghasilan_bruto', $data->penghasilan_bruto) }}">

                            </div>

                            @error('penghasilan_bruto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- PTKP --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                PTKP <span class="text-danger">*</span>
                            </label>

                            <div class="modern-value-group-elite">

                                <span class="value-symbol-elite value-symbol-success-elite">Rp</span>

                                <input type="text"
                                       id="ptkp_display"
                                       class="form-control modern-input-elite value-input-elite @error('ptkp') is-invalid @enderror"
                                       placeholder="0"
                                       inputmode="numeric"
                                       autocomplete="off">

                                <input type="hidden" name="ptkp" id="ptkp"
                                       value="{{ old('ptkp', $data->ptkp) }}">

                            </div>

                            @error('ptkp')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- PKP + TARIF --}}
                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label class="modern-label-elite">PKP</label>

                                    <div class="modern-value-group-elite modern-value-group-readonly-elite">

                                        <span class="value-symbol-elite">Rp</span>

                                        <input type="text"
                                               id="pkp_display"
                                               class="form-control modern-input-elite value-input-elite"
                                               readonly>

                                        <input type="hidden" name="pkp" id="pkp" value="{{ old('pkp', $data->pkp) }}">

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label class="modern-label-elite">
                                        Tarif Pajak (%) <span class="text-danger">*</span>
                                    </label>

                                    <div class="modern-value-group-elite">

                                        <span class="value-symbol-elite value-symbol-warning-elite">%</span>

                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               name="tarif_pajak"
                                               id="tarif_pajak"
                                               class="form-control modern-input-elite value-input-elite @error('tarif_pajak') is-invalid @enderror"
                                               value="{{ old('tarif_pajak', $data->tarif_pajak) }}"
                                               required>

                                    </div>

                                    @error('tarif_pajak')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- JUMLAH PAJAK --}}
                        <div class="total-box-elite mb-4">

                            <small class="total-box-label-elite">Jumlah Pajak</small>

                            <div class="modern-value-group-elite modern-value-group-readonly-elite" style="background:transparent;border:none;box-shadow:none;">
                                <input type="text"
                                       id="jumlah_pajak_display"
                                       class="total-box-value-elite"
                                       style="border:none;background:transparent;padding:0;width:100%;outline:none;"
                                       readonly>
                            </div>

                            <input type="hidden" name="jumlah_pajak" id="jumlah_pajak" value="{{ old('jumlah_pajak', $data->jumlah_pajak) }}">

                        </div>


                        {{-- STATUS --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                Status <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-control modern-input-elite @error('status') is-invalid @enderror"
                                    required>

                                <option value="Belum Dibayar" {{ old('status', $data->status) === 'Belum Dibayar' ? 'selected' : '' }}>
                                    Belum Dibayar
                                </option>

                                <option value="Dibayar" {{ old('status', $data->status) === 'Dibayar' ? 'selected' : '' }}>
                                    Dibayar
                                </option>

                            </select>

                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- TANGGAL BAYAR --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                Tanggal Bayar
                            </label>

                            <div class="modern-input-group-elite">

                                <i class="fas fa-calendar-alt input-icon-elite"></i>

                                <input type="date"
                                       name="tanggal_bayar"
                                       id="tanggal_bayar"
                                       class="form-control modern-input-elite @error('tanggal_bayar') is-invalid @enderror"
                                       value="{{ old('tanggal_bayar', optional($data->tanggal_bayar)->format('Y-m-d')) }}">

                            </div>

                            @error('tanggal_bayar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- CATATAN --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">
                                Catatan
                            </label>

                            <textarea name="catatan"
                                      rows="4"
                                      class="form-control modern-input-elite @error('catatan') is-invalid @enderror"
                                      placeholder="Catatan pajak...">{{ old('catatan', $data->catatan) }}</textarea>

                            @error('catatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- KOLOM KANAN --}}
                    {{-- ========================= --}}
                    <div class="col-lg-5">

                        <div class="summary-panel-elite h-100">

                            <div class="text-center mb-4">

                                <div class="summary-panel-icon-elite mx-auto mb-3">
                                    <i class="fas fa-calculator"></i>
                                </div>

                                <h6 class="summary-panel-title-elite">
                                    Ringkasan Perhitungan
                                </h6>

                                <small class="summary-panel-subtitle-elite">
                                    PKP dan jumlah pajak dihitung otomatis dari nilai yang dimasukkan.
                                </small>

                            </div>


                            <div class="calc-list-elite">

                                <div class="calc-row-elite">
                                    <span>Penghasilan Bruto</span>
                                    <strong id="ringkas_bruto">Rp 0</strong>
                                </div>

                                <div class="calc-row-elite">
                                    <span>PTKP</span>
                                    <strong id="ringkas_ptkp">Rp 0</strong>
                                </div>

                                <div class="calc-row-elite">
                                    <span>PKP</span>
                                    <strong id="ringkas_pkp">Rp 0</strong>
                                </div>

                                <div class="calc-row-elite">
                                    <span>Tarif Pajak</span>
                                    <strong id="ringkas_tarif">0%</strong>
                                </div>

                                <div class="calc-row-elite">
                                    <span>Jumlah Pajak</span>
                                    <strong class="text-danger" id="ringkas_pajak">Rp 0</strong>
                                </div>

                            </div>


                            <div class="tips-box-elite">

                                <div class="d-flex">

                                    <i class="fas fa-info-circle tips-icon-elite mr-2 mt-1"></i>

                                    <div class="small tips-text-elite">
                                        PKP dihitung dari penghasilan bruto dikurangi PTKP, lalu dikalikan tarif pajak.
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="elite-divider my-4">


                <div class="d-flex justify-content-end">

                    <a href="{{ route('pajak.show', ['pajak' => $data->id]) }}"
                       class="btn btn-light-elite btn-modern-elite mr-2">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-gradient-elite btn-modern-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
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

.pajak-form-elite {
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
</style>


@push('scripts')
<script>
function formatAngka(angka) {
    angka = parseInt(angka || 0);
    return angka.toLocaleString('id-ID');
}

function formatRupiahValue(angka) {
    return 'Rp ' + formatAngka(angka);
}

function setupRupiah(displayId, hiddenId) {

    const display = document.getElementById(displayId);
    const hidden = document.getElementById(hiddenId);

    if (!display || !hidden) return;

    function format() {

        let angka = display.value.replace(/[^0-9]/g, '');

        hidden.value = angka === '' ? 0 : angka;
        display.value = angka === '' ? '' : formatAngka(angka);

        hitungPajak();
    }

    display.addEventListener('input', format);
}

function hitungPajak() {

    const bruto = parseInt(document.getElementById('penghasilan_bruto').value || 0);
    const ptkp = parseInt(document.getElementById('ptkp').value || 0);
    const tarif = parseFloat(document.getElementById('tarif_pajak').value || 0);

    const pkp = Math.max(bruto - ptkp, 0);
    const jumlah = Math.round(pkp * tarif / 100);

    document.getElementById('pkp').value = pkp;
    document.getElementById('jumlah_pajak').value = jumlah;
    document.getElementById('pkp_display').value = formatAngka(pkp);
    document.getElementById('jumlah_pajak_display').value = formatAngka(jumlah);

    document.getElementById('ringkas_bruto').textContent = formatRupiahValue(bruto);
    document.getElementById('ringkas_ptkp').textContent = formatRupiahValue(ptkp);
    document.getElementById('ringkas_pkp').textContent = formatRupiahValue(pkp);
    document.getElementById('ringkas_tarif').textContent = tarif.toLocaleString('id-ID') + '%';
    document.getElementById('ringkas_pajak').textContent = formatRupiahValue(jumlah);
}

setupRupiah('penghasilan_bruto_display', 'penghasilan_bruto');
setupRupiah('ptkp_display', 'ptkp');

document.getElementById('tarif_pajak').addEventListener('input', hitungPajak);

document.addEventListener('DOMContentLoaded', function () {

    const bruto = document.getElementById('penghasilan_bruto').value;
    const ptkp = document.getElementById('ptkp').value;

    document.getElementById('penghasilan_bruto_display').value = bruto > 0 ? formatAngka(bruto) : '';
    document.getElementById('ptkp_display').value = ptkp > 0 ? formatAngka(ptkp) : '';

    hitungPajak();
});
</script>
@endpush

@endsection