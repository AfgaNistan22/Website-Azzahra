@extends('layouts.app')

@section('title', 'Data Perawatan')

@section('content')

<div class="container-fluid treatment-page-elite">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="modern-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">

            <div class="header-icon-elite">
                <i class="fas fa-spa"></i>
            </div>

            <div>
                <h1 class="page-title-elite">Data Perawatan</h1>
                <p class="page-subtitle-elite">Kelola informasi layanan perawatan Azzahra Salon dan Spa Muslimah</p>
            </div>

        </div>


        <div class="header-actions position-relative">

            {{-- EXPORT --}}
            <a href="{{ url('perawatan/laporan/pdf') }}"
               target="_blank"
               class="btn btn-outline-elite btn-modern-elite">

                <i class="fas fa-file-pdf mr-2"></i>
                Laporan

            </a>


            {{-- TAMBAH --}}
            <a href="{{ url('perawatan/create') }}"
               class="btn btn-gradient-elite btn-modern-elite">

                <i class="fas fa-plus mr-2"></i>
                Tambah Perawatan

            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERT
    ========================================================== --}}
    @if(session('success'))

        <div class="modern-alert-elite alert-success-elite mb-4">

            <div class="alert-icon-elite">
                <i class="fas fa-check"></i>
            </div>

            <div class="alert-content-elite">
                <strong>Berhasil</strong>
                <span>{{ session('success') }}</span>
            </div>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="modern-alert-elite alert-danger-elite mb-4">

            <div class="alert-icon-elite">
                <i class="fas fa-exclamation"></i>
            </div>

            <div class="alert-content-elite">
                <strong>Terjadi Kesalahan</strong>
                <span>{{ session('error') }}</span>
            </div>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>

    @endif


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="row summary-row">

        {{-- TOTAL PERAWATAN --}}
        <div class="col-xl-4 col-md-6 mb-4">

            <div class="summary-card-elite summary-maroon">

                <div class="summary-content-elite">
                    <span class="summary-label-elite">Total Perawatan</span>
                    <div class="summary-value-elite">{{ \App\Models\Perawatan::count() }}</div>
                    <span class="summary-description-elite">Seluruh layanan tersedia</span>
                </div>

                <div class="summary-icon-elite">
                    <i class="fas fa-spa"></i>
                </div>

            </div>

        </div>


        {{-- DURASI --}}
        <div class="col-xl-4 col-md-6 mb-4">

            <div class="summary-card-elite summary-gold">

                <div class="summary-content-elite">
                    <span class="summary-label-elite">Rata-rata Durasi</span>
                    <div class="summary-value-elite">{{ round(\App\Models\Perawatan::avg('durasi') ?? 0) }}</div>
                    <span class="summary-description-elite">Menit perawatan</span>
                </div>

                <div class="summary-icon-elite">
                    <i class="fas fa-clock"></i>
                </div>

            </div>

        </div>


        {{-- RATA-RATA HARGA --}}
        <div class="col-xl-4 col-md-12 mb-4">

            <div class="summary-card-elite summary-success">

                <div class="summary-content-elite">
                    <span class="summary-label-elite">Rata-rata Harga</span>
                    <div class="summary-value-elite summary-money-elite">
                        Rp {{ number_format(\App\Models\Perawatan::avg('harga') ?? 0, 0, ',', '.') }}
                    </div>
                    <span class="summary-description-elite">Harga rata-rata layanan</span>
                </div>

                <div class="summary-icon-elite">
                    <i class="fas fa-money-bill-wave"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-header-elite">

            <div class="card-title-wrapper-elite">
                <div class="card-title-icon-elite">
                    <i class="fas fa-filter"></i>
                </div>

                <div>
                    <h6 class="modern-card-title-elite">Filter Data</h6>
                    <span class="modern-card-subtitle-elite">Cari layanan berdasarkan nama, deskripsi, atau kategori</span>
                </div>
            </div>

        </div>


        <div class="modern-card-body-elite">

            <form method="GET" action="{{ url('perawatan') }}" id="filterForm">

                <div class="row">

                    {{-- SEARCH --}}
                    <div class="col-lg-7 col-md-6 mb-3">
                        <label class="modern-label-elite">Pencarian</label>

                        <div class="modern-input-group-elite">
                            <i class="fas fa-search input-icon-elite"></i>
                            <input type="search"
                                   name="katakunci"
                                   class="form-control modern-input-elite"
                                   placeholder="Cari nama atau deskripsi perawatan..."
                                   value="{{ request('katakunci') }}">
                        </div>
                    </div>


                    {{-- KATEGORI --}}
                    <div class="col-lg-2 col-md-3 mb-3">
                        <label class="modern-label-elite">Kategori</label>

                        <select name="kategori_id"
                                class="form-control modern-input-elite"
                                onchange="document.getElementById('filterForm').submit()">

                            <option value="">Semua</option>

                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- PER PAGE --}}
                    <div class="col-lg-1 col-md-3 mb-3">
                        <label class="modern-label-elite">Data</label>

                        <select name="per_page"
                                class="form-control modern-input-elite"
                                onchange="document.getElementById('filterForm').submit()">

                            @foreach([5,10,25,50,100] as $limit)
                                <option value="{{ $limit }}" {{ request('per_page', 5) == $limit ? 'selected' : '' }}>
                                    {{ $limit }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- BUTTON --}}
                    <div class="col-lg-2 col-md-12 mb-3">
                        <label class="modern-label-elite d-block">&nbsp;</label>

                        <div class="filter-buttons-elite">
                            <button type="submit" class="btn btn-gradient-elite modern-search-btn-elite">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>

                            <a href="{{ url('perawatan') }}" class="modern-reset-btn-elite" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-header-elite table-header-elite">

            <div class="card-title-wrapper-elite">
                <div class="card-title-icon-elite">
                    <i class="fas fa-spa"></i>
                </div>

                <div>
                    <h6 class="modern-card-title-elite">Daftar Perawatan</h6>
                    <span class="modern-card-subtitle-elite">Layanan perawatan yang tersedia pada sistem</span>
                </div>
            </div>

            <div class="data-counter-elite">
                <span><i class="fas fa-database mr-1"></i> {{ $data->total() }} data</span>
            </div>

        </div>


        <div class="modern-card-body-elite table-body-elite">

            <div class="table-responsive">

                <table class="table modern-table-elite">

                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="21%">Perawatan</th>
                            <th width="16%">Kategori</th>
                            <th width="12%" class="text-center">Durasi</th>
                            <th width="16%" class="text-center">Harga</th>
                            <th width="20%">Deskripsi</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($data as $item)

                            <tr>

                                <td class="text-center">
                                    <span class="number-badge-elite">{{ $data->firstItem() + $loop->index }}</span>
                                </td>


                                <td>
                                    <div class="treatment-info-elite">

                                        @if($item->foto)
                                            <img src="{{ asset('storage/foto_perawatan/'.$item->foto) }}"
                                                 alt="{{ $item->nama }}"
                                                 class="treatment-photo-elite">
                                        @else
                                            <div class="treatment-avatar-elite">
                                                <i class="fas fa-spa"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="treatment-name-elite">{{ $item->nama }}</div>
                                            <small class="text-muted">ID #{{ $item->id }}</small>
                                        </div>

                                    </div>
                                </td>


                                <td>
                                    @if($item->kategori)
                                        <span class="category-badge-elite">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $item->kategori->nama }}
                                        </span>
                                    @else
                                        <span class="category-empty-elite">Belum ada kategori</span>
                                    @endif
                                </td>


                                <td class="text-center">
                                    <span class="duration-badge-elite">
                                        <i class="fas fa-clock"></i>
                                        {{ $item->durasi }} menit
                                    </span>
                                </td>


                                <td class="text-center">
                                    <div class="price-wrapper-elite">
                                        <span class="price-label-elite">Harga</span>
                                        <span class="price-value-elite">
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </td>


                                <td>
                                    <div class="description-wrapper-elite">
                                        <span>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 70) }}</span>
                                    </div>
                                </td>


                                <td class="text-center">
                                    <div class="action-buttons-elite">

                                        <a href="{{ url('perawatan/'.$item->id) }}"
                                           class="action-btn-elite action-detail-elite" title="Detail Perawatan">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ url('perawatan/'.$item->id.'/edit') }}"
                                           class="action-btn-elite action-edit-elite" title="Edit Perawatan">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button" class="action-btn-elite action-delete-elite"
                                                title="Hapus Perawatan" onclick="hapusPerawatan({{ $item->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <form id="form-hapus-{{ $item->id }}"
                                              action="{{ url('perawatan/'.$item->id) }}"
                                              method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </div>
                                </td>

                            </tr>


                        @empty

                            <tr>
                                <td colspan="7" class="empty-state-elite">

                                    <div class="empty-icon-elite">
                                        <i class="fas fa-spa"></i>
                                    </div>

                                    <h5>Data Perawatan Belum Tersedia</h5>
                                    <p>Belum terdapat layanan perawatan yang sesuai dengan pencarian.</p>

                                    <a href="{{ url('perawatan/create') }}" class="btn btn-gradient-elite btn-sm">
                                        <i class="fas fa-plus mr-1"></i> Tambah Perawatan
                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($data->hasPages())

                <div class="pagination-wrapper-elite">

                    <div class="pagination-info-elite">
                        Menampilkan
                        <strong>{{ $data->firstItem() }}</strong> -
                        <strong>{{ $data->lastItem() }}</strong> dari
                        <strong>{{ $data->total() }}</strong> data
                    </div>

                    <div>
                        {{ $data->appends(request()->query())->links() }}
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        INFORMASI
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-header-elite">
            <div class="card-title-wrapper-elite">
                <div class="card-title-icon-elite">
                    <i class="fas fa-info-circle"></i>
                </div>

                <div>
                    <h6 class="modern-card-title-elite">Informasi Perawatan</h6>
                    <span class="modern-card-subtitle-elite">Informasi pengelolaan layanan perawatan</span>
                </div>
            </div>
        </div>


        <div class="modern-card-body-elite">

            <div class="info-grid-elite">

                <div class="info-item-elite">
                    <div class="info-number-elite info-maroon-elite">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="info-content-elite">
                        <strong>Layanan Perawatan</strong>
                        <p>Data perawatan digunakan untuk menyimpan daftar layanan yang tersedia pada Azzahra Salon dan Spa Muslimah.</p>
                    </div>
                </div>

                <div class="info-item-elite">
                    <div class="info-number-elite info-gold-elite">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="info-content-elite">
                        <strong>Kategori Perawatan</strong>
                        <p>Kategori digunakan untuk mengelompokkan layanan perawatan berdasarkan jenisnya agar lebih mudah dikelola.</p>
                    </div>
                </div>

                <div class="info-item-elite">
                    <div class="info-number-elite info-ink-elite">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content-elite">
                        <strong>Durasi Perawatan</strong>
                        <p>Durasi setiap perawatan digunakan dalam menentukan waktu mulai dan selesai pada proses reservasi.</p>
                    </div>
                </div>

                <div class="info-item-elite">
                    <div class="info-number-elite info-success-elite">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="info-content-elite">
                        <strong>Harga Layanan</strong>
                        <p>Harga perawatan menjadi dasar perhitungan biaya yang perlu dibayarkan pelanggan pada reservasi.</p>
                    </div>
                </div>

            </div>

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

