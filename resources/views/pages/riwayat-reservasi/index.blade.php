@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@section('content')

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
                    <h4 class="fw-bold mb-1 text-white">Riwayat Reservasi</h4>
                    <p class="text-white-50 mb-0 small">
                        Riwayat reservasi pelanggan Azzahra Salon dan Spa Muslimah
                    </p>
                </div>
            </div>

            <div class="header-total-elite">
                <div class="header-total-value-elite">{{ $riwayats->total() }}</div>
                <small class="header-total-label-elite">Total Riwayat</small>
            </div>

        </div>

    </div>


    {{-- ================= ALERT ================= --}}
    @if (session('success'))
        <div class="alert-elite alert-success-elite mb-4" role="alert">
            <i class="fas fa-check-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-elite alert-danger-elite mb-4" role="alert">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif


    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="row mb-2">

        {{-- TOTAL RIWAYAT --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-maroon-elite">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $totalRiwayat }}</div>
                    <div class="mini-stat-label-elite">Total Riwayat</div>
                </div>
            </div>
        </div>

        {{-- MENUNGGU --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-gold-elite">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $totalMenunggu }}</div>
                    <div class="mini-stat-label-elite">Reservasi Menunggu</div>
                </div>
            </div>
        </div>

        {{-- DIKONFIRMASI --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-info-elite">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $totalDikonfirmasi }}</div>
                    <div class="mini-stat-label-elite">Dikonfirmasi</div>
                </div>
            </div>
        </div>

        {{-- SELESAI --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-success-elite">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $totalSelesai }}</div>
                    <div class="mini-stat-label-elite">Reservasi Selesai</div>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= FILTER ================= --}}
    <div class="card-elite mb-4">

        <div class="card-body p-4">

            <div class="section-head-elite mb-3">
                <div class="section-icon-elite icon-maroon-elite">
                    <i class="fas fa-filter"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Filter Riwayat Reservasi</h6>
                    <small class="section-subtitle-elite">Cari berdasarkan nama, status, atau tanggal</small>
                </div>
            </div>

            <form action="{{ route('riwayat-reservasi.index') }}" method="GET" class="mt-3">

                <div class="row">

                    {{-- SEARCH --}}
                    <div class="col-md-4 mb-3">

                        <label for="keyword" class="form-label-elite">Cari</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="keyword"
                                   id="keyword"
                                   class="form-control-elite"
                                   placeholder="Nama pelanggan, perawatan, pegawai..."
                                   value="{{ request('keyword') }}">
                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-3 mb-3">

                        <label for="status" class="form-label-elite">Status</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-toggle-on"></i>
                            </span>

                            <select name="status" id="status" class="form-control-elite">

                                <option value="">Semua Status</option>

                                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>
                                    Menunggu
                                </option>

                                <option value="Dikonfirmasi" {{ request('status') == 'Dikonfirmasi' ? 'selected' : '' }}>
                                    Dikonfirmasi
                                </option>

                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>

                            </select>
                        </div>

                    </div>


                    {{-- TANGGAL --}}
                    <div class="col-md-3 mb-3">

                        <label for="tanggal" class="form-label-elite">Tanggal</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-calendar-alt"></i>
                            </span>

                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="form-control-elite"
                                   value="{{ request('tanggal') }}">
                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-md-2 mb-3 d-flex align-items-end">

                        <button type="submit" class="btn btn-gradient-elite btn-block btn-filter-elite">
                            <i class="fas fa-search mr-1"></i>
                            Cari
                        </button>

                    </div>

                </div>

                @if(request()->hasAny(['keyword', 'status', 'tanggal']) && (request('keyword') || request('status') || request('tanggal')))
                    <a href="{{ route('riwayat-reservasi.index') }}" class="reset-filter-elite">
                        <i class="fas fa-undo mr-1"></i> Reset filter
                    </a>
                @endif

            </form>

        </div>

    </div>


    {{-- ================= TIMELINE RIWAYAT ================= --}}
    <div class="card-elite mb-4">

        {{-- HEADER --}}
        <div class="table-card-header-elite">

            <div class="section-head-elite">
                <div class="section-icon-elite icon-maroon-elite">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Daftar Riwayat Reservasi</h6>
                    <small class="section-subtitle-elite">Seluruh data reservasi yang terdaftar pada sistem</small>
                </div>
            </div>

            <span class="badge-pill-elite badge-maroon-elite">
                {{ $riwayats->total() }} Data
            </span>

        </div>


        <div class="card-body p-4">

            <div class="riwayat-scroll-elite">

                @forelse ($riwayats as $item)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | PERAWATAN
                        |--------------------------------------------------------------------------
                        */

                        $perawatanList = collect();

                        if (
                            isset($item->perawatans) &&
                            $item->perawatans->count() > 0
                        ) {

                            $perawatanList = $item->perawatans;

                        } elseif ($item->perawatan) {

                            /*
                            | Fallback untuk data reservasi lama
                            */

                            $perawatanList = collect([
                                $item->perawatan
                            ]);

                        }

                        $namaPelanggan = optional($item->pelanggan)->nama;

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS -> ICON & WARNA TIMELINE
                        |--------------------------------------------------------------------------
                        */

                        $statusBadgeClass = match($item->status) {
                            'Menunggu'     => 'badge-warning-elite',
                            'Dikonfirmasi' => 'badge-info-elite',
                            'Selesai'      => 'badge-active-elite',
                            'Dibatalkan'   => 'badge-maroon-elite',
                            default        => 'badge-secondary-elite',
                        };

                        $statusIconClass = match($item->status) {
                            'Menunggu'     => 'timeline-icon-gold-elite',
                            'Dikonfirmasi' => 'timeline-icon-info-elite',
                            'Selesai'      => 'timeline-icon-success-elite',
                            'Dibatalkan'   => 'timeline-icon-maroon-elite',
                            default        => 'timeline-icon-secondary-elite',
                        };

                        $statusIcon = match($item->status) {
                            'Menunggu'     => 'fa-clock',
                            'Dikonfirmasi' => 'fa-check',
                            'Selesai'      => 'fa-check-circle',
                            'Dibatalkan'   => 'fa-times-circle',
                            default        => 'fa-history',
                        };

                    @endphp

                    <div class="timeline-item-elite">

                        <div class="timeline-icon-elite {{ $statusIconClass }}">
                            <i class="fas {{ $statusIcon }}"></i>
                        </div>

                        <div class="timeline-content-elite">

                            <div class="activity-card-elite">

                                <div class="row align-items-center">

                                    {{-- PELANGGAN --}}
                                    <div class="col-lg-3 mb-2 mb-lg-0">

                                        <div class="d-flex align-items-center">

                                            <div class="avatar-elite">
                                                {{ $namaPelanggan ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($namaPelanggan, 0, 1)) : '?' }}
                                            </div>

                                            <div>
                                                <div class="cell-title-elite">
                                                    {{ $namaPelanggan ?? '-' }}
                                                </div>

                                                @if ($item->pelanggan)
                                                    <small class="text-muted-elite">
                                                        ID #{{ $item->pelanggan->id }}
                                                    </small>
                                                @endif
                                            </div>

                                        </div>

                                    </div>


                                    {{-- STATUS --}}
                                    <div class="col-lg-2 mb-2 mb-lg-0">

                                        <span class="badge-pill-elite {{ $statusBadgeClass }}">
                                            <i class="fas {{ $statusIcon }} mr-1"></i>
                                            {{ $item->status }}
                                        </span>

                                    </div>


                                    {{-- PEGAWAI --}}
                                    <div class="col-lg-2 mb-2 mb-lg-0">

                                        <small class="text-muted-elite d-block">Terapis</small>
                                        <span class="cell-text-elite">
                                            {{ optional($item->pegawai)->nama ?? '-' }}
                                        </span>

                                    </div>


                                    {{-- TANGGAL --}}
                                    <div class="col-lg-2 mb-2 mb-lg-0">

                                        <small class="text-muted-elite d-block">Tanggal</small>
                                        <span class="cell-text-elite">
                                            @if ($item->tanggal)
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </span>

                                    </div>


                                    {{-- JAM --}}
                                    <div class="col-lg-2 mb-2 mb-lg-0 text-lg-right">

                                        <span class="time-pill-elite">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ substr($item->jam_mulai, 0, 5) }}
                                            -
                                            {{ substr($item->jam_selesai, 0, 5) }}
                                        </span>

                                    </div>


                                    {{-- AKSI --}}
                                    <div class="col-lg-1 text-lg-right">

                                        <a href="{{ route('riwayat-reservasi.show', $item->id) }}"
                                           class="btn-action-elite"
                                           title="Detail"
                                           aria-label="Lihat detail reservasi">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                    </div>

                                </div>


                                {{-- PERAWATAN --}}
                                @if($perawatanList->count() > 0)

                                    <hr class="activity-divider-elite">

                                    <div class="d-flex flex-wrap gap-2">

                                        @foreach($perawatanList as $perawatan)

                                            @php

                                                $jumlah =
                                                    isset($perawatan->pivot)
                                                        ? (int)($perawatan->pivot->jumlah ?? 1)
                                                        : 1;

                                                $durasi =
                                                    isset($perawatan->pivot)
                                                        ? (int)($perawatan->pivot->durasi ?? $perawatan->durasi)
                                                        : (int)$perawatan->durasi;

                                            @endphp

                                            <span class="treatment-chip-elite">
                                                <i class="fas fa-spa mr-1"></i>
                                                {{ $perawatan->nama }}

                                                @if($jumlah > 1)
                                                    <span class="treatment-chip-qty-elite">{{ $jumlah }}x</span>
                                                @endif

                                                <span class="treatment-chip-duration-elite">
                                                    <i class="far fa-clock mr-1"></i>{{ $durasi }} menit
                                                </span>
                                            </span>

                                        @endforeach

                                    </div>

                                @endif


                                {{-- WAKTU LENGKAP --}}
                                @if ($item->tanggal)
                                    <small class="text-muted-elite d-block mt-3">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                    </small>
                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    {{-- EMPTY --}}
                    <div class="empty-state-elite">

                        <div class="empty-icon-elite">
                            <i class="fas fa-history"></i>
                        </div>

                        <h6>Belum Ada Riwayat Reservasi</h6>

                        <p>Belum terdapat data reservasi pada sistem.</p>

                    </div>

                @endforelse

            </div>


            {{-- ================= PAGINATION ================= --}}
            @if ($riwayats->total() > 0)

                <div class="table-footer-elite riwayat-footer-elite">

                    <div class="text-muted-elite small">
                        Menampilkan
                        {{ $riwayats->firstItem() }}
                        -
                        {{ $riwayats->lastItem() }}
                        dari
                        {{ $riwayats->total() }}
                        data
                    </div>

                    @if ($riwayats->hasPages())
                        <div class="pagination-elite">
                            {{ $riwayats->links() }}
                        </div>
                    @endif

                </div>

            @endif

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

.header-total-elite {
    position: relative;
    z-index: 2;
    text-align: center;
    min-width: 140px;
    margin-left: 32px;
    padding: 12px 28px;
    border-radius: 14px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.22);
    backdrop-filter: blur(4px);
}

.header-total-value-elite {
    font-size: 24px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.1;
}

.header-total-label-elite {
    color: rgba(255,255,255,.75);
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: .4px;
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
.alert-success-elite { background: #eefaf3; border: 1px solid rgba(28,200,138,.25); }
.alert-success-elite > i { color: #1cc88a; }
.alert-danger-elite  { background: #fff0f0; border: 1px solid rgba(224,28,29,.2); }
.alert-danger-elite > i { color: var(--sb-red); }

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


/* =========================================================
   MINI STAT
========================================================== */

.mini-stat-elite {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 16px;
    padding: 18px;
    height: 100%;
    box-shadow: 0 6px 22px rgba(124,16,19,.06);
    transition: all .25s ease;
}
.mini-stat-elite:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 26px rgba(124,16,19,.1);
}

.mini-stat-icon-elite {
    width: 48px; height: 48px;
    min-width: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.icon-maroon-elite.mini-stat-icon-elite  { background: #fff0f0; color: var(--sb-red); }
.icon-gold-elite.mini-stat-icon-elite    { background: #fdf3e2; color: #a9781f; }
.icon-info-elite.mini-stat-icon-elite    { background: #e8f0fe; color: #3b6fd4; }
.icon-success-elite.mini-stat-icon-elite { background: #e8f8f1; color: #1cc88a; }

.mini-stat-value-elite {
    font-weight: 700;
    font-size: 22px;
    color: #3a2a26;
    line-height: 1.15;
}
.mini-stat-label-elite {
    font-size: 10.5px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-top: 2px;
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
   FORM FILTER
========================================================== */

.form-label-elite {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #5a4a44;
    margin-bottom: 6px;
}

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

.btn-filter-elite { height: 44px; }

.reset-filter-elite {
    display: inline-flex;
    align-items: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--sb-red-dark);
    text-decoration: none;
}
.reset-filter-elite:hover { color: var(--sb-red); text-decoration: underline; }


/* =========================================================
   TABLE-CARD HEADER (dipakai sebagai header daftar)
========================================================== */

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
   TIMELINE
========================================================== */

.riwayat-scroll-elite {
    max-height: 650px;
    overflow-y: auto;
    padding-right: 8px;
}

.riwayat-scroll-elite::-webkit-scrollbar {
    width: 7px;
}
.riwayat-scroll-elite::-webkit-scrollbar-thumb {
    background: var(--sb-gold);
    border-radius: 20px;
}
.riwayat-scroll-elite::-webkit-scrollbar-track {
    background: #fdf6ea;
}

.timeline-item-elite {
    position: relative;
    padding-left: 70px;
    margin-bottom: 22px;
}
.timeline-item-elite:last-child {
    margin-bottom: 0;
}

.timeline-item-elite::before {
    content: '';
    position: absolute;
    left: 24px;
    top: 0;
    bottom: -22px;
    width: 2px;
    background: rgba(217,172,79,.25);
}
.timeline-item-elite:last-child::before {
    display: none;
}

.timeline-icon-elite {
    position: absolute;
    left: 4px;
    top: 14px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 15px;
    z-index: 2;
    box-shadow: 0 6px 14px rgba(0,0,0,.14);
}

.timeline-icon-gold-elite      { background: linear-gradient(135deg, #f1cf85, var(--sb-gold)); color: #5a3f10; }
.timeline-icon-info-elite      { background: linear-gradient(135deg, #6f9af0, #3b6fd4); }
.timeline-icon-success-elite   { background: linear-gradient(135deg, #35d6a0, #0f9e6e); }
.timeline-icon-maroon-elite    { background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark)); }
.timeline-icon-secondary-elite { background: linear-gradient(135deg, #a7a39d, #857e74); }

.activity-card-elite {
    background: #fffdf9;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 14px;
    padding: 18px 20px;
    transition: all .25s ease;
}
.activity-card-elite:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(124,16,19,.08);
    border-color: rgba(217,172,79,.3);
}

.activity-divider-elite {
    border: none;
    border-top: 1px dashed rgba(217,172,79,.3);
    margin: 14px 0;
}

.cell-title-elite {
    font-weight: 700;
    color: #3a2a26;
    font-size: 13px;
}

.cell-text-elite {
    color: #5a4a44;
    font-weight: 600;
    font-size: 12.5px;
    display: block;
}

.avatar-elite {
    width: 38px; height: 38px;
    min-width: 38px;
    margin-right: 12px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
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
   TREATMENT CHIP
========================================================== */

.treatment-chip-elite {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #fff0f0;
    color: var(--sb-red-dark);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.treatment-chip-qty-elite {
    background: rgba(255,255,255,.7);
    border-radius: 999px;
    padding: 1px 7px;
    font-size: 10px;
}

.treatment-chip-duration-elite {
    color: #a09a94;
    font-weight: 500;
    font-size: 10.5px;
}


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
   ACTION BUTTON
========================================================== */

.btn-action-elite {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff0f0;
    color: var(--sb-red);
    border: 1px solid rgba(224,28,29,.15);
    text-decoration: none;
    transition: all .25s ease;
}
.btn-action-elite:hover {
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    border-color: transparent;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(224,28,29,.3);
}


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


/* =========================================================
   EMPTY STATE
========================================================== */

.empty-state-elite { padding: 40px 0; text-align: center; }

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
   FOOTER & PAGINATION
========================================================== */

.riwayat-footer-elite {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 18px 2px 2px;
    margin-top: 10px;
    border-top: 1px solid rgba(217,172,79,.15);
}

.pagination-elite .pagination {
    margin: 0;
    gap: 4px;
}
.pagination-elite .page-link {
    border: 1px solid #ecd9bf;
    border-radius: 8px !important;
    color: var(--sb-red-dark);
    font-size: 12.5px;
    font-weight: 600;
    background: #fff;
    padding: .4rem .75rem;
    box-shadow: none;
}
.pagination-elite .page-link:hover {
    background: rgba(217,172,79,.12);
    border-color: var(--sb-gold);
    color: var(--sb-red-dark);
}
.pagination-elite .page-item.active .page-link {
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    border-color: transparent;
    color: #fff;
}
.pagination-elite .page-item.disabled .page-link {
    background: #faf6ee;
    color: #c9b9a8;
    border-color: #f0e4cf;
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .timeline-item-elite { padding-left: 58px; }
    .timeline-icon-elite { left: 0; }
    .timeline-item-elite::before { left: 19px; }
}

@media (max-width: 767px) {
    .form-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }
    .header-total-elite {
        align-self: stretch;
        text-align: left;
        margin-left: 0;
        margin-top: 12px;
    }
    .table-card-header-elite {
        padding: 16px;
    }
    .btn-filter-elite { height: auto; padding: .6rem 1rem; }
}

</style>

@endsection