@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')

<div class="container-fluid px-4 detail-bayar-elite">

    {{-- ================= HEADER ================= --}}
    <div class="page-header-elite mb-4">

        <div>
            <div class="breadcrumb-elite mb-1">
                <a href="{{ route('pembayaran.index') }}">Data Pembayaran</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail</span>
            </div>
            <h4 class="fw-bold mb-0 title-elite">Detail Pembayaran</h4>
        </div>

        <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-gradient-elite px-3 mr-2"
                    onclick="hapusPembayaran({{ $data->id }})">
                <i class="fas fa-trash mr-1"></i> Hapus
            </button>

            <form id="form-hapus-pembayaran-{{ $data->id }}"
                  action="{{ route('pembayaran.destroy', $data->id) }}"
                  method="POST"
                  style="display:none;">
                @csrf
                @method('DELETE')
            </form>

            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-elite px-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
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
                            <i class="fas fa-receipt"></i>
                            <span>Pembayaran #{{ $data->id }}</span>
                        </div>
                    </div>

                    <div class="text-center px-4 pb-4 pt-3">

                        @if($data->reservasi)

                            <h5 class="fw-bold mb-2 title-elite">
                                {{ $data->reservasi->kode_reservasi }}
                            </h5>

                            <button type="button"
                                    class="copy-btn-elite mb-3"
                                    onclick="salinKode('{{ $data->reservasi->kode_reservasi }}')">
                                <i class="fas fa-copy mr-1"></i> Salin Kode
                            </button>

                        @else

                            <h5 class="fw-bold mb-3 title-elite">Pembayaran #{{ $data->id }}</h5>

                        @endif

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">

                            @if($data->status === 'Lunas')

                                <span class="badge-pill-elite badge-active-elite">
                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                </span>

                            @elseif($data->status === 'Menunggu')

                                <span class="badge-pill-elite badge-warning-elite">
                                    <i class="fas fa-clock mr-1"></i> Menunggu
                                </span>

                            @elseif($data->status === 'Dibatalkan')

                                <span class="badge-pill-elite badge-maroon-elite">
                                    <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                </span>

                            @else

                                <span class="badge-pill-elite badge-secondary-elite">
                                    {{ $data->status }}
                                </span>

                            @endif

                        </div>

                        <div class="quick-contact-elite text-left">

                            <span class="detail-label-elite d-block mb-1">Total Dibayar</span>

                            <span class="total-highlight-elite">
                                Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                            </span>

                            @if(($data->jumlah_diskon ?? 0) > 0)
                                <small class="text-success-elite">
                                    <i class="fas fa-tags mr-1"></i>
                                    Hemat Rp {{ number_format($data->jumlah_diskon, 0, ',', '.') }}
                                </small>
                            @endif

                            @if($data->reservasi && $data->reservasi->pelanggan)
                                <div class="description-text-elite mt-3">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $data->reservasi->pelanggan->nama }}
                                </div>
                            @endif

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

                {{-- STATUS --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite {{ $data->status === 'Lunas' ? 'icon-success-elite' : ($data->status === 'Menunggu' ? 'icon-gold-elite' : 'icon-maroon-elite') }}">
                            <i class="fas {{ $data->status === 'Lunas' ? 'fa-check-circle' : ($data->status === 'Menunggu' ? 'fa-clock' : 'fa-times-circle') }}"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">{{ $data->status }}</div>
                            <div class="mini-stat-label-elite">Status Pembayaran</div>
                        </div>
                    </div>
                </div>

                {{-- JUMLAH --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-maroon-elite">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                            </div>
                            <div class="mini-stat-label-elite">Jumlah Pembayaran</div>
                        </div>
                    </div>
                </div>

                {{-- METODE --}}
                <div class="col-md-4">
                    <div class="mini-stat-elite">
                        <div class="mini-stat-icon-elite icon-info-elite">
                            @if($data->metode_pembayaran === 'Cash')
                                <i class="fas fa-money-bill-wave"></i>
                            @elseif($data->metode_pembayaran === 'Transfer')
                                <i class="fas fa-university"></i>
                            @elseif($data->metode_pembayaran === 'E-Wallet')
                                <i class="fas fa-mobile-alt"></i>
                            @else
                                <i class="fas fa-credit-card"></i>
                            @endif
                        </div>
                        <div>
                            <div class="mini-stat-value-elite mini-stat-small-elite">
                                {{ $data->metode_pembayaran ?? '-' }}
                            </div>
                            <div class="mini-stat-label-elite">Metode Pembayaran</div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- INFORMASI PEMBAYARAN --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Pembayaran</h6>
                            <small class="section-subtitle-elite">Rincian pembayaran, promo, dan diskon</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">ID Pembayaran</span>
                            <span class="detail-value-elite">#{{ $data->id }}</span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Tanggal Pembayaran</span>
                            <span class="detail-value-elite">
                                @if($data->tanggal_bayar)
                                    {{ \Carbon\Carbon::parse($data->tanggal_bayar)->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-muted-elite">Belum dibayar</span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Metode Pembayaran</span>
                            <span class="detail-value-elite">

                                @if($data->metode_pembayaran === 'Cash')
                                    <span class="badge-pill-elite badge-active-elite">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Cash
                                    </span>
                                @elseif($data->metode_pembayaran === 'Transfer')
                                    <span class="badge-pill-elite badge-info-elite">
                                        <i class="fas fa-university mr-1"></i> Transfer
                                    </span>
                                @elseif($data->metode_pembayaran === 'E-Wallet')
                                    <span class="badge-pill-elite badge-info-elite">
                                        <i class="fas fa-mobile-alt mr-1"></i> E-Wallet
                                    </span>
                                @else
                                    <span class="badge-pill-elite badge-secondary-elite">
                                        {{ $data->metode_pembayaran ?? 'Belum dipilih' }}
                                    </span>
                                @endif

                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Status</span>
                            <span class="detail-value-elite">

                                @if($data->status === 'Lunas')
                                    <span class="badge-pill-elite badge-active-elite">
                                        <i class="fas fa-check-circle mr-1"></i> Lunas
                                    </span>
                                @elseif($data->status === 'Menunggu')
                                    <span class="badge-pill-elite badge-warning-elite">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @elseif($data->status === 'Dibatalkan')
                                    <span class="badge-pill-elite badge-maroon-elite">
                                        <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                    </span>
                                @else
                                    <span class="badge-pill-elite badge-secondary-elite">{{ $data->status }}</span>
                                @endif

                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Harga Sebelum Diskon</span>
                            <span class="detail-value-elite">
                                Rp {{ number_format($data->jumlah_sebelum_diskon ?? $data->jumlah, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Jumlah Diskon</span>
                            <span class="detail-value-elite">
                                @if(($data->jumlah_diskon ?? 0) > 0)
                                    <span class="text-success-elite">
                                        - Rp {{ number_format($data->jumlah_diskon, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted-elite">Rp 0</span>
                                @endif
                            </span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Promo</span>
                            <span class="detail-value-elite">

                                @if($data->promo)

                                    <span class="badge-pill-elite badge-warning-elite">
                                        <i class="fas fa-tags mr-1"></i>
                                        {{ $data->promo->nama }}
                                    </span>

                                    @if($data->promo->jenis === 'Persentase')
                                        <small class="text-muted-elite d-block mt-1">
                                            Diskon {{ $data->promo->nilai }}%
                                        </small>
                                    @elseif($data->promo->jenis === 'Nominal')
                                        <small class="text-muted-elite d-block mt-1">
                                            Potongan Rp {{ number_format($data->promo->nilai, 0, ',', '.') }}
                                        </small>
                                    @endif

                                @else

                                    <span class="text-muted-elite">Tidak menggunakan promo</span>

                                @endif

                            </span>
                        </div>

                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Total Dibayar</span>
                            <span class="total-pay-elite">
                                Rp {{ number_format($data->jumlah, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================= BUKTI PEMBAYARAN & VERIFIKASI ================= --}}
            <div class="card-elite mb-4">
                <div class="card-body p-4">
                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-gold-elite">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Bukti Pembayaran &amp; Verifikasi Admin</h6>
                            <small class="section-subtitle-elite">Pemeriksaan bukti transfer yang diunggah pelanggan</small>
                        </div>
                    </div>

                    @if($data->bukti_bayar)
                        <div class="p-3 mb-3 rounded" style="background:#fcf9f9; border: 1px solid #f0e6e8;">
                            <div class="row align-items-center">
                                <div class="col-md-5 text-center mb-3 mb-md-0">
                                    @php
                                        $ext = strtolower(pathinfo($data->bukti_bayar, PATHINFO_EXTENSION));
                                        $fileUrl = \Illuminate\Support\Facades\Storage::url($data->bukti_bayar);
                                    @endphp

                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                        <a href="{{ $fileUrl }}" target="_blank" title="Klik untuk memperbesar">
                                            <img src="{{ $fileUrl }}" alt="Bukti Pembayaran" class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: contain;">
                                        </a>
                                    @else
                                        <div class="p-4 bg-light rounded text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                            <div class="font-weight-bold">Berkas Bukti (PDF)</div>
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-external-link-alt mr-1"></i> Buka Ukuran Penuh
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h6 class="font-weight-bold text-dark mb-2">Status Pemeriksaan:</h6>
                                    @if($data->status === 'Lunas')
                                        <div class="badge badge-success px-3 py-2 font-weight-normal mb-3" style="font-size: 13px;">
                                            <i class="fas fa-check-circle mr-1"></i> Telah Diverifikasi &amp; LUNAS
                                        </div>
                                        <p class="text-muted small mb-0">Pembayaran ini sudah disetujui dan telah tercatat pada laporan pemasukan salon.</p>
                                    @else
                                        <div class="badge badge-warning px-3 py-2 font-weight-normal mb-3" style="font-size: 13px;">
                                            <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi Admin
                                        </div>
                                        <p class="text-muted small mb-3">
                                            Periksa keaslian bukti transfer di samping (nominal, rekening tujuan, tanggal transfer).
                                            Jika valid, klik tombol <strong>Verifikasi Lunas</strong>. Jika palsu atau tidak sesuai, klik <strong>Tolak Bukti</strong>.
                                        </p>

                                        <div class="d-flex flex-wrap gap-2">
                                            <form action="{{ route('pembayaran.verifikasi', $data->id) }}" method="POST" class="mr-2 mb-2" onsubmit="return confirm('Konfirmasi pembayaran ini sebagai LUNAS? Reservasi otomatis akan DIKONFIRMASI.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success px-3">
                                                    <i class="fas fa-check-circle mr-1"></i> Verifikasi Lunas
                                                </button>
                                            </form>

                                            <button type="button" class="btn btn-outline-danger px-3 mb-2" data-toggle="collapse" data-target="#formTolakBukti">
                                                <i class="fas fa-times-circle mr-1"></i> Tolak Bukti
                                            </button>
                                        </div>

                                        <div class="collapse mt-3" id="formTolakBukti">
                                            <form action="{{ route('pembayaran.tolak', $data->id) }}" method="POST" class="p-3 border rounded bg-white">
                                                @csrf
                                                @method('PATCH')
                                                <label class="small font-weight-bold">Alasan Penolakan Bukti:</label>
                                                <input type="text" name="alasan" class="form-control form-control-sm mb-2" placeholder="Contoh: Bukti buram / nominal tidak sesuai / bukan struk Azzahra" required>
                                                <button type="submit" class="btn btn-sm btn-danger">Kirim Penolakan</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border mb-0 text-muted">
                            <i class="fas fa-info-circle mr-1"></i> Belum ada bukti transfer yang diunggah oleh pelanggan.
                        </div>
                    @endif
                </div>
            </div>

            {{-- INFORMASI RESERVASI --}}
            <div class="card-elite mb-4">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Reservasi</h6>
                            <small class="section-subtitle-elite">Data reservasi yang dibayar</small>
                        </div>
                    </div>

                    @if($data->reservasi)

                        <div class="detail-grid-elite">

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Pelanggan</span>
                                <span class="detail-value-elite">
                                    {{ optional($data->reservasi->pelanggan)->nama ?? '-' }}
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Terapis / Pegawai</span>
                                <span class="detail-value-elite">
                                    {{ optional($data->reservasi->pegawai)->nama ?? '-' }}
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Tanggal Reservasi</span>
                                <span class="detail-value-elite">
                                    @if($data->reservasi->tanggal)
                                        {{ \Carbon\Carbon::parse($data->reservasi->tanggal)->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Jam Reservasi</span>
                                <span class="detail-value-elite">
                                    <i class="fas fa-clock mr-1 text-gold-elite"></i>
                                    {{ \Carbon\Carbon::parse($data->reservasi->jam_mulai)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($data->reservasi->jam_selesai)->format('H:i') }}
                                </span>
                            </div>

                            <div class="detail-cell-elite detail-cell-full-elite">

                                <span class="detail-label-elite">Perawatan</span>

                                @php
                                    $perawatans = $data->reservasi->perawatans;
                                @endphp

                                @if($perawatans && $perawatans->count() > 0)

                                    @foreach($perawatans as $item)

                                        <div class="treatment-line-elite">

                                            <div>

                                                <div class="treatment-line-name-elite">
                                                    {{ $item->nama }}
                                                </div>

                                                <div class="treatment-line-meta-elite">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $item->pivot->durasi }} menit

                                                    @if(($item->pivot->jumlah ?? 1) > 1)
                                                        <span class="badge-pill-elite badge-maroon-elite ml-1">
                                                            {{ $item->pivot->jumlah }}x
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>

                                            <div class="treatment-line-price-elite">
                                                Rp {{ number_format(
                                                    ($item->pivot->harga ?? $item->harga)
                                                    *
                                                    ($item->pivot->jumlah ?? 1),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </div>

                                        </div>

                                    @endforeach

                                @elseif($data->reservasi->perawatan)

                                    <span class="detail-value-elite">
                                        {{ $data->reservasi->perawatan->nama }}
                                    </span>

                                    <small class="text-muted-elite">
                                        {{ $data->reservasi->perawatan->durasi }} menit
                                    </small>

                                @else

                                    <span class="text-muted-elite">Tidak ada data perawatan.</span>

                                @endif

                            </div>

                        </div>

                    @else

                        <div class="alert-warning-elite mt-3">
                            <i class="fas fa-exclamation-triangle mt-1"></i>
                            <div>Data reservasi tidak ditemukan.</div>
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
                            <h6 class="section-title-elite">Catatan Pembayaran</h6>
                            <small class="section-subtitle-elite">Catatan tambahan terkait pembayaran</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">
                        <div class="detail-cell-elite detail-cell-full-elite">
                            <span class="detail-label-elite">Catatan</span>
                            <span class="detail-value-elite">
                                {{ $data->catatan ?: 'Tidak ada catatan pembayaran.' }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>


            {{-- INFORMASI PROMO --}}
            @if($data->promo)

                <div class="card-elite mb-4">

                    <div class="card-body p-4">

                        <div class="section-head-elite mb-3">
                            <div class="section-icon-elite icon-maroon-elite">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div>
                                <h6 class="section-title-elite">Informasi Promo</h6>
                                <small class="section-subtitle-elite">Promo yang digunakan pada pembayaran ini</small>
                            </div>
                        </div>

                        <div class="detail-grid-elite">

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Nama Promo</span>
                                <span class="detail-value-elite">{{ $data->promo->nama }}</span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Jenis Promo</span>
                                <span class="detail-value-elite">{{ $data->promo->jenis }}</span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Nilai Promo</span>
                                <span class="detail-value-elite text-success-elite">
                                    @if($data->promo->jenis === 'Persentase')
                                        {{ $data->promo->nilai }}%
                                    @else
                                        Rp {{ number_format($data->promo->nilai, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>

                            <div class="detail-cell-elite">
                                <span class="detail-label-elite">Periode Promo</span>
                                <span class="detail-value-elite">
                                    {{ $data->promo->tanggal_mulai
                                        ? \Carbon\Carbon::parse($data->promo->tanggal_mulai)->translatedFormat('d F Y')
                                        : '-'
                                    }}
                                    s/d
                                    {{ $data->promo->tanggal_selesai
                                        ? \Carbon\Carbon::parse($data->promo->tanggal_selesai)->translatedFormat('d F Y')
                                        : '-'
                                    }}
                                </span>
                            </div>

                            @if($data->promo->deskripsi)
                                <div class="detail-cell-elite detail-cell-full-elite">
                                    <span class="detail-label-elite">Deskripsi</span>
                                    <span class="detail-value-elite">{{ $data->promo->deskripsi }}</span>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            @endif


            {{-- INFORMASI SISTEM --}}
            <div class="card-elite">

                <div class="card-body p-4">

                    <div class="section-head-elite mb-3">
                        <div class="section-icon-elite icon-maroon-elite">
                            <i class="fas fa-database"></i>
                        </div>
                        <div>
                            <h6 class="section-title-elite">Informasi Sistem</h6>
                            <small class="section-subtitle-elite">Informasi pencatatan data pembayaran</small>
                        </div>
                    </div>

                    <div class="detail-grid-elite">

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Dibuat</span>
                            <span class="detail-value-elite">
                                {{ $data->created_at ? $data->created_at->translatedFormat('d F Y H:i') : '-' }}
                            </span>
                        </div>

                        <div class="detail-cell-elite">
                            <span class="detail-label-elite">Terakhir Diperbarui</span>
                            <span class="detail-value-elite">
                                {{ $data->updated_at ? $data->updated_at->translatedFormat('d F Y H:i') : '-' }}
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

.detail-bayar-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.title-elite { color: #3a2a26; }
.text-muted-elite { color: #a09a94; }
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

.pegawai-photo-square-elite {
    display: block;
    width: 100%;
    height: 170px;
    object-fit: cover;
    border-radius: 14px;
    border: 5px solid #fff;
    box-shadow: 0 6px 18px rgba(124,16,19,.2);
}

.tax-icon-square-elite {
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
.tax-icon-square-elite span {
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

.total-highlight-elite {
    display: block;
    font-size: 19px;
    font-weight: 800;
    color: var(--sb-red);
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
.icon-info-elite.mini-stat-icon-elite    { background: #e8f0fe; color: #3b6fd4; }

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


/* =========================================================
   RINCIAN (SUMMARY)
========================================================== */

.gaji-summary-elite {
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
   KHUSUS PEMBAYARAN (DETAIL)
========================================================== */

.badge-maroon-elite     { background: #fff0f0; color: var(--sb-red); }
.badge-secondary-elite  { background: #f4efe9; color: #8a7d74; }

.copy-btn-elite {
    border-radius: 10px;
    border: 1px solid var(--sb-gold);
    background: transparent;
    color: var(--sb-red-dark);
    font-size: 12px;
    font-weight: 600;
    padding: 5px 14px;
    cursor: pointer;
    transition: all .25s ease;
}
.copy-btn-elite:hover { background: rgba(217,172,79,.1); }

.treatment-line-elite {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 12px;
    background: #fff;
    border: 1px solid rgba(217,172,79,.2);
    border-radius: 10px;
    margin-top: 8px;
}
.treatment-line-elite:first-of-type { margin-top: 4px; }
.treatment-line-name-elite { font-weight: 700; color: #3a2a26; font-size: 13px; }
.treatment-line-meta-elite { color: #a09a94; font-size: 11.5px; }
.treatment-line-price-elite { font-weight: 700; color: var(--sb-red); font-size: 13px; white-space: nowrap; }

.total-pay-elite { font-size: 17px; font-weight: 800; color: var(--sb-red); }

.alert-warning-elite {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 14px 16px;
    border-radius: 12px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.35);
    color: #8a6a2f;
    font-size: 12.5px;
}
</style>


@push('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | SALIN KODE RESERVASI
    |--------------------------------------------------------------------------
    */

    function salinKode(kode)
    {

        if (
            navigator.clipboard &&
            navigator.clipboard.writeText
        ) {

            navigator.clipboard.writeText(kode)

                .then(function () {

                    if (typeof Swal !== 'undefined') {

                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text: 'Kode reservasi berhasil disalin.',

                            timer: 1500,

                            showConfirmButton: false

                        });

                    } else {

                        alert('Kode reservasi berhasil disalin.');

                    }

                })

                .catch(function () {

                    alert('Kode reservasi: ' + kode);

                });

        } else {

            alert('Kode reservasi: ' + kode);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    function hapusPembayaran(id)
    {

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
                    '<i class="fas fa-trash mr-1"></i> Ya, hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    document
                        .getElementById('form-hapus-pembayaran-' + id)
                        .submit();

                }

            });

        } else if (confirm('Yakin ingin menghapus pembayaran ini?')) {

            document
                .getElementById('form-hapus-pembayaran-' + id)
                .submit();

        }

    }

</script>

@endpush

@endsection