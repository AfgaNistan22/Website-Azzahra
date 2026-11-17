@extends('layouts.app')

@section('content')

<div class="container-fluid px-4 detail-pelanggan-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ url('pelanggan') }}">Data Pelanggan</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Pelanggan</h4>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url('pelanggan/'.$data->id.'/edit') }}" class="btn btn-edit-elite px-3 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            <form action="{{ url('pelanggan/'.$data->id) }}" method="POST" class="form-delete d-inline mr-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-gradient-elite px-3">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>

            <a href="{{ url('pelanggan') }}" class="btn btn-outline-elite px-3">
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

                        @if ($data->foto)
                            <img src="{{ asset('storage/foto_pelanggan/'.$data->foto) }}"
                                 alt="{{ $data->nama }}"
                                 class="profile-photo-elite">
                        @else
                            <div class="profile-photo-placeholder-elite mx-auto">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif

                    </div>

                    <div class="text-center px-4 pb-4">

                        <h5 class="fw-bold mb-1 title-elite">{{ $data->nama }}</h5>
                        <div class="id-badge-elite mb-3">ID Pelanggan #{{ $data->id }}</div>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge-pill-elite badge-gender-elite">
                                <i class="fas fa-venus mr-1"></i> Perempuan
                            </span>

                            @if ($data->member == 'Ya')
                                <span class="badge-pill-elite badge-success-elite">
                                    <i class="fas fa-crown mr-1"></i> Member
                                </span>
                            @else
                                <span class="badge-pill-elite badge-secondary-elite">
                                    Bukan Member
                                </span>
                            @endif
                        </div>

                        <div class="quick-contact-elite">

                            <div class="quick-contact-row-elite">
                                <i class="fas fa-phone icon-gold-elite"></i>
                                <span>{{ $data->no_telepon ?? '-' }}</span>
                            </div>

                            <div class="quick-contact-row-elite">
                                <i class="fas fa-envelope icon-maroon-elite"></i>
                                <span class="text-truncate">{{ $data->email ?? '-' }}</span>
                            </div>

                            <div class="quick-contact-row-elite align-items-start">
                                <i class="fas fa-map-marker-alt icon-maroon-elite mt-1"></i>
                                <span>{{ $data->alamat ?? '-' }}</span>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- MINI SYSTEM INFO --}}
                <div class="system-info-mini-elite mt-3">

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="sys-label-elite"><i class="fas fa-plus-circle mr-1"></i> Dibuat</span>
                        <span class="sys-value-elite">
                            @if ($data->created_at)
                                {{ $data->created_at->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top-mini-elite">
                        <span class="sys-label-elite"><i class="fas fa-sync-alt mr-1"></i> Diperbarui</span>
                        <span class="sys-value-elite">
                            @if ($data->updated_at)
                                {{ $data->updated_at->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                </div>

            </div>

        </div>


        {{-- ================= RIGHT: DETAIL SECTIONS ================= --}}
        <div class="col-lg-8">

            {{-- STAT ROW --}}
            <div class="row mb-4">

                <div class="col-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-maroon-elite">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite">{{ $data->jumlah_kedatangan ?? 0 }}</div>
                            <div class="mini-stat-label-elite">Kedatangan</div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-gold-elite">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite">
                                @if ($data->tgl_lahir)
                                    {{ \Carbon\Carbon::parse($data->tgl_lahir)->age }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="mini-stat-label-elite">Tahun</div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-success-elite">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                @if ($data->terakhir_datang)
                                    {{ \Carbon\Carbon::parse($data->terakhir_datang)->translatedFormat('d M Y') }}
                                @else
                                    Belum ada
                                @endif
                            </div>
                            <div class="mini-stat-label-elite">Terakhir Datang</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- INFORMASI PRIBADI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Pribadi</h6>
                            <small class="section-subtitle-elite">Data pribadi pelanggan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Nama Lengkap</span>
                            <span class="detail-value-elite">{{ $data->nama }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jenis Kelamin</span>
                            <span class="detail-value-elite">Perempuan</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Tanggal Lahir</span>
                            <span class="detail-value-elite">
                                @if ($data->tgl_lahir)
                                    {{ \Carbon\Carbon::parse($data->tgl_lahir)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Umur</span>
                            <span class="detail-value-elite">
                                @if ($data->tgl_lahir)
                                    {{ \Carbon\Carbon::parse($data->tgl_lahir)->age }} tahun
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- INFORMASI KONTAK --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-gold-elite">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Kontak</h6>
                            <small class="section-subtitle-elite">Informasi kontak pelanggan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">No. Telepon</span>
                            <span class="detail-value-elite">{{ $data->no_telepon ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Email</span>
                            <span class="detail-value-elite text-break">{{ $data->email ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Alamat</span>
                            <span class="detail-value-elite">{{ $data->alamat ?? '-' }}</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- MEMBER & KEDATANGAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Member & Kedatangan</h6>
                            <small class="section-subtitle-elite">Riwayat keanggotaan dan kunjungan pelanggan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Status Member</span>
                            <span class="detail-value-elite">
                                @if ($data->member == 'Ya')
                                    <span class="badge-pill-elite badge-success-elite badge-inline-elite">
                                        <i class="fas fa-check-circle mr-1"></i> Member
                                    </span>
                                @else
                                    <span class="badge-pill-elite badge-secondary-elite badge-inline-elite">
                                        <i class="fas fa-times-circle mr-1"></i> Bukan Member
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jumlah Kedatangan</span>
                            <span class="detail-value-elite">{{ $data->jumlah_kedatangan ?? 0 }} kali</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Terakhir Datang</span>
                            <span class="detail-value-elite">
                                @if ($data->terakhir_datang)
                                    {{ \Carbon\Carbon::parse($data->terakhir_datang)->translatedFormat('d F Y') }}
                                @else
                                    Belum pernah datang
                                @endif
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

.profile-photo-elite {
    display: block;
    width: 110px;
    height: 110px;
    margin: 0 auto;
    object-fit: cover;
    object-position: center top;
    border-radius: 50%;
    border: 5px solid #fff;
    box-shadow: 0 6px 18px rgba(124,16,19,.2);
    position: relative;
    z-index: 5;
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
.badge-inline-elite { font-size: 10.5px; padding: 4px 10px; }

.badge-gender-elite    { background: #fff0f0; color: var(--sb-red); }
.badge-success-elite   { background: #eafaf1; color: #198754; }
.badge-secondary-elite { background: #f4efe9; color: #8a7d74; }


/* =========================================================
   QUICK CONTACT
========================================================== */

.quick-contact-elite {
    text-align: left;
    border-top: 1px dashed rgba(217,172,79,.3);
    padding-top: 14px;
}

.quick-contact-row-elite {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 0;
    font-size: 12.5px;
    color: #5a4a44;
}
.quick-contact-row-elite i {
    width: 16px;
    text-align: center;
    font-size: 12px;
}

.icon-maroon-elite { color: var(--sb-red); }
.icon-gold-elite   { color: #a9781f; }
.icon-success-elite{ color: #198754; }


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
   MINI STAT ROW
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

.icon-maroon-elite.mini-stat-icon-elite  { background: #fff0f0; }
.icon-gold-elite.mini-stat-icon-elite    { background: #fdf3e2; }
.icon-success-elite.mini-stat-icon-elite { background: #e8f8f1; }

.mini-stat-value-elite {
    font-weight: 700;
    font-size: 18px;
    color: #3a2a26;
    line-height: 1.15;
}
.mini-stat-small-elite {
    font-size: 12.5px;
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

.icon-maroon-elite { background: #fff0f0; color: var(--sb-red); }
.icon-gold-elite   { background: #fdf3e2; color: #a9781f; }

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

.detail-label-elite {
    font-size: 11px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.detail-value-elite {
    font-size: 13.5px;
    font-weight: 600;
    color: #3a2a26;
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
    .detail-grid-elite {
        grid-template-columns: 1fr;
    }
}

</style>


{{-- ================= SWEETALERT ================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.form-delete').forEach(form => {

        form.addEventListener('submit', function(e) {

            e.preventDefault();

            Swal.fire({

                title: 'Yakin hapus pelanggan?',

                text: 'Data pelanggan yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#e01c1d',

                cancelButtonColor: '#858796',

                confirmButtonText: 'Ya, hapus!',

                cancelButtonText: 'Batal'

            }).then((result) => {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });

});

</script>

@endpush

@endsection