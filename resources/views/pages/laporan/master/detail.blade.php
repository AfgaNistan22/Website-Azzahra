@extends('layouts.app')

@section('title', 'Detail Laporan Master')

@section('content')

@php
    $periodeTeks = ($data->tanggal_mulai || $data->tanggal_selesai)
        ? ($data->tanggal_mulai?->format('d/m/Y') ?? '...') . ' - ' . ($data->tanggal_selesai?->format('d/m/Y') ?? 'sekarang')
        : 'Semua data';
@endphp

<div class="container-fluid px-4 bulanan-elite master-elite">

    {{-- HEADER --}}
    <div class="page-header-elite mb-4">
        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('master.index') }}">Laporan Master</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Laporan Master</h4>
        </div>

        <div class="d-flex">
            <a href="{{ route('master.edit', $data->id) }}" class="btn btn-edit-elite px-3 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            <a href="{{ route('laporan_master.laporan.pdf', ['katakunci' => $data->kode_laporan]) }}"
               target="_blank" class="btn btn-gradient-elite px-3 mr-2">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>

            <a href="{{ route('master.index') }}" class="btn btn-outline-elite px-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>


    <div class="row">

        {{-- KIRI --}}
        <div class="col-lg-4 mb-4">
            <div class="profile-card-sticky-elite">

                <div class="profile-card-elite">
                    <div class="profile-cover-mini-elite"></div>

                    <div class="px-4 photo-wrap-square-elite">
                        <div class="icon-square-elite">
                            <i class="fas {{ $kat['ikon'] ?? 'fa-layer-group' }}"></i>
                            <span>{{ $kat['label'] ?? \Illuminate\Support\Str::headline($data->kategori) }}</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">
                        <div class="detail-label-elite mb-1">Kode Laporan</div>
                        <h5 class="fw-bold mb-3 title-elite">{{ $data->kode_laporan }}</h5>

                        <div class="d-flex justify-content-center flex-wrap mb-4">
                            @if ($data->status === 'Final')
                                <span class="badge-pill-elite badge-active-elite"><i class="fas fa-check-circle mr-1"></i> Final</span>
                            @else
                                <span class="badge-pill-elite badge-warning-elite"><i class="fas fa-pen mr-1"></i> {{ $data->status }}</span>
                            @endif
                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Jumlah Data</span>
                            <span class="total-highlight-elite">{{ number_format($data->jumlah_data, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="system-info-mini-elite mt-3">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="sys-label-elite"><i class="fas fa-plus-circle mr-1"></i> Dibuat</span>
                        <span class="sys-value-elite">{{ $data->created_at ? $data->created_at->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-top-mini-elite">
                        <span class="sys-label-elite"><i class="fas fa-sync-alt mr-1"></i> Diperbarui</span>
                        <span class="sys-value-elite">{{ $data->updated_at ? $data->updated_at->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                </div>

            </div>
        </div>


        {{-- KANAN --}}
        <div class="col-lg-8">

            {{-- STAT --}}
            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-maroon-elite"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">{{ $periodeTeks }}</div>
                            <div class="mini-stat-label-elite">Periode</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-info-elite"><i class="fas fa-database"></i></div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">{{ number_format($data->jumlah_data, 0, ',', '.') }} data</div>
                            <div class="mini-stat-label-elite">Jumlah Data</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-success-elite"><i class="fas fa-coins"></i></div>
                        <div>
                            @if ($data->kolom_nominal)
                                <div class="mini-stat-value-elite mini-stat-small-elite">Rp {{ number_format($data->total_nominal, 0, ',', '.') }}</div>
                                <div class="mini-stat-label-elite">Total {{ $data->kolom_nominal }}</div>
                            @else
                                <div class="mini-stat-value-elite mini-stat-small-elite">-</div>
                                <div class="mini-stat-label-elite">Total Nominal</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            {{-- DATA KATEGORI --}}
            <div class="card-elite mb-4">

                <div class="table-card-header-elite">
                    <div class="section-head-elite">
                        <div class="section-icon-elite"><i class="fas {{ $kat['ikon'] ?? 'fa-table' }}"></i></div>
                        <div>
                            <h6 class="section-title-elite">Data {{ $kat['label'] ?? 'Kategori' }}</h6>
                            <small class="section-subtitle-elite">Seluruh data pada kategori dan periode laporan ini</small>
                        </div>
                    </div>

                    @if ($baris)
                        <form method="GET" class="m-0">
                            <select name="per_page" class="form-control modern-input-elite" style="height:36px;width:auto;"
                                    onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>{{ $n }} / halaman</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                @if (!$kat)

                    <div class="card-body p-4">
                        <div class="notice-warning-elite">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Kategori <strong>{{ $data->kategori }}</strong> tidak lagi tersedia (model tidak ditemukan atau dihapus dari daftar),
                            sehingga datanya tidak dapat ditampilkan. Ubah kategori lewat tombol Edit.
                        </div>
                    </div>

                @else

                    @if (!empty($totalUang))
                        <div class="px-4 pt-3">
                            @foreach ($totalUang as $t)
                                <span class="chip-kolom-elite">{{ $t['label'] }}: {{ $t['nilai'] }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="table-responsive data-table-wrap-elite">
                        <table class="table-elite" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width:50px;">No</th>
                                    @foreach ($kolom as $label)
                                        <th>{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($baris as $row)
                                    <tr>
                                        <td class="text-muted-elite">{{ $baris->firstItem() + $loop->index }}</td>

                                        @foreach ($row as $sel)
                                            @php $jenis = $sel['k'] ?? ''; @endphp

                                            <td class="{{ $jenis === 'money' ? 'nowrap-elite text-right' : '' }}">
                                                @if (str_starts_with($jenis, 'badge-'))
                                                    <span class="badge-pill-elite {{ $jenis }}">{{ $sel['t'] }}</span>
                                                @elseif ($jenis === 'money')
                                                    <strong class="money-total-elite">{{ $sel['t'] }}</strong>
                                                @else
                                                    {{ $sel['t'] }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($kolom) + 1 }}" class="text-center text-muted-elite py-4">
                                            Tidak ada data pada kategori dan periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($baris->hasPages())
                        <div class="table-footer-elite">
                            <div class="text-muted-elite small">
                                Menampilkan {{ $baris->firstItem() }} - {{ $baris->lastItem() }} dari {{ $baris->total() }} data
                            </div>
                            <div class="pagination-elite">{{ $baris->links() }}</div>
                        </div>
                    @endif

                @endif

            </div>


            {{-- CATATAN --}}
            <div class="card-elite mb-4">
                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite"><i class="fas fa-sticky-note"></i></div>
                        <div>
                            <h6 class="section-title-elite">Catatan</h6>
                            <small class="section-subtitle-elite">Catatan tambahan pada laporan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">
                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Catatan</span>
                            <span class="detail-value-elite">{!! nl2br(e($data->catatan ?: '-')) !!}</span>
                        </div>
                    </div>

                </div>
            </div>


            {{-- INFORMASI SISTEM --}}
            <div class="card-elite">
                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite"><i class="fas fa-database"></i></div>
                        <div>
                            <h6 class="section-title-elite">Informasi Sistem</h6>
                            <small class="section-subtitle-elite">Informasi pencatatan laporan master</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite detail-grid-3-elite">
                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Kode Laporan</span>
                            <span class="detail-value-elite">{{ $data->kode_laporan }}</span>
                        </div>
                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Data Dibuat</span>
                            <span class="detail-value-elite">{{ $data->created_at ? $data->created_at->translatedFormat('d F Y, H:i') : '-' }}</span>
                        </div>
                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Terakhir Diperbarui</span>
                            <span class="detail-value-elite">{{ $data->updated_at ? $data->updated_at->translatedFormat('d F Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/master.css') }}">
@endpush

@endsection