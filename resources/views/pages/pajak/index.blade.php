@extends('layouts.app')

@section('title', 'Data Pajak')

@section('content')

@php
    $bulanNama = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $totalData = $data->total();
    $totalDibayar = $data->where('status', 'Dibayar')->count();
    $totalBelum = $data->where('status', 'Belum Dibayar')->count();
    $totalNominal = $data->sum('jumlah_pajak');
@endphp

<div class="container-fluid px-4 pajak-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Data Pajak</h4>
                <p class="text-white-50 mb-0 small ml-2">
                    Kelola data pajak pegawai Azzahra Salon dan Spa Muslimah
                </p>
            </div>
        </div>

        <div class="header-actions-elite position-relative">

            <a href="{{ route('pajak.laporan.pdf', request()->query()) }}"
               target="_blank"
               class="btn btn-outline-elite-onheader mr-2">
                <i class="fas fa-file-pdf mr-1"></i>
                Laporan PDF
            </a>

            <a href="{{ route('pajak.create') }}" class="btn btn-header-solid-elite">
                <i class="fas fa-plus mr-1"></i>
                Tambah Pajak
            </a>

        </div>

    </div>


    {{-- ================= SUMMARY ================= --}}
    <div class="row mb-2">

        {{-- TOTAL DATA --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-maroon-elite">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($totalData, 0, ',', '.') }}</div>
                    <div class="mini-stat-label-elite">Total Data</div>
                </div>
            </div>
        </div>

        {{-- SUDAH DIBAYAR --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-success-elite">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite text-success-elite">{{ $totalDibayar }}</div>
                    <div class="mini-stat-label-elite">Sudah Dibayar</div>
                </div>
            </div>
        </div>

        {{-- BELUM DIBAYAR --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-gold-elite">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite text-gold-elite">{{ $totalBelum }}</div>
                    <div class="mini-stat-label-elite">Belum Dibayar</div>
                </div>
            </div>
        </div>

        {{-- NOMINAL PAJAK --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-info-elite">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite mini-stat-money-elite">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </div>
                    <div class="mini-stat-label-elite">Nominal Pajak</div>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= FILTER ================= --}}
    <div class="card-elite mb-4">

        <div class="card-body p-4">

            <div class="section-head-elite mb-3">
                <div class="section-icon-elite">
                    <i class="fas fa-filter"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Filter Data Pajak</h6>
                    <small class="section-subtitle-elite">Cari berdasarkan pegawai, periode, atau status pembayaran</small>
                </div>
            </div>

            <form method="GET" action="{{ route('pajak.index') }}" class="mt-3">

                <div class="row">

                    {{-- KATA KUNCI --}}
                    <div class="col-lg-4 mb-3">

                        <label for="katakunci" class="form-label-elite">Kata Kunci</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="katakunci"
                                   id="katakunci"
                                   class="form-control-elite"
                                   value="{{ request('katakunci') }}"
                                   placeholder="Nama pegawai / tahun / status">
                        </div>

                    </div>


                    {{-- BULAN --}}
                    <div class="col-lg-2 col-md-4 mb-3">

                        <label for="bulan" class="form-label-elite">Bulan</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-calendar-alt"></i>
                            </span>

                            <select name="bulan" id="bulan" class="form-control-elite">

                                <option value="">Semua Bulan</option>

                                @foreach($bulanNama as $key => $nama)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>


                    {{-- TAHUN --}}
                    <div class="col-lg-2 col-md-4 mb-3">

                        <label for="tahun" class="form-label-elite">Tahun</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-calendar-check"></i>
                            </span>

                            <input type="number"
                                   name="tahun"
                                   id="tahun"
                                   class="form-control-elite"
                                   value="{{ request('tahun') }}"
                                   placeholder="2026">
                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-2 col-md-4 mb-3">

                        <label for="status" class="form-label-elite">Status</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-toggle-on"></i>
                            </span>

                            <select name="status" id="status" class="form-control-elite">

                                <option value="">Semua Status</option>

                                <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>
                                    Belum Dibayar
                                </option>

                                <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>
                                    Dibayar
                                </option>

                            </select>
                        </div>

                    </div>


                    {{-- DATA / HALAMAN --}}
                    <div class="col-lg-2 mb-3">

                        <label for="per_page" class="form-label-elite">Data / Halaman</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-list-ol"></i>
                            </span>

                            <select name="per_page" id="per_page" class="form-control-elite">

                                @foreach([5,10,25,50,100] as $jumlah)
                                    <option value="{{ $jumlah }}" {{ request('per_page', 5) == $jumlah ? 'selected' : '' }}>
                                        {{ $jumlah }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                </div>


                <div class="d-flex justify-content-end">

                    <a href="{{ route('pajak.index') }}" class="btn btn-outline-elite px-4 mr-2">
                        <i class="fas fa-undo mr-1"></i>
                        Reset
                    </a>

                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-filter mr-1"></i>
                        Terapkan
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div class="card-elite mb-4">

        <div class="table-card-header-elite">

            <div class="section-head-elite">
                <div class="section-icon-elite">
                    <i class="fas fa-list"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Daftar Pajak</h6>
                    <small class="section-subtitle-elite">Rincian penghasilan bruto, PTKP, PKP, dan pajak pegawai</small>
                </div>
            </div>

            <span class="badge-pill-elite badge-maroon-elite">
                {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table-elite" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Pegawai</th>
                            <th>Periode</th>
                            <th>Bruto</th>
                            <th>PTKP</th>
                            <th>PKP</th>
                            <th>Tarif</th>
                            <th>Jumlah Pajak</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $item)

                            @php
                                $namaPegawai = $item->pegawai->nama ?? null;
                            @endphp

                            <tr>

                                {{-- NO --}}
                                <td class="text-center cell-number-elite">
                                    {{ $data->firstItem() + $loop->index }}
                                </td>


                                {{-- PEGAWAI --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="avatar-elite">
                                            {{ $namaPegawai ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($namaPegawai, 0, 1)) : '?' }}
                                        </div>

                                        <div class="cell-title-elite">
                                            {{ $namaPegawai ?? '-' }}
                                        </div>

                                    </div>

                                </td>


                                {{-- PERIODE --}}
                                <td>
                                    <span class="period-pill-elite">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $bulanNama[$item->bulan] ?? '-' }} {{ $item->tahun }}
                                    </span>
                                </td>


                                {{-- BRUTO --}}
                                <td class="cell-text-elite nowrap-elite">
                                    Rp {{ number_format($item->penghasilan_bruto, 0, ',', '.') }}
                                </td>


                                {{-- PTKP --}}
                                <td class="cell-text-elite nowrap-elite">
                                    Rp {{ number_format($item->ptkp, 0, ',', '.') }}
                                </td>


                                {{-- PKP --}}
                                <td class="cell-text-elite nowrap-elite">
                                    Rp {{ number_format($item->pkp, 0, ',', '.') }}
                                </td>


                                {{-- TARIF --}}
                                <td class="cell-text-elite nowrap-elite">
                                    {{ number_format((float) $item->tarif_pajak, 2, ',', '.') }}%
                                </td>


                                {{-- JUMLAH PAJAK --}}
                                <td class="nowrap-elite">
                                    <strong class="money-total-elite">
                                        Rp {{ number_format($item->jumlah_pajak, 0, ',', '.') }}
                                    </strong>
                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($item->status === 'Dibayar')

                                        <span class="badge-pill-elite badge-active-elite">
                                            <i class="fas fa-check mr-1"></i> Dibayar
                                        </span>

                                    @else

                                        <span class="badge-pill-elite badge-warning-elite">
                                            <i class="fas fa-clock mr-1"></i> Belum Dibayar
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="text-center nowrap-elite">

                                    <a href="{{ route('pajak.show', ['pajak' => $item->id]) }}"
                                       class="btn-action-elite action-view-elite"
                                       title="Detail"
                                       aria-label="Lihat detail pajak">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('pajak.edit', ['pajak' => $item->id]) }}"
                                       class="btn-action-elite action-edit-elite"
                                       title="Edit"
                                       aria-label="Edit pajak">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('pajak.destroy', ['pajak' => $item->id]) }}"
                                          method="POST"
                                          class="d-inline form-hapus">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-action-elite action-delete-elite"
                                                title="Hapus"
                                                aria-label="Hapus pajak">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-5">

                                    <div class="empty-state-elite">

                                        <div class="empty-icon-elite">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>

                                        <h6>Belum ada data pajak</h6>

                                        <p>Silakan tambahkan data pajak pegawai.</p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if($data->hasPages())

                <div class="table-footer-elite">

                    <div class="text-muted-elite small">
                        Menampilkan
                        {{ $data->firstItem() }}
                        -
                        {{ $data->lastItem() }}
                        dari
                        {{ $data->total() }}
                        data
                    </div>

                    <div class="pagination-elite">
                        {!! $data->links() !!}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.form-hapus').forEach(function (form) {

    form.addEventListener('submit', function (e) {

        e.preventDefault();

        Swal.fire({
            title: 'Hapus data pajak?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e01c1d',
            cancelButtonColor: '#858796',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus'
        }).then(function (result) {

            if (result.isConfirmed) {
                form.submit();
            }

        });

    });

});

