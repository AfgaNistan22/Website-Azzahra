@extends('layouts.app')

@section('title', 'Detail Riwayat Reservasi')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | AMBIL PERAWATAN
    |--------------------------------------------------------------------------
    */

    $perawatanList = collect();

    if (
        isset($riwayat->perawatans) &&
        $riwayat->perawatans->count() > 0
    ) {

        $perawatanList = $riwayat->perawatans;

    } elseif ($riwayat->perawatan) {

        /*
        | Fallback untuk data lama
        */

        $perawatanList = collect([
            $riwayat->perawatan
        ]);

    }

    $totalDurasi = 0;
    $totalHarga  = 0;
    $totalJumlah = 0;

    /*
    |--------------------------------------------------------------------------
    | STATUS -> BADGE & ICON
    |--------------------------------------------------------------------------
    */

    $statusBadgeClass = match($riwayat->status) {
        'Menunggu'     => 'badge-warning-elite',
        'Dikonfirmasi' => 'badge-info-elite',
        'Selesai'      => 'badge-active-elite',
        'Dibatalkan'   => 'badge-maroon-elite',
        default        => 'badge-secondary-elite',
    };

    $statusIcon = match($riwayat->status) {
        'Menunggu'     => 'fa-clock',
        'Dikonfirmasi' => 'fa-check',
        'Selesai'      => 'fa-check-circle',
        'Dibatalkan'   => 'fa-times-circle',
        default        => 'fa-history',
    };

    $namaPelanggan = optional($riwayat->pelanggan)->nama;

@endphp

