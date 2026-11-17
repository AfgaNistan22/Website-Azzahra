@extends('layouts.app')

@section('title', 'Laporan Bulanan Keuangan')

@section('content')

<div class="container-fluid px-4 bulanan-elite">

    {{-- HEADER --}}
    <div class="form-header-elite mb-4">
        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left">
            <div class="header-icon-elite"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h4 class="fw-bold mb-1 text-white">Laporan Bulanan Keuangan</h4>
                <p class="text-white-50 mb-0 small">Rekap pemasukan, pengeluaran, dan laba bersih berdasarkan bulan</p>
            </div>
        </div>

        <div class="header-actions-elite">
            <a href="{{ route('laporan_bulanan.laporan.pdf', request()->query()) }}" target="_blank"
               class="btn btn-outline-elite-onheader">
                <i class="fas fa-file-pdf mr-1"></i> Laporan PDF
            </a>

            <a href="{{ route('bulanan.create') }}" class="btn btn-header-solid-elite">
                <i class="fas fa-plus mr-1"></i> Buat Laporan
            </a>
        </div>
    </div>


    {{-- RINGKASAN --}}
    <div class="row mb-2">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-success-elite"><i class="fas fa-arrow-down"></i></div>
                <div>
                    <div class="mini-stat-value-elite mini-stat-money-elite text-success">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </div>
                    <div class="mini-stat-label-elite">Total Pemasukan</div>
                    <div class="cell-sub-elite">{{ number_format($jumlahPemasukan, 0, ',', '.') }} transaksi</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-maroon-elite"><i class="fas fa-arrow-up"></i></div>
                <div>
                    <div class="mini-stat-value-elite mini-stat-money-elite">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </div>
                    <div class="mini-stat-label-elite">Total Pengeluaran</div>
                    <div class="cell-sub-elite">{{ number_format($jumlahPengeluaran, 0, ',', '.') }} transaksi</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-info-elite"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="mini-stat-value-elite mini-stat-money-elite {{ $labaBersih < 0 ? 'lh-negative' : 'lh-positive' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </div>
                    <div class="mini-stat-label-elite">Laba Bersih</div>
                    <div class="cell-sub-elite">Pemasukan dikurangi pengeluaran</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-gold-elite"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($jumlahLaporan, 0, ',', '.') }} Bulan</div>
                    <div class="mini-stat-label-elite">Jumlah Laporan</div>
                    <div class="cell-sub-elite">Sesuai filter yang aktif</div>
                </div>
            </div>
        </div>

    </div>


    {{-- FILTER --}}
    <div class="card-elite mb-4">
        <div class="card-body p-4">

            <div class="section-head-elite mb-3">
                <div class="section-icon-elite"><i class="fas fa-filter"></i></div>
                <div>
                    <h6 class="section-title-elite">Filter Laporan</h6>
                    <small class="section-subtitle-elite">Cari berdasarkan kode, rentang bulan, atau status</small>
                </div>
            </div>

            <form method="GET" action="{{ route('bulanan.index') }}" class="mt-3">
                <div class="row">

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="katakunci" class="form-label-elite">Kata Kunci</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-search"></i></span>
                            <input type="text" name="katakunci" id="katakunci" class="form-control-elite"
                                   value="{{ request('katakunci') }}" placeholder="Kode / catatan...">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="bulan_mulai" class="form-label-elite">Bulan Mulai</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-calendar-alt"></i></span>
                            <input type="month" name="bulan_mulai" id="bulan_mulai" class="form-control-elite"
                                   value="{{ request('bulan_mulai') }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="bulan_selesai" class="form-label-elite">Bulan Selesai</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-calendar-check"></i></span>
                            <input type="month" name="bulan_selesai" id="bulan_selesai" class="form-control-elite"
                                   value="{{ request('bulan_selesai') }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="status" class="form-label-elite">Status</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-toggle-on"></i></span>
                            <select name="status" id="status" class="form-control-elite">
                                <option value="">Semua Status</option>
                                <option value="Draft" @selected(request('status') === 'Draft')>Draft</option>
                                <option value="Final" @selected(request('status') === 'Final')>Final</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-1 col-md-6 mb-3">
                        <label for="per_page" class="form-label-elite">Data</label>
                        <div class="input-group-elite">
                            <select name="per_page" id="per_page" class="form-control-elite" style="padding-left:.85rem;">
                                @foreach ([5, 10, 25, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('bulanan.index') }}" class="btn btn-outline-elite px-4 mr-2">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- TABEL --}}
    <div class="card-elite mb-4">

        <div class="table-card-header-elite">
            <div class="section-head-elite">
                <div class="section-icon-elite"><i class="fas fa-list"></i></div>
                <div>
                    <h6 class="section-title-elite">Daftar Laporan Bulanan</h6>
                    <small class="section-subtitle-elite">Rekap keuangan per bulan</small>
                </div>
            </div>

            <span class="badge-pill-elite badge-maroon-elite">{{ $data->total() }} data</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-elite" width="100%" cellspacing="0" style="min-width:900px;">

                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Pemasukan</th>
                            <th>Pengeluaran</th>
                            <th>Laba Bersih</th>
                            <th>Transaksi</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>
                                    <span class="period-pill-elite">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $item->periode->locale('id')->translatedFormat('F Y') }}
                                    </span>
                                    <div class="cell-sub-elite">{{ $item->kode_laporan }}</div>
                                </td>

                                <td class="nowrap-elite">
                                    <strong class="money-total-elite">Rp {{ number_format($item->total_pemasukan, 0, ',', '.') }}</strong>
                                </td>

                                <td class="nowrap-elite">
                                    <strong class="money-total-elite">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</strong>
                                </td>

                                <td class="nowrap-elite">
                                    <strong class="{{ $item->laba_bersih < 0 ? 'money-neg-elite' : 'money-total-elite' }}">
                                        Rp {{ number_format($item->laba_bersih, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td class="nowrap-elite">
                                    <span class="money-pos-elite">{{ $item->jumlah_pemasukan }}</span>
                                    <span class="text-muted-elite">/</span>
                                    <span class="money-neg-elite">{{ $item->jumlah_pengeluaran }}</span>
                                </td>

                                <td>
                                    @if ($item->status === 'Final')
                                        <span class="badge-pill-elite badge-active-elite">
                                            <i class="fas fa-check mr-1"></i> {{ $item->status }}
                                        </span>
                                    @else
                                        <span class="badge-pill-elite badge-warning-elite">
                                            <i class="fas fa-pen mr-1"></i> {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center nowrap-elite">
                                    <a href="{{ route('bulanan.show', $item->id) }}" class="btn-action-elite action-view-elite"
                                       title="Detail" aria-label="Lihat detail laporan"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route('bulanan.edit', $item->id) }}" class="btn-action-elite action-edit-elite"
                                       title="Edit" aria-label="Edit laporan"><i class="fas fa-edit"></i></a>

                                    <form action="{{ route('bulanan.destroy', $item->id) }}" method="POST" class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-elite action-delete-elite"
                                                title="Hapus" aria-label="Hapus laporan"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state-elite">
                                        <div class="empty-icon-elite"><i class="fas fa-calendar-alt"></i></div>
                                        <h6>Belum ada laporan bulanan</h6>
                                        <p>Laporan dibuat otomatis saat ada transaksi, atau buat secara manual.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if ($data->hasPages())
                <div class="table-footer-elite">
                    <div class="text-muted-elite small">
                        Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data
                    </div>
                    <div class="pagination-elite">{{ $data->links() }}</div>
                </div>
            @endif
        </div>

    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/bulanan.css') }}">
@endpush

@endsection


@push('scripts')
    @include('pages.laporan.bulanan._preview-script')
@endpush