@if(session('success'))
Swal.fire({icon:'success',title:'Berhasil',text:@json(session('success')),confirmButtonColor:'#e01c1d'});
@endif

@if(session('error'))
Swal.fire({icon:'error',title:'Gagal',text:@json(session('error')),confirmButtonColor:'#e01c1d'});
@endif
</script>
@endpush


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.pajak-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.text-muted-elite   { color: #a09a94; }
.text-success-elite { color: #12865d !important; }
.text-gold-elite    { color: #a9781f !important; }
.nowrap-elite       { white-space: nowrap; }


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

.header-actions-elite {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 0;
}

.btn-outline-elite-onheader {
    border-radius: 10px;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,.5);
    color: #ffffff;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(2px);
    transition: all .25s ease;
    padding: .5rem 1rem;
}
.btn-outline-elite-onheader:hover {
    background: rgba(255,255,255,.2);
    color: #ffffff;
    border-color: #fff;
}

.btn-header-solid-elite {
    border-radius: 10px;
    font-weight: 700;
    border: none;
    padding: .5rem 1rem;
    background: linear-gradient(135deg, #ffffff, #f4e6c8);
    color: var(--sb-red-dark);
    box-shadow: 0 6px 16px rgba(0,0,0,.15);
    transition: all .25s ease;
}
.btn-header-solid-elite:hover {
    color: var(--sb-red-dark);
    filter: brightness(1.03);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2);
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
.mini-stat-money-elite {
    font-size: 16px;
}
.mini-stat-label-elite {
    font-size: 10.5px;
    color: #a09a94;
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-top: 2px;
}


/* =========================================================
   CARD & SECTION
========================================================== */

.card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 18px;
    box-shadow: 0 6px 26px rgba(124,16,19,.06);
    overflow: hidden;
}

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
    background: #fff0f0;
    color: var(--sb-red);
}

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
   FILTER
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


/* =========================================================
   TABLE
========================================================== */

.table-elite {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-elite thead th {
    padding: 14px 16px;
    background: #fdf6ea;
    color: #8a6a2f;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    border-bottom: 1px solid rgba(217,172,79,.25);
    white-space: nowrap;
}

.table-elite tbody td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f5ece0;
    font-size: 13px;
    color: #5a4a44;
}

.table-elite tbody tr { transition: background .2s ease; }
.table-elite tbody tr:hover { background: #fffbf3; }
.table-elite tbody tr:last-child td { border-bottom: none; }

.cell-number-elite { color: #a09a94; font-weight: 600; }

.cell-title-elite {
    font-weight: 700;
    color: #3a2a26;
    font-size: 13px;
}

.cell-text-elite { color: #5a4a44; font-weight: 500; }

.money-plus-elite  { color: #12865d; font-weight: 600; }
.money-minus-elite { color: var(--sb-red); font-weight: 600; }
.money-total-elite { color: #3a2a26; font-weight: 700; font-size: 13.5px; }

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

.period-pill-elite {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
}
.period-pill-elite i { color: var(--sb-red); }


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
.badge-maroon-elite  { background: #fff0f0; color: var(--sb-red); }
.badge-active-elite  { background: #e8f8f1; color: #1cc88a; }
.badge-warning-elite { background: #fdf3e2; color: #a9781f; }


/* =========================================================
   ACTION BUTTONS
========================================================== */

.btn-action-elite {
    width: 34px; height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid transparent;
    text-decoration: none;
    cursor: pointer;
    font-size: 13px;
    transition: all .25s ease;
}

.action-view-elite   { background: #e8f0fe; color: #3b6fd4; border-color: rgba(59,111,212,.15); }
.action-edit-elite   { background: #fdf3e2; color: #a9781f; border-color: rgba(217,172,79,.3); }
.action-delete-elite { background: #fff0f0; color: var(--sb-red); border-color: rgba(224,28,29,.15); }

.action-view-elite:hover {
    background: linear-gradient(135deg, #5b8def, #3b6fd4);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(59,111,212,.3);
}
.action-edit-elite:hover {
    background: linear-gradient(135deg, #f1cf85, #d9ac4f);
    color: #5a3f10;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(217,172,79,.35);
}
.action-delete-elite:hover {
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(224,28,29,.3);
}


/* =========================================================
   BUTTONS
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


/* =========================================================
   EMPTY STATE
========================================================== */

.empty-state-elite { padding: 24px 0; }

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

.table-footer-elite {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 24px;
    border-top: 1px solid rgba(217,172,79,.15);
    background: #fdfaf5;
}

.pagination-elite .pagination {
    margin: 0;
    gap: 4px;
    flex-wrap: wrap;
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
    .form-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 767px) {
    .header-actions-elite {
        width: 100%;
    }
    .header-actions-elite .btn {
        flex: 1;
        text-align: center;
    }
    .table-card-header-elite,
    .table-footer-elite {
        padding: 16px;
    }
}

</style>

@endsection