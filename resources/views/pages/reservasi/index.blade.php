@extends('layouts.app')

@section('title', 'Data Reservasi')

@section('content')

<div class="container-fluid reservation-page-elite">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="modern-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">

            <div class="header-icon-elite">
                <i class="fas fa-calendar-alt"></i>
            </div>

            <div>
                <h1 class="page-title-elite">Data Reservasi</h1>
                <p class="page-subtitle-elite">Kelola jadwal reservasi perawatan pelanggan</p>
            </div>

        </div>

        <div class="header-actions position-relative">

            <a href="{{ route('reservasi.laporan.pdf', request()->query()) }}"
               class="btn btn-outline-elite btn-modern-elite">
                <i class="fas fa-file-pdf mr-2"></i>
                Laporan
            </a>

            <a href="{{ route('reservasi.create') }}" class="btn btn-gradient-elite btn-modern-elite">
                <i class="fas fa-plus mr-2"></i>
                Tambah Reservasi
            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERT
    ========================================================== --}}
    @if(session('success'))

        <div class="modern-alert-elite alert-success-elite mb-4">
            <div class="alert-icon-elite"><i class="fas fa-check"></i></div>
            <div class="alert-content-elite">
                <strong>Berhasil</strong>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>

    @endif


    @if(session('error'))

        <div class="modern-alert-elite alert-danger-elite mb-4">
            <div class="alert-icon-elite"><i class="fas fa-exclamation"></i></div>
            <div class="alert-content-elite">
                <strong>Terjadi Kesalahan</strong>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>

    @endif


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="row summary-row">

        {{-- TOTAL --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="summary-card-elite summary-maroon">
                <div class="summary-content-elite">
                    <span class="summary-label-elite">Total Reservasi</span>
                    <div class="summary-value-elite">{{ $totalReservasi }}</div>
                    <span class="summary-description-elite">Seluruh data reservasi</span>
                </div>
                <div class="summary-icon-elite"><i class="fas fa-calendar-alt"></i></div>
            </div>
        </div>


        {{-- MENUNGGU --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="summary-card-elite summary-gold">
                <div class="summary-content-elite">
                    <span class="summary-label-elite">Menunggu</span>
                    <div class="summary-value-elite">{{ $totalMenunggu }}</div>
                    <span class="summary-description-elite">Menunggu pembayaran</span>
                </div>
                <div class="summary-icon-elite"><i class="fas fa-clock"></i></div>
            </div>
        </div>


        {{-- DIKONFIRMASI --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="summary-card-elite summary-ink">
                <div class="summary-content-elite">
                    <span class="summary-label-elite">Dikonfirmasi</span>
                    <div class="summary-value-elite">{{ $totalDikonfirmasi }}</div>
                    <span class="summary-description-elite">Jadwal telah dikonfirmasi</span>
                </div>
                <div class="summary-icon-elite"><i class="fas fa-check"></i></div>
            </div>
        </div>


        {{-- SELESAI --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="summary-card-elite summary-success">
                <div class="summary-content-elite">
                    <span class="summary-label-elite">Selesai</span>
                    <div class="summary-value-elite">{{ $totalSelesai }}</div>
                    <span class="summary-description-elite">Reservasi selesai</span>
                </div>
                <div class="summary-icon-elite"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        FILTER
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-header-elite">
            <div class="card-title-wrapper-elite">
                <div class="card-title-icon-elite"><i class="fas fa-filter"></i></div>
                <div>
                    <h6 class="modern-card-title-elite">Filter Data</h6>
                    <span class="modern-card-subtitle-elite">Cari dan saring data reservasi</span>
                </div>
            </div>
        </div>


        <div class="modern-card-body-elite">

            <form action="{{ route('reservasi.index') }}" method="GET">

                <div class="row">

                    {{-- SEARCH --}}
                    <div class="col-lg-5 col-md-6 mb-3">
                        <label class="modern-label-elite">Pencarian</label>

                        <div class="modern-input-group-elite">
                            <i class="fas fa-search input-icon-elite"></i>
                            <input type="text"
                                   name="katakunci"
                                   class="form-control modern-input-elite"
                                   value="{{ request('katakunci') }}"
                                   placeholder="Kode, pelanggan, perawatan, terapis...">
                        </div>
                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label class="modern-label-elite">Status</label>

                        <select name="status" class="form-control modern-input-elite">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Dikonfirmasi" {{ request('status') === 'Dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>


                    {{-- TANGGAL --}}
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="modern-label-elite">Tanggal</label>

                        <input type="date"
                               name="tanggal"
                               class="form-control modern-input-elite"
                               value="{{ request('tanggal') }}">
                    </div>


                    {{-- BUTTON --}}
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label class="modern-label-elite d-block">&nbsp;</label>

                        <div class="filter-buttons-elite">
                            <button type="submit" class="btn btn-gradient-elite modern-search-btn-elite">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>

                            <a href="{{ route('reservasi.index') }}" class="modern-reset-btn-elite" title="Reset Filter">
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
                <div class="card-title-icon-elite"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <h6 class="modern-card-title-elite">Daftar Reservasi</h6>
                    <span class="modern-card-subtitle-elite">Data jadwal reservasi pelanggan</span>
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
                            <th>Kode Reservasi</th>
                            <th>Pelanggan</th>
                            <th>Perawatan</th>
                            <th>Terapis</th>
                            <th>Jadwal</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($data as $item)

                            <tr>

                                <td class="text-center">
                                    <span class="number-badge-elite">{{ $data->firstItem() + $loop->index }}</span>
                                </td>


                                <td>
                                    <a href="{{ route('reservasi.show', $item->id) }}" class="reservation-code-elite">
                                        {{ $item->kode_reservasi ?? 'RSV-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>


                                <td>
                                    <div class="customer-info-elite">
                                        <div class="customer-avatar-elite"><i class="fas fa-user"></i></div>
                                        <div>
                                            <div class="customer-name-elite">{{ optional($item->pelanggan)->nama ?? '-' }}</div>
                                            <small class="text-muted">Pelanggan</small>
                                        </div>
                                    </div>
                                </td>


                                <td class="treatment-column-elite">

                                    @php

                                        $daftarPerawatan = $item->perawatans;

                                        if (
                                            (!$daftarPerawatan || $daftarPerawatan->count() === 0)
                                            && $item->perawatan
                                        ) {
                                            $daftarPerawatan = collect([$item->perawatan]);
                                        }

                                    @endphp


                                    @if($daftarPerawatan && $daftarPerawatan->count() > 0)

                                        @foreach($daftarPerawatan as $perawatan)

                                            <div class="treatment-item-elite">

                                                <div class="treatment-name-elite-inline">
                                                    <span class="treatment-icon-elite"><i class="fas fa-spa"></i></span>
                                                    <span>
                                                        {{ $perawatan->nama }}
                                                        @if(isset($perawatan->pivot) && $perawatan->pivot->jumlah > 1)
                                                            <span class="quantity-badge-elite">x{{ $perawatan->pivot->jumlah }}</span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="treatment-duration-elite">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    @if(isset($perawatan->pivot))
                                                        {{ $perawatan->pivot->durasi ?? $perawatan->durasi }}
                                                    @else
                                                        {{ $perawatan->durasi ?? 0 }}
                                                    @endif
                                                    menit
                                                </div>

                                            </div>

                                        @endforeach


                                        @php

                                            $totalDurasi = 0;
                                            $totalHarga = 0;

                                            foreach ($daftarPerawatan as $perawatan) {

                                                $jumlah = isset($perawatan->pivot) ? ($perawatan->pivot->jumlah ?? 1) : 1;
                                                $durasi = isset($perawatan->pivot) ? ($perawatan->pivot->durasi ?? $perawatan->durasi ?? 0) : ($perawatan->durasi ?? 0);
                                                $harga = isset($perawatan->pivot) ? ($perawatan->pivot->harga ?? $perawatan->harga ?? 0) : ($perawatan->harga ?? 0);

                                                $totalDurasi += $durasi * $jumlah;
                                                $totalHarga += $harga * $jumlah;

                                            }

                                        @endphp


                                        <div class="treatment-total-elite">
                                            <div><i class="fas fa-hourglass-half mr-1"></i> {{ $totalDurasi }} menit</div>
                                            <strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong>
                                        </div>

                                    @else

                                        <span class="text-muted">-</span>

                                    @endif

                                </td>


                                <td>
                                    <div class="therapist-info-elite">
                                        <div class="therapist-icon-elite"><i class="fas fa-user-md"></i></div>
                                        <span>{{ optional($item->pegawai)->nama ?? '-' }}</span>
                                    </div>
                                </td>


                                <td>
                                    <div class="schedule-box-elite">
                                        <div class="schedule-date-elite">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                        </div>
                                        <div class="schedule-time-elite">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                            <span class="mx-1">–</span>
                                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                        </div>
                                    </div>
                                </td>


                                <td>
                                    @if($item->pembayaran)

                                        @if($item->pembayaran->status === 'Lunas')
                                            <span class="status-badge-elite status-success-elite">
                                                <i class="fas fa-check-circle"></i> Lunas
                                            </span>
                                        @elseif($item->pembayaran->status === 'Menunggu')
                                            <span class="status-badge-elite status-warning-elite">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        @else
                                            <span class="status-badge-elite status-danger-elite">
                                                <i class="fas fa-times-circle"></i> Dibatalkan
                                            </span>
                                        @endif

                                    @else
                                        <span class="status-badge-elite status-secondary-elite">
                                            <i class="fas fa-minus-circle"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>


                                <td>
                                    @if($item->status === 'Menunggu')
                                        <span class="status-badge-elite status-warning-elite">
                                            <i class="fas fa-clock"></i> Menunggu
                                        </span>
                                    @elseif($item->status === 'Dikonfirmasi')
                                        <span class="status-badge-elite status-ink-elite">
                                            <i class="fas fa-check"></i> Dikonfirmasi
                                        </span>
                                    @elseif($item->status === 'Selesai')
                                        <span class="status-badge-elite status-success-elite">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    @else
                                        <span class="status-badge-elite status-danger-elite">
                                            <i class="fas fa-times-circle"></i> Dibatalkan
                                        </span>
                                    @endif
                                </td>


                                <td class="text-center">
                                    <div class="action-buttons-elite">

                                        <a href="{{ route('reservasi.show', $item->id) }}"
                                           class="action-btn-elite action-detail-elite" title="Detail Reservasi">
                                            <i class="fas fa-eye"></i>
                                        </a>


                                        @if(
                                            $item->status === 'Menunggu' &&
                                            (!$item->pembayaran || $item->pembayaran->status !== 'Lunas')
                                        )

                                            <a href="{{ route('reservasi.edit', $item->id) }}"
                                               class="action-btn-elite action-edit-elite" title="Edit Reservasi">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <button type="button" class="action-btn-elite action-delete-elite"
                                                    title="Hapus Reservasi" onclick="hapusReservasi({{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <form id="form-hapus-{{ $item->id }}"
                                                  action="{{ route('reservasi.destroy', $item->id) }}"
                                                  method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="empty-state-elite">
                                    <div class="empty-icon-elite"><i class="fas fa-calendar-times"></i></div>
                                    <h5>Belum Ada Data Reservasi</h5>
                                    <p>Belum terdapat data reservasi yang sesuai dengan pencarian.</p>
                                    <a href="{{ route('reservasi.create') }}" class="btn btn-gradient-elite btn-sm">
                                        <i class="fas fa-plus mr-1"></i> Tambah Reservasi
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
                    <div>{{ $data->withQueryString()->links() }}</div>
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        INFO ALUR
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-header-elite">
            <div class="card-title-wrapper-elite">
                <div class="card-title-icon-elite"><i class="fas fa-route"></i></div>
                <div>
                    <h6 class="modern-card-title-elite">Alur Status Reservasi</h6>
                    <span class="modern-card-subtitle-elite">Tahapan proses reservasi pelanggan</span>
                </div>
            </div>
        </div>


        <div class="modern-card-body-elite">

            <div class="status-flow-elite">

                {{-- MENUNGGU --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-warning-elite">1</div>
                    <div class="flow-content-elite">
                        <span class="flow-badge-elite flow-badge-warning-elite">Menunggu</span>
                        <p>Reservasi telah dibuat dan menunggu proses pembayaran.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- LUNAS --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-success-elite">2</div>
                    <div class="flow-content-elite">
                        <span class="flow-badge-elite flow-badge-success-elite">Pembayaran Lunas</span>
                        <p>Pembayaran telah dikonfirmasi oleh admin.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- DIKONFIRMASI --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-ink-elite">3</div>
                    <div class="flow-content-elite">
                        <span class="flow-badge-elite flow-badge-ink-elite">Dikonfirmasi</span>
                        <p>Jadwal reservasi telah dikonfirmasi dan tidak dapat diedit.</p>
                    </div>
                </div>

                <div class="flow-arrow-elite"><i class="fas fa-chevron-right"></i></div>

                {{-- SELESAI --}}
                <div class="flow-item-elite">
                    <div class="flow-number-elite flow-success-elite">4</div>
                    <div class="flow-content-elite">
                        <span class="flow-badge-elite flow-badge-success-elite">Selesai</span>
                        <p>Perawatan telah selesai dilakukan dan menjadi riwayat.</p>
                    </div>
                </div>

            </div>


            <div class="modern-info-box-elite">

                <div class="modern-info-icon-elite"><i class="fas fa-info-circle"></i></div>

                <div>
                    <strong>Informasi Sistem</strong>
                    <p>
                        Setelah pembayaran berstatus <strong class="text-success-elite">Lunas</strong>,
                        reservasi otomatis berubah menjadi <strong class="text-ink-elite">Dikonfirmasi</strong>.
                        Data jadwal kemudian tidak dapat diedit untuk menjaga konsistensi jadwal reservasi.
                    </p>
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

.reservation-page-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    padding-bottom: 20px;
}

.text-success-elite { color: #198754 !important; }
.text-ink-elite { color: #4a4d56 !important; }


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

.summary-content-elite { position: relative; z-index: 2; }

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

.summary-description-elite {
    color: #a09a94; font-size: 11px;
}

.summary-icon-elite {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 21px;
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

.summary-ink {
    color: #4a4d56;
    border-left: 4px solid #4a4d56;
}
.summary-ink .summary-icon-elite {
    color: #4a4d56;
    background: rgba(74,77,86,.08);
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
    min-width: 1250px;
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

.number-badge-elite {
    width: 27px; height: 27px;
    display: inline-flex; align-items: center; justify-content: center;
    background: #fdf3e2;
    color: #a9781f;
    border-radius: 8px;
    font-size: 11px; font-weight: 600;
}


/* =========================================================
   RESERVATION CODE
========================================================== */

.reservation-code-elite {
    color: var(--sb-red);
    font-weight: 700;
    font-size: 12px;
    text-decoration: none;
}
.reservation-code-elite:hover {
    color: var(--sb-red-dark);
    text-decoration: none;
}


/* =========================================================
   CUSTOMER
========================================================== */

.customer-info-elite {
    display: flex; align-items: center; gap: 9px;
}

.customer-avatar-elite {
    width: 34px; height: 34px; min-width: 34px;
    border-radius: 10px;
    background: rgba(224,28,29,.08);
    color: var(--sb-red);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
}

.customer-name-elite {
    font-weight: 600; color: #3a2a26;
    white-space: nowrap;
}


/* =========================================================
   TREATMENT
========================================================== */

.treatment-column-elite { min-width: 230px; }

.treatment-item-elite { padding: 4px 0; }

.treatment-name-elite-inline {
    display: flex; align-items: center; gap: 6px;
    font-weight: 600; color: #3a2a26;
}

.treatment-icon-elite {
    width: 24px; height: 24px;
    border-radius: 7px;
    background: #fdf3e2;
    color: #a9781f;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 10px;
}

.treatment-duration-elite {
    margin-left: 30px;
    color: #a09a94;
    font-size: 10px;
    margin-top: 2px;
}

.quantity-badge-elite {
    background: #f4efe9;
    color: #8a7d74;
    border-radius: 5px;
    padding: 2px 5px;
    font-size: 9px;
    margin-left: 3px;
}

.treatment-total-elite {
    border-top: 1px dashed rgba(217,172,79,.3);
    margin-top: 6px;
    padding-top: 7px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    font-size: 10px;
    color: #a09a94;
}

.treatment-total-elite strong {
    color: var(--sb-red);
    font-size: 11px;
    white-space: nowrap;
}


/* =========================================================
   THERAPIST
========================================================== */

.therapist-info-elite {
    display: flex; align-items: center; gap: 7px;
    white-space: nowrap;
}

.therapist-icon-elite {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: #f1f2f4;
    color: #4a4d56;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
}


/* =========================================================
   SCHEDULE
========================================================== */

.schedule-box-elite { min-width: 125px; }

.schedule-date-elite {
    font-weight: 600; color: #3a2a26;
    margin-bottom: 4px;
}

.schedule-time-elite {
    color: #a09a94;
    font-size: 10px;
}


/* =========================================================
   STATUS
========================================================== */

.status-badge-elite {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 9px; border-radius: 7px;
    font-size: 9px; font-weight: 700;
    white-space: nowrap;
}

.status-success-elite   { color: #12865d; background: #e8f8f1; }
.status-warning-elite   { color: #9b7300; background: #fff7df; }
.status-ink-elite       { color: #4a4d56; background: #f1f2f4; }
.status-danger-elite    { color: #c42a2b; background: #fff0f0; }
.status-secondary-elite { color: #8a7d74; background: #f4efe9; }


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
}

.action-detail-elite { background: #eaf8fb; color: #17a2b8; }
.action-detail-elite:hover { background: #17a2b8; color: #fff; transform: translateY(-2px); }

.action-edit-elite { background: #fdf3e2; color: #a9781f; }
.action-edit-elite:hover { background: var(--sb-gold); color: #fff; transform: translateY(-2px); }

.action-delete-elite { background: #fff0f0; color: var(--sb-red); }
.action-delete-elite:hover { background: var(--sb-red); color: #fff; transform: translateY(-2px); }


/* =========================================================
   EMPTY STATE
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
   STATUS FLOW
========================================================== */

.status-flow-elite {
    display: flex;
    align-items: stretch;
    justify-content: space-between;
    gap: 10px;
}

.flow-item-elite {
    flex: 1;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 15px;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 12px;
    background: #fffdf9;
}

.flow-number-elite {
    width: 30px; height: 30px; min-width: 30px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
}

.flow-warning-elite { background: #fff5d9; color: #d29a00; }
.flow-success-elite { background: #e7f8f0; color: #159768; }
.flow-ink-elite      { background: #f1f2f4; color: #4a4d56; }

.flow-content-elite { flex: 1; }

.flow-badge-elite {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 700;
    margin-bottom: 6px;
}

.flow-badge-warning-elite { color: #9b7300; background: #fff7df; }
.flow-badge-success-elite { color: #12865d; background: #e8f8f1; }
.flow-badge-ink-elite     { color: #4a4d56; background: #f1f2f4; }

.flow-content-elite p {
    color: #a09a94; font-size: 10px; line-height: 1.6; margin: 0;
}

.flow-arrow-elite {
    display: flex; align-items: center; justify-content: center;
    color: #e3d3ac; font-size: 11px;
}


/* =========================================================
   INFO BOX
========================================================== */

.modern-info-box-elite {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 18px;
    padding: 14px 16px;
    background: #fdfaf5;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 11px;
}

.modern-info-icon-elite {
    width: 32px; height: 32px; min-width: 32px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: #fdf3e2;
    color: #a9781f;
    font-size: 12px;
}

.modern-info-box-elite strong {
    display: block; color: #5a4a44; font-size: 11px; margin-bottom: 3px;
}

.modern-info-box-elite p {
    color: #a09a94; font-size: 10px; line-height: 1.6; margin: 0;
}


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {
    .modern-header-elite { align-items: flex-start; flex-direction: column; }
    .header-actions { width: 100%; }
    .header-actions .btn { flex: 1; }
    .status-flow-elite { flex-direction: column; }
    .flow-arrow-elite { transform: rotate(90deg); height: 15px; }
}

@media (max-width: 767px) {
    .page-title-elite { font-size: 20px; }
    .header-icon-elite { width: 45px; height: 45px; font-size: 18px; }
    .modern-card-body-elite { padding: 15px; }
    .pagination-wrapper-elite { flex-direction: column; align-items: center; }
    .flow-item-elite { width: 100%; }
}

</style>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

    function hapusReservasi(id) {

        if (typeof Swal !== 'undefined') {

            Swal.fire({
                title: 'Hapus reservasi?',
                html: 'Data reservasi yang dihapus <strong>tidak dapat dikembalikan</strong>.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e01c1d',
                cancelButtonColor: '#858796',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {

                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + id).submit();
                }

            });

        } else {

            if (confirm('Yakin ingin menghapus reservasi ini?')) {
                document.getElementById('form-hapus-' + id).submit();
            }

        }

    }

</script>

@endsection