.treatment-page-elite {
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
}

.btn-gradient-elite {
    background: linear-gradient(135deg, #ffffff, #f4e6c8);
    color: var(--sb-red-dark) !important;
    border: none;
    box-shadow: 0 6px 16px rgba(0,0,0,.15);
}
.btn-gradient-elite:hover {
    filter: brightness(1.03);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
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


/* =========================================================
   ALERT
========================================================== */

.modern-alert-elite {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 13px 16px;
    border-radius: 12px;
    border: 1px solid rgba(217, 172, 79, .2);
    box-shadow: 0 3px 12px rgba(0,0,0,.04);
}

.alert-success-elite { background: #eefaf3; }
.alert-danger-elite  { background: #fff0f0; }

.alert-icon-elite {
    width: 34px; height: 34px; min-width: 34px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.7);
}
.alert-success-elite .alert-icon-elite { color: #1cc88a; }
.alert-danger-elite .alert-icon-elite  { color: var(--sb-red); }

.alert-content-elite {
    display: flex; flex-direction: column; gap: 2px;
    font-size: 13px;
}
.alert-content-elite strong { font-size: 13px; }
.alert-content-elite span   { font-size: 12px; color: #6b6b6b; }


/* =========================================================
   SUMMARY
========================================================== */

.summary-card-elite {
    min-height: 145px;
    border-radius: 16px;
    padding: 22px;
    position: relative;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 6px 22px rgba(124,16,19,.06);
    border: 1px solid rgba(217,172,79,.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all .25s ease;
}
.summary-card-elite:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(124,16,19,.12);
}

.summary-content-elite { position: relative; z-index: 2; max-width: 80%; }

.summary-label-elite {
    display: block;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 7px;
}

.summary-value-elite {
    font-size: 28px; line-height: 1; font-weight: 700;
    color: #2d2020;
    margin-bottom: 7px;
}

.summary-money-elite {
    font-size: 21px;
}

.summary-description-elite {
    color: #a09a94; font-size: 11px;
}

.summary-icon-elite {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 21px;
    flex-shrink: 0;
}

.summary-maroon {
    color: var(--sb-red);
    border-left: 4px solid var(--sb-red);
}
.summary-maroon .summary-icon-elite {
    color: var(--sb-red);
    background: rgba(224,28,29,.09);
}

.summary-gold {
    color: #a9781f;
    border-left: 4px solid var(--sb-gold);
}
.summary-gold .summary-icon-elite {
    color: #a9781f;
    background: rgba(217,172,79,.16);
}

.summary-success {
    color: #1cc88a;
    border-left: 4px solid #1cc88a;
}
.summary-success .summary-icon-elite {
    color: #1cc88a;
    background: rgba(28,200,138,.10);
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

.modern-card-header-elite {
    padding: 18px 20px;
    border-bottom: 1px solid rgba(217,172,79,.15);
    background: #fffdf9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title-wrapper-elite {
    display: flex; align-items: center; gap: 12px;
}

.card-title-icon-elite {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    font-size: 15px;
}

.modern-card-title-elite {
    margin: 0; font-size: 14px; font-weight: 700;
    color: #3a2a26;
}

.modern-card-subtitle-elite {
    display: block; margin-top: 3px;
    color: #a09a94; font-size: 11px;
}

.modern-card-body-elite { padding: 20px; }


/* =========================================================
   FILTER
========================================================== */

.modern-label-elite {
    display: block; font-size: 11px; font-weight: 700;
    color: #6b5750; margin-bottom: 7px;
}

.modern-input-elite {
    height: 42px;
    border: 1px solid #ecd9bf;
    border-radius: 9px;
    font-size: 12px;
    background: #fffdf9;
    box-shadow: none !important;
    transition: all .2s ease;
}
.modern-input-elite:focus {
    border-color: var(--sb-gold);
    box-shadow: 0 0 0 3px rgba(217,172,79,.18) !important;
    background: #fff;
}

.modern-input-group-elite { position: relative; }

.input-icon-elite {
    position: absolute; left: 14px; top: 14px;
    color: #c9b48a; font-size: 13px; z-index: 2;
}

.modern-input-group-elite .modern-input-elite { padding-left: 38px; }

.filter-buttons-elite { display: flex; gap: 6px; }

.modern-search-btn-elite {
    height: 42px; flex: 1;
    border-radius: 9px; font-size: 12px; font-weight: 600;
}

.modern-reset-btn-elite {
    height: 42px; width: 42px;
    border: 1px solid #ecd9bf;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: #a9906f;
    transition: all .2s ease;
}
.modern-reset-btn-elite:hover {
    background: rgba(217,172,79,.12);
    color: var(--sb-red-dark);
}


/* =========================================================
   TABLE
========================================================== */

.table-body-elite { padding: 0; }
.table-header-elite { padding: 17px 20px; }

.data-counter-elite { color: #a09a94; font-size: 11px; }

.modern-table-elite {
    margin-bottom: 0;
    min-width: 1150px;
}

.modern-table-elite thead th {
    background: #fdf8ef;
    color: #8a7156;
    border-top: none;
    border-bottom: 1px solid rgba(217,172,79,.2);
    padding: 13px 12px;
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .35px;
    white-space: nowrap;
}

.modern-table-elite tbody td {
    padding: 14px 12px;
    vertical-align: middle;
    border-color: #f5ece0;
    font-size: 12px;
    color: #6b5750;
}

.modern-table-elite tbody tr { transition: all .2s ease; }
.modern-table-elite tbody tr:hover { background: #fffaf2; }


/* =========================================================
   NUMBER
========================================================== */

.number-badge-elite {
    width: 27px; height: 27px;
    display: inline-flex; align-items: center; justify-content: center;
    background: #fdf3e2;
    color: #a9781f;
    border-radius: 8px;
    font-size: 11px; font-weight: 600;
}


/* =========================================================
   TREATMENT
========================================================== */

.treatment-info-elite {
    display: flex; align-items: center; gap: 10px;
}

.treatment-photo-elite,
.treatment-avatar-elite {
    width: 42px; height: 42px; min-width: 42px;
    border-radius: 11px;
}

.treatment-photo-elite {
    object-fit: cover;
    border: 1px solid rgba(217,172,79,.25);
}

.treatment-avatar-elite {
    background: rgba(224,28,29,.08);
    color: var(--sb-red);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px;
}

.treatment-name-elite {
    font-weight: 600; color: #3a2a26;
    white-space: nowrap;
}


/* =========================================================
   CATEGORY
========================================================== */

.category-badge-elite {
    display: inline-flex;
    align-items: center;
    padding: 7px 10px;
    border-radius: 8px;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.category-empty-elite {
    color: #c9b9a8;
    font-size: 10px;
}


/* =========================================================
   DURATION
========================================================== */

.duration-badge-elite {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 10px;
    background: #fff0f0;
    color: var(--sb-red);
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}


/* =========================================================
   PRICE
========================================================== */

.price-wrapper-elite {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.price-label-elite {
    color: #a09a94;
    font-size: 9px;
}

.price-value-elite {
    color: #12865d;
    font-weight: 700;
    font-size: 12px;
    white-space: nowrap;
}


/* =========================================================
   DESCRIPTION
========================================================== */

.description-wrapper-elite {
    max-width: 270px;
    line-height: 1.5;
    color: #8a7156;
    font-size: 11px;
}


/* =========================================================
   ACTION
========================================================== */

.action-buttons-elite {
    display: flex; justify-content: center; align-items: center; gap: 5px;
}

.action-btn-elite {
    width: 31px; height: 31px;
    border-radius: 8px; border: none;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px;
    transition: all .2s ease;
    text-decoration: none !important;
    cursor: pointer;
}

.action-detail-elite { background: #eaf8fb; color: #17a2b8; }
.action-detail-elite:hover { background: #17a2b8; color: #fff; transform: translateY(-2px); }

.action-edit-elite { background: #fdf3e2; color: #a9781f; }
.action-edit-elite:hover { background: var(--sb-gold); color: #fff; transform: translateY(-2px); }

.action-delete-elite { background: #fff0f0; color: var(--sb-red); }
.action-delete-elite:hover { background: var(--sb-red); color: #fff; transform: translateY(-2px); }


/* =========================================================
   EMPTY
========================================================== */

.empty-state-elite { padding: 60px 20px !important; text-align: center; }

.empty-icon-elite {
    width: 70px; height: 70px;
    border-radius: 20px;
    background: #fff1f1; color: var(--sb-red);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; margin: 0 auto 15px;
}

.empty-state-elite h5 { font-size: 15px; font-weight: 700; color: #3a2a26; margin-bottom: 6px; }
.empty-state-elite p  { color: #a09a94; font-size: 12px; margin-bottom: 15px; }


/* =========================================================
   PAGINATION
========================================================== */

.pagination-wrapper-elite {
    padding: 18px 20px;
    border-top: 1px solid rgba(217,172,79,.15);
    display: flex; align-items: center; justify-content: space-between; gap: 15px;
}

.pagination-info-elite { color: #a09a94; font-size: 11px; }
.pagination-wrapper-elite .pagination { margin: 0; }


/* =========================================================
   INFO
========================================================== */

.info-grid-elite {
    display: flex; align-items: stretch; gap: 12px;
}

.info-item-elite {
    flex: 1;
    display: flex; align-items: flex-start; gap: 10px;
    padding: 15px;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 12px;
    background: #fffdf9;
}

.info-number-elite {
    width: 34px; height: 34px; min-width: 34px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
}

.info-maroon-elite  { background: #fff0f0; color: var(--sb-red); }
.info-gold-elite    { background: #fdf3e2; color: #a9781f; }
.info-ink-elite     { background: #f1f2f4; color: #4a4d56; }
.info-success-elite { background: #e8f8f1; color: #1cc88a; }

.info-content-elite { flex: 1; }
.info-content-elite strong { display: block; color: #5a4a44; font-size: 11px; margin-bottom: 4px; }
.info-content-elite p { color: #a09a94; font-size: 10px; line-height: 1.6; margin: 0; }


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .modern-header-elite { align-items: flex-start; flex-direction: column; }
    .header-actions { width: 100%; }
    .header-actions .btn { flex: 1; }
    .info-grid-elite { flex-direction: column; }
}

@media (max-width: 767px) {
    .page-title-elite { font-size: 20px; }
    .header-icon-elite { width: 45px; height: 45px; font-size: 18px; }
    .modern-card-body-elite { padding: 15px; }
    .pagination-wrapper-elite { flex-direction: column; align-items: center; }
    .summary-money-elite { font-size: 18px; }
}

</style>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

function hapusPerawatan(id)
{
    if(typeof Swal !== 'undefined')
    {
        Swal.fire({
            title: 'Hapus perawatan?',
            html: 'Data perawatan yang dihapus <strong>tidak dapat dikembalikan</strong>.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e01c1d',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(function(result)
        {
            if(result.isConfirmed)
            {
                document.getElementById('form-hapus-' + id).submit();
            }
        });
    }
    else
    {
        if(confirm('Yakin ingin menghapus perawatan ini?'))
        {
            document.getElementById('form-hapus-' + id).submit();
        }
    }
}

document.addEventListener('DOMContentLoaded', function()
{
    const searchInput = document.querySelector('input[name="katakunci"]');

    if(searchInput)
    {
        searchInput.addEventListener('keydown', function(e)
        {
            if(e.key === 'Enter')
            {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    }
});

</script>

@endpush

@endsection