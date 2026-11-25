@extends('layouts.app')

@section('title', 'Data Pembayaran')

@section('content')

<div class="container-fluid px-4 bayar-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Data Pembayaran</h4>
                <p class="text-white-50 mb-0 small ml-2">
                    Kelola pembayaran reservasi pelanggan
                </p>
            </div>
        </div>

        <div class="header-actions-elite position-relative">

            <a href="{{ route('pembayaran.create') }}" class="btn btn-header-solid-elite">
                <i class="fas fa-plus mr-1"></i>
                Tambah Pembayaran
            </a>

        </div>

    </div>


    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="alert-elite alert-success-elite mb-4" data-autohide="true">
            <i class="fas fa-check-circle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Berhasil</strong>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Terjadi Kesalahan</strong>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
            <div class="alert-body-elite">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close-elite" onclick="this.closest('.alert-elite').remove()" aria-label="Tutup">&times;</button>
        </div>
    @endif


    {{-- ================= SUMMARY ================= --}}
    <div class="row mb-2">

        {{-- TOTAL --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-maroon-elite">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite">{{ $totalPembayaran }}</div>
                    <div class="mini-stat-label-elite">Total Pembayaran</div>
                </div>
            </div>
        </div>

        {{-- LUNAS --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-success-elite">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite text-success-elite">{{ $totalLunas }}</div>
                    <div class="mini-stat-label-elite">Pembayaran Lunas</div>
                </div>
            </div>
        </div>

        {{-- MENUNGGU --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-gold-elite">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite text-gold-elite">{{ $totalMenunggu }}</div>
                    <div class="mini-stat-label-elite">Menunggu Konfirmasi</div>
                </div>
            </div>
        </div>

        {{-- TOTAL NOMINAL --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-info-elite">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="mini-stat-value-elite mini-stat-money-elite">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </div>
                    <div class="mini-stat-label-elite">Total Nominal Lunas</div>
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
                    <h6 class="section-title-elite">Filter Data</h6>
                    <small class="section-subtitle-elite">Cari dan saring pembayaran pelanggan</small>
                </div>
            </div>

            <form method="GET" action="{{ route('pembayaran.index') }}" class="mt-3">

                <div class="row">

                    {{-- PENCARIAN --}}
                    <div class="col-lg-7 col-md-6 mb-3">

                        <label for="katakunci" class="form-label-elite">Pencarian</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="katakunci"
                                   id="katakunci"
                                   class="form-control-elite"
                                   placeholder="Kode reservasi, pelanggan, metode..."
                                   value="{{ request('katakunci') }}">
                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-3 col-md-4 mb-3">

                        <label for="status" class="form-label-elite">Status</label>

                        <div class="input-group-elite">
                            <span class="input-icon-box-elite">
                                <i class="fas fa-toggle-on"></i>
                            </span>

                            <select name="status" id="status" class="form-control-elite">

                                <option value="">Semua Status</option>

                                <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>
                                    Menunggu
                                </option>

                                <option value="Lunas" {{ request('status') === 'Lunas' ? 'selected' : '' }}>
                                    Lunas
                                </option>

                                <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>

                            </select>
                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-lg-2 col-md-2 mb-3">

                        <label class="form-label-elite d-block">&nbsp;</label>

                        <div class="filter-actions-elite">

                            <button type="submit" class="btn btn-gradient-elite btn-filter-elite">
                                <i class="fas fa-search mr-1"></i>
                                Cari
                            </button>

                            <a href="{{ route('pembayaran.index') }}"
                               class="btn-reset-icon-elite"
                               title="Reset"
                               aria-label="Reset filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>


            {{-- FILTER AKTIF --}}
            @if(request('katakunci') || request('status'))

                <div class="active-filter-elite">

                    <div>

                        <i class="fas fa-filter mr-2"></i>

                        <strong>Filter aktif</strong>

                        @if(request('katakunci'))
                            <span class="filter-chip-elite">
                                Pencarian: "{{ request('katakunci') }}"
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="filter-chip-elite filter-chip-danger-elite">
                                Status: {{ request('status') }}
                            </span>
                        @endif

                    </div>

                    <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-elite btn-sm px-3">
                        <i class="fas fa-times mr-1"></i>
                        Reset
                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div class="card-elite mb-4">

        <div class="table-card-header-elite">

            <div class="section-head-elite">
                <div class="section-icon-elite">
                    <i class="fas fa-receipt"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Daftar Pembayaran</h6>
                    <small class="section-subtitle-elite">Riwayat pembayaran reservasi pelanggan</small>
                </div>
            </div>

            <span class="badge-pill-elite badge-maroon-elite">
                <i class="fas fa-database mr-1"></i>
                {{ $data->total() }} data
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table-elite table-wide-elite" width="100%" cellspacing="0">

                    <thead>
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th>Reservasi</th>
                            <th>Pelanggan</th>
                            <th>Perawatan</th>
                            <th>Terapis</th>
                            <th>Tanggal</th>
                            <th class="text-center">Metode</th>
                            <th>Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th width="9%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $pembayaran)

                            <tr>

                                {{-- NO --}}
                                <td class="text-center cell-number-elite">
                                    {{ $data->firstItem() + $loop->index }}
                                </td>


                                {{-- RESERVASI --}}
                                <td>

                                    @if($pembayaran->reservasi)

                                        <a href="{{ route('reservasi.show', $pembayaran->reservasi->id) }}"
                                           class="reservation-code-elite">
                                            <i class="fas fa-ticket-alt mr-1"></i>
                                            {{ $pembayaran->reservasi->kode_reservasi }}
                                        </a>

                                        <div class="schedule-mini-elite">

                                            @if($pembayaran->reservasi->tanggal)
                                                <div>
                                                    <i class="far fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($pembayaran->reservasi->tanggal)->format('d/m/Y') }}
                                                </div>
                                            @endif

                                            @if($pembayaran->reservasi->jam_mulai && $pembayaran->reservasi->jam_selesai)
                                                <div>
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($pembayaran->reservasi->jam_mulai)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($pembayaran->reservasi->jam_selesai)->format('H:i') }}
                                                </div>
                                            @endif

                                        </div>

                                    @else

                                        <span class="text-muted-elite">Data reservasi tidak tersedia</span>

                                    @endif

                                </td>


                                {{-- PELANGGAN --}}
                                <td>

                                    @if($pembayaran->reservasi && $pembayaran->reservasi->pelanggan)

                                        @php $namaPelanggan = $pembayaran->reservasi->pelanggan->nama; @endphp

                                        <div class="d-flex align-items-center">

                                            <div class="avatar-elite">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($namaPelanggan, 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="cell-title-elite nowrap-elite">
                                                    {{ $namaPelanggan }}
                                                </div>

                                                @if($pembayaran->reservasi->pelanggan->no_telepon)
                                                    <small class="text-muted-elite">
                                                        <i class="fas fa-phone mr-1"></i>
                                                        {{ $pembayaran->reservasi->pelanggan->no_telepon }}
                                                    </small>
                                                @endif
                                            </div>

                                        </div>

                                    @else

                                        <span class="text-muted-elite">-</span>

                                    @endif

                                </td>


                                {{-- PERAWATAN --}}
                                <td class="treatment-col-elite">

                                    @if($pembayaran->reservasi)

                                        @php

                                            $perawatans = $pembayaran->reservasi->perawatans;

                                            if (
                                                $perawatans->isEmpty() &&
                                                $pembayaran->reservasi->perawatan
                                            ) {
                                                $perawatans = collect([
                                                    $pembayaran->reservasi->perawatan
                                                ]);
                                            }

                                        @endphp

                                        @if($perawatans->count() > 0)

                                            @foreach($perawatans as $perawatan)

                                                <div class="treatment-line-index-elite">

                                                    <span class="treatment-icon-elite">
                                                        <i class="fas fa-spa"></i>
                                                    </span>

                                                    <div class="treatment-name-elite">

                                                        {{ $perawatan->nama }}

                                                        @if(
                                                            isset($perawatan->pivot->jumlah) &&
                                                            $perawatan->pivot->jumlah > 1
                                                        )
                                                            <span class="qty-badge-elite">
                                                                x{{ $perawatan->pivot->jumlah }}
                                                            </span>
                                                        @endif

                                                    </div>

                                                </div>

                                            @endforeach

                                        @else

                                            <span class="text-muted-elite">-</span>

                                        @endif

                                    @else

                                        <span class="text-muted-elite">-</span>

                                    @endif

                                </td>


                                {{-- TERAPIS --}}
                                <td>

                                    @if($pembayaran->reservasi && $pembayaran->reservasi->pegawai)

                                        <div class="therapist-elite">

                                            <div class="therapist-icon-elite">
                                                <i class="fas fa-user-md"></i>
                                            </div>

                                            <span class="cell-text-elite">
                                                {{ $pembayaran->reservasi->pegawai->nama }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="text-muted-elite">-</span>

                                    @endif

                                </td>


                                {{-- TANGGAL --}}
                                <td>

                                    @if($pembayaran->tanggal_bayar)

                                        <div class="payment-date-elite">
                                            <i class="far fa-calendar-check mr-1"></i>
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}
                                        </div>

                                        @if($pembayaran->status === 'Lunas')
                                            <small class="text-success-elite">
                                                <i class="fas fa-check mr-1"></i>
                                                Sudah dibayar
                                            </small>
                                        @else
                                            <small class="text-muted-elite">
                                                Tanggal pembayaran
                                            </small>
                                        @endif

                                    @else

                                        <span class="status-badge-elite status-warning-elite">
                                            <i class="fas fa-clock"></i>
                                            Belum dibayar
                                        </span>

                                    @endif

                                </td>


                                {{-- METODE --}}
                                <td class="text-center">

                                    @if($pembayaran->metode_pembayaran === 'Cash')

                                        <span class="method-badge-elite method-cash-elite">
                                            <i class="fas fa-money-bill-wave"></i> Cash
                                        </span>

                                    @elseif($pembayaran->metode_pembayaran === 'Transfer')

                                        <span class="method-badge-elite method-transfer-elite">
                                            <i class="fas fa-university"></i> Transfer
                                        </span>

                                    @elseif($pembayaran->metode_pembayaran === 'E-Wallet')

                                        <span class="method-badge-elite method-ewallet-elite">
                                            <i class="fas fa-mobile-alt"></i> E-Wallet
                                        </span>

                                    @else

                                        <span class="method-badge-elite method-none-elite">
                                            Belum dipilih
                                        </span>

                                    @endif

                                </td>


                                {{-- NOMINAL + PROMO --}}
                                <td>

                                    @php

                                        $jumlahAwal =
                                            ($pembayaran->jumlah_sebelum_diskon ?? 0) > 0
                                                ? $pembayaran->jumlah_sebelum_diskon
                                                : $pembayaran->jumlah;

                                        $jumlahDiskon =
                                            $pembayaran->jumlah_diskon ?? 0;

                                    @endphp

                                    @if($jumlahDiskon > 0)

                                        <div class="price-old-elite">
                                            Rp {{ number_format($jumlahAwal, 0, ',', '.') }}
                                        </div>

                                        <div class="price-final-elite">
                                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </div>

                                        <div class="discount-info-elite">
                                            <i class="fas fa-tags mr-1"></i>
                                            Hemat Rp {{ number_format($jumlahDiskon, 0, ',', '.') }}
                                        </div>

                                        @if($pembayaran->promo)
                                            <span class="promo-badge-elite">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $pembayaran->promo->nama }}
                                            </span>
                                        @endif

                                    @else

                                        <div class="price-final-elite">
                                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </div>

                                        <small class="text-muted-elite">Tanpa promo</small>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td class="text-center">

                                    @if($pembayaran->status === 'Lunas')

                                        <span class="status-badge-elite status-success-elite">
                                            <i class="fas fa-check-circle"></i> Lunas
                                        </span>

                                    @elseif($pembayaran->status === 'Menunggu')

                                        <span class="status-badge-elite status-warning-elite">
                                            <i class="fas fa-clock"></i> Menunggu
                                        </span>

                                        @if($pembayaran->bukti_bayar)
                                            <div class="mt-1">
                                                <span class="badge badge-primary px-2 py-1 font-weight-normal" style="font-size: 11px;">
                                                    <i class="fas fa-file-invoice mr-1"></i> Ada Bukti
                                                </span>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                <small class="text-muted" style="font-size: 10px;">Belum Unggah</small>
                                            </div>
                                        @endif

                                    @elseif($pembayaran->status === 'Dibatalkan')

                                        <span class="status-badge-elite status-danger-elite">
                                            <i class="fas fa-times-circle"></i> Dibatalkan
                                        </span>

                                    @else

                                        <span class="status-badge-elite status-secondary-elite">
                                            {{ $pembayaran->status }}
                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="text-center">

                                    <div class="action-group-elite">

                                        {{-- DETAIL --}}
                                        <a href="{{ route('pembayaran.show', $pembayaran->id) }}"
                                           class="btn-action-elite action-view-elite"
                                           title="Detail Pembayaran"
                                           aria-label="Lihat detail pembayaran">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- BAYAR --}}
                                        @if($pembayaran->status === 'Menunggu')

                                            <a href="{{ route('pembayaran.edit', $pembayaran->id) }}"
                                               class="btn-action-elite action-pay-elite"
                                               title="Bayar Pembayaran"
                                               aria-label="Bayar pembayaran">
                                                <i class="fas fa-credit-card"></i>
                                            </a>

                                        @endif

                                        {{-- HAPUS --}}
                                        @if($pembayaran->status === 'Menunggu')

                                            <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}"
                                                  method="POST"
                                                  class="form-hapus d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn-action-elite action-delete-elite"
                                                        title="Hapus Pembayaran"
                                                        aria-label="Hapus pembayaran">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-5">

                                    <div class="empty-state-elite">

                                        <div class="empty-icon-elite">
                                            <i class="fas fa-money-check-alt"></i>
                                        </div>

                                        <h6>Data Pembayaran Belum Tersedia</h6>

                                        @if(request('katakunci') || request('status'))

                                            <p>Tidak ditemukan pembayaran sesuai filter yang dipilih.</p>

                                            <a href="{{ route('pembayaran.index') }}" class="btn btn-gradient-elite btn-sm mt-3 px-3">
                                                <i class="fas fa-sync-alt mr-1"></i>
                                                Reset Filter
                                            </a>

                                        @else

                                            <p>Silakan tambahkan data pembayaran terlebih dahulu.</p>

                                            <a href="{{ route('pembayaran.create') }}" class="btn btn-gradient-elite btn-sm mt-3 px-3">
                                                <i class="fas fa-plus mr-1"></i>
                                                Tambah Pembayaran
                                            </a>

                                        @endif

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
                        <strong>{{ $data->firstItem() }}</strong>
                        -
                        <strong>{{ $data->lastItem() }}</strong>
                        dari
                        <strong>{{ $data->total() }}</strong>
                        data
                    </div>

                    <div class="pagination-elite">
                        {{ $data->appends(request()->query())->links() }}
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ================= ALUR PEMBAYARAN ================= --}}
    <div class="card-elite mb-4">

        <div class="table-card-header-elite">

            <div class="section-head-elite">
                <div class="section-icon-elite">
                    <i class="fas fa-route"></i>
                </div>
                <div>
                    <h6 class="section-title-elite">Alur Pembayaran</h6>
                    <small class="section-subtitle-elite">Tahapan proses pembayaran reservasi</small>
                </div>
            </div>

        </div>

        <div class="card-body p-4">

            <div class="payment-flow-elite">

                {{-- MENUNGGU --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-warning-elite">1</div>
                    <div class="flow-content-elite">
                        <span class="badge-pill-elite badge-warning-elite">Menunggu</span>
                        <p>Pembayaran belum dikonfirmasi oleh admin.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- PROMO --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-danger-elite">2</div>
                    <div class="flow-content-elite">
                        <span class="badge-pill-elite badge-maroon-elite">Promo</span>
                        <p>Promo aktif dapat digunakan sesuai ketentuan yang berlaku.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- LUNAS --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-success-elite">3</div>
                    <div class="flow-content-elite">
                        <span class="badge-pill-elite badge-active-elite">Lunas</span>
                        <p>Pembayaran telah dikonfirmasi dan reservasi menjadi dikonfirmasi.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- RESERVASI --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-primary-elite">4</div>
                    <div class="flow-content-elite">
                        <span class="badge-pill-elite badge-info-elite">Reservasi</span>
                        <p>Reservasi dapat diproses sesuai jadwal yang telah ditentukan.</p>
                    </div>
                </div>

            </div>


            {{-- INFO --}}
            <div class="info-note-elite">

                <div class="info-note-icon-elite">
                    <i class="fas fa-info-circle"></i>
                </div>

                <div>

                    <strong>Informasi Pembayaran</strong>

                    <p>
                        Pembayaran berstatus
                        <strong class="text-gold-elite d-inline">Menunggu</strong>
                        dapat dikonfirmasi melalui tombol pembayaran.
                        Setelah pembayaran menjadi
                        <strong class="text-success-elite d-inline">Lunas</strong>,
                        reservasi otomatis menjadi
                        <strong class="d-inline" style="color:#3b6fd4;">Dikonfirmasi</strong>.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ================= STYLE ================= --}}