<div class="container-fluid px-4 riwayat-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center justify-content-between position-relative flex-wrap gap-3 w-100">

            <div class="d-flex align-items-center gap-3">
                <div class="header-icon-elite">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1 text-white">Detail Riwayat Reservasi</h4>
                    <p class="text-white-50 mb-0 small">
                        Informasi lengkap reservasi pelanggan Azzahra Salon dan Spa Muslimah
                    </p>
                </div>
            </div>

            <a href="{{ route('riwayat-reservasi.index') }}" class="btn-back-elite">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>

        </div>

    </div>


    <div class="row">

        {{-- ================================================= --}}
        {{-- KOLOM KIRI --}}
        {{-- ================================================= --}}
        <div class="col-lg-8">

            {{-- ================= INFORMASI RESERVASI ================= --}}
            <div class="card-elite mb-4">

                <div class="table-card-header-elite">

                    <div class="section-head-elite">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Reservasi</h6>
                            <small class="section-subtitle-elite">Data pelanggan, terapis, dan jadwal</small>
                        </div>
                    </div>

                    <span class="badge-pill-elite {{ $statusBadgeClass }}">
                        <i class="fas {{ $statusIcon }} mr-1"></i>
                        {{ $riwayat->status }}
                    </span>

                </div>

                <div class="card-body p-4">

                    <div class="row">

                        {{-- KODE --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Kode Reservasi</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="fas fa-hashtag"></i></span>
                                <span class="info-value-elite">
                                    {{ $riwayat->kode_reservasi ?? '#' . $riwayat->id }}
                                </span>
                            </div>
                        </div>

                        {{-- PELANGGAN --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Nama Pelanggan</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="fas fa-user"></i></span>
                                <span class="info-value-elite">{{ $namaPelanggan ?? '-' }}</span>
                            </div>
                        </div>

                        {{-- PEGAWAI --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Pegawai / Terapis</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="fas fa-user-nurse"></i></span>
                                <span class="info-value-elite">
                                    {{ optional($riwayat->pegawai)->nama ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Tanggal Reservasi</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="fas fa-calendar-alt"></i></span>
                                <span class="info-value-elite">
                                    @if($riwayat->tanggal)
                                        {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- JAM MULAI --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Jam Mulai</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="fas fa-clock"></i></span>
                                <span class="info-value-elite">{{ substr($riwayat->jam_mulai, 0, 5) }}</span>
                            </div>
                        </div>

                        {{-- JAM SELESAI --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label-elite">Jam Selesai</label>
                            <div class="info-box-elite">
                                <span class="info-icon-elite"><i class="far fa-clock"></i></span>
                                <span class="info-value-elite">{{ substr($riwayat->jam_selesai, 0, 5) }}</span>
                            </div>
                        </div>

                        {{-- CATATAN --}}
                        <div class="col-12">
                            <label class="form-label-elite">Catatan</label>
                            <div class="info-box-elite info-box-note-elite">
                                <span class="info-icon-elite"><i class="fas fa-sticky-note"></i></span>
                                <span class="info-value-elite">
                                    {{ $riwayat->catatan ?: 'Tidak ada catatan.' }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================= PERAWATAN ================= --}}
            <div class="card-elite mb-4">

                <div class="table-card-header-elite">

                    <div class="section-head-elite">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-spa"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Perawatan</h6>
                            <small class="section-subtitle-elite">Daftar perawatan pada reservasi ini</small>
                        </div>
                    </div>

                    <span class="badge-pill-elite badge-maroon-elite">
                        {{ $perawatanList->count() }} Perawatan
                    </span>

                </div>

                <div class="card-body p-4">

                    @if($perawatanList->count() > 0)

                        <div class="row">

                            @foreach($perawatanList as $item)

                                @php

                                    $jumlah =
                                        isset($item->pivot)
                                            ? (int)($item->pivot->jumlah ?? 1)
                                            : 1;

                                    $harga =
                                        isset($item->pivot)
                                            ? (int)($item->pivot->harga ?? $item->harga)
                                            : (int)$item->harga;

                                    $durasi =
                                        isset($item->pivot)
                                            ? (int)($item->pivot->durasi ?? $item->durasi)
                                            : (int)$item->durasi;

                                    $subtotalHarga  = $harga * $jumlah;
                                    $subtotalDurasi = $durasi * $jumlah;

                                    $totalHarga  += $subtotalHarga;
                                    $totalDurasi += $subtotalDurasi;
                                    $totalJumlah += $jumlah;

                                @endphp

                                <div class="col-md-6 mb-3">

                                    <div class="treatment-card-elite">

                                        {{-- FOTO --}}
                                        <div class="treatment-image-elite">
                                            @if($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                     alt="{{ $item->nama }}">
                                            @else
                                                <i class="fas fa-spa"></i>
                                            @endif
                                        </div>

                                        {{-- INFORMASI --}}
                                        <div class="treatment-content-elite">

                                            <div class="d-flex justify-content-between align-items-start">

                                                <div>
                                                    <div class="cell-title-elite">{{ $item->nama }}</div>
                                                    <small class="text-muted-elite">
                                                        <i class="far fa-clock mr-1"></i>{{ $durasi }} menit
                                                    </small>
                                                </div>

                                                <span class="badge-pill-elite badge-maroon-elite">
                                                    {{ $jumlah }}x
                                                </span>

                                            </div>

                                            <div class="mt-2">
                                                <small class="text-muted-elite">Harga</small>
                                                <div class="treatment-price-elite">
                                                    Rp {{ number_format($harga, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="treatment-subtotal-elite">
                                                <span>
                                                    Durasi:
                                                    <strong>{{ $subtotalDurasi }} menit</strong>
                                                </span>
                                                <span>
                                                    Subtotal:
                                                    <strong class="text-red-elite">
                                                        Rp {{ number_format($subtotalHarga, 0, ',', '.') }}
                                                    </strong>
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="empty-state-elite">
                            <div class="empty-icon-elite">
                                <i class="fas fa-spa"></i>
                            </div>
                            <h6>Data Perawatan Tidak Ditemukan</h6>
                            <p>Reservasi ini belum memiliki data perawatan.</p>
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- KOLOM KANAN (RINGKASAN) --}}
        {{-- ================================================= --}}
        <div class="col-lg-4">

            <div class="card-elite mb-4 sticky-summary-elite">

                <div class="summary-header-elite">
                    <i class="fas fa-receipt mr-2"></i>
                    Ringkasan Reservasi
                </div>

                <div class="card-body p-4">

                    {{-- PELANGGAN --}}
                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar-elite">
                            {{ $namaPelanggan ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($namaPelanggan, 0, 1)) : '?' }}
                        </div>

                        <div>
                            <div class="cell-title-elite">{{ $namaPelanggan ?? '-' }}</div>
                            @if($riwayat->pelanggan)
                                <small class="text-muted-elite">ID #{{ $riwayat->pelanggan->id }}</small>
                            @endif
                        </div>

                    </div>

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <small class="text-muted-elite d-block mb-1">Status Reservasi</small>
                        <span class="badge-pill-elite {{ $statusBadgeClass }}">
                            <i class="fas {{ $statusIcon }} mr-1"></i>
                            {{ $riwayat->status }}
                        </span>
                    </div>

                    {{-- JADWAL --}}
                    <div class="mb-4">
                        <small class="text-muted-elite d-block mb-1">Jadwal</small>
                        <span class="time-pill-elite">
                            <i class="fas fa-clock mr-1"></i>
                            {{ substr($riwayat->jam_mulai, 0, 5) }}
                            -
                            {{ substr($riwayat->jam_selesai, 0, 5) }}
                        </span>
                    </div>

                    {{-- TOTAL --}}
                    <div class="summary-card-elite">

                        <div class="summary-row-elite">
                            <span>Total Perawatan</span>
                            <strong>{{ $totalJumlah }} item</strong>
                        </div>

                        <div class="summary-row-elite">
                            <span>Total Durasi</span>
                            <strong>{{ $totalDurasi }} menit</strong>
                        </div>

                        <div class="summary-row-elite total">
                            <span>Total Harga</span>
                            <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                        </div>

                    </div>

                    <a href="{{ route('riwayat-reservasi.index') }}"
                       class="btn btn-gradient-elite btn-block mt-4 btn-filter-elite d-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Riwayat
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.riwayat-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.text-muted-elite { color: #a09a94; }
.text-red-elite   { color: var(--sb-red); }

/* Bootstrap 4 tidak punya utilitas gap-*, jadi didefinisikan manual */
.riwayat-elite .gap-2 { gap: 8px; }
.riwayat-elite .gap-3 { gap: 16px; }


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

.btn-back-elite {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    margin-left: 32px;
    padding: 10px 22px;
    border-radius: 12px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.28);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all .25s ease;
}
.btn-back-elite:hover {
    background: rgba(255,255,255,.22);
    color: #ffffff;
    text-decoration: none;
    transform: translateY(-1px);
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

.table-card-header-elite {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(217,172,79,.15);
    background: #fffdf9;
}


/* =========================================================
   SECTION HEAD
========================================================== */

.section-head-elite {
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-icon-elite {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.icon-maroon-elite.section-icon-elite { background: #fff0f0; color: var(--sb-red); }

.section-title-elite {
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 1px;
    font-size: 14.5px;
}
.section-subtitle-elite {
    color: #a09a94;
    font-size: 11.5px;
}


/* =========================================================
   INFO BOX
========================================================== */

.form-label-elite {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #5a4a44;
    margin-bottom: 6px;
}

.info-box-elite {
    display: flex;
    align-items: stretch;
    border: 1px solid #ecd9bf;
    border-radius: 10px;
    overflow: hidden;
    background: #fffdf9;
}

.info-icon-elite {
    width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 13px;
    flex-shrink: 0;
}

.info-value-elite {
    flex: 1;
    padding: 0.65rem 0.85rem;
    font-size: 13.5px;
    font-weight: 600;
    color: #3a2a26;
    min-width: 0;
    word-break: break-word;
}

.info-box-note-elite { min-height: 80px; }
.info-box-note-elite .info-icon-elite { align-items: flex-start; padding-top: 14px; }
.info-box-note-elite .info-value-elite { font-weight: 500; color: #5a4a44; }


/* =========================================================
   TREATMENT
========================================================== */

.treatment-card-elite {
    display: flex;
    align-items: center;
    gap: 14px;
    height: 100%;
    padding: 14px;
    border: 1px solid rgba(217,172,79,.2);
    border-radius: 14px;
    background: #fffdf9;
    transition: all .25s ease;
}
.treatment-card-elite:hover {
    transform: translateY(-2px);
    border-color: rgba(217,172,79,.45);
    box-shadow: 0 10px 24px rgba(124,16,19,.08);
}

.treatment-image-elite {
    width: 70px; height: 70px;
    flex-shrink: 0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff0f0;
    color: var(--sb-red);
    font-size: 25px;
}
.treatment-image-elite img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.treatment-content-elite {
    flex: 1;
    min-width: 0;
}

.treatment-price-elite {
    font-weight: 700;
    font-size: 14px;
    color: var(--sb-red);
}

.treatment-subtotal-elite {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px dashed rgba(217,172,79,.35);
    font-size: 11px;
    color: #a09a94;
}
.treatment-subtotal-elite strong { color: #5a4a44; }
.treatment-subtotal-elite strong.text-red-elite { color: var(--sb-red); }

.cell-title-elite {
    font-weight: 700;
    color: #3a2a26;
    font-size: 13px;
}


/* =========================================================
   SUMMARY
========================================================== */

.sticky-summary-elite {
    position: sticky;
    top: 20px;
}

.summary-header-elite {
    padding: 16px 24px;
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    background:
        radial-gradient(circle at 12% 0%, rgba(217, 172, 79, .22) 0%, transparent 50%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
}

.summary-card-elite {
    padding: 14px 16px;
    border-radius: 14px;
    background: #fff8f2;
    border: 1px solid rgba(217,172,79,.25);
}

.summary-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    font-size: 13px;
}
.summary-row-elite span   { color: #a09a94; }
.summary-row-elite strong { color: #3a2a26; }

.summary-row-elite.total {
    margin-top: 5px;
    padding-top: 12px;
    border-top: 1px dashed rgba(217,172,79,.45);
}
.summary-row-elite.total span   { color: #5a4a44; font-weight: 700; }
.summary-row-elite.total strong { color: var(--sb-red); font-size: 18px; }


/* =========================================================
   AVATAR & PILL
========================================================== */

.avatar-elite {
    width: 42px; height: 42px;
    min-width: 42px;
    margin-right: 12px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    box-shadow: 0 4px 12px rgba(224,28,29,.22);
}

.time-pill-elite {
    display: inline-flex;
    align-items: center;
    padding: 5px 11px;
    border-radius: 20px;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}
.time-pill-elite i { color: var(--sb-red); }


/* =========================================================
   BADGE
========================================================== */

.badge-pill-elite {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-maroon-elite    { background: #fff0f0; color: var(--sb-red); }
.badge-active-elite    { background: #e8f8f1; color: #1cc88a; }
.badge-warning-elite   { background: #fdf3e2; color: #a9781f; }
.badge-info-elite      { background: #e8f0fe; color: #3b6fd4; }
.badge-secondary-elite { background: #f4efe9; color: #8a7d74; }


/* =========================================================
   BUTTON
========================================================== */

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

.btn-filter-elite { height: 44px; }


/* =========================================================
   EMPTY STATE
========================================================== */

.empty-state-elite { padding: 30px 0; text-align: center; }

.empty-icon-elite {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #fff0f0;
    color: var(--sb-red);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 28px;
}
.empty-state-elite h6 {
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 4px;
}
.empty-state-elite p {
    margin: 0;
    color: #a09a94;
    font-size: 12.5px;
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .sticky-summary-elite { position: static; }
}

@media (max-width: 767px) {
    .form-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }
    .btn-back-elite {
        align-self: stretch;
        justify-content: center;
        margin-left: 0;
        margin-top: 12px;
    }
    .table-card-header-elite { padding: 16px; }
}

@media (max-width: 575px) {
    .treatment-card-elite { align-items: flex-start; }
    .treatment-image-elite {
        width: 55px; height: 55px;
        font-size: 20px;
    }
    .treatment-subtotal-elite {
        flex-direction: column;
        align-items: flex-start;
    }
}

</style>

@endsection