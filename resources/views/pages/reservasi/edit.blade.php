@extends('layouts.app')

@section('title', 'Edit Reservasi')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | DATA PERAWATAN YANG SUDAH DIPILIH
    |--------------------------------------------------------------------------
    */

    $selectedPerawatan = [];

    if ($data->perawatans && $data->perawatans->count()) {

        foreach ($data->perawatans as $item) {

            $selectedPerawatan[$item->id] = [
                'jumlah' => (int) ($item->pivot->jumlah ?? 1),
                'harga'  => (int) ($item->pivot->harga ?? $item->harga),
                'durasi' => (int) ($item->pivot->durasi ?? $item->durasi),
            ];

        }

    } elseif ($data->perawatan) {

        $selectedPerawatan[$data->perawatan->id] = [
            'jumlah' => 1,
            'harga'  => (int) $data->perawatan->harga,
            'durasi' => (int) $data->perawatan->durasi,
        ];

    }

@endphp


<div class="container-fluid px-4 customer-form-elite">

    {{-- ================= HEADER ================= --}}
    <div class="form-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="d-flex align-items-center gap-3 position-relative">
            <div class="header-icon-elite">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-white ml-2">Edit Reservasi</h4>
                <p class="text-white-50 mb-0 small ml-2">Perbarui data reservasi perawatan pelanggan</p>
            </div>
        </div>

        <a href="{{ route('reservasi.index') }}" class="btn btn-outline-elite-onheader position-relative">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>

    </div>


    {{-- ================= ALERT ================= --}}

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Periksa kembali data berikut:</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif

    {{-- ERROR SESSION --}}
    @if(session('error'))
        <div class="alert-elite alert-danger-elite mb-4">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif

    {{-- BENTROK --}}
    @if(session('bentrok'))
        <div class="alert-elite alert-warning-elite mb-4">
            <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Jadwal berpotensi bentrok</strong>
                <div>{{ session('bentrok') }}</div>
            </div>
            <button type="button" class="alert-close-elite" data-dismiss="alert" aria-label="Tutup">&times;</button>
        </div>
    @endif

    {{-- REKOMENDASI --}}
    @if(session('rekomendasi'))
        <div class="alert-elite alert-info-elite mb-4">
            <i class="fas fa-lightbulb mr-2 mt-1"></i>
            <div class="flex-grow-1">
                <strong>Rekomendasi jadwal alternatif</strong>
                <ul class="mb-0 mt-1 pl-3">
                    @foreach(session('rekomendasi') as $item)
                        <li>
                            {{ $item['tanggal'] ?? '' }}
                            -
                            {{ $item['jam_mulai'] ?? '' }}
                            s/d
                            {{ $item['jam_selesai'] ?? '' }}
                            @if(isset($item['pegawai']))
                                ({{ $item['pegawai'] }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- ================= FORM ================= --}}
    <form action="{{ route('reservasi.update', $data->id) }}"
          method="POST"
          id="formReservasi">

        @csrf
        @method('PUT')

        <div class="row">

            {{-- ================= LEFT ================= --}}
            <div class="col-lg-8">

                <div class="card-elite mb-4">

                    <div class="card-body p-4 p-md-5">

                        {{-- ========== DATA PELANGGAN ========== --}}
                        <div class="section-title-elite mb-4">
                            <h6>Data Pelanggan</h6>
                            <p>Pilih pelanggan yang melakukan reservasi.</p>
                        </div>

                        <div class="mb-4">

                            <label for="pelanggan_id" class="form-label-elite">
                                Pelanggan <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-user"></i>
                                </span>

                                <select name="pelanggan_id"
                                        id="pelanggan_id"
                                        class="form-control-elite @error('pelanggan_id') is-invalid @enderror"
                                        required>

                                    <option value="">-- Pilih Pelanggan --</option>

                                    @foreach($pelanggan as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('pelanggan_id', $data->pelanggan_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}@if($item->no_telepon) - {{ $item->no_telepon }}@endif
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @error('pelanggan_id')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        <hr class="divider-elite my-4">


                        {{-- ========== PILIH PERAWATAN ========== --}}
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">

                            <div class="section-title-elite">
                                <h6>Pilih Perawatan</h6>
                                <p>Pilih satu atau beberapa perawatan.</p>
                            </div>

                            <span class="badge-pill-elite badge-gold-elite" id="jumlahPilihan">
                                0 Dipilih
                            </span>

                        </div>

                        @error('perawatan_ids')
                            <div class="alert-elite alert-danger-elite mb-3 py-2">
                                <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                                <div>{{ $message }}</div>
                            </div>
                        @enderror

                        @error('perawatan_id')
                            <div class="alert-elite alert-danger-elite mb-3 py-2">
                                <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                                <div>{{ $message }}</div>
                            </div>
                        @enderror

                        <div class="info-box-elite mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle mr-2 mt-1"></i>
                                <small>
                                    Kamu dapat memilih lebih dari satu perawatan.
                                    Total durasi dan harga akan dihitung otomatis.
                                </small>
                            </div>
                        </div>


                        {{-- FILTER KATEGORI PERAWATAN --}}
                        <div class="treatment-filter-wrapper mb-4">

                            <div class="d-flex flex-wrap align-items-center justify-content-between">

                                <div class="mb-2 mb-md-0">
                                    <label for="filterKategoriPerawatan" class="mb-1 font-weight-bold title-elite">
                                        <i class="fas fa-filter mr-1 text-maroon-elite"></i>
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

                                            $selectedData =
                                                $selectedPerawatan[$item->id]
                                                ?? null;

                                            $oldJumlah =
                                                old(
                                                    'jumlah_perawatan.' . $item->id,
                                                    $selectedData['jumlah'] ?? 0
                                                );

                                            $selected =
                                                (int) $oldJumlah > 0;

                                            $kategoriItem =
                                                optional($item->kategori)->nama ?? 'Lainnya';

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
                                                                <img src="{{ asset('storage/foto_perawatan/' . $item->foto) }}" alt="{{ $item->nama }}">
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
                                                <h6 class="fw-bold title-elite">Belum ada perawatan</h6>
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


                        <hr class="divider-elite my-4">


                        {{-- ========== JADWAL ========== --}}
                        <div class="section-title-elite mb-4">
                            <h6>Jadwal Reservasi</h6>
                            <p>Atur tanggal, terapis, dan jam perawatan.</p>
                        </div>

                        <div class="row">

                            {{-- TANGGAL --}}
                            <div class="col-md-6 mb-4">

                                <label for="tanggal" class="form-label-elite">
                                    Tanggal <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>

                                    <input type="date"
                                           name="tanggal"
                                           id="tanggal"
                                           value="{{ old('tanggal', optional($data->tanggal)->format('Y-m-d')) }}"
                                           class="form-control-elite @error('tanggal') is-invalid @enderror"
                                           required>
                                </div>

                                @error('tanggal')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- PEGAWAI --}}
                            <div class="col-md-6 mb-4">

                                <label for="pegawai_id" class="form-label-elite">
                                    Terapis / Pegawai <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="fas fa-user-tie"></i>
                                    </span>

                                    <select name="pegawai_id"
                                            id="pegawai_id"
                                            class="form-control-elite @error('pegawai_id') is-invalid @enderror"
                                            required>

                                        <option value="">-- Pilih Terapis --</option>

                                        @foreach($pegawai as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('pegawai_id', $data->pegawai_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                @error('pegawai_id')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- JAM MULAI --}}
                            <div class="col-md-6 mb-4">

                                <label for="jam_mulai" class="form-label-elite">
                                    Jam Mulai <span class="text-maroon-elite">*</span>
                                </label>

                                <div class="input-group-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="far fa-clock"></i>
                                    </span>

                                    <input type="time"
                                           name="jam_mulai"
                                           id="jam_mulai"
                                           value="{{ old('jam_mulai', \Carbon\Carbon::parse($data->jam_mulai)->format('H:i')) }}"
                                           class="form-control-elite @error('jam_mulai') is-invalid @enderror"
                                           min="08:00"
                                           max="17:00"
                                           required>
                                </div>

                                @error('jam_mulai')
                                    <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                                @enderror

                            </div>


                            {{-- JAM SELESAI --}}
                            <div class="col-md-6 mb-4">

                                <label for="jam_selesai" class="form-label-elite">Jam Selesai</label>

                                <div class="input-group-elite input-group-readonly-elite">
                                    <span class="input-icon-box-elite">
                                        <i class="far fa-clock"></i>
                                    </span>

                                    <input type="time"
                                           name="jam_selesai"
                                           id="jam_selesai"
                                           value="{{ old('jam_selesai', \Carbon\Carbon::parse($data->jam_selesai)->format('H:i')) }}"
                                           class="form-control-elite"
                                           readonly>
                                </div>

                                <small class="text-muted-elite d-block mt-1">
                                    Jam selesai dihitung otomatis berdasarkan total durasi perawatan.
                                </small>

                            </div>

                        </div>


                        {{-- REKOMENDASI JADWAL OTOMATIS --}}
                        <div id="scheduleRecommendation" class="d-none mb-4">

                            <div class="schedule-recommendation-box">

                                <div class="schedule-recommendation-header">
                                    <div>
                                        <div class="font-weight-bold">
                                            <i class="fas fa-lightbulb mr-2"></i>
                                            Rekomendasi Jadwal
                                        </div>
                                        <small>Sistem mengecek jadwal terapis sebelum perubahan disimpan.</small>
                                    </div>
                                </div>

                                <div id="scheduleRecommendationContent" class="schedule-recommendation-content"></div>

                            </div>

                        </div>


                        <hr class="divider-elite my-4">


                        {{-- ========== STATUS ========== --}}
                        <div class="section-title-elite mb-4">
                            <h6>Status Reservasi</h6>
                            <p>Tentukan status reservasi saat ini.</p>
                        </div>

                        <div class="mb-4">

                            <label for="status" class="form-label-elite">
                                Status <span class="text-maroon-elite">*</span>
                            </label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite">
                                    <i class="fas fa-toggle-on"></i>
                                </span>

                                <select name="status"
                                        id="status"
                                        class="form-control-elite @error('status') is-invalid @enderror"
                                        required>

                                    @foreach(['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'] as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $data->status) == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            @error('status')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>


                        <hr class="divider-elite my-4">


                        {{-- ========== CATATAN ========== --}}
                        <div class="section-title-elite mb-4">
                            <h6>Catatan Reservasi</h6>
                            <p>Tambahkan catatan jika diperlukan.</p>
                        </div>

                        <div class="mb-0">

                            <label for="catatan" class="form-label-elite">Catatan</label>

                            <div class="input-group-elite">
                                <span class="input-icon-box-elite align-items-start pt-3">
                                    <i class="fas fa-sticky-note"></i>
                                </span>

                                <textarea name="catatan"
                                          id="catatan"
                                          rows="4"
                                          class="form-control-elite @error('catatan') is-invalid @enderror"
                                          placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan', $data->catatan) }}</textarea>
                            </div>

                            @error('catatan')
                                <small class="text-maroon-elite d-block mt-1">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================= RIGHT: RINGKASAN ================= --}}
            <div class="col-lg-4">

                <div class="reservation-detail-card">

                    {{-- HEADER --}}
                    <div class="detail-header">

                        <div class="detail-header-ornament"></div>

                        <div class="position-relative">
                            <div class="detail-store">
                                <i class="fas fa-spa"></i>
                                Azzahra Salon & Spa
                            </div>
                            <h5>Edit Reservasi</h5>
                        </div>

                        <i class="fas fa-receipt detail-header-icon"></i>

                    </div>


                    {{-- PERAWATAN --}}
                    <div class="selected-treatment-section">

                        <div class="section-title">
                            <span>Perawatan Dipilih</span>
                            <span id="detailJumlah" class="badge-pill-elite badge-maroon-elite">0</span>
                        </div>

                        <div id="selectedTreatment">

                            <div class="empty-treatment">

                                <div class="empty-icon">
                                    <i class="fas fa-spa"></i>
                                </div>

                                <h6>Belum ada perawatan</h6>

                                <p>Pilih perawatan di sebelah kiri.</p>

                            </div>

                        </div>

                    </div>


                    {{-- JADWAL --}}
                    <div class="detail-section">

                        <div class="detail-section-title">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Jadwal
                        </div>

                        <div class="detail-row">
                            <span>Tanggal</span>
                            <strong id="detailTanggal">-</strong>
                        </div>

                        <div class="detail-row">
                            <span>Terapis</span>
                            <strong id="detailPegawai">-</strong>
                        </div>

                        <div class="detail-row">
                            <span>Jam Mulai</span>
                            <strong id="detailJamMulai">-</strong>
                        </div>

                        <div class="detail-row">
                            <span>Jam Selesai</span>
                            <strong id="detailJamSelesai">-</strong>
                        </div>

                    </div>


                    {{-- DURASI --}}
                    <div class="detail-section">

                        <div class="detail-section-title">
                            <i class="far fa-clock mr-2"></i>
                            Durasi
                        </div>

                        <div class="duration-box">

                            <div class="duration-icon">
                                <i class="far fa-clock"></i>
                            </div>

                            <div>
                                <small>Total waktu perawatan</small>
                                <strong id="detailDurasi">0 menit</strong>
                            </div>

                        </div>

                    </div>


                    {{-- TOTAL --}}
                    <div class="detail-total">
                        <span>Total Perawatan</span>
                        <strong id="detailHarga">Rp0</strong>
                    </div>


                    {{-- INFO --}}
                    <div class="detail-info">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            Sistem akan mengecek bentrokan jadwal
                            sebelum perubahan reservasi disimpan.
                        </span>
                    </div>


                    {{-- FOOTER --}}
                    <div class="detail-footer">

                        <button type="submit" class="btn btn-gradient-elite btn-block btn-save-reservation">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('reservasi.index') }}" class="btn btn-outline-elite btn-block mt-2">
                            Batal
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<style>

