@extends('layouts.app')

@section('title', 'Detail Kategori Perawatan')

@section('content')

<div class="container-fluid px-4 detail-pelanggan-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('kategori.index') }}">Kategori Perawatan</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Kategori Perawatan</h4>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('kategori.edit',$data->id) }}" class="btn btn-edit-elite px-3">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            <a href="{{ route('kategori.index') }}" class="btn btn-outline-elite px-3 ml-2">
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

                    <div class="text-center profile-photo-wrap-elite">
                        <div class="profile-photo-placeholder-elite mx-auto">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4">

                        <h5 class="fw-bold mb-1 title-elite">{{ $data->nama }}</h5>
                        <div class="id-badge-elite mb-3">ID Kategori #{{ $data->id }}</div>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            @if(($data->status ?? 'Aktif') === 'Aktif')
                                <span class="badge-pill-elite badge-success-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge-pill-elite badge-secondary-elite">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Deskripsi</span>
                            <span class="description-text-elite">
                                {{ $data->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================= RIGHT: DAFTAR PERAWATAN ================= --}}
        <div class="col-lg-8 mb-4">

            @php $daftarPerawatan = $data->perawatans ?? collect(); @endphp

            <div class="mini-stat-elite mb-4">
                <div class="mini-stat-icon-elite icon-maroon-elite">
                    <i class="fas fa-spa"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $daftarPerawatan->count() }}</div>
                    <div class="mini-stat-label-elite">Perawatan dalam kategori ini</div>
                </div>
            </div>


            <div class="card-elite">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-spa"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Perawatan dalam Kategori</h6>
                            <small class="section-subtitle-elite">Daftar layanan yang termasuk kategori ini</small>
                        </div>
                    </div>


                    @if($daftarPerawatan->count())

                        <div class="treatment-list-elite">

                            @foreach($daftarPerawatan as $item)

                                <div class="treatment-row-elite">

                                    <div class="treatment-info-elite">
                                        <div class="treatment-icon-elite">
                                            <i class="fas fa-spa"></i>
                                        </div>
                                        <div>
                                            <div class="treatment-name-elite">{{ $item->nama }}</div>
                                            <small class="treatment-duration-elite">
                                                <i class="far fa-clock mr-1"></i>{{ $item->durasi }} menit
                                            </small>
                                        </div>
                                    </div>

                                    <span class="treatment-price-elite">
                                        Rp{{ number_format($item->harga,0,',','.') }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="empty-state-elite">
                            <div class="empty-icon-elite">
                                <i class="fas fa-spa"></i>
                            </div>
                            <h5>Belum Ada Perawatan</h5>
                            <p>Belum ada perawatan dalam kategori ini.</p>
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

.detail-pelanggan-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.title-elite { color: #3a2a26; }


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
    height: 90px;
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

.profile-photo-wrap-elite {
    position: relative;
    z-index: 5;
    margin-top: -55px;
}

.profile-photo-placeholder-elite {
    position: relative;
    z-index: 5;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: #fff0f0;
    border: 5px solid #fff;
    box-shadow: 0 6px 18px rgba(124,16,19,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--sb-red);
    font-size: 42px;
    margin: 0 auto;
}

.id-badge-elite {
    color: #a09a94;
    font-size: 12px;
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

.badge-success-elite   { background: #eafaf1; color: #198754; }
.badge-secondary-elite { background: #f4efe9; color: #8a7d74; }


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


/* =========================================================
   MINI STAT
========================================================== */

.mini-stat-elite {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 4px 14px rgba(124,16,19,.05);
}

.mini-stat-icon-elite {
    width: 46px; height: 46px;
    min-width: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: #fff0f0;
    color: var(--sb-red);
}

.mini-stat-value-elite {
    font-weight: 700;
    font-size: 20px;
    color: #3a2a26;
    line-height: 1.2;
}
.mini-stat-label-elite {
    font-size: 11.5px;
    color: #a09a94;
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
   TREATMENT LIST
========================================================== */

.treatment-list-elite {
    margin-top: 12px;
}

.treatment-row-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px;
    background: #fdfaf5;
    border: 1px solid rgba(217,172,79,.12);
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all .2s ease;
}
.treatment-row-elite:last-child { margin-bottom: 0; }
.treatment-row-elite:hover {
    background: #fffaf2;
    border-color: rgba(217,172,79,.25);
}

.treatment-info-elite {
    display: flex;
    align-items: center;
    gap: 12px;
}

.treatment-icon-elite {
    width: 38px; height: 38px; min-width: 38px;
    border-radius: 10px;
    background: #fdf3e2;
    color: #a9781f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.treatment-name-elite {
    font-weight: 600;
    color: #3a2a26;
    font-size: 13.5px;
}

.treatment-duration-elite {
    color: #a09a94;
    font-size: 11px;
}

.treatment-price-elite {
    font-weight: 700;
    color: var(--sb-red);
    font-size: 13.5px;
    white-space: nowrap;
}


/* =========================================================
   EMPTY STATE
========================================================== */

.empty-state-elite {
    padding: 50px 20px !important;
    text-align: center;
}

.empty-icon-elite {
    width: 70px; height: 70px;
    border-radius: 20px;
    background: #fff1f1; color: var(--sb-red);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; margin: 0 auto 15px;
}

.empty-state-elite h5 { font-size: 15px; font-weight: 700; color: #3a2a26; margin-bottom: 6px; }
.empty-state-elite p  { color: #a09a94; font-size: 12px; margin: 0; }


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

</style>

@endsection