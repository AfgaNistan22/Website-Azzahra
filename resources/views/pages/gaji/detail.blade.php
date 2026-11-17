@extends('layouts.app')

@section('title', 'Detail Gaji')

@section('content')

<div class="container-fluid px-4 detail-gaji-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('gaji.index') }}">Data Gaji</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Gaji</h4>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('gaji.edit', $data->id) }}" class="btn btn-edit-elite px-3 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            <a href="{{ route('gaji.index') }}" class="btn btn-outline-elite px-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

        </div>

    </div>


    <div class="row">

        {{-- ================= LEFT: PROFILE CARD (STICKY) ================= --}}
        <div class="col-lg-4 mb-4">

            <div class="profile-card-sticky-elite">

                <div class="profile-card-elite">

                    <div class="profile-cover-mini-elite">
                        <div class="cover-ornament-1"></div>
                        <div class="cover-ornament-2"></div>
                    </div>

                    <div class="px-4 photo-wrap-square-elite">
                        <div class="salary-icon-square-elite">
                            <i class="fas fa-user-tie"></i>
                            <span>Data Pegawai</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">

                        <h5 class="fw-bold mb-1 title-elite">{{ $data->pegawai->nama ?? '-' }}</h5>

                        <div class="description-text-elite mb-3">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $data->pegawai->jabatan ?? 'Pegawai' }}
                        </div>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                            <span class="badge-pill-elite badge-info-elite">
                                {{ $data->nama_periode }}
                            </span>

                            @if($data->status === 'Dibayar')
                                <span class="badge-pill-elite badge-active-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Dibayar
                                </span>
                            @else
                                <span class="badge-pill-elite badge-warning-elite">
                                    <i class="fas fa-clock mr-1"></i> Belum Dibayar
                                </span>
                            @endif

                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Total Gaji Diterima</span>
                            <span class="total-highlight-elite">
                                Rp {{ number_format($data->total_gaji, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>

                </div>


                {{-- MINI SYSTEM INFO --}}
                <div class="system-info-mini-elite mt-3">

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="sys-label-elite"><i class="fas fa-plus-circle mr-1"></i> Dibuat</span>
                        <span class="sys-value-elite">
                            {{ $data->created_at ? $data->created_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top-mini-elite">
                        <span class="sys-label-elite"><i class="fas fa-sync-alt mr-1"></i> Diperbarui</span>
                        <span class="sys-value-elite">
                            {{ $data->updated_at ? $data->updated_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>

                </div>

            </div>

        </div>


        {{-- ================= RIGHT: DETAIL SECTIONS ================= --}}
        <div class="col-lg-8">

            {{-- STAT ROW --}}
            <div class="row mb-4">

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-maroon-elite">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                {{ $data->nama_periode }}
                            </div>
                            <div class="mini-stat-label-elite">Periode</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-gold-elite">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                {{ $data->status === 'Dibayar' ? 'Dibayar' : 'Belum Dibayar' }}
                            </div>
                            <div class="mini-stat-label-elite">Status Pembayaran</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-success-elite">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($data->total_gaji, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Total Gaji</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- RINCIAN GAJI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Rincian Gaji</h6>
                            <small class="section-subtitle-elite">
                                Komponen perhitungan gaji periode {{ $data->nama_periode }}
                            </small>
                        </div>
                    </div>

                    <div class="gaji-summary-elite mt-3">

                        <div class="summary-row-elite">
                            <span>Gaji Pokok</span>
                            <strong>Rp {{ number_format($data->gaji_pokok, 0, ',', '.') }}</strong>
                        </div>

                        <div class="summary-row-elite">
                            <span>Tunjangan</span>
                            <strong class="text-success-elite">
                                + Rp {{ number_format($data->tunjangan, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="summary-row-elite">
                            <span>Potongan</span>
                            <strong class="text-maroon-value-elite">
                                - Rp {{ number_format($data->potongan, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="summary-row-elite summary-total-elite">
                            <span>Total Gaji Diterima</span>
                            <strong>Rp {{ number_format($data->total_gaji, 0, ',', '.') }}</strong>
                        </div>

                    </div>

                </div>

            </div>


            {{-- INFORMASI PEMBAYARAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Pembayaran</h6>
                            <small class="section-subtitle-elite">Status dan informasi pembayaran gaji</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Status</span>
                            <span class="detail-value-elite">
                                @if($data->status === 'Dibayar')
                                    <span class="badge-pill-elite badge-active-elite">
                                        <i class="fas fa-check-circle mr-1"></i> Dibayar
                                    </span>
                                @else
                                    <span class="badge-pill-elite badge-warning-elite">
                                        <i class="fas fa-clock mr-1"></i> Belum Dibayar
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Tanggal Bayar</span>
                            <span class="detail-value-elite">
                                {{ $data->tanggal_bayar
                                    ? $data->tanggal_bayar->translatedFormat('d F Y')
                                    : '-' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- CATATAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Catatan</h6>
                            <small class="section-subtitle-elite">Catatan tambahan terkait penggajian</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">
                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Catatan</span>
                            <span class="detail-value-elite">
                                {{ $data->catatan ?: 'Tidak ada catatan.' }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>


            {{-- INFORMASI SISTEM --}}
            <div class="card-elite">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Sistem</h6>
                            <small class="section-subtitle-elite">Informasi pencatatan data gaji</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite detail-grid-3-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">ID Gaji</span>
                            <span class="detail-value-elite">#{{ $data->id }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Data Dibuat</span>
                            <span class="detail-value-elite">
                                {{ $data->created_at
                                    ? $data->created_at->translatedFormat('d F Y, H:i')
                                    : '-' }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Terakhir Diperbarui</span>
                            <span class="detail-value-elite">
                                {{ $data->updated_at
                                    ? $data->updated_at->translatedFormat('d F Y, H:i')
                                    : '-' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.detail-gaji-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.title-elite { color: #3a2a26; }
.text-muted-elite { color: #a09a94; }
.text-success-elite { color: #12865d; }
.text-maroon-value-elite { color: var(--sb-red); }


/* =========================================================
   PAGE HEADER
========================================================== */

.page-header-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.breadcrumb-elite {
    font-size: 12px;
    color: #a09a94;
}
.breadcrumb-elite a {
    color: #a09a94;
    text-decoration: none;
}
.breadcrumb-elite a:hover { color: var(--sb-red-dark); }
.breadcrumb-elite i {
    font-size: 9px;
    margin: 0 6px;
    color: #d9ac4f;
}
.breadcrumb-elite span { color: var(--sb-red-dark); font-weight: 600; }


/* =========================================================
   STICKY WRAPPER
========================================================== */

.profile-card-sticky-elite {
    position: sticky;
    top: 20px;
}


/* =========================================================
   PROFILE CARD
========================================================== */

.profile-card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 18px;
    box-shadow: 0 8px 28px rgba(124,16,19,.08);
    overflow: hidden;
}

.profile-cover-mini-elite {
    position: relative;
    z-index: 1;
    height: 70px;
    overflow: hidden;
    background:
        radial-gradient(circle at 15% 0%, rgba(217, 172, 79, .22) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
}
.cover-ornament-1 {
    position: absolute;
    width: 160px; height: 160px;
    top: -100px; right: -40px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 172, 79, .2) 0%, transparent 70%);
}
.cover-ornament-2 {
    position: absolute;
    width: 110px; height: 110px;
    bottom: -75px; right: 30px;
    border-radius: 50%;
    border: 1px solid rgba(217, 172, 79, .25);
}

.photo-wrap-square-elite {
    position: relative;
    z-index: 5;
    margin-top: -45px;
}

.salary-icon-square-elite {
    width: 100%;
    height: 130px;
    border-radius: 14px;
    background: #fff0f0;
    border: 5px solid #fff;
    box-shadow: 0 6px 18px rgba(124,16,19,.2);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: var(--sb-red);
    font-size: 38px;
}
.salary-icon-square-elite span {
    font-size: 12px;
    color: #a09a94;
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

.badge-active-elite  { background: #e8f8f1; color: #1cc88a; }
.badge-warning-elite { background: #fdf3e2; color: #a9781f; }
.badge-info-elite    { background: #e8f0fe; color: #3b6fd4; }


/* =========================================================
   QUICK CONTACT / DESCRIPTION
========================================================== */

.quick-contact-elite {
    border-top: 1px dashed rgba(217,172,79,.3);
    padding-top: 14px;
}

.detail-label-elite {
    font-size: 11px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.description-text-elite {
    font-size: 13px;
    color: #5a4a44;
    line-height: 1.6;
}

.total-highlight-elite {
    display: block;
    font-size: 19px;
    font-weight: 800;
    color: var(--sb-red);
}


/* =========================================================
   MINI SYSTEM INFO
========================================================== */

.system-info-mini-elite {
    background: #fdfaf5;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 14px;
    padding: 6px 16px;
}

.sys-label-elite {
    font-size: 11.5px;
    color: #a09a94;
}
.sys-value-elite {
    font-size: 11.5px;
    font-weight: 600;
    color: #5a4a44;
}
.border-top-mini-elite {
    border-top: 1px solid rgba(217,172,79,.15);
}


/* =========================================================
   MINI STAT
========================================================== */

.mini-stat-elite {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 14px;
    padding: 14px;
    height: 100%;
    box-shadow: 0 4px 14px rgba(124,16,19,.05);
}

.mini-stat-icon-elite {
    width: 42px; height: 42px;
    min-width: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.icon-maroon-elite.mini-stat-icon-elite  { background: #fff0f0; color: var(--sb-red); }
.icon-success-elite.mini-stat-icon-elite { background: #e8f8f1; color: #1cc88a; }
.icon-gold-elite.mini-stat-icon-elite    { background: #fdf3e2; color: #a9781f; }

.mini-stat-value-elite {
    font-weight: 700;
    font-size: 18px;
    color: #3a2a26;
    line-height: 1.15;
}
.mini-stat-small-elite {
    font-size: 13px;
}
.mini-stat-label-elite {
    font-size: 10.5px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
}


/* =========================================================
   CARD
========================================================== */

.card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 16px;
    box-shadow: 0 6px 22px rgba(124,16,19,.06);
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
   DETAIL GRID
========================================================== */

.detail-grid-elite {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 12px;
}
.detail-grid-3-elite {
    grid-template-columns: repeat(3, 1fr);
}

.detail-cell-elite {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
    background: #fdfaf5;
    border: 1px solid rgba(217,172,79,.12);
    border-radius: 10px;
}
.detail-cell-full-elite {
    grid-column: 1 / -1;
}

.detail-value-elite {
    font-size: 13.5px;
    font-weight: 600;
    color: #3a2a26;
}


/* =========================================================
   RINCIAN GAJI (SUMMARY)
========================================================== */

.gaji-summary-elite {
    padding: 13px 16px;
    background: #fdf6ea;
    border-radius: 12px;
    border: 1px solid rgba(217,172,79,.3);
}

.summary-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    font-size: 13px;
}
.summary-row-elite span   { color: #8a6a2f; }
.summary-row-elite strong { color: #3a2a26; }

.summary-total-elite {
    margin-top: 5px;
    padding-top: 10px;
    border-top: 1px solid rgba(217,172,79,.35);
    font-size: 15px;
}
.summary-total-elite strong {
    color: var(--sb-red);
    font-size: 18px;
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

.btn-edit-elite {
    border-radius: 10px;
    font-weight: 600;
    border: none;
    color: #fff;
    background: linear-gradient(135deg, #f0ad4e, #d9ac4f);
    box-shadow: 0 4px 14px rgba(217,172,79,.35);
    transition: all .25s ease;
}
.btn-edit-elite:hover {
    color: #fff;
    filter: brightness(1.05);
    transform: translateY(-1px);
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .profile-card-sticky-elite {
        position: static;
    }
    .page-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }
    .page-header-elite .d-flex {
        width: 100%;
        flex-wrap: wrap;
    }
    .page-header-elite .btn {
        flex: 1;
    }
}

@media (max-width: 576px) {
    .detail-grid-elite,
    .detail-grid-3-elite {
        grid-template-columns: 1fr;
    }
}

</style>

@endsection