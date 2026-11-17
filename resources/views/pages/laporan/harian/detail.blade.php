@extends('layouts.app')

@section('title', 'Detail Laporan Harian')

@section('content')

@php
    $labaHitung = $data->total_pemasukan - $data->total_pengeluaran;
@endphp

<div class="container-fluid px-4 detail-harian-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('harian.index') }}">Laporan Harian</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Laporan Harian</h4>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('harian.edit', ['laporan_harian' => $data->id]) }}"
               class="btn btn-edit-elite px-3 mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>

            <a href="{{ route('laporan_harian.laporan.pdf', ['katakunci' => $data->kode_laporan]) }}"
               target="_blank"
               class="btn btn-gradient-elite px-3 mr-2">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>

            <a href="{{ route('harian.index') }}" class="btn btn-outline-elite px-3">
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
                        <div class="tax-icon-square-elite">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Laporan Harian</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">

                        <div class="detail-label-elite mb-1">Kode Laporan</div>

                        <h5 class="fw-bold mb-3 title-elite">{{ $data->kode_laporan }}</h5>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                            @if($data->status === 'Final')
                                <span class="badge-pill-elite badge-active-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Final
                                </span>
                            @else
                                <span class="badge-pill-elite badge-warning-elite">
                                    <i class="fas fa-pen mr-1"></i> {{ $data->status }}
                                </span>
                            @endif

                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Laba Bersih</span>
                            <span class="total-highlight-elite {{ $labaHitung < 0 ? '' : 'lh-positive' }}">
                                Rp {{ number_format($labaHitung, 0, ',', '.') }}
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
                                {{ $data->tanggal?->translatedFormat('d M Y') }}
                            </div>
                            <div class="mini-stat-label-elite">Tanggal Laporan</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-success-elite">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($data->total_pemasukan, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Pemasukan</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-gold-elite">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($data->total_pengeluaran, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Pengeluaran</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- RINCIAN KEUANGAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Rincian Keuangan</h6>
                            <small class="section-subtitle-elite">Rincian kondisi keuangan harian</small>
                        </div>
                    </div>

                    <div class="gaji-summary-elite mt-3">

                        <div class="summary-row-elite">
                            <span>Total Pemasukan</span>
                            <strong class="lh-positive">
                                + Rp {{ number_format($data->total_pemasukan, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="summary-row-elite">
                            <span>Total Pengeluaran</span>
                            <strong class="text-maroon-value-elite">
                                - Rp {{ number_format($data->total_pengeluaran, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="summary-row-elite summary-total-elite">
                            <span>Laba Bersih</span>
                            <strong>Rp {{ number_format($labaHitung, 0, ',', '.') }}</strong>
                        </div>

                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Transaksi Pemasukan</span>
                            <span class="detail-value-elite">{{ $data->jumlah_pemasukan }} transaksi</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Transaksi Pengeluaran</span>
                            <span class="detail-value-elite">{{ $data->jumlah_pengeluaran }} transaksi</span>
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
                            <small class="section-subtitle-elite">Catatan tambahan pada laporan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">
                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Catatan</span>
                            <span class="detail-value-elite">
                                {!! nl2br(e($data->catatan ?: '-')) !!}
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
                            <small class="section-subtitle-elite">Informasi pencatatan laporan harian</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite detail-grid-3-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Kode Laporan</span>
                            <span class="detail-value-elite">{{ $data->kode_laporan }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Data Dibuat</span>
                            <span class="detail-value-elite">
                                {{ $data->created_at ? $data->created_at->translatedFormat('d F Y, H:i') : '-' }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Terakhir Diperbarui</span>
                            <span class="detail-value-elite">
                                {{ $data->updated_at ? $data->updated_at->translatedFormat('d F Y, H:i') : '-' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/harian.css') }}">
@endpush

@endsection