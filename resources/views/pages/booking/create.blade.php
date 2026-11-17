@extends('pages.auth.guest')

@section('title', 'Buat Booking | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan Daftar Booking, lalu penataan khusus form --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/booking.css') }}?v={{ file_exists(public_path('template/css/booking-create-modern.css')) ? filemtime(public_path('template/css/booking-create-modern.css')) : time() }}">
@endpush

@section('content')

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    RUANG PERSONAL AZZAHRA
                </span>

                <h1>
                    Buat
                    <em>Booking.</em>
                </h1>

                <p>
                    Pilih perawatan, terapis, dan jadwal yang Anda inginkan.
                    Kami cek ketersediaan jadwal sebelum booking disimpan.
                </p>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-calendar-plus"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Beauty • Calm • You</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

    {{-- =========================================================
        NOTIFIKASI
    ========================================================== --}}
    @if ($errors->any())

        <div class="bkc-alert bkc-alert-danger" role="alert">
            <div class="bkc-alert-title">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Periksa kembali data berikut:
            </div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
        </div>

    @endif


    @if (session('error'))

        <div class="bkc-alert bkc-alert-danger" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ session('error') }}
            <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
        </div>

    @endif


    @if (session('bentrok'))

        <div class="bkc-alert bkc-alert-warning" role="alert">
            <div class="bkc-alert-title">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Jadwal Berpotensi Bentrok
            </div>
            <div>{{ session('bentrok') }}</div>
            <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
        </div>

    @endif


    @if (session('rekomendasi'))

        <div class="bkc-alert bkc-alert-gold">
            <div class="bkc-alert-title">
                <i class="fas fa-lightbulb mr-1"></i>
                Rekomendasi Jadwal Alternatif
            </div>
            <ul>
                @foreach (session('rekomendasi') as $item)
                    <li>
                        {{ $item['tanggal'] ?? '' }} - {{ $item['jam_mulai'] ?? '' }} s/d {{ $item['jam_selesai'] ?? '' }}
                        @if (isset($item['pegawai']))
                            ({{ $item['pegawai'] }})
                        @endif
                    </li>
                @endforeach
            </ul>
            <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
        </div>

    @endif

    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form action="{{ route('booking.store') }}" method="POST" id="formReservasi">

        @csrf

        <div class="row">

            {{-- =====================================================
                BAGIAN KIRI
            ====================================================== --}}
            <div class="col-lg-8">

                {{-- DATA PELANGGAN --}}
                <div class="card-elite mb-4 bkl-reveal">

                    <div class="card-header-elite">
                        <h6 class="section-title-elite-inline">
                            <i class="fas fa-user mr-2"></i>
                            Data Anda
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        {{-- PELANGGAN LOGIN: data diambil dari akun --}}
                        @auth
                            <div class="d-flex align-items-center" style="gap:14px;">
                                <div class="empty-icon-elite" style="margin:0;"><i class="fas fa-user"></i></div>
                                <div>
                                    <div style="font-weight:700;color:#3a2a26;">{{ optional($pelangganLogin)->nama }}</div>
                                    <div class="small text-muted-elite">{{ optional($pelangganLogin)->no_telepon ?: 'Nomor telepon belum diisi' }}</div>
                                </div>
                            </div>

                            {{-- dikirim agar skrip validasi tetap bekerja; server memakai pelanggan dari akun login --}}
                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ optional($pelangganLogin)->id }}">
                        @endauth

                        {{-- TAMU (belum login): cukup nama dan nomor WhatsApp --}}
                        @guest
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label for="nama_tamu" class="form-label-elite">
                                        Nama Lengkap <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite"><i class="fas fa-user"></i></span>
                                        <input type="text"
                                            name="nama_tamu"
                                            id="nama_tamu"
                                            value="{{ old('nama_tamu') }}"
                                            class="form-control-elite @error('nama_tamu') is-invalid @enderror"
                                            maxlength="100"
                                            autocomplete="name"
                                            required>
                                    </div>

                                    @error('nama_tamu')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="no_whatsapp" class="form-label-elite">
                                        Nomor WhatsApp <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite"><i class="fab fa-whatsapp"></i></span>
                                        <input type="tel"
                                            name="no_whatsapp"
                                            id="no_whatsapp"
                                            value="{{ old('no_whatsapp') }}"
                                            class="form-control-elite @error('no_whatsapp') is-invalid @enderror"
                                            inputmode="tel"
                                            placeholder="08xxxxxxxxxx"
                                            autocomplete="tel"
                                            required>
                                    </div>

                                    @error('no_whatsapp')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            {{-- honeypot anti-bot: jangan dihapus, harus tetap kosong --}}
                            <input type="text"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                                aria-hidden="true"
                                style="position:absolute;left:-9999px">

                            <div class="small text-muted-elite">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" style="color:#64172D;font-weight:700;">Masuk</a>
                                &middot;
                                Sudah booking?
                                <a href="{{ route('booking.lacak') }}" style="color:#64172D;font-weight:700;">Lacak booking</a>
                            </div>
                        @endguest

                    </div>

                </div>


                {{-- PEMILIHAN PERAWATAN --}}
                <div class="card-elite mb-4 bkl-reveal">

                    <div class="card-header-elite">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="section-title-elite-inline">
                                    <i class="fas fa-spa mr-2"></i>
                                    Pilih Perawatan
                                </h6>
                                <small class="text-muted-elite">Pilih satu atau beberapa perawatan</small>
                            </div>

                            <span class="count-badge-elite" id="jumlahPilihan">0 Dipilih</span>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        @error('perawatan_ids')
                            <div class="bkc-alert bkc-alert-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        @error('perawatan_id')
                            <div class="bkc-alert bkc-alert-danger">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="info-box-elite mb-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kamu dapat memilih lebih dari satu perawatan.
                            Total durasi dan harga akan dihitung otomatis.
                        </div>


                        {{-- FILTER KATEGORI PERAWATAN --}}
                        <div class="treatment-filter-wrapper mb-4">

                            <div class="d-flex flex-wrap align-items-center justify-content-between">

                                <div class="mb-2 mb-md-0">
                                    <label for="filterKategoriPerawatan" class="mb-1 font-weight-bold" style="color:#3a2a26;">
                                        <i class="fas fa-filter mr-1" style="color:#64172D;"></i>
                                        Filter Perawatan
                                    </label>
                                    <div class="small text-muted-elite">
                                        Pilih kategori untuk menampilkan perawatan yang sesuai.
                                    </div>
                                </div>

                                <div class="treatment-filter-select">

                                    <select id="filterKategoriPerawatan" class="form-control form-control-sm">

                                        <option value="semua">Semua Perawatan</option>

                                        @php
                                            $kategoriPerawatan = collect($perawatan)
                                                ->map(function ($item) {
                                                    return optional($item->kategori)->nama;
                                                })
                                                ->filter()
                                                ->unique()
                                                ->sort()
                                                ->values();
                                        @endphp

                                        @foreach($kategoriPerawatan as $kategori)
                                            <option value="{{ \Illuminate\Support\Str::slug($kategori) }}">
                                                {{ $kategori }}
                                            </option>
                                        @endforeach

                                        @if($kategoriPerawatan->isEmpty())
                                            <option value="lainnya">Lainnya</option>
                                        @endif

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- SLIDER PERAWATAN --}}
                        <div class="treatment-slider-wrapper">

                            <button type="button" class="treatment-slider-btn treatment-slider-prev" id="treatmentPrev" aria-label="Perawatan sebelumnya">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <div class="treatment-slider" id="treatmentSlider">

                                <div class="treatment-slider-track" id="treatmentSliderTrack">

                                    @forelse($perawatan as $item)

                                        @php
                                            // Perawatan yang dipilih dari halaman detail layanan (?perawatan=ID)
                                            $default = (int) ($perawatanDipilih ?? 0) === (int) $item->id ? 1 : 0;
                                            $oldJumlah = old('jumlah_perawatan.' . $item->id, old('perawatan_ids.' . $item->id, $default));
                                            $selected = (int) $oldJumlah > 0;
                                            $kategoriItem = optional($item->kategori)->nama ?? 'Lainnya';
                                        @endphp

                                        <div class="treatment-slide" data-category="{{ \Illuminate\Support\Str::slug($kategoriItem) }}">

                                            <div class="treatment-item">

                                                <input type="checkbox"
                                                    name="perawatan_ids[{{ $item->id }}]"
                                                    value="{{ $selected ? $oldJumlah : 1 }}"
                                                    id="perawatan_{{ $item->id }}"
                                                    class="treatment-checkbox"
                                                    data-id="{{ $item->id }}"
                                                    data-name="{{ $item->nama }}"
                                                    data-duration="{{ $item->durasi }}"
                                                    data-price="{{ $item->harga }}"
                                                    data-description="{{ $item->deskripsi ?? '' }}"
                                                    data-category="{{ $kategoriItem }}"
                                                    {{ $selected ? 'checked' : '' }}>

                                                <label for="perawatan_{{ $item->id }}" class="treatment-card">

                                                    <div class="treatment-image-wrap">

                                                        <div class="treatment-image">
                                                            @if($item->foto)
                                                                <img src="{{ asset('storage/' . (\Illuminate\Support\Str::contains($item->foto, '/') ? $item->foto : 'foto_perawatan/' . $item->foto)) }}" alt="{{ $item->nama }}">
                                                            @else
                                                                <div class="treatment-image-placeholder">
                                                                    <i class="fas fa-spa"></i>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="treatment-check">
                                                            <i class="fas fa-check"></i>
                                                        </div>

                                                        <div class="treatment-price">
                                                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                        </div>

                                                    </div>

                                                    <div class="treatment-content">

                                                        <div class="treatment-category-badge">
                                                            <i class="fas fa-tag mr-1"></i>
                                                            {{ $kategoriItem }}
                                                        </div>

                                                        <h6 class="treatment-name">{{ $item->nama }}</h6>

                                                        <div class="treatment-meta">
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ $item->durasi }} menit
                                                        </div>

                                                        <div class="quantity-wrapper">

                                                            <span class="quantity-label">Jumlah</span>

                                                            <div class="quantity-control">

                                                                <button type="button" class="btn-qty btn-minus" data-id="{{ $item->id }}">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>

                                                                <span class="quantity-value" id="qty_{{ $item->id }}">
                                                                    {{ $selected ? $oldJumlah : 0 }}
                                                                </span>

                                                                <button type="button" class="btn-qty btn-plus" data-id="{{ $item->id }}">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </label>

                                            </div>

                                        </div>

                                    @empty

                                        <div class="treatment-empty-slide">
                                            <div class="text-center py-5">
                                                <i class="fas fa-spa fa-3x mb-3" style="color:#c9b9a8;"></i>
                                                <h6 class="fw-bold" style="color:#5a4a44;">Belum ada perawatan</h6>
                                                <p class="text-muted-elite mb-0">Silakan tambahkan data perawatan terlebih dahulu.</p>
                                            </div>
                                        </div>

                                    @endforelse

                                </div>

                            </div>

                            <button type="button" class="treatment-slider-btn treatment-slider-next" id="treatmentNext" aria-label="Perawatan berikutnya">
                                <i class="fas fa-chevron-right"></i>
                            </button>

                        </div>

                        @if($perawatan->count() > 4)
                            <div class="text-center mt-3 small text-muted-elite">
                                <i class="fas fa-arrows-alt-h mr-1"></i>
                                Geser atau gunakan tombol panah untuk melihat perawatan lainnya.
                            </div>
                        @endif

                    </div>

                </div>


                {{-- JADWAL RESERVASI --}}
                <div class="card-elite mb-4 bkl-reveal">

                    <div class="card-header-elite">
                        <h6 class="section-title-elite-inline">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Jadwal Booking
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <div class="row">

                            {{-- TANGGAL --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label for="tanggal" class="form-label-elite">
                                        Tanggal <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite"><i class="fas fa-calendar"></i></span>
                                        <input type="date"
                                            name="tanggal"
                                            id="tanggal"
                                            value="{{ old('tanggal', date('Y-m-d')) }}"
                                            min="{{ date('Y-m-d') }}"
                                            class="form-control-elite @error('tanggal') is-invalid @enderror"
                                            required>
                                    </div>

                                    @error('tanggal')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>


                            {{-- PEGAWAI --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label for="pegawai_id" class="form-label-elite">
                                        Terapis <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite"><i class="fas fa-user-md"></i></span>
                                        <select name="pegawai_id"
                                                id="pegawai_id"
                                                class="form-control-elite @error('pegawai_id') is-invalid @enderror"
                                                required>

                                            <option value="">Pilih Terapis</option>

                                            @foreach($pegawai as $item)
                                                <option value="{{ $item->id }}" {{ old('pegawai_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    @error('pegawai_id')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>


                            {{-- JAM MULAI --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label for="jam_mulai" class="form-label-elite">
                                        Jam Mulai <span class="text-maroon-elite">*</span>
                                    </label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite"><i class="far fa-clock"></i></span>
                                        <input type="time"
                                            name="jam_mulai"
                                            id="jam_mulai"
                                            value="{{ old('jam_mulai', '08:00') }}"
                                            class="form-control-elite @error('jam_mulai') is-invalid @enderror"
                                            min="08:00"
                                            max="17:00"
                                            required>
                                    </div>

                                    @error('jam_mulai')
                                        <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                    @enderror

                                </div>
                            </div>


                            {{-- JAM SELESAI --}}
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label for="jam_selesai" class="form-label-elite">Jam Selesai</label>

                                    <div class="input-group-elite">
                                        <span class="input-icon-box-elite" style="color:#198754;background:#e8f8f1;"><i class="far fa-clock"></i></span>
                                        <input type="time"
                                            name="jam_selesai"
                                            id="jam_selesai"
                                            value="{{ old('jam_selesai') }}"
                                            class="form-control-elite"
                                            readonly>
                                    </div>

                                    <small class="text-muted-elite d-block mt-1">
                                        Jam selesai dihitung otomatis berdasarkan total durasi perawatan.
                                    </small>

                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- REKOMENDASI JADWAL OTOMATIS --}}
                    <div id="scheduleRecommendation" class="d-none px-4 pb-4">

                        <div class="schedule-recommendation-box">

                            <div class="schedule-recommendation-header">
                                <div>
                                    <div class="fw-bold">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        Rekomendasi Jadwal
                                    </div>
                                    <small>Kami mengecek jadwal terapis sebelum booking disimpan.</small>
                                </div>
                            </div>

                            <div id="scheduleRecommendationContent" class="schedule-recommendation-content"></div>

                        </div>

                    </div>

                </div>


                {{-- CATATAN --}}
                <div class="card-elite mb-4 bkl-reveal">

                    <div class="card-header-elite">
                        <h6 class="section-title-elite-inline">
                            <i class="fas fa-sticky-note mr-2"></i>
                            Catatan Booking
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <textarea name="catatan"
                                id="catatan"
                                rows="4"
                                class="form-control form-control-elite @error('catatan') is-invalid @enderror"
                                placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =====================================================
                BAGIAN KANAN
            ====================================================== --}}
            <div class="col-lg-4">

                <div class="reservation-detail-card-elite sticky-top" style="top: 20px;">

                    <div class="detail-header-elite">

                        <div class="header-ornament-1"></div>
                        <div class="header-ornament-2"></div>

                        <div class="position-relative">
                            <div class="detail-store-elite">
                                <i class="fas fa-spa"></i>
                                Azzahra Salon & Spa
                            </div>
                            <h5>Ringkasan Booking</h5>
                        </div>

                        <i class="fas fa-receipt detail-header-icon-elite position-relative"></i>

                    </div>


                    <div class="selected-treatment-section-elite">

                        <div class="section-title-row-elite">
                            <span>Perawatan Dipilih</span>
                            <span id="detailJumlah" class="count-pill-elite">0</span>
                        </div>

                        <div id="selectedTreatment">

                            <div class="empty-treatment-elite">
                                <div class="empty-icon-elite"><i class="fas fa-spa"></i></div>
                                <h6>Belum ada perawatan</h6>
                                <p>Pilih perawatan di sebelah kiri.</p>
                            </div>

                        </div>

                    </div>


                    <div class="detail-section-elite">

                        <div class="detail-section-title-elite">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Jadwal
                        </div>

                        <div class="detail-row-elite">
                            <span>Tanggal</span>
                            <strong id="detailTanggal">-</strong>
                        </div>

                        <div class="detail-row-elite">
                            <span>Terapis</span>
                            <strong id="detailPegawai">-</strong>
                        </div>

                        <div class="detail-row-elite">
                            <span>Jam Mulai</span>
                            <strong id="detailJamMulai">-</strong>
                        </div>

                        <div class="detail-row-elite">
                            <span>Jam Selesai</span>
                            <strong id="detailJamSelesai">-</strong>
                        </div>

                    </div>


                    <div class="detail-section-elite">

                        <div class="detail-section-title-elite">
                            <i class="far fa-clock mr-2"></i>
                            Durasi
                        </div>

                        <div class="duration-box-elite">
                            <div class="duration-icon-elite"><i class="far fa-clock"></i></div>
                            <div>
                                <small>Total waktu perawatan</small>
                                <strong id="detailDurasi">0 menit</strong>
                            </div>
                        </div>

                    </div>


                    <div class="detail-total-elite">
                        <span>Total Perawatan</span>
                        <strong id="detailHarga">Rp0</strong>
                    </div>


                    <div class="detail-info-elite">
                        <i class="fas fa-info-circle"></i>
                        <span>Kami mengecek bentrokan jadwal terapis sebelum booking disimpan.</span>
                    </div>

                    @guest
                        <div class="detail-info-elite" style="margin-top:-6px;">
                            <i class="fab fa-whatsapp"></i>
                            <span>
                                Setelah booking dibuat, kirim bukti pembayaran dalam waktu 2 jam.
                                Booking tanpa bukti pembayaran akan dibatalkan otomatis.
                            </span>
                        </div>
                    @endguest


                    <div class="detail-footer-elite">

                        <button type="submit" class="btn btn-gradient-elite btn-block btn-save-reservation-elite">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Buat Booking
                        </button>

                        <a href="{{ auth()->check() ? route('booking.daftar') : route('perawatan-layanan.index') }}"
                           class="btn btn-outline-elite btn-block mt-2">
                            Batal
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>


    </div>

</main>


{{-- ===============================================================
    JAVASCRIPT: sama dengan form admin (hanya alamat cek jadwal dan warna yang disesuaikan)
================================================================ --}}

<script>
window.Swal || document.write('<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"><\/script>');
</script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | KONSTANTA
    |--------------------------------------------------------------------------
    */

    const JAM_BUKA = '08:00';
    const JAM_TUTUP = '17:00';
    const MAX_QTY = 10;
    const TREATMENT_SLIDE_GAP = 14;
    const TREATMENT_IDEAL_CARD_WIDTH = 190;
    const TREATMENT_MAX_PER_PAGE = 4;


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('formReservasi');
    const tanggal = document.getElementById('tanggal');
    const pegawai = document.getElementById('pegawai_id');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');
    const checkboxes = document.querySelectorAll('.treatment-checkbox');

    // Data pelanggan: salah satu ada, tergantung status login
    const idPelanggan = document.getElementById('pelanggan_id');   // pelanggan login
    const namaTamu = document.getElementById('nama_tamu');         // tamu
    const waTamu = document.getElementById('no_whatsapp');         // tamu


    /*
    |--------------------------------------------------------------------------
    | SLIDER & FILTER PERAWATAN
    |--------------------------------------------------------------------------
    */

    const treatmentSlider = document.getElementById('treatmentSlider');
    const treatmentTrack = document.getElementById('treatmentSliderTrack');
    const treatmentPrev = document.getElementById('treatmentPrev');
    const treatmentNext = document.getElementById('treatmentNext');
    const filterKategoriPerawatan = document.getElementById('filterKategoriPerawatan');


    function getTreatmentPerPage() {

        const wrapperWidth = treatmentSlider
            ? treatmentSlider.clientWidth
            : window.innerWidth;

        let perPage = Math.round(
            (wrapperWidth + TREATMENT_SLIDE_GAP) /
            (TREATMENT_IDEAL_CARD_WIDTH + TREATMENT_SLIDE_GAP)
        );

        if (perPage < 1) {
            perPage = 1;
        }

        if (perPage > TREATMENT_MAX_PER_PAGE) {
            perPage = TREATMENT_MAX_PER_PAGE;
        }

        return perPage;

    }


    function applyTreatmentSlideWidths() {

        if (!treatmentSlider || !treatmentTrack) {
            return;
        }

        const perPage = getTreatmentPerPage();
        const wrapperWidth = treatmentSlider.clientWidth;

        if (wrapperWidth <= 0) {
            return;
        }

        const slideWidth =
            (wrapperWidth - (TREATMENT_SLIDE_GAP * (perPage - 1))) /
            perPage;

        treatmentTrack
            .querySelectorAll('.treatment-slide')
            .forEach(function (slide) {

                slide.style.setProperty('flex', '0 0 ' + slideWidth + 'px', 'important');
                slide.style.setProperty('width', slideWidth + 'px', 'important');
                slide.style.setProperty('max-width', slideWidth + 'px', 'important');

            });

    }


    function updateTreatmentSliderButtons() {

        if (!treatmentSlider || !treatmentTrack || !treatmentPrev || !treatmentNext) {
            return;
        }

        const visibleSlides = treatmentTrack.querySelectorAll(
            '.treatment-slide:not([style*="display: none"])'
        );

        if (visibleSlides.length <= getTreatmentPerPage()) {

            treatmentPrev.disabled = true;
            treatmentNext.disabled = true;

            return;

        }

        const maxScroll =
            treatmentSlider.scrollWidth -
            treatmentSlider.clientWidth;

        treatmentPrev.disabled = treatmentSlider.scrollLeft <= 5;
        treatmentNext.disabled = treatmentSlider.scrollLeft >= maxScroll - 5;

    }


    function applyTreatmentFilter() {

        if (!treatmentTrack) {
            return;
        }

        const selectedCategory = filterKategoriPerawatan
            ? filterKategoriPerawatan.value
            : 'semua';

        const slides = treatmentTrack.querySelectorAll('.treatment-slide');

        let visibleCount = 0;

        slides.forEach(function (slide) {

            const category = slide.dataset.category || 'lainnya';

            const show =
                selectedCategory === 'semua' ||
                category === selectedCategory;

            slide.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;
            }

        });

        if (treatmentSlider) {
            treatmentSlider.scrollLeft = 0;
        }

        applyTreatmentSlideWidths();
        updateTreatmentSliderButtons();

        if (treatmentPrev && treatmentNext) {

            const canSlide = visibleCount > 2;

            treatmentPrev.style.display = canSlide ? 'flex' : 'none';
            treatmentNext.style.display = canSlide ? 'flex' : 'none';

        }

    }


    if (treatmentPrev && treatmentSlider) {

        treatmentPrev.addEventListener('click', function () {

            treatmentSlider.scrollBy({
                left: -treatmentSlider.clientWidth,
                behavior: 'smooth'
            });

        });

    }


    if (treatmentNext && treatmentSlider) {

        treatmentNext.addEventListener('click', function () {

            treatmentSlider.scrollBy({
                left: treatmentSlider.clientWidth,
                behavior: 'smooth'
            });

        });

    }


    if (treatmentSlider) {

        treatmentSlider.addEventListener('scroll', function () {
            updateTreatmentSliderButtons();
        });

    }


    if (filterKategoriPerawatan) {

        filterKategoriPerawatan.addEventListener('change', function () {
            applyTreatmentFilter();
        });

    }


    applyTreatmentSlideWidths();
    applyTreatmentFilter();


    let treatmentResizeTimeout;

    window.addEventListener('resize', function () {

        clearTimeout(treatmentResizeTimeout);

        treatmentResizeTimeout = setTimeout(function () {

            applyTreatmentSlideWidths();
            updateTreatmentSliderButtons();

        }, 150);

    });


    /*
    |--------------------------------------------------------------------------
    | FORMAT & HELPER
    |--------------------------------------------------------------------------
    */

    function formatRupiah(angka) {

        angka = parseInt(angka || 0);

        return 'Rp' + angka.toLocaleString('id-ID');

    }


    function formatTanggal(value) {

        if (!value) {
            return '-';
        }

        const date = new Date(value + 'T00:00:00');

        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

    }


    function getQuantity(id) {

        const element = document.getElementById('qty_' + id);

        if (!element) {
            return 0;
        }

        let qty = parseInt(element.innerText) || 0;

        if (qty < 0) {
            qty = 0;
        }

        if (qty > MAX_QTY) {
            qty = MAX_QTY;
        }

        return qty;

    }


    function updateCheckboxValue(checkbox) {

        const id = checkbox.dataset.id;
        const qty = getQuantity(id);

        checkbox.value = qty > 0 ? qty : 1;

    }


    function updateQuantity(id, quantity) {

        let qty = parseInt(quantity) || 0;

        if (qty < 0) {
            qty = 0;
        }

        if (qty > MAX_QTY) {
            qty = MAX_QTY;
        }

        const qtyElement = document.getElementById('qty_' + id);

        if (qtyElement) {
            qtyElement.innerText = qty;
        }

        const checkbox = document.getElementById('perawatan_' + id);

        if (checkbox) {

            checkbox.checked = qty > 0;
            checkbox.value = qty > 0 ? qty : 1;

        }

        updateDetail();

    }


    function getSelectedData() {

        const selected = [];

        checkboxes.forEach(function (checkbox) {

            if (!checkbox.checked) {
                return;
            }

            const id = checkbox.dataset.id;
            const quantity = getQuantity(id);

            if (quantity <= 0) {
                return;
            }

            selected.push({
                id: id,
                name: checkbox.dataset.name,
                duration: parseInt(checkbox.dataset.duration || 0),
                price: parseInt(checkbox.dataset.price || 0),
                quantity: quantity,
                description: checkbox.dataset.description || ''
            });

        });

        return selected;

    }


    function calculateTotal() {

        const selected = getSelectedData();

        let totalDuration = 0;
        let totalPrice = 0;
        let totalQuantity = 0;

        selected.forEach(function (item) {

            totalDuration += item.duration * item.quantity;
            totalPrice += item.price * item.quantity;
            totalQuantity += item.quantity;

        });

        return {
            selected: selected,
            totalDuration: totalDuration,
            totalPrice: totalPrice,
            totalQuantity: totalQuantity
        };

    }


    function escapeHtml(text) {

        const div = document.createElement('div');

        div.innerText = text;

        return div.innerHTML;

    }


    function updateSelectedTreatment() {

        const result = calculateTotal();

        const container = document.getElementById('selectedTreatment');

        document.getElementById('jumlahPilihan').innerText =
            result.totalQuantity + ' Dipilih';

        document.getElementById('detailJumlah').innerText =
            result.totalQuantity;

        if (result.selected.length === 0) {

            container.innerHTML = `

                <div class="empty-treatment-elite">

                    <div class="empty-icon-elite">

                        <i class="fas fa-spa"></i>

                    </div>

                    <h6>
                        Belum ada perawatan
                    </h6>

                    <p>
                        Pilih perawatan di sebelah kiri.
                    </p>

                </div>

            `;

            return;

        }

        let html = '';

        result.selected.forEach(function (item) {

            const totalItem = item.price * item.quantity;

            html += `

                <div class="selected-item">

                    <div class="selected-item-image
                                d-flex
                                align-items-center
                                justify-content-center">

                        <i class="fas fa-spa"></i>

                    </div>

                    <div class="selected-item-info" style="flex:1;min-width:0;">

                        <div class="selected-item-name">

                            ${escapeHtml(item.name)}

                            <span class="selected-item-qty">

                                × ${item.quantity}

                            </span>

                        </div>

                        <div class="selected-item-meta">

                            <i class="far fa-clock mr-1"></i>

                            ${item.duration}
                            menit

                        </div>

                        <div class="selected-item-price">

                            ${formatRupiah(totalItem)}

                        </div>

                    </div>

                </div>

            `;

        });

        container.innerHTML = html;

    }


    function hitungJamSelesai() {

        const result = calculateTotal();

        if (!jamMulai.value || result.totalDuration <= 0) {

            jamSelesai.value = '';

            return;

        }

        const parts = jamMulai.value.split(':');

        let hours = parseInt(parts[0]);
        let minutes = parseInt(parts[1]);

        minutes += result.totalDuration;

        hours += Math.floor(minutes / 60);
        minutes = minutes % 60;

        const formattedHours = String(hours).padStart(2, '0');
        const formattedMinutes = String(minutes).padStart(2, '0');

        jamSelesai.value = formattedHours + ':' + formattedMinutes;

    }


    function updateScheduleDetail() {

        document.getElementById('detailTanggal').innerText =
            formatTanggal(tanggal.value);

        document.getElementById('detailPegawai').innerText =
            pegawai.value
                ? pegawai.options[pegawai.selectedIndex].text
                : '-';

        document.getElementById('detailJamMulai').innerText =
            jamMulai.value || '-';

        document.getElementById('detailJamSelesai').innerText =
            jamSelesai.value || '-';

    }


    function updateSummary() {

        const result = calculateTotal();

        document.getElementById('detailDurasi').innerText =
            result.totalDuration + ' menit';

        document.getElementById('detailHarga').innerText =
            formatRupiah(result.totalPrice);

        hitungJamSelesai();
        updateScheduleDetail();

    }


    function updateDetail() {

        updateSelectedTreatment();
        updateSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | EVENT PERAWATAN
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const id = this.dataset.id;

            const qtyElement = document.getElementById('qty_' + id);

            if (this.checked) {

                if (!qtyElement || parseInt(qtyElement.innerText) <= 0) {

                    updateQuantity(id, 1);

                } else {

                    updateCheckboxValue(this);

                }

            } else {

                updateQuantity(id, 0);

            }

            updateDetail();

        });

    });


    document.querySelectorAll('.btn-plus').forEach(function (button) {

        button.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            const id = this.dataset.id;
            const current = getQuantity(id);

            if (current >= MAX_QTY) {
                return;
            }

            updateQuantity(id, current + 1);

        });

    });


    document.querySelectorAll('.btn-minus').forEach(function (button) {

        button.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            const id = this.dataset.id;
            const current = getQuantity(id);

            if (current <= 1) {

                updateQuantity(id, 0);

            } else {

                updateQuantity(id, current - 1);

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EVENT JADWAL
    |--------------------------------------------------------------------------
    */

    jamMulai.addEventListener('change', function () {

        hitungJamSelesai();
        updateScheduleDetail();
        resetScheduleRecommendation();
        cekJadwal();

    });


    tanggal.addEventListener('change', function () {

        updateScheduleDetail();
        resetScheduleRecommendation();
        cekJadwal();

    });


    pegawai.addEventListener('change', function () {

        updateScheduleDetail();
        resetScheduleRecommendation();
        cekJadwal();

    });


    /*
    |--------------------------------------------------------------------------
    | CEK JADWAL & REKOMENDASI
    |--------------------------------------------------------------------------
    */

    let hasilCekJadwal = null;
    let sedangCekJadwal = false;

    function getSchedulePayload() {

        hitungJamSelesai();

        return {
            tanggal: tanggal.value,
            pegawai_id: pegawai.value,
            jam_mulai: jamMulai.value,
            jam_selesai: jamSelesai.value
        };

    }


    function resetScheduleRecommendation() {

        hasilCekJadwal = null;

        const box = document.getElementById('scheduleRecommendation');
        const content = document.getElementById('scheduleRecommendationContent');

        if (box) {
            box.classList.add('d-none');
        }

        if (content) {
            content.innerHTML = '';
        }

    }


    function showScheduleRecommendation(data) {

        const box = document.getElementById('scheduleRecommendation');
        const content = document.getElementById('scheduleRecommendationContent');

        if (!box || !content) {
            return;
        }

        box.classList.remove('d-none');

        let html = '';

        if (data.status === 'tersedia') {

            html += `
                <div class="alert alert-success schedule-check-alert mb-0">
                    <i class="fas fa-check-circle mr-1"></i>
                    <strong>Jadwal tersedia.</strong>
                    Terapis dapat melayani pada waktu yang dipilih.
                </div>
            `;

        } else if (data.status === 'tunggu') {

            html += `
                <div class="alert alert-warning schedule-check-alert">
                    <i class="fas fa-clock mr-1"></i>
                    <strong>Jadwal masih dapat diterima.</strong><br>
                    Anda akan menunggu <strong>${data.wait_minutes} menit</strong>
                    dikarenakan menunggu terapi dari reservasi sebelumnya.
                </div>
            `;

            if (data.reservasi_berbenturan) {

                html += `
                    <div class="text-muted" style="font-size:11px;">
                        <i class="fas fa-info-circle mr-1"></i>
                        Reservasi sebelumnya berakhir sekitar
                        <strong>${data.reservasi_berbenturan.jam_selesai}</strong>.
                    </div>
                `;

            }

        } else if (data.status === 'bentrok') {

            html += `
                <div class="alert alert-danger schedule-check-alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Jadwal bentrok.</strong><br>
                    Jadwal yang dipilih bertabrakan lebih dari 15 menit
                    dengan reservasi lain pada terapis yang sama.
                    Silakan pilih salah satu jadwal rekomendasi berikut.
                </div>
            `;

            if (Array.isArray(data.rekomendasi) && data.rekomendasi.length > 0) {

                html += `
                    <div class="font-weight-bold" style="font-size:12px;color:#3a2a26;">
                        <i class="fas fa-calendar-check mr-1" style="color:#64172D;"></i>
                        Rekomendasi jadwal terdekat
                    </div>

                    <div class="schedule-recommendation-list">
                `;

                data.rekomendasi.forEach(function (item) {

                    html += `
                        <div class="schedule-recommendation-item"
                            data-jam-mulai="${item.jam_mulai}"
                            data-jam-selesai="${item.jam_selesai}">

                            <div>
                                <div class="schedule-recommendation-time">
                                    <i class="far fa-clock mr-1" style="color:#64172D;"></i>
                                    ${item.jam_mulai} - ${item.jam_selesai}
                                </div>

                                <div class="schedule-recommendation-meta">
                                    ${item.tanggal}
                                </div>
                            </div>

                            <div class="schedule-recommendation-use">
                                Pilih
                                <i class="fas fa-chevron-right ml-1"></i>
                            </div>

                        </div>
                    `;

                });

                html += `
                    </div>
                `;

            } else {

                html += `
                    <div class="text-muted"
                        style="font-size:11px;">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tidak ditemukan slot kosong pada jam operasional
                        08:00 - 17:00 untuk durasi perawatan tersebut.
                    </div>
                `;

            }

        }

        content.innerHTML = html;

        content
            .querySelectorAll('.schedule-recommendation-item')
            .forEach(function (item) {

                item.addEventListener('click', function () {

                    jamMulai.value = this.dataset.jamMulai;

                    hitungJamSelesai();
                    updateScheduleDetail();
                    resetScheduleRecommendation();
                    cekJadwal();

                });

            });

    }


    async function cekJadwal() {

        const payload = getSchedulePayload();

        if (
            !payload.tanggal ||
            !payload.pegawai_id ||
            !payload.jam_mulai ||
            !payload.jam_selesai
        ) {

            resetScheduleRecommendation();

            return {
                status: 'belum_lengkap'
            };

        }

        const box = document.getElementById('scheduleRecommendation');
        const content = document.getElementById('scheduleRecommendationContent');

        if (!box || !content) {

            return {
                status: 'belum_lengkap'
            };

        }

        box.classList.remove('d-none');

        content.innerHTML = `
            <div class="schedule-loading">
                <i class="fas fa-spinner fa-spin mr-1"></i>
                Sedang mengecek jadwal terapis...
            </div>
        `;

        sedangCekJadwal = true;

        try {

            const query = new URLSearchParams(payload);

            const response = await fetch(
                '{{ route('booking.cek-jadwal') }}?' + query.toString(),
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Gagal mengecek jadwal.');
            }

            const data = await response.json();

            hasilCekJadwal = data;

            showScheduleRecommendation(data);

            return data;

        } catch (error) {

            console.error(error);

            hasilCekJadwal = null;

            content.innerHTML = `
                <div class="alert alert-danger schedule-check-alert mb-0">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    Sistem tidak dapat mengecek jadwal saat ini.
                    Silakan coba kembali.
                </div>
            `;

            return {
                status: 'error'
            };

        } finally {

            sedangCekJadwal = false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT FORM
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', async function (event) {

        const result = calculateTotal();

        // Pelanggan login: data akun harus terbaca
        if (idPelanggan && !idPelanggan.value) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Data pelanggan tidak ditemukan',
                text: 'Muat ulang halaman lalu coba lagi.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        // Tamu: nama dan nomor WhatsApp wajib valid
        if (namaTamu && namaTamu.value.trim().length < 3) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Nama belum diisi',
                text: 'Silakan isi nama lengkap Anda.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        if (waTamu && !/^(\+?62|0)8[0-9]{7,12}$/.test(waTamu.value.replace(/[\s\-().]/g, ''))) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Nomor WhatsApp tidak valid',
                text: 'Contoh penulisan: 081234567890.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        if (result.selected.length === 0 || result.totalQuantity <= 0) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Perawatan belum dipilih',
                text: 'Silakan pilih minimal satu perawatan.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        if (!pegawai.value) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Terapis belum dipilih',
                text: 'Silakan pilih terapis terlebih dahulu.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        if (!jamMulai.value) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Jam mulai belum dipilih',
                text: 'Silakan pilih jam mulai booking.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        hitungJamSelesai();

        if (!jamSelesai.value || jamSelesai.value > JAM_TUTUP) {

            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Jadwal di luar jam operasional',
                html:
                    'Jam operasional Azzahra Salon & Spa adalah ' +
                    '<strong>08:00 - 17:00</strong>.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        event.preventDefault();

        const scheduleResult = await cekJadwal();

        if (scheduleResult.status === 'bentrok') {

            Swal.fire({
                icon: 'warning',
                title: 'Jadwal Bentrok',
                html:
                    'Jadwal yang dipilih bertabrakan lebih dari ' +
                    '<strong>15 menit</strong> dengan reservasi lain.<br>' +
                    'Silakan gunakan salah satu rekomendasi jadwal yang tersedia.',
                confirmButtonColor: '#64172D',
                confirmButtonText: 'Lihat Rekomendasi'
            });

            return;

        }

        if (scheduleResult.status === 'error') {

            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengecek Jadwal',
                text:
                    'Sistem belum dapat memastikan ketersediaan jadwal. ' +
                    'Silakan coba kembali.',
                confirmButtonColor: '#64172D'
            });

            return;

        }

        checkboxes.forEach(function (checkbox) {

            if (checkbox.checked) {
                updateCheckboxValue(checkbox);
            }

        });

        event.preventDefault();

        Swal.fire({
            title: 'Buat Booking?',
            html:
                'Total <strong>' +
                result.totalQuantity +
                '</strong> perawatan dengan total durasi <strong>' +
                result.totalDuration +
                ' menit</strong>.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#64172D',
            cancelButtonColor: '#858796',
            confirmButtonText: '<i class="fas fa-calendar-check"></i> Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then(function (response) {

            if (response.isConfirmed) {
                form.submit();
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | INISIALISASI NILAI AWAL PERAWATAN
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(function (checkbox) {

        const id = checkbox.dataset.id;

        const qtyElement = document.getElementById('qty_' + id);

        if (checkbox.checked) {

            let qty = parseInt(qtyElement.innerText) || 1;

            if (qty <= 0) {
                qty = 1;
            }

            if (qty > MAX_QTY) {
                qty = MAX_QTY;
            }

            qtyElement.innerText = qty;
            checkbox.value = qty;

        } else {

            qtyElement.innerText = '0';
            checkbox.value = '1';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | GESER SLIDER KE PERAWATAN YANG SUDAH TERPILIH
    | (misalnya datang dari tombol "Pesan Perawatan" di halaman detail layanan)
    |--------------------------------------------------------------------------
    */

    const terpilihPertama = document.querySelector('.treatment-checkbox:checked');

    if (terpilihPertama && treatmentSlider) {

        const slide = terpilihPertama.closest('.treatment-slide');

        if (slide) {

            treatmentSlider.scrollLeft =
                slide.offsetLeft - treatmentSlider.offsetLeft - 4;

            updateTreatmentSliderButtons();

        }

    }


    updateDetail();

});

</script>

{{-- ANIMASI ANGKA: harga, durasi, dan jumlah perawatan menghitung naik/turun saat pilihan berubah --}}
<script>
(function () {

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var configs = [
        { id: 'detailHarga',   fmt: function (n) { return 'Rp' + n.toLocaleString('id-ID'); } },
        { id: 'detailDurasi',  fmt: function (n) { return n + ' menit'; } },
        { id: 'detailJumlah',  fmt: function (n) { return String(n); } },
        { id: 'jumlahPilihan', fmt: function (n) { return n + ' Dipilih'; } }
    ];

    function parseNumber(text) {
        return parseInt(String(text).replace(/\D/g, ''), 10) || 0;
    }

    function easeOut(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    configs.forEach(function (cfg) {

        var el = document.getElementById(cfg.id);

        if (!el) {
            return;
        }

        var shown = parseNumber(el.textContent);   // angka yang sedang tampil
        var lastText = el.textContent;             // teks terakhir yang ditulis animasi
        var raf = null;

        function write(value) {
            lastText = cfg.fmt(value);
            el.textContent = lastText;
        }

        function animateTo(target) {

            if (raf) {
                cancelAnimationFrame(raf);
            }

            var from = shown;

            if (reduce || from === target) {
                shown = target;
                write(target);
                return;
            }

            var duration = 600;
            var start = null;

            el.classList.remove('bkl-bump');
            void el.offsetWidth;
            el.classList.add('bkl-bump');

            function step(now) {

                if (start === null) {
                    start = now;
                }

                var progress = Math.min((now - start) / duration, 1);

                shown = Math.round(from + (target - from) * easeOut(progress));
                write(shown);

                if (progress < 1) {
                    raf = requestAnimationFrame(step);
                } else {
                    raf = null;
                    shown = target;
                    write(target);
                }

            }

            raf = requestAnimationFrame(step);

        }

        // Tangkap setiap perubahan teks yang dilakukan updateDetail()
        var observer = new MutationObserver(function () {

            if (el.textContent === lastText) {
                return; // perubahan dari animasi sendiri
            }

            var target = parseNumber(el.textContent);

            write(shown);        // kembalikan ke angka lama agar tidak berkedip
            animateTo(target);

        });

        observer.observe(el, { childList: true, characterData: true, subtree: true });

    });

})();
</script>


{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection

