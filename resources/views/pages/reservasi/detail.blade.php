@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | STATUS RESERVASI & PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $pembayaranLunas =
        $data->pembayaran && $data->pembayaran->status === 'Lunas';

    $terkunci =
        $data->status === 'Dikonfirmasi' ||
        $data->status === 'Selesai' ||
        $data->status === 'Dibatalkan' ||
        $pembayaranLunas;

    $bolehEdit =
        $data->status === 'Menunggu' && !$pembayaranLunas;


    /*
    |--------------------------------------------------------------------------
    | DAFTAR PERAWATAN (MULTI + FALLBACK DATA LAMA)
    |--------------------------------------------------------------------------
    */

    $perawatanList = collect();

    if (isset($data->perawatans) && $data->perawatans->count() > 0) {

        $perawatanList = $data->perawatans;

    } elseif ($data->perawatan) {

        $perawatanList = collect([$data->perawatan]);

    }

    $totalDurasi = 0;
    $totalHarga  = 0;
    $totalJumlah = 0;

    $perawatanRows = $perawatanList->map(function ($item) use (&$totalDurasi, &$totalHarga, &$totalJumlah) {

        $jumlah = isset($item->pivot)
            ? (int) ($item->pivot->jumlah ?? 1)
            : 1;

        $harga = isset($item->pivot)
            ? (int) ($item->pivot->harga ?? $item->harga ?? 0)
            : (int) ($item->harga ?? 0);

        $durasi = isset($item->pivot)
            ? (int) ($item->pivot->durasi ?? $item->durasi ?? 0)
            : (int) ($item->durasi ?? 0);

        $totalDurasi += $durasi * $jumlah;
        $totalHarga  += $harga * $jumlah;
        $totalJumlah += $jumlah;

        return (object) [
            'item'           => $item,
            'jumlah'         => $jumlah,
            'harga'          => $harga,
            'durasi'         => $durasi,
            'subtotalHarga'  => $harga * $jumlah,
            'subtotalDurasi' => $durasi * $jumlah,
        ];

    });

@endphp

<div class="container-fluid px-4 detail-pelanggan-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('reservasi.index') }}">Data Reservasi</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Reservasi</h4>
        </div>

        <div class="d-flex gap-2">

            {{-- EDIT: hanya untuk reservasi menunggu --}}
            @if($bolehEdit)
                <a href="{{ route('reservasi.edit', $data->id) }}" class="btn btn-edit-elite px-3 mr-2">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            @endif

            {{-- LOCK --}}
            @if($terkunci)
                <span class="btn btn-locked-elite px-3 mr-2" title="Reservasi terkunci">
                    <i class="fas fa-lock mr-1"></i> Terkunci
                </span>
            @endif

            <a href="{{ route('reservasi.index') }}" class="btn btn-outline-elite px-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

        </div>

    </div>


    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="alert-elite alert-success-elite mb-4">
            <i class="fas fa-check-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
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
                        <div class="reservation-icon-square-elite">
                            <i class="fas fa-calendar-check"></i>
                            <span>Kode Reservasi</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">

                        <h5 class="fw-bold mb-1 title-elite">{{ $data->kode_reservasi }}</h5>

                        <button type="button"
                                class="btn btn-outline-elite btn-sm px-3 mb-3"
                                onclick="salinKodeReservasi('{{ $data->kode_reservasi }}')">
                            <i class="fas fa-copy mr-1"></i> Salin Kode
                        </button>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                            @if($data->status === 'Menunggu')
                                <span class="badge-pill-elite badge-warning-elite">
                                    <i class="fas fa-clock mr-1"></i> Menunggu
                                </span>
                            @elseif($data->status === 'Dikonfirmasi')
                                <span class="badge-pill-elite badge-info-elite">
                                    <i class="fas fa-check mr-1"></i> Dikonfirmasi
                                </span>
                            @elseif($data->status === 'Selesai')
                                <span class="badge-pill-elite badge-active-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge-pill-elite badge-gender-elite">
                                    <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                </span>
                            @endif

                        </div>

                        <div class="quick-contact-elite text-left">
                            <span class="detail-label-elite d-block mb-1">Pelanggan</span>
                            <span class="description-text-elite d-block font-weight-bold">
                                {{ optional($data->pelanggan)->nama ?? '-' }}
                            </span>
                            <span class="description-text-elite d-block">
                                {{ optional($data->pelanggan)->no_telepon ?? '-' }}
                            </span>
                        </div>

                    </div>

                </div>


                {{-- MINI SYSTEM INFO --}}
                <div class="system-info-mini-elite mt-3">

                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="sys-label-elite"><i class="fas fa-plus-circle mr-1"></i> Dibuat</span>
                        <span class="sys-value-elite">
                            @if($data->created_at)
                                {{ $data->created_at->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top-mini-elite">
                        <span class="sys-label-elite"><i class="fas fa-sync-alt mr-1"></i> Diperbarui</span>
                        <span class="sys-value-elite">
                            @if($data->updated_at)
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

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-maroon-elite">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div class="mini-stat-label-elite">Tanggal</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-gold-elite">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                {{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}
                            </div>
                            <div class="mini-stat-label-elite">Jam Reservasi</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-success-elite">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Total Harga</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- STATUS LOCK INFO --}}
            @if($terkunci)

                <div class="alert-elite alert-lock-elite mb-4">

                    <i class="fas fa-lock mr-3 mt-1"></i>

                    <div>

                        @if($data->status === 'Dikonfirmasi')

                            <strong class="text-info-elite">Reservasi telah dikonfirmasi.</strong>
                            <div class="small mt-1">
                                Pembayaran telah lunas sehingga data pelanggan, perawatan,
                                terapis, tanggal, dan jadwal tidak dapat diedit.
                            </div>

                        @elseif($data->status === 'Selesai')

                            <strong class="text-success-elite">Reservasi telah selesai.</strong>
                            <div class="small mt-1">
                                Data reservasi telah selesai diproses dan tidak dapat diubah.
                            </div>

                        @elseif($data->status === 'Dibatalkan')

                            <strong class="text-maroon-value-elite">Reservasi telah dibatalkan.</strong>
                            <div class="small mt-1">
                                Data reservasi yang telah dibatalkan tidak dapat diubah.
                            </div>

                        @else

                            <strong class="text-success-elite">Pembayaran telah lunas.</strong>
                            <div class="small mt-1">
                                Data reservasi tidak dapat diedit setelah pembayaran lunas.
                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- INFORMASI PELANGGAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Pelanggan</h6>
                            <small class="section-subtitle-elite">Data pelanggan yang melakukan reservasi</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Nama</span>
                            <span class="detail-value-elite">{{ optional($data->pelanggan)->nama ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">No. Telepon</span>
                            <span class="detail-value-elite">{{ optional($data->pelanggan)->no_telepon ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Email</span>
                            <span class="detail-value-elite">{{ optional($data->pelanggan)->email ?? '-' }}</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- INFORMASI PERAWATAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-spa"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Perawatan</h6>
                            <small class="section-subtitle-elite">Layanan perawatan yang dipesan</small>
                        </div>
                    </div>

                    @if($perawatanRows->count() > 0)

                        <div class="mt-3">

                            @foreach($perawatanRows as $row)

                                <div class="treatment-detail-item">

                                    <div class="d-flex align-items-center">

                                        {{-- FOTO --}}
                                        <div class="treatment-detail-image mr-3">
                                            @if($row->item->foto)
                                                <img src="{{ asset('storage/' . $row->item->foto) }}"
                                                     alt="{{ $row->item->nama }}">
                                            @else
                                                <i class="fas fa-spa"></i>
                                            @endif
                                        </div>

                                        {{-- INFORMASI --}}
                                        <div class="flex-grow-1">

                                            <div class="detail-value-elite">{{ $row->item->nama }}</div>

                                            <div class="small text-muted-elite mt-1">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $row->durasi }} menit
                                                @if($row->jumlah > 1)
                                                    × {{ $row->jumlah }}
                                                @endif
                                            </div>

                                            <div class="small text-maroon-value-elite mt-1">
                                                Rp {{ number_format($row->harga, 0, ',', '.') }}
                                                @if($row->jumlah > 1)
                                                    × {{ $row->jumlah }}
                                                @endif
                                            </div>

                                        </div>

                                        {{-- JUMLAH --}}
                                        <span class="badge-pill-elite badge-gender-elite ml-2">
                                            {{ $row->jumlah }}x
                                        </span>

                                    </div>

                                    {{-- SUBTOTAL --}}
                                    <div class="treatment-subtotal-elite">

                                        <span>
                                            Durasi: <strong>{{ $row->subtotalDurasi }} menit</strong>
                                        </span>

                                        <span>
                                            Subtotal:
                                            <strong class="text-maroon-value-elite">
                                                Rp {{ number_format($row->subtotalHarga, 0, ',', '.') }}
                                            </strong>
                                        </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                        {{-- RINGKASAN --}}
                        <div class="treatment-summary-elite mt-3">

                            <div class="summary-row-elite">
                                <span>Total Perawatan</span>
                                <strong>{{ $totalJumlah }} item</strong>
                            </div>

                            <div class="summary-row-elite">
                                <span>Total Durasi</span>
                                <strong>{{ $totalDurasi }} menit</strong>
                            </div>

                            <div class="summary-row-elite summary-total-elite">
                                <span>Total Harga</span>
                                <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                            </div>

                        </div>

                    @else

                        <div class="text-muted-elite mt-3">
                            <i class="fas fa-spa mr-1"></i>
                            Data perawatan tidak tersedia.
                        </div>

                    @endif

                </div>

            </div>


            {{-- JADWAL RESERVASI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Jadwal Reservasi</h6>
                            <small class="section-subtitle-elite">Tanggal dan waktu perawatan</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Tanggal</span>
                            <span class="detail-value-elite">
                                {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jam Mulai</span>
                            <span class="detail-value-elite">
                                <i class="fas fa-hourglass-start period-icon-success-elite mr-1"></i>
                                {{ \Carbon\Carbon::parse($data->jam_mulai)->format('H:i') }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jam Selesai</span>
                            <span class="detail-value-elite">
                                <i class="fas fa-hourglass-end period-icon-danger-elite mr-1"></i>
                                {{ \Carbon\Carbon::parse($data->jam_selesai)->format('H:i') }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- TERAPIS / PEGAWAI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Terapis / Pegawai</h6>
                            <small class="section-subtitle-elite">Terapis yang menangani reservasi</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Nama</span>
                            <span class="detail-value-elite">{{ optional($data->pegawai)->nama ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">No. Telepon</span>
                            <span class="detail-value-elite">{{ optional($data->pegawai)->no_telepon ?? '-' }}</span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Status</span>
                            <span class="detail-value-elite">

                                @if(optional($data->pegawai)->status === 'Aktif')

                                    <span class="badge-pill-elite badge-active-elite">Aktif</span>

                                @elseif(optional($data->pegawai)->status)

                                    <span class="badge-pill-elite badge-gender-elite">Tidak Aktif</span>

                                @else

                                    -

                                @endif

                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- PEMBAYARAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Pembayaran</h6>
                            <small class="section-subtitle-elite">Status dan rincian pembayaran reservasi</small>
                        </div>
                    </div>

                    @if($data->pembayaran)

                        <div class="detail-grid-elite">

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Status Pembayaran</span>
                                <span class="detail-value-elite">

                                    @if($data->pembayaran->status === 'Lunas')
                                        <span class="badge-pill-elite badge-active-elite">
                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @elseif($data->pembayaran->status === 'Menunggu')
                                        <span class="badge-pill-elite badge-warning-elite">
                                            <i class="fas fa-clock mr-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="badge-pill-elite badge-gender-elite">
                                            <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                        </span>
                                    @endif

                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Jumlah</span>
                                <span class="detail-value-elite text-maroon-value-elite">
                                    Rp {{ number_format($data->pembayaran->jumlah, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($data->pembayaran->tanggal_bayar)
                                <div class="detail-cell-elite">
                                    <span class="detail-label-elite">Tanggal Bayar</span>
                                    <span class="detail-value-elite">
                                        {{ \Carbon\Carbon::parse($data->pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            @endif

                            @if($data->pembayaran->metode_pembayaran)
                                <div class="detail-cell-elite">
                                    <span class="detail-label-elite">Metode</span>
                                    <span class="detail-value-elite">{{ $data->pembayaran->metode_pembayaran }}</span>
                                </div>
                            @endif

                        </div>


                        {{-- TOMBOL PEMBAYARAN --}}
                        <div class="mt-3">

                            @if($data->pembayaran->status === 'Menunggu')

                                <a href="{{ route('pembayaran.edit', $data->pembayaran->id) }}"
                                   class="btn btn-outline-success-elite">
                                    <i class="fas fa-receipt mr-1"></i> Konfirmasi Pembayaran
                                </a>

                            @else

                                <a href="{{ route('pembayaran.show', $data->pembayaran->id) }}"
                                   class="btn btn-outline-elite">
                                    <i class="fas fa-eye mr-1"></i> Lihat Pembayaran
                                </a>

                            @endif

                        </div>

                    @else

                        {{-- BELUM ADA PEMBAYARAN --}}
                        <div class="alert-elite alert-warning-elite mt-3 mb-0">

                            <i class="fas fa-clock mr-2 mt-1"></i>

                            <div>
                                <strong>Pembayaran belum dilakukan.</strong>
                                <div class="small mt-1">
                                    Reservasi akan otomatis menjadi <strong>Dikonfirmasi</strong>
                                    setelah pembayaran berhasil dikonfirmasi.
                                </div>

                                @if($data->kode_reservasi && $data->status === 'Menunggu')
                                    <a href="{{ route('pembayaran.create', ['kode_reservasi' => $data->kode_reservasi]) }}"
                                       class="btn btn-success-elite btn-sm mt-3">
                                        <i class="fas fa-credit-card mr-1"></i> Bayar Reservasi
                                    </a>
                                @endif
                            </div>

                        </div>

                    @endif

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
                            <small class="section-subtitle-elite">Catatan tambahan dari reservasi</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">
                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Catatan</span>
                            <span class="detail-value-elite">
                                @if($data->catatan)
                                    {!! nl2br(e($data->catatan)) !!}
                                @else
                                    Tidak ada catatan reservasi.
                                @endif
                            </span>
                        </div>
                    </div>

                </div>

            </div>


            {{-- AKSI RESERVASI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Aksi Reservasi</h6>
                            <small class="section-subtitle-elite">Tindakan yang tersedia sesuai status reservasi</small>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center mt-3">

                        {{-- ========== MENUNGGU ========== --}}
                        @if($data->status === 'Menunggu')

                            @if(!$data->pembayaran)

                                <a href="{{ route('pembayaran.create', ['kode_reservasi' => $data->kode_reservasi]) }}"
                                   class="btn btn-success-elite mr-2 mb-2">
                                    <i class="fas fa-credit-card mr-1"></i> Bayar Reservasi
                                </a>

                            @elseif($data->pembayaran->status === 'Menunggu')

                                <a href="{{ route('pembayaran.edit', $data->pembayaran->id) }}"
                                   class="btn btn-outline-success-elite mr-2 mb-2">
                                    <i class="fas fa-receipt mr-1"></i> Konfirmasi Pembayaran
                                </a>

                            @endif


                            @if($bolehEdit)

                                <a href="{{ route('reservasi.edit', $data->id) }}"
                                   class="btn btn-edit-elite mr-2 mb-2">
                                    <i class="fas fa-edit mr-1"></i> Edit Reservasi
                                </a>

                            @endif


                            <form action="{{ route('reservasi.batal', $data->id) }}"
                                  method="POST"
                                  class="mr-2 mb-2"
                                  onsubmit="return confirmBatal(event, this)">

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-gradient-elite">
                                    <i class="fas fa-times mr-1"></i> Batalkan Reservasi
                                </button>

                            </form>


                            <form action="{{ route('reservasi.destroy', $data->id) }}"
                                  method="POST"
                                  class="mb-2"
                                  onsubmit="return confirmHapus(event, this)">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger-elite">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>

                            </form>

                        @endif


                        {{-- ========== DIKONFIRMASI ========== --}}
                        @if($data->status === 'Dikonfirmasi')

                            <form action="{{ route('reservasi.selesai', $data->id) }}"
                                  method="POST"
                                  class="mr-2 mb-2"
                                  onsubmit="return confirmSelesai(event, this)">

                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-success-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Tandai Selesai
                                </button>

                            </form>

                            <div class="alert-elite alert-lock-elite mb-2 flex-grow-1">
                                <i class="fas fa-lock mr-2 mt-1"></i>
                                <div>
                                    <strong>Reservasi terkunci.</strong>
                                    Data reservasi tidak dapat diedit setelah pembayaran dikonfirmasi.
                                </div>
                            </div>

                        @endif


                        {{-- ========== SELESAI ========== --}}
                        @if($data->status === 'Selesai')

                            <div class="alert-elite alert-success-elite mb-0 w-100">
                                <i class="fas fa-check-circle mr-2 mt-1"></i>
                                <div>
                                    <strong>Reservasi telah selesai.</strong>
                                    Data reservasi tidak dapat diubah.
                                </div>
                            </div>

                        @endif


                        {{-- ========== DIBATALKAN ========== --}}
                        @if($data->status === 'Dibatalkan')

                            <div class="alert-elite alert-danger-elite mb-0 w-100">
                                <i class="fas fa-times-circle mr-2 mt-1"></i>
                                <div>
                                    <strong>Reservasi telah dibatalkan.</strong>
                                    Data reservasi tidak dapat diubah.
                                </div>
                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- INFORMASI PENJADWALAN --}}
            <div class="card-elite">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Penjadwalan</h6>
                            <small class="section-subtitle-elite">Pendekatan yang dipakai sistem untuk mengatur jadwal</small>
                        </div>
                    </div>

                    <p class="description-text-elite mt-3 mb-0">
                        Sistem menggunakan pendekatan
                        <strong>Greedy dengan Interval Scheduling</strong>
                        untuk membantu mencari jadwal yang sesuai dan
                        mengurangi kemungkinan terjadinya tumpang tindih
                        jadwal terapis.
                    </p>

                    <div class="detail-grid-elite detail-grid-3-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jam Operasional</span>
                            <span class="detail-value-elite">08:00 - 17:00</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Toleransi Bentrok</span>
                            <span class="detail-value-elite">15 menit</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Status Saat Ini</span>
                            <span class="detail-value-elite">{{ $data->status }}</span>
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
.text-muted-elite { color: #a09a94; }
.text-info-elite { color: #3b6fd4; }
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
.alert-elite strong { color: #3a2a26; }

.alert-success-elite { background: #eefaf3; border: 1px solid rgba(28,200,138,.25); }
.alert-success-elite > i { color: #1cc88a; }

.alert-danger-elite { background: #fff0f0; border: 1px solid rgba(224,28,29,.2); }
.alert-danger-elite > i { color: var(--sb-red); }

.alert-warning-elite { background: #fdf6ea; border: 1px solid rgba(217,172,79,.35); color: #8a6a2f; }
.alert-warning-elite > i { color: var(--sb-gold); }

.alert-lock-elite { background: #fdfaf5; border: 1px solid rgba(217,172,79,.25); }
.alert-lock-elite > i { color: #8a7d74; font-size: 16px; }

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

.reservation-icon-square-elite {
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
.reservation-icon-square-elite span {
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

.badge-gender-elite  { background: #fff0f0; color: var(--sb-red); }
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
.text-maroon-value-elite {
    color: var(--sb-red);
    font-weight: 700;
}

.period-icon-success-elite { color: #1cc88a; }
.period-icon-danger-elite  { color: var(--sb-red); }


/* =========================================================
   DETAIL PERAWATAN
========================================================== */

.treatment-detail-item {
    padding: 12px;
    border: 1px solid rgba(217,172,79,.18);
    border-radius: 12px;
    margin-bottom: 10px;
    background: #fff;
    transition: all .2s ease;
}
.treatment-detail-item:hover {
    border-color: var(--sb-gold);
    background: #fffdf9;
}

.treatment-detail-image {
    width: 60px;
    height: 60px;
    min-width: 60px;
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff0f0;
    color: var(--sb-red);
    font-size: 22px;
}
.treatment-detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.treatment-subtotal-elite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px dashed rgba(217,172,79,.35);
    font-size: 11px;
    color: #a09a94;
}

.treatment-summary-elite {
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

.btn-success-elite {
    background: linear-gradient(135deg, #1cc88a, #12865d);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all .25s ease;
    box-shadow: 0 4px 14px rgba(28,200,138,.3);
}
.btn-success-elite:hover {
    color: #fff;
    filter: brightness(1.06);
    transform: translateY(-1px);
}

.btn-outline-success-elite {
    border-radius: 10px;
    font-weight: 600;
    border: 1px solid #1cc88a;
    color: #12865d;
    background: transparent;
    transition: all .25s ease;
}
.btn-outline-success-elite:hover {
    background: #e8f8f1;
    color: #12865d;
    border-color: #1cc88a;
}

.btn-outline-danger-elite {
    border-radius: 10px;
    font-weight: 600;
    border: 1px solid var(--sb-red);
    color: var(--sb-red);
    background: transparent;
    transition: all .25s ease;
}
.btn-outline-danger-elite:hover {
    background: #fff0f0;
    color: var(--sb-red-dark);
    border-color: var(--sb-red);
}

.btn-locked-elite {
    border-radius: 10px;
    font-weight: 600;
    background: #f4efe9;
    color: #8a7d74;
    border: 1px solid rgba(217,172,79,.2);
    cursor: default;
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
    .treatment-detail-image {
        width: 50px;
        height: 50px;
        min-width: 50px;
    }
    .treatment-subtotal-elite {
        flex-direction: column;
        align-items: flex-start;
    }
}

</style>


@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | SALIN KODE RESERVASI
    |--------------------------------------------------------------------------
    */

    function salinKodeReservasi(kode) {

        if (navigator.clipboard && navigator.clipboard.writeText) {

            navigator.clipboard.writeText(kode)

                .then(function () {

                    tampilkanBerhasil('Kode reservasi berhasil disalin.');

                })

                .catch(function () {

                    fallbackSalin(kode);

                });

        } else {

            fallbackSalin(kode);

        }

    }


    function fallbackSalin(kode) {

        const textarea = document.createElement('textarea');

        textarea.value = kode;

        textarea.style.position = 'fixed';

        textarea.style.opacity = '0';

        document.body.appendChild(textarea);

        textarea.select();

        try {

            document.execCommand('copy');

            tampilkanBerhasil('Kode reservasi berhasil disalin.');

        } catch (error) {

            alert('Kode reservasi: ' + kode);

        }

        document.body.removeChild(textarea);

    }


    function tampilkanBerhasil(pesan) {

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text: pesan,

                timer: 1500,

                showConfirmButton: false

            });

        } else {

            alert(pesan);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI BATAL
    |--------------------------------------------------------------------------
    */

    function confirmBatal(event, form) {

        event.preventDefault();

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                title: 'Batalkan reservasi?',

                html:
                    'Reservasi akan berubah menjadi <strong>Dibatalkan</strong>.<br>' +
                    'Data tidak dapat diedit kembali.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#e01c1d',

                cancelButtonColor: '#858796',

                confirmButtonText: 'Ya, batalkan',

                cancelButtonText: 'Tidak',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        } else if (confirm('Yakin ingin membatalkan reservasi?')) {

            form.submit();

        }

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS
    |--------------------------------------------------------------------------
    */

    function confirmHapus(event, form) {

        event.preventDefault();

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                title: 'Hapus reservasi?',

                text: 'Data reservasi yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#e01c1d',

                cancelButtonColor: '#858796',

                confirmButtonText: 'Ya, hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        } else if (confirm('Yakin ingin menghapus reservasi?')) {

            form.submit();

        }

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI SELESAI
    |--------------------------------------------------------------------------
    */

    function confirmSelesai(event, form) {

        event.preventDefault();

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                title: 'Selesaikan reservasi?',

                html: 'Status reservasi akan berubah menjadi <strong>SELESAI</strong>.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#1cc88a',

                cancelButtonColor: '#858796',

                confirmButtonText: 'Ya, tandai selesai',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        } else if (confirm('Yakin ingin menandai reservasi sebagai selesai?')) {

            form.submit();

        }

        return false;

    }

</script>

@endpush

@endsection