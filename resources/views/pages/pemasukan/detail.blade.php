@extends('layouts.app')

@section('title', 'Detail Pemasukan')

@section('content')

<div class="container-fluid px-4 detail-masuk-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('pemasukan.index') }}">Pemasukan</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Pemasukan</h4>
        </div>

        <div class="d-flex gap-2">

            @if($data->sumber_jenis === 'Manual')
                <a href="{{ route('pemasukan.edit', $data) }}" class="btn btn-edit-elite px-3 mr-2">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endif

            <a href="{{ route('pemasukan.index') }}" class="btn btn-outline-elite px-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

        </div>

    </div>


    @if(session('warning'))
        <div class="alert-elite alert-warning-elite mb-4">
            <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
            <div class="alert-body-elite">{{ session('warning') }}</div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif


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
                        <div class="tax-icon-square-elite">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>Pemasukan</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">

                        <h5 class="fw-bold mb-3 title-elite">{{ $data->kode_pemasukan }}</h5>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                            @if($data->sumber_jenis === 'Reservasi')
                                <span class="badge-pill-elite badge-active-elite">
                                    <i class="fas fa-calendar-check mr-1"></i> {{ $data->sumber_jenis }}
                                </span>
                            @else
                                <span class="badge-pill-elite badge-info-elite">
                                    <i class="fas fa-pen mr-1"></i> {{ $data->sumber_jenis }}
                                </span>
                            @endif

                            @if($data->status === 'Diterima')
                                <span class="badge-pill-elite badge-active-elite ml-1">
                                    <i class="fas fa-check-circle mr-1"></i> {{ $data->status }}
                                </span>
                            @else
                                <span class="badge-pill-elite badge-secondary-elite ml-1">
                                    <i class="fas fa-ban mr-1"></i> {{ $data->status }}
                                </span>
                            @endif

                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Nominal</span>
                            <span class="total-highlight-elite">
                                Rp {{ number_format($data->nominal, 0, ',', '.') }}
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
                                {{ $data->tanggal?->translatedFormat('d M Y') ?? '-' }}
                            </div>
                            <div class="mini-stat-label-elite">Tanggal</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite {{ $data->status === 'Diterima' ? 'icon-success-elite' : 'icon-gold-elite' }}">
                            <i class="fas {{ $data->status === 'Diterima' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">{{ $data->status }}</div>
                            <div class="mini-stat-label-elite">Status</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-info-elite">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($data->nominal, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Nominal</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- INFORMASI PEMASUKAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Pemasukan</h6>
                            <small class="section-subtitle-elite">Rincian data pemasukan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Kode Pemasukan</span>
                            <span class="detail-value-elite">{{ $data->kode_pemasukan }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jenis Sumber</span>
                            <span class="detail-value-elite">
                                @if($data->sumber_jenis === 'Reservasi')
                                    <span class="badge-pill-elite badge-active-elite">{{ $data->sumber_jenis }}</span>
                                @else
                                    <span class="badge-pill-elite badge-info-elite">{{ $data->sumber_jenis }}</span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Tanggal</span>
                            <span class="detail-value-elite">
                                {{ $data->tanggal?->translatedFormat('d F Y') ?? '-' }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Kategori</span>
                            <span class="detail-value-elite">{{ $data->kategori }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Sumber</span>
                            <span class="detail-value-elite">{{ $data->sumber ?: '-' }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Metode Pembayaran</span>
                            <span class="detail-value-elite">{{ $data->metode_pembayaran ?: '-' }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Nominal</span>
                            <span class="detail-value-elite text-maroon-value-elite" style="font-size:17px;">
                                Rp {{ number_format($data->nominal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Status</span>
                            <span class="detail-value-elite">
                                @if($data->status === 'Diterima')
                                    <span class="badge-pill-elite badge-active-elite">{{ $data->status }}</span>
                                @else
                                    <span class="badge-pill-elite badge-secondary-elite">{{ $data->status }}</span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Keterangan</span>
                            <span class="detail-value-elite">
                                {!! nl2br(e($data->keterangan ?: '-')) !!}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- SUMBER TRANSAKSI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Sumber Transaksi</h6>
                            <small class="section-subtitle-elite">Asal data pemasukan ini</small>
                        </div>
                    </div>

                    @if($data->sumber_jenis === 'Reservasi' && $data->pembayaran)

                        <div class="detail-grid-elite">

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Pembayaran</span>
                                <span class="detail-value-elite">#{{ $data->pembayaran->id }}</span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Status Pembayaran</span>
                                <span class="detail-value-elite">
                                    <span class="badge-pill-elite badge-active-elite">
                                        {{ $data->pembayaran->status }}
                                    </span>
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Reservasi</span>
                                <span class="detail-value-elite">
                                    {{ $data->pembayaran->reservasi?->kode_reservasi ?? '-' }}
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Pelanggan</span>
                                <span class="detail-value-elite">
                                    {{ $data->pembayaran->reservasi?->pelanggan?->nama ?? '-' }}
                                </span>
                            </div>

                        </div>

                    @else

                        <div class="alert-info-elite d-flex align-items-start mt-3" style="padding:14px 16px;border-radius:12px;font-size:12.5px;color:#5a4a44;">
                            <i class="fas fa-pen mr-2 mt-1" style="color:#3b6fd4;"></i>
                            <div>Pemasukan ini dicatat secara manual oleh admin.</div>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.detail-masuk-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.title-elite { color: #3a2a26; }
.text-muted-elite { color: #a09a94; }
.text-success-elite { color: #12865d; }


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

.pegawai-photo-square-elite {
    display: block;
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 14px;
    border: 5px solid #fff;
    box-shadow: 0 6px 18px rgba(124,16,19,.2);
}

.tax-icon-square-elite {
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
.tax-icon-square-elite span {
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
.icon-info-elite.mini-stat-icon-elite    { background: #e8f0fe; color: #3b6fd4; }

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
   RINCIAN (SUMMARY)
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

.btn-gradient-elite {
    background: linear-gradient(135deg, #e01c1d, #7c1013);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all .25s ease;
    box-shadow: 0 4px 14px rgba(224,28,29,.3);
}
.btn-gradient-elite:hover {
    color: #fff;
    filter: brightness(1.06);
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
   BADGE TAMBAHAN
========================================================== */

.badge-info-elite      { background: #e8f0fe; color: #3b6fd4; }
.badge-secondary-elite { background: #f4efe9; color: #8a7d74; }
.badge-maroon-elite    { background: #fff0f0; color: var(--sb-red); }
.badge-active-elite    { background: #e8f8f1; color: #1cc88a; }
.badge-warning-elite   { background: #fdf3e2; color: #a9781f; }

.badge-pill-elite {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
</style>

@endsection