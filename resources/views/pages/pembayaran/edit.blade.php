@extends('layouts.app')

@section('title', $pembayaran->status === 'Menunggu' ? 'Bayar Reservasi' : 'Edit Pembayaran')

@section('content')

<div class="container-fluid px-4 bayar-form-elite">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}
    <div class="modern-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">

            <div class="header-icon-elite">
                <i class="fas fa-money-bill-wave"></i>
            </div>

            <div>
                <h1 class="page-title-elite">
                    {{ $pembayaran->status === 'Menunggu' ? 'Bayar Reservasi' : 'Edit Pembayaran' }}
                </h1>
                <p class="page-subtitle-elite">
                    {{ $pembayaran->status === 'Menunggu'
                        ? 'Konfirmasi pembayaran reservasi pelanggan'
                        : 'Perbarui data pembayaran reservasi' }}
                </p>
            </div>

        </div>


        <div class="header-actions position-relative">

            <a href="{{ route('pembayaran.show', $pembayaran->id) }}"
               class="btn btn-outline-elite btn-modern-elite">

                <i class="fas fa-arrow-left mr-2"></i>
                Kembali

            </a>

        </div>

    </div>


    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="alert-elite alert-success-elite mb-4" data-autohide="true">
            <i class="fas fa-check-circle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Berhasil</strong>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Terjadi Kesalahan</strong>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif


    {{-- ================================================= --}}
    {{-- FORM --}}
    {{-- ================================================= --}}
    <form id="formPembayaran"
          action="{{ route('pembayaran.update', $pembayaran->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- ================================================= --}}
            {{-- LEFT CONTENT --}}
            {{-- ================================================= --}}
            <div class="col-lg-8">

                <div class="modern-card-elite mb-4">

                    <div class="modern-card-body-elite">

                        {{-- ========== INFORMASI RESERVASI ========== --}}
                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Informasi Reservasi</div>
                                <small class="section-label-subtitle-elite">Data reservasi yang akan dibayar</small>
                            </div>
                        </div>

                        <div class="info-grid-elite">

                            <div class="info-cell-elite">
                                <span class="info-label-elite">Kode Reservasi</span>
                                <span class="info-value-elite">
                                    {{ $pembayaran->reservasi->kode_reservasi ?? '-' }}
                                </span>
                            </div>

                            <div class="info-cell-elite">
                                <span class="info-label-elite">Pelanggan</span>
                                <span class="info-value-elite">
                                    {{ $pembayaran->reservasi->pelanggan->nama ?? '-' }}
                                </span>
                            </div>

                            <div class="info-cell-elite">
                                <span class="info-label-elite">Terapis</span>
                                <span class="info-value-elite">
                                    {{ $pembayaran->reservasi->pegawai->nama ?? '-' }}
                                </span>
                            </div>

                            <div class="info-cell-elite">
                                <span class="info-label-elite">Tanggal Reservasi</span>
                                <span class="info-value-elite">
                                    {{ optional($pembayaran->reservasi->tanggal)->format('d-m-Y') ?? '-' }}
                                </span>
                            </div>

                            <div class="info-cell-elite info-cell-full-elite">
                                <span class="info-label-elite">Jadwal</span>
                                <span class="info-value-elite">
                                    {{ $pembayaran->reservasi->jam_mulai ?? '-' }}
                                    -
                                    {{ $pembayaran->reservasi->jam_selesai ?? '-' }}
                                </span>
                            </div>

                        </div>


                        <hr class="elite-divider my-4">


                        {{-- ========== PERAWATAN ========== --}}
                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-spa"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Perawatan</div>
                                <small class="section-label-subtitle-elite">Layanan perawatan pada reservasi ini</small>
                            </div>
                        </div>

                        @php
                            $perawatans = $pembayaran->reservasi->perawatans;
                        @endphp

                        @if($perawatans && $perawatans->count() > 0)

                            @foreach($perawatans as $perawatan)

                                @php
                                    $qty      = $perawatan->pivot->jumlah ?? 1;
                                    $harga    = $perawatan->pivot->harga ?? $perawatan->harga;
                                    $subtotal = $harga * $qty;
                                @endphp

                                <div class="treat-card-elite item-perawatan"
                                     data-perawatan-id="{{ $perawatan->id }}">

                                    <div class="row align-items-center">

                                        <div class="col-md-7">
                                            <div class="treat-name-elite">
                                                {{ $perawatan->nama }}
                                                <span class="badge-promo badge-promo-elite d-none">
                                                    <i class="fas fa-tag mr-1"></i>Kena Promo
                                                </span>
                                            </div>
                                            <div class="treat-meta-elite">
                                                <i class="far fa-clock mr-1"></i>
                                                Durasi: {{ $perawatan->durasi }} menit
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="treat-meta-elite">Jumlah</div>
                                            <div class="treat-name-elite">{{ $qty }}</div>
                                        </div>

                                        <div class="col-md-3 text-md-right">
                                            <div class="treat-meta-elite">Subtotal</div>
                                            <div class="treat-price-elite">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @elseif($pembayaran->reservasi->perawatan)

                            @php
                                $perawatan = $pembayaran->reservasi->perawatan;
                                $harga     = $perawatan->harga;
                            @endphp

                            <div class="treat-card-elite item-perawatan"
                                 data-perawatan-id="{{ $perawatan->id }}">

                                <div class="row align-items-center">

                                    <div class="col-md-8">
                                        <div class="treat-name-elite">
                                            {{ $perawatan->nama }}
                                            <span class="badge-promo badge-promo-elite d-none">
                                                <i class="fas fa-tag mr-1"></i>Kena Promo
                                            </span>
                                        </div>
                                        <div class="treat-meta-elite">
                                            <i class="far fa-clock mr-1"></i>
                                            Durasi: {{ $perawatan->durasi }} menit
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-md-right">
                                        <div class="treat-price-elite">
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="alert-elite alert-warning-elite">
                                <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                                <div class="alert-body-elite">Data perawatan tidak ditemukan.</div>
                            </div>

                        @endif


                        <hr class="elite-divider my-4">


                        {{-- ========== DATA PEMBAYARAN ========== --}}
                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-wallet"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Data Pembayaran</div>
                                <small class="section-label-subtitle-elite">Tanggal, metode, promo, dan catatan</small>
                            </div>
                        </div>

                        <div class="row">

                            {{-- TANGGAL BAYAR --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="tanggal_bayar" class="modern-label-elite">
                                        Tanggal Pembayaran <span class="text-danger">*</span>
                                    </label>

                                    <div class="modern-input-group-elite">

                                        <i class="fas fa-calendar-alt input-icon-elite"></i>

                                        <input type="date"
                                               name="tanggal_bayar"
                                               id="tanggal_bayar"
                                               class="form-control modern-input-elite @error('tanggal_bayar') is-invalid @enderror"
                                               value="{{ old('tanggal_bayar', optional($pembayaran->tanggal_bayar)->format('Y-m-d')) }}"
                                               required>

                                    </div>

                                    @error('tanggal_bayar')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>


                            {{-- METODE --}}
                            <div class="col-md-6">

                                <div class="form-group-elite mb-3">

                                    <label for="metode_pembayaran" class="modern-label-elite">
                                        Metode Pembayaran <span class="text-danger">*</span>
                                    </label>

                                    <select name="metode_pembayaran"
                                            id="metode_pembayaran"
                                            class="form-control modern-input-elite @error('metode_pembayaran') is-invalid @enderror"
                                            required>

                                        <option value="">-- Pilih Metode --</option>

                                        @foreach(['Cash', 'Transfer', 'E-Wallet'] as $metode)
                                            <option value="{{ $metode }}"
                                                {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) === $metode ? 'selected' : '' }}>
                                                {{ $metode }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('metode_pembayaran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- PROMO --}}
                        <div class="form-group-elite mb-3">

                            <label for="promo_id" class="modern-label-elite">
                                <i class="fas fa-tags text-danger mr-1"></i>
                                Promo
                                <span class="field-hint-elite font-weight-normal">(Opsional)</span>
                            </label>

                            <select name="promo_id"
                                    id="promo_id"
                                    class="form-control modern-input-elite @error('promo_id') is-invalid @enderror">

                                <option value="">-- Tidak Menggunakan Promo --</option>

                                @foreach($promos as $promo)
                                    <option value="{{ $promo->id }}"
                                            data-perawatan="{{ $promo->perawatan_id }}"
                                            data-jenis="{{ $promo->jenis }}"
                                            data-nilai="{{ $promo->nilai }}"
                                            {{ old('promo_id', $pembayaran->promo_id) == $promo->id ? 'selected' : '' }}>

                                        {{ $promo->nama }}
                                        -
                                        @if($promo->jenis === 'Persentase')
                                            {{ $promo->nilai }}%
                                        @else
                                            Rp {{ number_format($promo->nilai, 0, ',', '.') }}
                                        @endif

                                        @if($promo->perawatan)
                                            - {{ $promo->perawatan->nama }}
                                        @endif
                                    </option>
                                @endforeach

                            </select>

                            @error('promo_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- INFO PROMO --}}
                        <div id="infoPromo" class="promo-info-elite d-none">

                            <div class="promo-info-title-elite">
                                <i class="fas fa-tags mr-1"></i>
                                Informasi Promo
                            </div>

                            <div id="detailPromo" class="small"></div>

                        </div>


                        {{-- RINGKASAN --}}
                        <div id="ringkasanDiskon" class="recap-box-elite">

                            <div class="recap-row-elite">
                                <span class="text-muted-elite">Harga Awal</span>
                                <strong id="hargaAwalPromo">Rp 0</strong>
                            </div>

                            {{-- KETERANGAN DASAR DISKON --}}
                            <div id="barisDasarDiskon" class="recap-row-elite d-none">
                                <span class="text-muted-elite small">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Dasar diskon:
                                    <span id="labelDasarDiskon" class="font-weight-bold"></span>
                                </span>
                                <span class="small text-muted-elite" id="nilaiDasarDiskon">Rp 0</span>
                            </div>

                            <div class="recap-row-elite">
                                <span class="text-danger">Diskon</span>
                                <strong class="text-danger" id="nilaiDiskonPromo">- Rp 0</strong>
                            </div>

                            <div class="recap-row-elite recap-total-elite mb-0">
                                <span class="font-weight-bold">Total Pembayaran</span>
                                <strong class="recap-total-value-elite" id="totalSetelahPromo">Rp 0</strong>
                            </div>

                            {{-- KETERANGAN --}}
                            <div id="keteranganDiskon" class="recap-note-elite d-none"></div>

                        </div>


                        {{-- CATATAN --}}
                        <div class="form-group-elite mb-0">

                            <label for="catatan" class="modern-label-elite">Catatan</label>

                            <textarea name="catatan"
                                      id="catatan"
                                      rows="4"
                                      class="form-control modern-input-elite"
                                      placeholder="Tambahkan catatan pembayaran jika diperlukan...">{{ old('catatan', $pembayaran->catatan) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- RIGHT SUMMARY --}}
            {{-- ================================================= --}}
            <div class="col-lg-4">

                <div class="summary-sticky-elite">

                    <div class="summary-sticky-header-elite">
                        <h6>
                            <i class="fas fa-receipt mr-1"></i>
                            Ringkasan Pembayaran
                        </h6>
                    </div>

                    <div class="summary-sticky-body-elite">

                        <div class="mb-3">
                            <small class="info-label-elite d-block">Kode Reservasi</small>
                            <div class="info-value-elite">
                                {{ $pembayaran->reservasi->kode_reservasi ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="info-label-elite d-block">Pelanggan</small>
                            <div class="info-value-elite">
                                {{ $pembayaran->reservasi->pelanggan->nama ?? '-' }}
                            </div>
                        </div>

                        <div class="pt-3" style="border-top:1px dashed rgba(217,172,79,.35);">

                            <div class="side-row-elite">
                                <span>Harga Awal</span>
                                <strong id="sideHargaAwal">Rp 0</strong>
                            </div>

                            <div class="side-row-elite">
                                <span class="text-danger">Diskon</span>
                                <strong class="text-danger" id="sideDiskon">- Rp 0</strong>
                            </div>

                            <div id="sideKeteranganDiskon" class="small text-muted-elite mb-2 d-none"></div>

                            <div class="side-row-elite mt-3 pt-3" style="border-top:1px solid rgba(217,172,79,.25);">
                                <span class="font-weight-bold" style="color:#3a2a26;">Total Bayar</span>
                                <strong class="side-total-elite" id="sideTotal">Rp 0</strong>
                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div class="mt-4">

                            @if($pembayaran->status === 'Menunggu')

                                <div class="status-note-elite status-note-warning-elite">
                                    <i class="fas fa-clock mr-1"></i>
                                    <strong>Menunggu Pembayaran</strong>
                                    <div class="small">
                                        Setelah tombol <strong>Konfirmasi Pembayaran</strong>
                                        ditekan, status pembayaran menjadi <strong>Lunas</strong>.
                                    </div>
                                </div>

                                <input type="hidden" name="status" value="Lunas">

                            @elseif($pembayaran->status === 'Lunas')

                                <div class="status-note-elite status-note-success-elite">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <strong>Lunas</strong>
                                </div>

                                <input type="hidden" name="status" value="Lunas">

                            @else

                                <div class="status-note-elite status-note-danger-elite">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    <strong>Dibatalkan</strong>
                                </div>

                                <input type="hidden" name="status" value="Dibatalkan">

                            @endif

                        </div>


                        {{-- BUTTON --}}
                        <div class="mt-4">

                            @if($pembayaran->status === 'Menunggu')
                                <button type="submit" class="btn btn-success-elite btn-block btn-modern-elite justify-content-center">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Konfirmasi Pembayaran
                                </button>
                            @else
                                <button type="submit" class="btn btn-gradient-elite btn-block btn-modern-elite justify-content-center">
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan Perubahan
                                </button>
                            @endif

                            <a href="{{ route('pembayaran.show', $pembayaran->id) }}"
                               class="btn btn-light-elite btn-block btn-modern-elite justify-content-center mt-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Batal
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </form>

</div>


<style>
/* =========================================================
   DESIGN TOKENS
========================================================== */

.bayar-form-elite {
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

/* =========================================================
   KHUSUS PEMBAYARAN (FORM)
========================================================== */

.info-grid-elite {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.info-cell-elite {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
    background: #fdfaf5;
    border: 1px solid rgba(217,172,79,.12);
    border-radius: 10px;
}
.info-cell-full-elite { grid-column: 1 / -1; }

.info-label-elite {
    font-size: 11px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.info-value-elite {
    font-size: 13.5px;
    font-weight: 600;
    color: #3a2a26;
    word-break: break-word;
}

.info-total-elite { font-size: 18px; font-weight: 800; color: #12865d; }

.field-hint-elite { color: #a09a94; font-size: 11.5px; }

/* kartu perawatan (edit) */
.treat-card-elite {
    padding: 14px 16px;
    margin-bottom: 12px;
    background: #fff;
    border: 1px solid rgba(217,172,79,.25);
    border-radius: 12px;
    transition: all .2s ease;
}
.treat-card-elite:last-child { margin-bottom: 0; }
.treat-card-elite:hover { border-color: var(--sb-gold); background: #fffdf9; }

.treat-name-elite { font-weight: 700; color: #3a2a26; font-size: 13.5px; }
.treat-meta-elite { color: #a09a94; font-size: 11.5px; }
.treat-price-elite { font-weight: 700; color: var(--sb-red); font-size: 13.5px; }

.badge-promo-elite {
    display: inline-flex;
    align-items: center;
    margin-left: 6px;
    padding: 3px 10px;
    border-radius: 20px;
    background: #fff0f0;
    color: var(--sb-red);
    font-size: 10px;
    font-weight: 700;
}
.badge-promo-elite.d-none { display: none !important; }

/* info promo */
.promo-info-elite {
    padding: 14px 16px;
    margin-bottom: 14px;
    border-radius: 12px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.3);
    color: #8a6a2f;
}
.promo-info-elite.d-none { display: none !important; }
.promo-info-title-elite { font-weight: 700; color: var(--sb-red); margin-bottom: 6px; font-size: 12.5px; }

/* ringkasan diskon */
.recap-box-elite {
    padding: 16px 18px;
    margin-bottom: 18px;
    border-radius: 14px;
    background:
        radial-gradient(circle at 100% 0%, rgba(217,172,79,.16) 0%, transparent 60%),
        linear-gradient(135deg, #fff8f8, #fff0f0);
    border: 1px solid rgba(224,28,29,.15);
}
.recap-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
    font-size: 13px;
}
.recap-row-elite.d-none { display: none !important; }
.recap-total-elite {
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px solid rgba(224,28,29,.15);
}
.recap-total-value-elite { font-size: 19px; font-weight: 800; color: #12865d; }
.recap-note-elite {
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px dashed rgba(224,28,29,.25);
    font-size: 11.5px;
    color: #8a6a2f;
    line-height: 1.6;
}
.recap-note-elite.d-none { display: none !important; }

/* panel ringkasan kanan (sticky) */
.summary-sticky-elite {
    position: sticky;
    top: 20px;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(217,172,79,.15);
    box-shadow: 0 8px 28px rgba(124,16,19,.08);
    margin-bottom: 24px;
}

.summary-sticky-header-elite {
    position: relative;
    overflow: hidden;
    padding: 18px 20px;
    color: #fff;
    background:
        radial-gradient(circle at 15% 0%, rgba(217, 172, 79, .22) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
}
.summary-sticky-header-elite h6 { margin: 0; font-weight: 700; position: relative; }

.summary-sticky-body-elite { padding: 20px; }

.side-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 13px;
}
.side-row-elite span { color: #a09a94; }

.side-total-elite { font-size: 20px; font-weight: 800; color: #12865d; }

.status-note-elite {
    padding: 13px 15px;
    border-radius: 12px;
    font-size: 12.5px;
}
.status-note-elite .small { font-size: 11.5px; margin-top: 4px; }
.status-note-warning-elite { background: #fdf6ea; border: 1px solid rgba(217,172,79,.35); color: #8a6a2f; }
.status-note-success-elite { background: #eefaf3; border: 1px solid rgba(28,200,138,.25); color: #12865d; }
.status-note-danger-elite  { background: #fff0f0; border: 1px solid rgba(224,28,29,.2); color: var(--sb-red-dark); }

@media (max-width: 991px) {
    .summary-sticky-elite { position: static; }
}
@media (max-width: 576px) {
    .info-grid-elite { grid-template-columns: 1fr; }
}
</style>

@endsection


@push('scripts')

@php
    /*
    | Peta perawatan di reservasi ini: id => [nama, subtotal]
    | Subtotal = harga x jumlah. Dipakai JS untuk menghitung dasar diskon.
    */
    $petaPerawatan = [];

    if ($pembayaran->reservasi->perawatans && $pembayaran->reservasi->perawatans->count() > 0) {

        foreach ($pembayaran->reservasi->perawatans as $p) {
            $petaPerawatan[$p->id] = [
                'nama'     => $p->nama,
                'subtotal' => ((int) ($p->pivot->harga ?? $p->harga)) * ((int) ($p->pivot->jumlah ?? 1)),
            ];
        }

    } elseif ($pembayaran->reservasi->perawatan) {

        $p = $pembayaran->reservasi->perawatan;

        $petaPerawatan[$p->id] = [
            'nama'     => $p->nama,
            'subtotal' => (int) $p->harga,
        ];
    }

    $totalAwalPhp = $pembayaran->jumlah_sebelum_diskon > 0
        ? $pembayaran->jumlah_sebelum_diskon
        : $pembayaran->jumlah;
@endphp

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | DATA DARI SERVER
    |--------------------------------------------------------------------------
    */
    const totalAwal      = {{ (int) $totalAwalPhp }};
    const petaPerawatan  = @json($petaPerawatan);


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    function rp(angka) {
        return 'Rp ' + formatRupiah(angka);
    }


    /*
    |--------------------------------------------------------------------------
    | RESET TAMPILAN PROMO
    |--------------------------------------------------------------------------
    */
    function tampilkanTanpaPromo() {

        $('#infoPromo').addClass('d-none');
        $('#barisDasarDiskon').addClass('d-none');
        $('#keteranganDiskon').addClass('d-none').empty();
        $('#sideKeteranganDiskon').addClass('d-none').empty();
        $('.badge-promo').addClass('d-none');

        $('#hargaAwalPromo').text(rp(totalAwal));
        $('#nilaiDiskonPromo').text('- Rp 0');
        $('#totalSetelahPromo').text(rp(totalAwal));

        $('#sideHargaAwal').text(rp(totalAwal));
        $('#sideDiskon').text('- Rp 0');
        $('#sideTotal').text(rp(totalAwal));
    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG PROMO
    | Diskon dihitung dari harga PERAWATAN YANG TERKAIT PROMO saja.
    | Jika promo tidak terikat perawatan tertentu, dasar diskon = total semua.
    |--------------------------------------------------------------------------
    */
    function hitungPromo() {

        if (!$('#promo_id').val()) {
            tampilkanTanpaPromo();
            return;
        }

        const selected    = $('#promo_id option:selected');
        const jenis       = selected.data('jenis');
        const nilai       = Number(selected.data('nilai'));
        const perawatanId = selected.data('perawatan');
        const namaPromo   = selected.text().trim().replace(/\s+/g, ' ');

        $('.badge-promo').addClass('d-none');

        let dasarDiskon   = totalAwal;
        let labelDasar    = 'Semua perawatan';
        let promoSesuai   = true;

        /*
        | Promo untuk perawatan tertentu
        */
        if (perawatanId) {

            const item = petaPerawatan[perawatanId];

            if (item) {
                dasarDiskon = Number(item.subtotal);
                labelDasar  = item.nama;

                $('.item-perawatan[data-perawatan-id="' + perawatanId + '"] .badge-promo')
                    .removeClass('d-none');
            } else {
                dasarDiskon = 0;
                labelDasar  = '-';
                promoSesuai = false;
            }
        }

        /*
        | Hitung diskon
        */
        let diskon = 0;

        if (promoSesuai) {
            diskon = (jenis === 'Persentase')
                ? (dasarDiskon * nilai) / 100
                : nilai;

            diskon = Math.min(diskon, dasarDiskon);
            diskon = Math.round(diskon);
        }

        const totalAkhir = totalAwal - diskon;


        /*
        | Info promo
        */
        let info = '';

        if (!promoSesuai) {

            info = '<span class="text-danger"><i class="fas fa-exclamation-triangle mr-1"></i>' +
                   'Promo <strong>' + namaPromo + '</strong> tidak berlaku karena reservasi ini ' +
                   'tidak memiliki perawatan yang sesuai dengan promo.</span>';

        } else if (jenis === 'Persentase') {

            info = 'Promo <strong>' + namaPromo + '</strong> memberikan diskon sebesar <strong>' +
                   nilai + '%</strong>';

            info += perawatanId
                ? ' dari harga perawatan <strong>' + labelDasar + '</strong> (' + rp(dasarDiskon) + ').'
                : ' dari total seluruh perawatan.';

        } else {

            info = 'Promo <strong>' + namaPromo + '</strong> memberikan potongan sebesar <strong>' +
                   rp(nilai) + '</strong>';

            info += perawatanId
                ? ' untuk perawatan <strong>' + labelDasar + '</strong>.'
                : ' dari total seluruh perawatan.';
        }

        $('#detailPromo').html(info);
        $('#infoPromo').removeClass('d-none');


        /*
        | Baris dasar diskon + keterangan di Data Pembayaran
        */
        $('#labelDasarDiskon').text(labelDasar);
        $('#nilaiDasarDiskon').text(rp(dasarDiskon));
        $('#barisDasarDiskon').removeClass('d-none');

        let keterangan = '';

        if (!promoSesuai) {

            keterangan = '<i class="fas fa-exclamation-circle text-danger mr-1"></i>' +
                         'Promo tidak dapat diterapkan pada reservasi ini.';

        } else if (perawatanId) {

            keterangan = '<i class="fas fa-info-circle mr-1"></i>' +
                         '<strong>Keterangan:</strong> diskon hanya dihitung dari harga perawatan ' +
                         '<strong>' + labelDasar + '</strong> (' + rp(dasarDiskon) + '), ' +
                         'bukan dari total keseluruhan (' + rp(totalAwal) + '). ' +
                         'Perawatan lain tidak mendapat potongan.';

        } else {

            keterangan = '<i class="fas fa-info-circle mr-1"></i>' +
                         '<strong>Keterangan:</strong> promo ini berlaku untuk semua perawatan, ' +
                         'sehingga diskon dihitung dari total keseluruhan (' + rp(totalAwal) + ').';
        }

        $('#keteranganDiskon').html(keterangan).removeClass('d-none');

        $('#sideKeteranganDiskon')
            .html('Diskon dari: <strong>' + labelDasar + '</strong> (' + rp(dasarDiskon) + ')')
            .removeClass('d-none');


        /*
        | Ringkasan
        */
        $('#hargaAwalPromo').text(rp(totalAwal));
        $('#nilaiDiskonPromo').text('- ' + rp(diskon));
        $('#totalSetelahPromo').text(rp(totalAkhir));

        $('#sideHargaAwal').text(rp(totalAwal));
        $('#sideDiskon').text('- ' + rp(diskon));
        $('#sideTotal').text(rp(totalAkhir));
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT & INITIAL
    |--------------------------------------------------------------------------
    */
    $('#promo_id').on('change', hitungPromo);

    hitungPromo();


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    $('#formPembayaran').on('submit', function (e) {

        @if($pembayaran->status === 'Menunggu')

            e.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Konfirmasi pembayaran?',
                html: 'Pembayaran akan dikonfirmasi sebagai <strong>LUNAS</strong>.' +
                      '<br>Status reservasi akan berubah menjadi <strong>DIKONFIRMASI</strong>.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
                cancelButtonColor: '#858796',
                confirmButtonText: '<i class="fas fa-check-circle mr-1"></i> Ya, Konfirmasi',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        @endif

    });

});

</script>

@endpush