/* =========================================================
   DESIGN TOKENS
========================================================== */

.customer-form-elite {
    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

.title-elite { color: #3a2a26; }
.text-maroon-elite { color: var(--sb-red) !important; }
.text-muted-elite { color: #a09a94; }


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


/* =========================================================
   CARD
========================================================== */

.card-elite {
    background: #ffffff;
    border: 1px solid rgba(217,172,79,.15);
    border-radius: 18px;
    box-shadow: 0 6px 26px rgba(124,16,19,.06);
    overflow: hidden;
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


/* =========================================================
   SECTION TITLE
========================================================== */

.section-title-elite h6 {
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 3px;
    position: relative;
    padding-left: 12px;
}
.section-title-elite h6::before {
    content: "";
    position: absolute;
    left: 0; top: 2px;
    width: 4px; height: 16px;
    border-radius: 3px;
    background: linear-gradient(180deg, var(--sb-red), var(--sb-gold));
}
.section-title-elite p {
    color: #a09a94;
    font-size: 12.5px;
    margin: 0 0 0 12px;
}


/* =========================================================
   LABEL
========================================================== */

.form-label-elite {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #5a4a44;
    margin-bottom: 6px;
}


/* =========================================================
   INPUT GROUP
========================================================== */

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
.input-group-readonly-elite {
    background: #faf6ee;
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

.form-control-elite.is-invalid {
    background: rgba(224,28,29,.04);
}


/* =========================================================
   INFO BOX
========================================================== */

.info-box-elite {
    padding: 14px 16px;
    border-radius: 10px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.3);
    color: #8a6a2f;
    font-size: 12.5px;
}
.info-box-elite i {
    color: var(--sb-gold);
}


/* =========================================================
   DIVIDER
========================================================== */

.divider-elite {
    border-top: 1px solid rgba(217,172,79,.2);
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
.badge-gold-elite   { background: #fdf3e2; color: #a9781f; }
.badge-maroon-elite { background: #fff0f0; color: var(--sb-red); }


/* =========================================================
   PILIHAN PERAWATAN (SAMA DENGAN CREATE)
========================================================== */

.treatment-item { position: relative; height: 100%; }
.treatment-checkbox { position: absolute; opacity: 0; pointer-events: none; }

.treatment-card {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    background: #fff;
    border: 1px solid #e3e6f0;
    border-radius: 16px;
    padding: 0;
    margin: 0;
    height: 100%;
    min-height: 230px;
    cursor: pointer;
    overflow: hidden;
    transition: all .2s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,.03);
}
.treatment-card:hover {
    transform: translateY(-2px);
    border-color: var(--sb-gold);
    box-shadow: 0 8px 20px rgba(224,28,29,.12);
}
.treatment-checkbox:checked + .treatment-card {
    border: 2px solid var(--sb-red);
    background: #fff8f7;
    box-shadow: 0 8px 25px rgba(224,28,29,.15);
}
.treatment-checkbox:focus-visible + .treatment-card {
    outline: 2px solid var(--sb-red);
    outline-offset: 2px;
}

.treatment-image-wrap {
    position: relative;
    width: 100%;
    border-radius: 16px 16px 0 0;
    overflow: hidden;
}
.treatment-image-wrap::after {
    content: '';
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 55%;
    background: linear-gradient(to top, rgba(0,0,0,.35), rgba(0,0,0,0));
    pointer-events: none;
}

.treatment-image {
    width: 100%;
    height: 100px;
    background: #f8f9fc;
}
.treatment-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.treatment-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff0f1;
    color: var(--sb-red);
    font-size: 30px;
}

.treatment-check {
    position: absolute;
    top: 8px; right: 8px;
    z-index: 3;
    width: 24px; height: 24px;
    border-radius: 50%;
    background: rgba(255,255,255,.92);
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    font-size: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
    transition: .2s;
}
.treatment-checkbox:checked + .treatment-card .treatment-check {
    background: var(--sb-red);
    color: #fff;
    transform: scale(1.08);
}

.treatment-price {
    position: absolute;
    left: 8px; bottom: 8px;
    z-index: 3;
    margin: 0;
    padding: 3px 8px;
    border-radius: 999px;
    background: rgba(255,255,255,.95);
    color: var(--sb-red);
    font-size: 12px;
    font-weight: 800;
    box-shadow: 0 4px 10px rgba(0,0,0,.12);
}

.treatment-content {
    padding: 10px 12px 12px;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.treatment-category-badge {
    display: inline-flex;
    align-items: center;
    align-self: flex-start;
    max-width: 100%;
    margin: 0 0 6px;
    padding: 5px 9px;
    border-radius: 999px;
    background: #fdf3e2;
    color: #a9781f;
    border: 1px solid rgba(217,172,79,.3);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.treatment-name {
    margin-bottom: 7px;
    color: #343a40;
    font-weight: 700;
    font-size: 13.5px;
    line-height: 1.3;
    min-height: 34px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.treatment-meta {
    color: #858796;
    font-size: 11.5px;
    margin-bottom: 8px;
}
.treatment-meta i { color: var(--sb-red); }

.quantity-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 8px;
    border-top: 1px dashed #eee;
}
.quantity-label { font-size: 11px; color: #858796; }
.quantity-control { display: flex; align-items: center; gap: 7px; }

.btn-qty {
    width: 26px; height: 26px; min-width: 26px;
    border-radius: 6px;
    border: 1px solid var(--sb-red);
    background: #fff;
    color: var(--sb-red);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .2s;
}
.btn-qty:hover { background: var(--sb-red); color: #fff; }

.quantity-value {
    min-width: 18px;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    color: #343a40;
}

.treatment-empty-slide { flex: 0 0 100%; max-width: 100%; }


/* FILTER KATEGORI */

.treatment-filter-wrapper {
    position: relative;
    background: linear-gradient(135deg, #fffafa 0%, #ffffff 65%, #fdf3e2 100%);
    border: 1px solid rgba(217,172,79,.2);
    border-radius: 18px;
    padding: 18px 20px;
    box-shadow: 0 5px 18px rgba(36, 37, 42, .035);
}
.treatment-filter-wrapper::before {
    content: '';
    position: absolute;
    left: 0; top: 17px; bottom: 17px;
    width: 3px;
    border-radius: 0 4px 4px 0;
    background: linear-gradient(180deg, var(--sb-red), var(--sb-gold));
}

.treatment-filter-select { min-width: 235px; position: relative; }
.treatment-filter-select .form-control {
    height: 44px;
    border: 1px solid #ecd9bf;
    border-radius: 12px;
    background: #fff;
    color: #3a2a26;
    font-size: 13px;
    font-weight: 600;
    padding-left: 14px;
}
.treatment-filter-select .form-control:focus {
    border-color: var(--sb-gold);
    box-shadow: 0 0 0 3px rgba(217,172,79,.18);
}


/* SLIDER */

.treatment-slider-wrapper { position: relative; padding: 0 46px; }

.treatment-slider {
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    border-radius: 18px;
    padding: 6px 4px 12px;
}
.treatment-slider::-webkit-scrollbar { display: none; }

.treatment-slider-track {
    display: flex;
    flex-wrap: nowrap;
    gap: 14px;
    width: max-content;
    align-items: stretch;
    margin: 0;
}

.treatment-slide {
    min-width: 0;
    padding: 0;
    box-sizing: border-box;
    flex: 0 0 calc((100% - 42px) / 4);
    width: calc((100% - 42px) / 4);
}

.treatment-slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 39px; height: 39px;
    border: 1px solid #f0c0c1;
    border-radius: 50%;
    background: #fff;
    color: var(--sb-red);
    box-shadow: 0 7px 18px rgba(31, 45, 61, .10);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .2s ease;
    cursor: pointer;
}
.treatment-slider-btn:hover:not(:disabled) {
    background: var(--sb-red);
    border-color: var(--sb-red);
    color: #fff;
    transform: translateY(-50%) scale(1.06);
}
.treatment-slider-btn:disabled {
    background: #f7f7f8;
    border-color: #e4e5e8;
    color: #b9bdc4;
    cursor: not-allowed;
    box-shadow: none;
}
.treatment-slider-prev { left: 0; }
.treatment-slider-next { right: 0; }


/* =========================================================
   REKOMENDASI JADWAL
========================================================== */

.schedule-recommendation-box {
    border: 1px solid rgba(217,172,79,.3);
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
}

.schedule-recommendation-header {
    padding: 13px 16px;
    background: #fdf3e2;
    color: #a9781f;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.schedule-recommendation-header small {
    display: block;
    margin-top: 3px;
    color: #a09a94;
    font-size: 11px;
    font-weight: 400;
}

.schedule-recommendation-content { padding: 14px 16px; }

.schedule-check-alert {
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 12px;
    font-size: 12px;
    border: 1px solid transparent;
}
.schedule-check-alert:last-child { margin-bottom: 0; }

.schedule-tone-success { background: #eefaf3; border-color: rgba(28,200,138,.25); color: #12865d; }
.schedule-tone-warning { background: #fdf6ea; border-color: rgba(217,172,79,.35); color: #8a6a2f; }
.schedule-tone-danger  { background: #fff0f0; border-color: rgba(224,28,29,.2);   color: var(--sb-red-dark); }

.schedule-recommendation-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.schedule-recommendation-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border: 1px solid #ecd9bf;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    transition: all .2s ease;
}
.schedule-recommendation-item:hover {
    border-color: var(--sb-red);
    background: #fff8f7;
    transform: translateY(-1px);
}

.schedule-recommendation-time { font-weight: 700; color: #3a2a26; font-size: 13px; }
.schedule-recommendation-time i { color: var(--sb-red); }
.schedule-recommendation-meta { color: #a09a94; font-size: 11px; margin-top: 2px; }
.schedule-recommendation-use { white-space: nowrap; font-size: 11px; font-weight: 700; color: var(--sb-red); }
.schedule-loading { text-align: center; color: #a09a94; font-size: 12px; padding: 5px 0; }


/* =========================================================
   KARTU RINGKASAN (KANAN)
========================================================== */

.reservation-detail-card {
    position: sticky;
    top: 20px;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(124,16,19,.08);
    border: 1px solid rgba(217,172,79,.15);
    margin-bottom: 24px;
}

.detail-header {
    position: relative;
    overflow: hidden;
    padding: 20px;
    background:
        radial-gradient(circle at 15% 0%, rgba(217, 172, 79, .22) 0%, transparent 45%),
        linear-gradient(135deg, var(--sb-red) 0%, var(--sb-red-dark) 100%);
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-header-ornament {
    position: absolute;
    width: 150px; height: 150px;
    top: -95px; right: -30px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 172, 79, .2) 0%, transparent 70%);
    pointer-events: none;
}

.detail-store {
    font-size: 12.5px;
    opacity: .85;
    margin-bottom: 5px;
}

.detail-store i { margin-right: 5px; }

.detail-header h5 {
    margin: 0;
    font-weight: 700;
}

.detail-header-icon {
    font-size: 35px;
    opacity: .2;
    position: relative;
}

.selected-treatment-section {
    padding: 18px;
    border-bottom: 8px solid #fdfaf5;
}

.section-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 14px;
    font-weight: 700;
    color: #3a2a26;
}

.empty-treatment {
    text-align: center;
    padding: 20px 10px;
    background: #fdfaf5;
    border-radius: 12px;
}

.empty-icon {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: #fff0f0;
    color: var(--sb-red);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 23px;
}

.empty-treatment h6 {
    font-weight: 700;
    margin-bottom: 4px;
    color: #3a2a26;
}

.empty-treatment p {
    margin: 0;
    color: #a09a94;
    font-size: 12px;
}

.selected-item {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid rgba(217,172,79,.15);
}

.selected-item:last-child { border-bottom: none; }

.selected-item-image {
    width: 55px;
    height: 55px;
    min-width: 55px;
    border-radius: 10px;
    object-fit: cover;
    background: #fff0f0;
}

.selected-item-info {
    flex: 1;
    min-width: 0;
}

.selected-item-name {
    font-size: 13px;
    font-weight: 700;
    color: #3a2a26;
    margin-bottom: 4px;
}

.selected-item-meta {
    color: #a09a94;
    font-size: 11px;
}

.selected-item-price {
    color: var(--sb-red);
    font-weight: 700;
    font-size: 12px;
    margin-top: 3px;
}

.selected-item-qty {
    font-size: 11px;
    background: #fdf3e2;
    color: #a9781f;
    border-radius: 5px;
    padding: 2px 6px;
    margin-left: 4px;
}

.detail-section {
    padding: 18px;
    border-bottom: 1px solid rgba(217,172,79,.15);
}

.detail-section-title {
    font-weight: 700;
    font-size: 13px;
    color: #3a2a26;
    margin-bottom: 13px;
}
.detail-section-title i { color: var(--sb-gold); }

.detail-row {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 8px 0;
    font-size: 12px;
}

.detail-row span { color: #a09a94; }

.detail-row strong {
    color: #3a2a26;
    text-align: right;
}

.duration-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.3);
    border-radius: 12px;
}

.duration-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--sb-red), var(--sb-red-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.duration-box small {
    display: block;
    color: #8a6a2f;
    font-size: 11px;
}

.duration-box strong {
    display: block;
    color: #3a2a26;
    font-size: 14px;
}

.detail-total {
    padding: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
}

.detail-total span {
    font-size: 13px;
    color: #8a7d74;
}

.detail-total strong {
    color: var(--sb-red);
    font-size: 20px;
}

.detail-info {
    margin: 0 18px 18px;
    padding: 12px;
    background: #fdf6ea;
    border: 1px solid rgba(217,172,79,.3);
    border-radius: 10px;
    display: flex;
    gap: 8px;
    font-size: 11px;
    color: #8a6a2f;
}

.detail-info i {
    color: var(--sb-gold);
    margin-top: 2px;
}

.detail-footer {
    padding: 18px;
    background: #fdfaf5;
    border-top: 1px solid rgba(217,172,79,.15);
}

.btn-save-reservation {
    padding: 11px;
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


/* =========================================================
   RESPONSIVE
========================================================== */

@media (max-width: 991px) {

    .reservation-detail-card {
        position: static;
    }

}

@media (max-width: 767px) {

    .form-header-elite {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-header-elite .btn-outline-elite-onheader {
        width: 100%;
        text-align: center;
    }

}

@media (max-width: 991.98px) {
    .treatment-slide {
        flex: 0 0 calc((100% - 28px) / 3);
        width: calc((100% - 28px) / 3);
    }
}

@media (max-width: 767.98px) {
    .treatment-filter-select { width: 100%; min-width: 0; margin-top: 10px; }
    .treatment-slide {
        flex: 0 0 calc((100% - 14px) / 2);
        width: calc((100% - 14px) / 2);
    }
    .treatment-image { height: 95px; }
}

@media (max-width: 575.98px) {
    .treatment-slider-wrapper { padding: 0 36px; }
    .treatment-slide { flex: 0 0 100%; width: 100%; }
    .treatment-card { min-height: 200px; }
    .treatment-image { height: 90px; }
    .treatment-name { font-size: 13px; min-height: 32px; }
}

</style>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const JAM_BUKA = '08:00';

    const JAM_TUTUP = '17:00';

    const MAX_QTY = 10;


    const form =
        document.getElementById('formReservasi');

    const tanggal =
        document.getElementById('tanggal');

    const pegawai =
        document.getElementById('pegawai_id');

    const jamMulai =
        document.getElementById('jam_mulai');

    const jamSelesai =
        document.getElementById('jam_selesai');

    const checkboxes =
        document.querySelectorAll('.treatment-checkbox');


    /*
    |--------------------------------------------------------------------------
    | SLIDER & FILTER PERAWATAN
    |--------------------------------------------------------------------------
    */

    const TREATMENT_SLIDE_GAP = 14;

    const TREATMENT_IDEAL_CARD_WIDTH = 190;

    const TREATMENT_MAX_PER_PAGE = 4;


    const treatmentSlider =
        document.getElementById('treatmentSlider');

    const treatmentTrack =
        document.getElementById('treatmentSliderTrack');

    const treatmentPrev =
        document.getElementById('treatmentPrev');

    const treatmentNext =
        document.getElementById('treatmentNext');

    const filterKategoriPerawatan =
        document.getElementById('filterKategoriPerawatan');


    function getTreatmentPerPage() {

        const wrapperWidth =
            treatmentSlider
                ? treatmentSlider.clientWidth
                : window.innerWidth;

        let perPage =
            Math.round(
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


        const perPage =
            getTreatmentPerPage();

        const wrapperWidth =
            treatmentSlider.clientWidth;


        if (wrapperWidth <= 0) {

            return;

        }


        const slideWidth =
            (wrapperWidth - (TREATMENT_SLIDE_GAP * (perPage - 1))) /
            perPage;


        treatmentTrack
            .querySelectorAll('.treatment-slide')
            .forEach(function (slide) {

                slide.style.setProperty(
                    'flex',
                    '0 0 ' + slideWidth + 'px',
                    'important'
                );

                slide.style.setProperty(
                    'width',
                    slideWidth + 'px',
                    'important'
                );

                slide.style.setProperty(
                    'max-width',
                    slideWidth + 'px',
                    'important'
                );

            });

    }


    function updateTreatmentSliderButtons() {

        if (
            !treatmentSlider ||
            !treatmentTrack ||
            !treatmentPrev ||
            !treatmentNext
        ) {

            return;

        }


        const visibleSlides =
            treatmentTrack.querySelectorAll(
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


        treatmentPrev.disabled =
            treatmentSlider.scrollLeft <= 5;

        treatmentNext.disabled =
            treatmentSlider.scrollLeft >= maxScroll - 5;

    }


    function applyTreatmentFilter() {

        if (!treatmentTrack) {

            return;

        }


        const selectedCategory =
            filterKategoriPerawatan
                ? filterKategoriPerawatan.value
                : 'semua';


        const slides =
            treatmentTrack.querySelectorAll('.treatment-slide');


        let visibleCount = 0;


        slides.forEach(function (slide) {

            const category =
                slide.dataset.category || 'lainnya';


            const show =
                selectedCategory === 'semua' ||
                category === selectedCategory;


            slide.style.display =
                show ? '' : 'none';


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

            const canSlide =
                visibleCount > 2;

            treatmentPrev.style.display =
                canSlide ? 'flex' : 'none';

            treatmentNext.style.display =
                canSlide ? 'flex' : 'none';

        }

    }


    if (treatmentPrev && treatmentSlider) {

        treatmentPrev.addEventListener(
            'click',
            function () {

                treatmentSlider.scrollBy({
                    left: -treatmentSlider.clientWidth,
                    behavior: 'smooth'
                });

            }
        );

    }


    if (treatmentNext && treatmentSlider) {

        treatmentNext.addEventListener(
            'click',
            function () {

                treatmentSlider.scrollBy({
                    left: treatmentSlider.clientWidth,
                    behavior: 'smooth'
                });

            }
        );

    }


    if (treatmentSlider) {

        treatmentSlider.addEventListener(
            'scroll',
            updateTreatmentSliderButtons
        );

    }


    if (filterKategoriPerawatan) {

        filterKategoriPerawatan.addEventListener(
            'change',
            applyTreatmentFilter
        );

    }


    applyTreatmentSlideWidths();

    applyTreatmentFilter();


    let treatmentResizeTimeout;

    window.addEventListener(
        'resize',
        function () {

            clearTimeout(treatmentResizeTimeout);

            treatmentResizeTimeout =
                setTimeout(function () {

                    applyTreatmentSlideWidths();

                    updateTreatmentSliderButtons();

                }, 150);

        }
    );



    function formatRupiah(angka) {

        angka =
            parseInt(angka || 0);

        return 'Rp' +
            angka.toLocaleString('id-ID');

    }


    function formatTanggal(value) {

        if (!value) {
            return '-';
        }

        const date =
            new Date(value + 'T00:00:00');

        return date.toLocaleDateString(
            'id-ID',
            {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }
        );

    }


    function getQuantity(id) {

        const element =
            document.getElementById('qty_' + id);

        if (!element) {
            return 0;
        }

        let qty =
            parseInt(element.innerText) || 0;

        if (qty < 0) {
            qty = 0;
        }

        if (qty > MAX_QTY) {
            qty = MAX_QTY;
        }

        return qty;

    }


    function updateCheckboxValue(checkbox) {

        const id =
            checkbox.dataset.id;

        const qty =
            getQuantity(id);

        checkbox.value =
            qty > 0 ? qty : 1;

    }


    function updateQuantity(id, quantity) {

        let qty =
            parseInt(quantity) || 0;

        if (qty < 0) {
            qty = 0;
        }

        if (qty > MAX_QTY) {
            qty = MAX_QTY;
        }


        const qtyElement =
            document.getElementById('qty_' + id);

        if (qtyElement) {

            qtyElement.innerText =
                qty;

        }


        const checkbox =
            document.getElementById(
                'perawatan_' + id
            );

        if (checkbox) {

            checkbox.checked =
                qty > 0;

            checkbox.value =
                qty > 0 ? qty : 1;

        }


        updateDetail();

        jadwalBerubah();

    }


    function getSelectedData() {

        const selected = [];


        checkboxes.forEach(function (checkbox) {

            if (!checkbox.checked) {
                return;
            }


            const id =
                checkbox.dataset.id;

            const quantity =
                getQuantity(id);


            if (quantity <= 0) {
                return;
            }


            selected.push({

                id: id,

                name:
                    checkbox.dataset.name,

                duration:
                    parseInt(
                        checkbox.dataset.duration || 0
                    ),

                price:
                    parseInt(
                        checkbox.dataset.price || 0
                    ),

                quantity: quantity,

                description:
                    checkbox.dataset.description || ''

            });

        });


        return selected;

    }


    function calculateTotal() {

        const selected =
            getSelectedData();


        let totalDuration = 0;

        let totalPrice = 0;

        let totalQuantity = 0;


        selected.forEach(function (item) {

            totalDuration +=
                item.duration *
                item.quantity;

            totalPrice +=
                item.price *
                item.quantity;

            totalQuantity +=
                item.quantity;

        });


        return {

            selected:
                selected,

            totalDuration:
                totalDuration,

            totalPrice:
                totalPrice,

            totalQuantity:
                totalQuantity

        };

    }


    function escapeHtml(text) {

        const div =
            document.createElement('div');

        div.innerText =
            text;

        return div.innerHTML;

    }


    function updateSelectedTreatment() {

        const result =
            calculateTotal();


        const container =
            document.getElementById(
                'selectedTreatment'
            );


        document.getElementById(
            'jumlahPilihan'
        ).innerText =
            result.totalQuantity +
            ' Dipilih';


        document.getElementById(
            'detailJumlah'
        ).innerText =
            result.totalQuantity;


        if (result.selected.length === 0) {

            container.innerHTML = `

                <div class="empty-treatment">

                    <div class="empty-icon">

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

            const totalItem =
                item.price *
                item.quantity;


            html += `

                <div class="selected-item">

                    <div class="selected-item-image
                                d-flex
                                align-items-center
                                justify-content-center">

                        <i class="fas fa-spa text-maroon-elite"></i>

                    </div>


                    <div class="selected-item-info">

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


        container.innerHTML =
            html;

    }


    function hitungJamSelesai() {

        const result =
            calculateTotal();


        if (
            !jamMulai.value ||
            result.totalDuration <= 0
        ) {

            jamSelesai.value = '';

            return;

        }


        const parts =
            jamMulai.value.split(':');


        let hours =
            parseInt(parts[0]);

        let minutes =
            parseInt(parts[1]);


        minutes +=
            result.totalDuration;


        hours +=
            Math.floor(minutes / 60);


        minutes =
            minutes % 60;


        jamSelesai.value =
            String(hours).padStart(2, '0') +
            ':' +
            String(minutes).padStart(2, '0');

    }


    function updateScheduleDetail() {

        document.getElementById(
            'detailTanggal'
        ).innerText =
            formatTanggal(
                tanggal.value
            );


        document.getElementById(
            'detailPegawai'
        ).innerText =
            pegawai.value
                ? pegawai.options[
                    pegawai.selectedIndex
                ].text
                : '-';


        document.getElementById(
            'detailJamMulai'
        ).innerText =
            jamMulai.value ||
            '-';


        document.getElementById(
            'detailJamSelesai'
        ).innerText =
            jamSelesai.value ||
            '-';

    }


    function updateSummary() {

        const result =
            calculateTotal();


        document.getElementById(
            'detailDurasi'
        ).innerText =
            result.totalDuration +
            ' menit';


        document.getElementById(
            'detailHarga'
        ).innerText =
            formatRupiah(
                result.totalPrice
            );


        hitungJamSelesai();

        updateScheduleDetail();

    }


    function updateDetail() {

        updateSelectedTreatment();

        updateSummary();

    }


    checkboxes.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            function () {

                const id =
                    this.dataset.id;


                const qtyElement =
                    document.getElementById(
                        'qty_' + id
                    );


                if (this.checked) {

                    if (
                        !qtyElement ||
                        parseInt(
                            qtyElement.innerText
                        ) <= 0
                    ) {

                        updateQuantity(
                            id,
                            1
                        );

                    } else {

                        updateCheckboxValue(
                            this
                        );

                    }

                } else {

                    updateQuantity(
                        id,
                        0
                    );

                }


                updateDetail();

            }
        );

    });


    document.querySelectorAll('.btn-plus')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();


                    const id =
                        this.dataset.id;


                    const current =
                        getQuantity(id);


                    if (current >= MAX_QTY) {
                        return;
                    }


                    updateQuantity(
                        id,
                        current + 1
                    );

                }
            );

        });


    document.querySelectorAll('.btn-minus')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();


                    const id =
                        this.dataset.id;


                    const current =
                        getQuantity(id);


                    if (current <= 1) {

                        updateQuantity(
                            id,
                            0
                        );

                    } else {

                        updateQuantity(
                            id,
                            current - 1
                        );

                    }

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | CEK JADWAL & REKOMENDASI
    |--------------------------------------------------------------------------
    */

    let hasilCekJadwal = null;

    let sedangCekJadwal = false;

    let jadwalTimeout;


    function getSchedulePayload() {

        hitungJamSelesai();

        return {
            tanggal: tanggal.value,
            pegawai_id: pegawai.value,
            jam_mulai: jamMulai.value,
            jam_selesai: jamSelesai.value,

            /* reservasi ini dikecualikan agar tidak dianggap bentrok dengan dirinya sendiri */
            exclude_id: '{{ $data->id }}'
        };

    }


    function resetScheduleRecommendation() {

        hasilCekJadwal = null;

        const box =
            document.getElementById(
                'scheduleRecommendation'
            );

        const content =
            document.getElementById(
                'scheduleRecommendationContent'
            );

        if (box) {
            box.classList.add('d-none');
        }

        if (content) {
            content.innerHTML = '';
        }

    }


    /* dipanggil saat perawatan berubah (durasi berubah) */
    function jadwalBerubah() {

        clearTimeout(jadwalTimeout);

        jadwalTimeout =
            setTimeout(function () {

                resetScheduleRecommendation();

                cekJadwal();

            }, 400);

    }


    function showScheduleRecommendation(data) {

        const box =
            document.getElementById(
                'scheduleRecommendation'
            );

        const content =
            document.getElementById(
                'scheduleRecommendationContent'
            );

        if (!box || !content) {
            return;
        }

        box.classList.remove('d-none');

        let html = '';

        if (data.status === 'tersedia') {

            html += `
                <div class="schedule-check-alert schedule-tone-success">
                    <i class="fas fa-check-circle mr-1"></i>
                    <strong>Jadwal tersedia.</strong>
                    Terapis dapat melayani pada waktu yang dipilih.
                </div>
            `;

        } else if (data.status === 'tunggu') {

            html += `
                <div class="schedule-check-alert schedule-tone-warning">
                    <i class="fas fa-clock mr-1"></i>
                    <strong>Jadwal masih dapat diterima.</strong><br>
                    Anda akan menunggu <strong>${data.wait_minutes} menit</strong>
                    dikarenakan menunggu terapi dari reservasi sebelumnya.
                </div>
            `;

            if (data.reservasi_berbenturan) {

                html += `
                    <div class="text-muted-elite" style="font-size:11px;">
                        <i class="fas fa-info-circle mr-1"></i>
                        Reservasi sebelumnya berakhir sekitar
                        <strong>${data.reservasi_berbenturan.jam_selesai}</strong>.
                    </div>
                `;

            }

        } else if (data.status === 'bentrok') {

            html += `
                <div class="schedule-check-alert schedule-tone-danger">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Jadwal bentrok.</strong><br>
                    Jadwal yang dipilih bertabrakan lebih dari 15 menit
                    dengan reservasi lain pada terapis yang sama.
                    Silakan pilih salah satu jadwal rekomendasi berikut.
                </div>
            `;

            if (
                Array.isArray(data.rekomendasi) &&
                data.rekomendasi.length > 0
            ) {

                html += `
                    <div class="font-weight-bold title-elite" style="font-size:12px;">
                        <i class="fas fa-calendar-check mr-1 text-maroon-elite"></i>
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
                                    <i class="far fa-clock mr-1"></i>
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
                    <div class="text-muted-elite" style="font-size:11px;">
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

                item.addEventListener(
                    'click',
                    function () {

                        jamMulai.value =
                            this.dataset.jamMulai;

                        hitungJamSelesai();

                        updateScheduleDetail();

                        resetScheduleRecommendation();

                        cekJadwal();

                    }
                );

            });

    }


    async function cekJadwal() {

        const payload =
            getSchedulePayload();

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

        const box =
            document.getElementById(
                'scheduleRecommendation'
            );

        const content =
            document.getElementById(
                'scheduleRecommendationContent'
            );

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

            const query =
                new URLSearchParams(payload);

            const response =
                await fetch(
                    '{{ url('/reservasi/cek-jadwal') }}?' +
                    query.toString(),
                    {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

            if (!response.ok) {

                throw new Error(
                    'Gagal mengecek jadwal.'
                );

            }

            const data =
                await response.json();

            hasilCekJadwal = data;

            showScheduleRecommendation(data);

            return data;

        } catch (error) {

            console.error(error);

            hasilCekJadwal = null;

            content.innerHTML = `
                <div class="schedule-check-alert schedule-tone-danger">
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


    jamMulai.addEventListener(
        'change',
        function () {

            hitungJamSelesai();

            updateScheduleDetail();

            resetScheduleRecommendation();

            cekJadwal();

        }
    );


    tanggal.addEventListener(
        'change',
        function () {

            updateScheduleDetail();

            resetScheduleRecommendation();

            cekJadwal();

        }
    );


    pegawai.addEventListener(
        'change',
        function () {

            updateScheduleDetail();

            resetScheduleRecommendation();

            cekJadwal();

        }
    );


    form.addEventListener(
        'submit',
        async function (event) {

            const result =
                calculateTotal();


            if (!document.getElementById(
                'pelanggan_id'
            ).value) {

                event.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Pelanggan belum dipilih',

                    text:
                        'Silakan pilih pelanggan terlebih dahulu.',

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            if (
                result.selected.length === 0 ||
                result.totalQuantity <= 0
            ) {

                event.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Perawatan belum dipilih',

                    text:
                        'Silakan pilih minimal satu perawatan.',

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            if (!pegawai.value) {

                event.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Terapis belum dipilih',

                    text:
                        'Silakan pilih terapis terlebih dahulu.',

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            if (!jamMulai.value) {

                event.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Jam mulai belum dipilih',

                    text:
                        'Silakan pilih jam mulai reservasi.',

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            hitungJamSelesai();


            if (
                !jamSelesai.value ||
                jamSelesai.value > JAM_TUTUP
            ) {

                event.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Jadwal di luar jam operasional',

                    html:
                        'Jam operasional Azzahra Salon & Spa adalah ' +
                        '<strong>08:00 - 17:00</strong>.',

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            /* cek bentrok jadwal sebelum konfirmasi simpan */
            event.preventDefault();

            const scheduleResult =
                await cekJadwal();


            if (scheduleResult.status === 'bentrok') {

                Swal.fire({

                    icon: 'warning',

                    title: 'Jadwal Bentrok',

                    html:
                        'Jadwal yang dipilih bertabrakan lebih dari ' +
                        '<strong>15 menit</strong> dengan reservasi lain.<br>' +
                        'Silakan gunakan salah satu rekomendasi jadwal yang tersedia.',

                    confirmButtonColor:
                        '#e01c1d',

                    confirmButtonText:
                        'Lihat Rekomendasi'

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

                    confirmButtonColor:
                        '#e01c1d'

                });

                return;

            }


            checkboxes.forEach(
                function (checkbox) {

                    if (checkbox.checked) {

                        updateCheckboxValue(
                            checkbox
                        );

                    }

                }
            );


            event.preventDefault();


            Swal.fire({

                title: 'Simpan Perubahan?',

                html:
                    'Total <strong>' +
                    result.totalQuantity +
                    '</strong> perawatan dengan total durasi <strong>' +
                    result.totalDuration +
                    ' menit</strong>.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor:
                    '#e01c1d',

                cancelButtonColor:
                    '#858796',

                confirmButtonText:
                    '<i class="fas fa-save"></i> Ya, Simpan',

                cancelButtonText:
                    'Batal'

            }).then(function (response) {

                if (response.isConfirmed) {

                    form.submit();

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL VALUE
    |--------------------------------------------------------------------------
    */

    checkboxes.forEach(function (checkbox) {

        const id =
            checkbox.dataset.id;


        const qtyElement =
            document.getElementById(
                'qty_' + id
            );


        if (checkbox.checked) {

            let qty =
                parseInt(
                    qtyElement.innerText
                ) || 1;


            if (qty <= 0) {
                qty = 1;
            }


            if (qty > MAX_QTY) {
                qty = MAX_QTY;
            }


            qtyElement.innerText =
                qty;


            checkbox.value =
                qty;

        } else {

            qtyElement.innerText =
                '0';

            checkbox.value =
                '1';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | INITIAL DETAIL
    |--------------------------------------------------------------------------
    */

    updateDetail();

});

</script>

@endpush

@endsection