<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.bayar-elite {
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
   KHUSUS PEMBAYARAN (INDEX)
========================================================== */

.table-wide-elite { min-width: 1300px; }

.filter-actions-elite { display: flex; gap: 8px; }
.filter-actions-elite .btn-filter-elite { flex: 1; }
.btn-reset-icon-elite {
    width: 44px; height: 44px;
    border-radius: 10px;
    border: 1px solid var(--sb-gold);
    color: var(--sb-red-dark);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all .25s ease;
}
.btn-reset-icon-elite:hover {
    background: rgba(217,172,79,.1);
    color: var(--sb-red-dark);
}

.active-filter-elite {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 6px;
    padding: 11px 14px;
    border: 1px solid rgba(217,172,79,.3);
    border-radius: 10px;
    background: #fdf6ea;
    font-size: 12px;
    color: #8a6a2f;
}
.active-filter-elite i { color: var(--sb-red); }
.filter-chip-elite {
    display: inline-block;
    margin-left: 7px;
    padding: 4px 10px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid rgba(217,172,79,.35);
    color: #8a6a2f;
    font-size: 11px;
    font-weight: 600;
}
.filter-chip-danger-elite { background: #fff0f0; color: var(--sb-red-dark); border-color: rgba(224,28,29,.2); }

.reservation-code-elite {
    color: var(--sb-red);
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}
.reservation-code-elite:hover { color: var(--sb-red-dark); text-decoration: underline; }

.schedule-mini-elite {
    margin-top: 5px;
    color: #a09a94;
    font-size: 10.5px;
    line-height: 1.8;
}
.schedule-mini-elite i { color: var(--sb-gold); }

.treatment-col-elite { min-width: 200px; }
.treatment-line-index-elite {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}
.treatment-line-index-elite:last-child { margin-bottom: 0; }
.treatment-icon-elite {
    width: 26px; height: 26px;
    min-width: 26px;
    border-radius: 8px;
    background: #fff0f0;
    color: var(--sb-red);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}
.treatment-name-elite { font-weight: 600; color: #3a2a26; font-size: 12px; }
.qty-badge-elite {
    margin-left: 4px;
    padding: 2px 7px;
    border-radius: 10px;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 10px;
    font-weight: 700;
}

.therapist-elite {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}
.therapist-icon-elite {
    width: 30px; height: 30px;
    border-radius: 9px;
    background: #e8f0fe;
    color: #3b6fd4;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
}

.payment-date-elite { color: #3a2a26; font-weight: 600; white-space: nowrap; }
.payment-date-elite i { color: var(--sb-gold); }

.method-badge-elite {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.method-cash-elite     { background: #e8f8f1; color: #12865d; }
.method-transfer-elite { background: #e8f0fe; color: #3b6fd4; }
.method-ewallet-elite  { background: #e6f7fb; color: #16849a; }
.method-none-elite     { background: #f4efe9; color: #8a7d74; }

.price-old-elite { color: #a09a94; text-decoration: line-through; font-size: 11px; }
.price-final-elite { color: var(--sb-red); font-size: 13px; font-weight: 700; white-space: nowrap; }
.discount-info-elite { margin-top: 3px; color: #12865d; font-size: 11px; white-space: nowrap; }
.promo-badge-elite {
    display: inline-block;
    margin-top: 5px;
    padding: 4px 9px;
    border-radius: 20px;
    color: #8a6a2f;
    background: #fdf3e2;
    font-size: 10px;
    font-weight: 700;
}

.status-badge-elite {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.status-success-elite { background: #e8f8f1; color: #1cc88a; }
.status-warning-elite { background: #fdf3e2; color: #a9781f; }
.status-danger-elite  { background: #fff0f0; color: var(--sb-red); }
.status-secondary-elite { background: #f4efe9; color: #8a7d74; }

.action-pay-elite   { background: #e8f8f1; color: #12865d; border-color: rgba(28,200,138,.25); }
.action-pay-elite:hover {
    background: linear-gradient(135deg, #1cc88a, #12865d);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(28,200,138,.3);
}
.action-group-elite { display: inline-flex; gap: 6px; align-items: center; justify-content: center; }

/* alur pembayaran */
.payment-flow-elite {
    display: flex;
    align-items: stretch;
    gap: 10px;
}
.flow-item-elite {
    flex: 1;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 15px;
    border: 1px solid rgba(217,172,79,.2);
    border-radius: 14px;
    background: #fffdf9;
}
.flow-number-elite {
    width: 32px; height: 32px;
    min-width: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}
.flow-warning-elite { background: #fdf3e2; color: #a9781f; }
.flow-danger-elite  { background: #fff0f0; color: var(--sb-red); }
.flow-success-elite { background: #e8f8f1; color: #12865d; }
.flow-primary-elite { background: #e8f0fe; color: #3b6fd4; }
.flow-content-elite { flex: 1; }
.flow-content-elite p { margin: 6px 0 0; color: #a09a94; font-size: 11px; line-height: 1.6; }
.flow-arrow-elite {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d9ac4f;
    font-size: 11px;
}

.info-note-elite {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 18px;
    padding: 14px 16px;
    border: 1px solid rgba(217,172,79,.3);
    border-radius: 12px;
    background: #fdf6ea;
}
.info-note-icon-elite {
    width: 34px; height: 34px;
    min-width: 34px;
    border-radius: 10px;
    background: #fff;
    color: var(--sb-gold);
    display: flex;
    align-items: center;
    justify-content: center;
}
.info-note-elite strong { display: block; margin-bottom: 3px; color: #3a2a26; font-size: 12.5px; }
.info-note-elite p { margin: 0; color: #8a6a2f; font-size: 11.5px; line-height: 1.6; }

@media (max-width: 991px) {
    .payment-flow-elite { flex-direction: column; }
    .flow-arrow-elite { height: 12px; transform: rotate(90deg); }
}
</style>


{{-- ================= JAVASCRIPT ================= --}}
@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS
    |--------------------------------------------------------------------------
    */

    $('.form-hapus').on('submit', function (e) {

        e.preventDefault();

        const form = this;

        if (typeof Swal !== 'undefined') {

            Swal.fire({

                title: 'Hapus pembayaran?',

                html:
                    'Data pembayaran yang dihapus ' +
                    '<strong>tidak dapat dikembalikan</strong>.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#e01c1d',

                cancelButtonColor: '#858796',

                confirmButtonText:
                    '<i class="fas fa-trash mr-1"></i> Ya, Hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        } else if (confirm('Yakin ingin menghapus pembayaran ini?')) {

            form.submit();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | AUTO HIDE ALERT
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        $('.alert-elite[data-autohide]').fadeOut(300, function () {
            $(this).remove();
        });

    }, 5000);

});

</script>

@endpush

@endsection