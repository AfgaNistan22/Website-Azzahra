@extends('pages.auth.guest')

@section('title', 'Konfirmasi Pembayaran | Azzahra')

{{--
    HALAMAN: Konfirmasi Pembayaran (pelanggan)

    Dikirim BookingController@pembayaran:
      $booking           Reservasi (sudah di-map(): kode_booking, total, terapis, perawatan)
      $metodePembayaran  koleksi MetodePembayaran::aktif()
      $promoTersedia     koleksi promo yang masih berlaku & cocok untuk booking ini
                         (diskon_hitung, perawatan, tanggal_selesai, khusus_member, ...),
                         urut dari potongan terbesar.
                         perawatan_id kosong = promo berlaku untuk semua perawatan.
                         khusus_member = true hanya muncul bila pelanggan adalah member.
      $totalAsli         total sebelum promo
    Form dikirim ke route booking.pembayaran.simpan (POST, multipart)
    Nama field: metode_pembayaran_id, promo_id, bukti_bayar, catatan  (tidak diubah)
--}}

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman pembayaran --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/pembayaran-modern.css') }}?v={{ file_exists(public_path('template/css/pembayaran-modern.css')) ? filemtime(public_path('template/css/pembayaran-modern.css')) : time() }}">
@endpush

@section('content')

@php
    $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
    $adaPromo = $promoTersedia->isNotEmpty();
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    PEMBAYARAN
                </span>

                <h1>
                    Konfirmasi
                    <em>Pembayaran.</em>
                </h1>

                <p>
                    Pilih metode pembayaran, lakukan transfer, lalu unggah
                    bukti pembayaran untuk booking <strong>{{ $booking->kode_booking }}</strong>.
                </p>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-wallet"></i></div>

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

        {{-- ===================== NOTIFIKASI ===================== --}}
        @if (session('error'))
            <div class="bkl-alert bkl-alert-error" role="alert">
                <span class="bkl-alert-icon"><i class="fas fa-exclamation"></i></span>
                <span>{{ session('error') }}</span>
                <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="bkl-alert bkl-alert-error" role="alert">
                <span class="bkl-alert-icon"><i class="fas fa-exclamation"></i></span>
                <div class="bkl-alert-list">
                    Periksa kembali data berikut:
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        {{-- LANGKAH --}}
        <ol class="pay-steps bkl-reveal" id="langkah" aria-label="Langkah pembayaran">
            <li class="pay-step is-current" data-step="metode"><span class="pay-step-no">1</span><span class="pay-step-label">Pilih metode</span></li>
            @if ($adaPromo)
                <li class="pay-step" data-step="promo"><span class="pay-step-no">2</span><span class="pay-step-label">Pilih promo</span></li>
            @endif
            <li class="pay-step" data-step="bukti"><span class="pay-step-no">{{ $adaPromo ? 3 : 2 }}</span><span class="pay-step-label">Unggah bukti</span></li>
        </ol>

        <form action="{{ route('booking.pembayaran.simpan', $booking->id) }}"
              method="POST"
              enctype="multipart/form-data"
              id="formPembayaran"
              novalidate>
            @csrf

            <div class="pay-grid">

                {{-- ================= KIRI ================= --}}
                <div class="pay-card bkl-reveal">

                    {{-- METODE --}}
                    <div class="pay-block">
                        <div class="pay-head">
                            <span class="pay-head-ico"><i class="fas fa-wallet" aria-hidden="true"></i></span>
                            <div>
                                <h2>Metode pembayaran</h2>
                                <p>Pilih rekening tujuan transfer Anda.</p>
                            </div>
                        </div>

                        @if ($metodePembayaran->isNotEmpty())
                            <div class="pay-options" role="radiogroup" aria-label="Metode pembayaran">
                                @foreach ($metodePembayaran as $m)
                                    <label class="pay-method">
                                        <input type="radio"
                                               name="metode_pembayaran_id"
                                               value="{{ $m->id }}"
                                               data-nama="{{ $m->metode }}"
                                               data-norek="{{ $m->norek }}"
                                               data-an="{{ $m->an }}"
                                               data-tipe="{{ $m->tipe ?? 'Transfer' }}"
                                               data-qris="{{ ! empty($m->qris) ? asset('storage/' . ltrim(preg_replace('#^(public|storage)/#', '', $m->qris), '/')) : '' }}"
                                               {{ (int) old('metode_pembayaran_id') === (int) $m->id ? 'checked' : '' }}>
                                        <div class="pay-method-card">
                                            <div class="pay-method-icon">
                                                @if ($m->logo)
                                                    <img src="{{ asset('storage/' . $m->logo) }}" alt="" onerror="this.replaceWith(Object.assign(document.createElement('i'),{className:'fas fa-university'}))">
                                                @else
                                                    <i class="fas {{ ($m->tipe ?? '') === 'QRIS' ? 'fa-qrcode' : 'fa-university' }}" aria-hidden="true"></i>
                                                @endif
                                            </div>
                                            <div class="pay-method-body">
                                                <div class="pay-method-name">{{ $m->metode }}</div>
                                                @if (($m->tipe ?? '') === 'QRIS')
                                                    <div class="pay-method-meta">Scan kode QR dengan e-wallet atau m-banking</div>
                                                @elseif ($m->norek || $m->an)
                                                    <div class="pay-method-meta">
                                                        {{ $m->norek }}@if ($m->norek && $m->an) · @endif{{ $m->an ? 'a.n. ' . $m->an : '' }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="pay-method-tick"><i class="fas fa-check" aria-hidden="true"></i></span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Detail transfer muncul setelah memilih metode --}}
                            <div class="pay-transfer" id="detailTransfer" hidden aria-live="polite">
                                <div class="pay-transfer-top">
                                    <i class="fas fa-university" id="tfIkon" aria-hidden="true"></i>
                                    <span id="tfJudul">Detail transfer</span>
                                </div>

                                {{-- Khusus QRIS --}}
                                <div class="tf-qris" hidden>
                                    <div class="pay-qr">
                                        <div class="pay-qr-box">
                                            <img id="qrGambar" src="" alt="Kode QRIS pembayaran">
                                            <div class="pay-qr-gagal" id="qrGagal" hidden>
                                                <i class="fas fa-image" aria-hidden="true"></i>
                                                Gambar QR belum tersedia.<br>Silakan hubungi admin salon.
                                            </div>
                                        </div>
                                        <div class="pay-qr-info">
                                            <strong>Scan untuk membayar</strong>
                                            <p>Buka e-wallet atau m-banking, pilih <b>Scan QRIS</b>, lalu arahkan ke kode ini. Masukkan nominal <b>sama persis</b> dengan jumlah di bawah.</p>
                                            <a href="#" id="qrUnduh" class="pay-copy" download="qris-azzahra.png" target="_blank" rel="noopener">
                                                <i class="fas fa-download" aria-hidden="true"></i><span>Unduh QR</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Khusus transfer bank / e-wallet --}}
                                <div class="pay-transfer-row tf-rek">
                                    <div><small>Bank / e-wallet</small><strong id="tfNama">-</strong></div>
                                </div>
                                <div class="pay-transfer-row tf-rek">
                                    <div><small>Nomor rekening</small><strong id="tfNorek">-</strong></div>
                                    <button type="button" class="pay-copy" data-salin="tfNorek"><i class="far fa-copy" aria-hidden="true"></i><span>Salin</span></button>
                                </div>

                                <div class="pay-transfer-row">
                                    <div><small id="tfAnLabel">Atas nama</small><strong id="tfAn">-</strong></div>
                                </div>
                                <div class="pay-transfer-row">
                                    <div><small>Jumlah pembayaran</small><strong class="is-big" id="tfJumlah">{{ $rp($totalAsli) }}</strong></div>
                                    <button type="button" class="pay-copy" data-salin="tfJumlah" data-angka="1"><i class="far fa-copy" aria-hidden="true"></i><span>Salin</span></button>
                                </div>
                            </div>
                        @else
                            <div class="pay-info">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                <span>Belum ada metode pembayaran yang aktif. Silakan hubungi admin salon.</span>
                            </div>
                        @endif

                        <p class="pay-error {{ $errors->has('metode_pembayaran_id') ? 'is-show' : '' }}" id="errMetode" role="alert">
                            {{ $errors->first('metode_pembayaran_id') ?: 'Pilih metode pembayaran terlebih dahulu.' }}
                        </p>
                    </div>

                    {{-- PROMO --}}
                    @if ($adaPromo)
                        <div class="pay-block">
                            <div class="pay-head">
                                <span class="pay-head-ico"><i class="fas fa-tags" aria-hidden="true"></i></span>
                                <div>
                                    <h2>Promo</h2>
                                    <p>Gunakan promo yang tersedia untuk booking ini.</p>
                                </div>
                                <span class="pay-opt">Opsional</span>
                            </div>

                            <div class="pay-options is-single" role="radiogroup" aria-label="Promo">
                                <label class="pay-method">
                                    <input type="radio" name="promo_id" value="" data-diskon="0"
                                           {{ old('promo_id') ? '' : 'checked' }}>
                                    <div class="pay-method-card">
                                        <div class="pay-method-icon"><i class="fas fa-ban" aria-hidden="true"></i></div>
                                        <div class="pay-method-body">
                                            <div class="pay-method-name">Tanpa promo</div>
                                            <div class="pay-method-meta">Bayar sesuai harga normal.</div>
                                        </div>
                                        <span class="pay-method-tick"><i class="fas fa-check" aria-hidden="true"></i></span>
                                    </div>
                                </label>

                                @foreach ($promoTersedia as $promo)
                                    @php
                                        // Sisa hari sampai promo berakhir (0 = berakhir hari ini)
                                        $sisaHari = (int) today()->diffInDays($promo->tanggal_selesai, false);

                                        // Promo pertama = potongan terbesar (daftar sudah diurutkan di controller)
                                        $palingHemat = $loop->first && $promoTersedia->count() > 1;
                                    @endphp

                                    <label class="pay-method">
                                        <input type="radio"
                                               name="promo_id"
                                               value="{{ $promo->id }}"
                                               data-diskon="{{ $promo->diskon_hitung }}"
                                               data-perawatan="{{ $promo->perawatan_id }}"
                                               data-nama="{{ $promo->nama }}"
                                               data-nama-perawatan="{{ $promo->berlaku_semua ? '' : $promo->nama_perawatan }}"
                                               {{ (int) old('promo_id') === (int) $promo->id ? 'checked' : '' }}>
                                        <div class="pay-method-card">
                                            <div class="pay-method-icon">
                                                <i class="fas {{ $promo->khusus_member ? 'fa-crown' : ($promo->berlaku_semua ? 'fa-globe' : 'fa-percent') }}" aria-hidden="true"></i>
                                            </div>
                                            <div class="pay-method-body">
                                                <div class="pay-method-name">
                                                    {{ $promo->nama }}
                                                    @if ($palingHemat)
                                                        <span style="margin-left:6px;font-size:.68rem;font-weight:700;color:var(--maroon);">· Hemat terbanyak</span>
                                                    @endif
                                                </div>
                                                <div class="pay-method-meta">
                                                    {{ $promo->jenis === 'Persentase' ? $promo->nilai . '%' : $rp($promo->nilai) }}
                                                    @if ($promo->berlaku_semua)
                                                        · semua perawatan
                                                    @else
                                                        · khusus {{ $promo->nama_perawatan }}
                                                    @endif
                                                    @if($promo->khusus_member) · khusus member @endif
                                                    · s/d {{ $promo->tanggal_selesai->translatedFormat('d M Y') }}
                                                    @if ($sisaHari <= 0)
                                                        · berakhir hari ini
                                                    @elseif ($sisaHari <= 3)
                                                        · berakhir {{ $sisaHari }} hari lagi
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="pay-save">&minus;{{ $rp($promo->diskon_hitung) }}</span>
                                            <span class="pay-method-tick"><i class="fas fa-check" aria-hidden="true"></i></span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('promo_id')
                                <p class="pay-error is-show">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    {{-- BUKTI --}}
                    <div class="pay-block">
                        <div class="pay-head">
                            <span class="pay-head-ico"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                            <div>
                                <h2>Bukti pembayaran</h2>
                                <p>Unggah foto atau tangkapan layar bukti transfer.</p>
                            </div>
                        </div>

                        <label class="pay-drop" id="dropZone" for="bukti_bayar">
                            <input type="file" name="bukti_bayar" id="bukti_bayar" accept=".jpg,.jpeg,.png,.pdf,image/*,application/pdf">
                            <div class="pay-drop-ico"><i class="fas fa-cloud-upload-alt" aria-hidden="true"></i></div>
                            <div class="pay-drop-title">Seret file ke sini atau <span>pilih dari perangkat</span></div>
                            <div class="pay-drop-hint">Format JPG, PNG, atau PDF. Maksimal 2 MB.</div>
                        </label>

                        <div class="pay-file" id="fileCard">
                            <div class="pay-file-thumb" id="fileThumb"><i class="far fa-file-alt" aria-hidden="true"></i></div>
                            <div class="pay-file-info">
                                <div class="pay-file-name" id="fileNama">-</div>
                                <div class="pay-file-size" id="fileUkuran">-</div>
                                <div class="pay-file-ok"><i class="fas fa-check-circle" aria-hidden="true"></i> Siap dikirim</div>
                            </div>
                            <button type="button" class="pay-file-del" id="fileHapus" aria-label="Hapus file">
                                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                            </button>
                        </div>

                        <p class="pay-error {{ $errors->has('bukti_bayar') ? 'is-show' : '' }}" id="errBukti" role="alert">
                            {{ $errors->first('bukti_bayar') ?: 'Unggah bukti pembayaran terlebih dahulu.' }}
                        </p>
                    </div>

                    {{-- CATATAN --}}
                    <div class="pay-block">
                        <div class="pay-head">
                            <span class="pay-head-ico"><i class="fas fa-sticky-note" aria-hidden="true"></i></span>
                            <div>
                                <h2>Catatan</h2>
                                <p>Tambahkan informasi bila diperlukan.</p>
                            </div>
                            <span class="pay-opt">Opsional</span>
                        </div>

                        <textarea name="catatan"
                                  id="catatan"
                                  rows="3"
                                  maxlength="255"
                                  class="pay-note"
                                  placeholder="Contoh: transfer atas nama berbeda dari akun.">{{ old('catatan') }}</textarea>
                        <p class="pay-count"><span id="hitungCatatan">0</span>/255</p>

                        @error('catatan')
                            <p class="pay-error is-show">{{ $message }}</p>
                        @enderror
                    </div>

                </div>


                {{-- ================= KANAN: RINGKASAN ================= --}}
                <div class="pay-card pay-aside">

                    <div class="pay-aside-head">
                        <small>Ringkasan booking</small>
                        <h2>{{ $booking->kode_booking }}</h2>
                    </div>

                    <div class="pay-aside-block">
                        <div class="pay-sum-row">
                            <span>Jadwal</span>
                            <strong>
                                {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}
                                @if (! empty($booking->jam_mulai)) · {{ substr($booking->jam_mulai, 0, 5) }} @endif
                            </strong>
                        </div>
                        <div class="pay-sum-row"><span>Terapis</span><strong>{{ optional($booking->terapis)->nama ?? '-' }}</strong></div>
                    </div>

                    <div class="pay-aside-block">
                        <h3 class="pay-aside-title">Perawatan</h3>

                        @foreach ($booking->perawatan as $p)
                            @php
                                $jumlahItem = (int) data_get($p, 'pivot.jumlah', 1);
                                $hargaItem  = (int) data_get($p, 'pivot.harga', $p->harga);
                            @endphp
                            <div class="pay-item" data-perawatan="{{ $p->id }}" data-subtotal="{{ $hargaItem * $jumlahItem }}">
                                <div>
                                    <div class="pay-item-name">{{ $p->nama }} × {{ $jumlahItem }}</div>
                                    <div class="pay-item-meta">{{ $rp($hargaItem) }} per perawatan</div>
                                    <div class="pay-item-promo"></div>
                                </div>
                                <div style="text-align:right;">
                                    <div class="pay-item-asli"></div>
                                    <strong class="pay-item-total">{{ $rp($hargaItem * $jumlahItem) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pay-aside-block" style="padding-top:14px;padding-bottom:14px;">
                        <div class="pay-sum-row"><span>Subtotal</span><strong>{{ $rp($totalAsli) }}</strong></div>
                        <div class="pay-sum-row" id="barisDiskon" style="display:none;">
                            <span id="labelDiskon">Diskon promo</span>
                            <strong style="color:var(--maroon);" id="nilaiDiskon">-Rp0</strong>
                        </div>
                    </div>

                    <div class="pay-total">
                        <span>Total pembayaran</span>
                        <strong id="totalAkhir" data-total="{{ $totalAsli }}">{{ $rp($totalAsli) }}</strong>
                    </div>

                    <div class="pay-actions">
                        <ul class="pay-checklist" id="daftarSiap">
                            <li data-cek="metode"><i class="fas fa-check" aria-hidden="true"></i> Metode pembayaran dipilih</li>
                            <li data-cek="bukti"><i class="fas fa-check" aria-hidden="true"></i> Bukti pembayaran diunggah</li>
                        </ul>

                        <button type="submit" class="bkl-btn bkl-btn-maroon pay-submit pay-submit-desktop">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Kirim Bukti Pembayaran
                        </button>
                        <a href="{{ route('booking.daftar') }}" class="bkl-btn bkl-btn-line">Kembali</a>

                        <div class="pay-secure"><i class="fas fa-lock" aria-hidden="true"></i> Data Anda aman dan hanya dilihat admin salon</div>
                    </div>

                </div>

            </div>

            {{-- Bilah bawah untuk ponsel --}}
            <div class="pay-mobilebar">
                <div><small>Total pembayaran</small><strong id="totalAkhirMobile">{{ $rp($totalAsli) }}</strong></div>
                <button type="submit" class="bkl-btn bkl-btn-maroon pay-submit">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i> Kirim
                </button>
            </div>

        </form>

    </div>
</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var form      = document.getElementById('formPembayaran');
    var tombol    = document.querySelectorAll('.pay-submit');
    var MAKS      = 2 * 1024 * 1024;
    var TIPE      = ['image/jpeg', 'image/png', 'application/pdf'];

    function rupiah(n) {
        return 'Rp' + (parseInt(n) || 0).toLocaleString('id-ID');
    }

    /* ---------- Langkah & daftar kesiapan ---------- */
    var langkah = document.getElementById('langkah');

    function setStep(nama, selesai) {
        var el = langkah.querySelector('[data-step="' + nama + '"]');
        if (el) el.classList.toggle('is-done', selesai);
    }

    function setCek(nama, selesai) {
        var el = document.querySelector('#daftarSiap [data-cek="' + nama + '"]');
        if (el) el.classList.toggle('is-done', selesai);
    }

    function perbaruiLangkah() {
        var metodeOk = !!document.querySelector('input[name="metode_pembayaran_id"]:checked');
        var buktiOk  = !!(input.files && input.files[0]);

        setStep('metode', metodeOk);
        setStep('promo', metodeOk);                      // promo opsional: ikut selesai setelah metode dipilih
        setStep('bukti', buktiOk);
        setCek('metode', metodeOk);
        setCek('bukti', buktiOk);

        langkah.querySelectorAll('.pay-step').forEach(function (s) { s.classList.remove('is-current'); });
        var berikut = langkah.querySelector('.pay-step:not(.is-done)');
        if (berikut) berikut.classList.add('is-current');
    }

    /* ---------- Metode: detail transfer ---------- */
    var detail = document.getElementById('detailTransfer');
    var errMet = document.getElementById('errMetode');

    function tampilkanMetode() {
        var dipilih = document.querySelector('input[name="metode_pembayaran_id"]:checked');
        if (!detail) return;

        if (!dipilih) { detail.hidden = true; return; }

        var qris = (dipilih.dataset.tipe || '') === 'QRIS';

        detail.querySelectorAll('.tf-rek').forEach(function (el) { el.hidden = qris; });
        detail.querySelector('.tf-qris').hidden = !qris;

        document.getElementById('tfJudul').textContent = qris ? 'Bayar dengan QRIS' : 'Detail transfer';
        document.getElementById('tfIkon').className    = qris ? 'fas fa-qrcode' : 'fas fa-university';
        document.getElementById('tfAnLabel').textContent = qris ? 'Nama merchant' : 'Atas nama';

        document.getElementById('tfNama').textContent  = dipilih.dataset.nama || '-';
        document.getElementById('tfNorek').textContent = dipilih.dataset.norek || '-';
        document.getElementById('tfAn').textContent    = dipilih.dataset.an || '-';

        if (qris) {
            var gambar = dipilih.dataset.qris || '';
            var img    = document.getElementById('qrGambar');
            var unduh  = document.getElementById('qrUnduh');

            var gagal = document.getElementById('qrGagal');

            function qrTersedia(ok) {
                img.style.display   = ok ? '' : 'none';
                gagal.hidden        = ok;
                unduh.style.display = ok ? '' : 'none';
            }

            img.onload  = function () { qrTersedia(true); };
            img.onerror = function () { qrTersedia(false); };

            unduh.href = gambar || '#';
            if (gambar) {
                qrTersedia(true);
                img.src = gambar;
            } else {
                qrTersedia(false);
            }
        }

        detail.hidden = false;
        errMet.classList.remove('is-show');
    }

    document.querySelectorAll('input[name="metode_pembayaran_id"]').forEach(function (r) {
        r.addEventListener('change', function () { tampilkanMetode(); perbaruiLangkah(); });
    });

    /* ---------- Salin ke papan klip ---------- */
    document.querySelectorAll('.pay-copy').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sumber = document.getElementById(btn.dataset.salin);
            var teks   = sumber.textContent.trim();
            if (btn.dataset.angka) teks = teks.replace(/\D/g, '');

            var selesai = function () {
                var label = btn.querySelector('span');
                btn.classList.add('is-ok');
                label.textContent = 'Tersalin';
                setTimeout(function () { btn.classList.remove('is-ok'); label.textContent = 'Salin'; }, 1600);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(teks).then(selesai, function () {});
            } else {
                var t = document.createElement('textarea');
                t.value = teks; document.body.appendChild(t); t.select();
                try { document.execCommand('copy'); selesai(); } catch (e) {}
                document.body.removeChild(t);
            }
        });
    });

    /* ---------- Unggah bukti: pilih, seret, validasi, hapus ---------- */
    var input   = document.getElementById('bukti_bayar');
    var zona    = document.getElementById('dropZone');
    var kartu   = document.getElementById('fileCard');
    var thumb   = document.getElementById('fileThumb');
    var errBkt  = document.getElementById('errBukti');
    var PESAN_BUKTI = errBkt.textContent.trim();

    function ukuran(b) {
        return b >= 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB';
    }

    function galat(teks) {
        errBkt.textContent = teks || PESAN_BUKTI;
        errBkt.classList.toggle('is-show', !!teks);
    }

    function kosongkan() {
        input.value = '';
        kartu.classList.remove('is-show');
        zona.hidden = false;
        thumb.innerHTML = '<i class="far fa-file-alt" aria-hidden="true"></i>';
        perbaruiLangkah();
    }

    function terapkan(file) {
        if (!file) { kosongkan(); return; }

        if (TIPE.indexOf(file.type) === -1) {
            kosongkan(); galat('Format file harus JPG, PNG, atau PDF.'); return;
        }
        if (file.size > MAKS) {
            kosongkan(); galat('Ukuran file melebihi 2 MB. Pilih file yang lebih kecil.'); return;
        }

        galat('');
        document.getElementById('fileNama').textContent   = file.name;
        document.getElementById('fileUkuran').textContent = ukuran(file.size);

        if (file.type.indexOf('image/') === 0) {
            var reader = new FileReader();
            reader.onload = function (e) {
                thumb.innerHTML = '';
                var img = document.createElement('img');
                img.src = e.target.result; img.alt = 'Pratinjau bukti pembayaran';
                thumb.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            thumb.innerHTML = '<i class="far fa-file-pdf" aria-hidden="true"></i>';
        }

        zona.hidden = true;
        kartu.classList.add('is-show');
        perbaruiLangkah();
    }

    input.addEventListener('change', function () { terapkan(this.files && this.files[0]); });
    document.getElementById('fileHapus').addEventListener('click', function () { galat(''); kosongkan(); });

    ['dragenter', 'dragover'].forEach(function (n) {
        zona.addEventListener(n, function (e) { e.preventDefault(); zona.classList.add('is-over'); });
    });
    ['dragleave', 'drop'].forEach(function (n) {
        zona.addEventListener(n, function (e) { e.preventDefault(); zona.classList.remove('is-over'); });
    });
    zona.addEventListener('drop', function (e) {
        var file = e.dataTransfer && e.dataTransfer.files[0];
        if (!file) return;
        try {
            var dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
        } catch (err) { /* browser lama: gunakan tombol pilih file */ }
        terapkan(file);
    });

    /* ---------- Catatan ---------- */
    var catatan = document.getElementById('catatan');
    var hitung  = document.getElementById('hitungCatatan');
    catatan.addEventListener('input', function () { hitung.textContent = catatan.value.length; });
    hitung.textContent = catatan.value.length;

    /* ---------- Total & promo (angka final tetap dihitung ulang di server) ---------- */
    var totalEl   = document.getElementById('totalAkhir');
    var totalMob  = document.getElementById('totalAkhirMobile');
    var barisEl   = document.getElementById('barisDiskon');
    var diskonEl  = document.getElementById('nilaiDiskon');
    var tfJumlah  = document.getElementById('tfJumlah');
    var totalAsli = parseInt(totalEl.dataset.total || 0);

    function perbaruiTotal() {

        var dipilih = document.querySelector('input[name="promo_id"]:checked');
        var diskon  = dipilih ? parseInt(dipilih.dataset.diskon || 0) : 0;

        // Kembalikan semua baris perawatan ke harga normal
        document.querySelectorAll('.pay-item').forEach(function (item) {
            item.querySelector('.pay-item-promo').style.display = 'none';
            item.querySelector('.pay-item-asli').style.display  = 'none';
            item.querySelector('.pay-item-total').textContent   = rupiah(item.dataset.subtotal);
        });

        var label = 'Diskon promo';

        if (diskon > 0) {

            var idPerawatan = dipilih.dataset.perawatan;

            if (idPerawatan) {

                // Promo khusus: potongan dipasang di baris perawatan yang bersangkutan
                var baris = document.querySelector('.pay-item[data-perawatan="' + idPerawatan + '"]');

                if (baris) {
                    var subtotal = parseInt(baris.dataset.subtotal || 0);

                    baris.querySelector('.pay-item-promo').textContent   = 'Promo ' + dipilih.dataset.nama + ': -' + rupiah(diskon);
                    baris.querySelector('.pay-item-promo').style.display = 'block';
                    baris.querySelector('.pay-item-asli').textContent    = rupiah(subtotal);
                    baris.querySelector('.pay-item-asli').style.display  = 'block';
                    baris.querySelector('.pay-item-total').textContent   = rupiah(Math.max(subtotal - diskon, 0));
                }

                label = 'Diskon ' + (dipilih.dataset.namaPerawatan || 'perawatan');

            } else {

                // Promo semua perawatan: potongan berlaku untuk total keseluruhan,
                // jadi ditampilkan di baris ringkasan (bukan di satu perawatan saja)
                label = 'Diskon ' + dipilih.dataset.nama + ' (semua perawatan)';

            }
        }

        var akhir = rupiah(Math.max(totalAsli - diskon, 0));

        document.getElementById('labelDiskon').textContent = label;
        barisEl.style.display = diskon > 0 ? 'flex' : 'none';
        diskonEl.textContent  = '-' + rupiah(diskon);
        totalEl.textContent   = akhir;
        if (totalMob) totalMob.textContent = akhir;
        if (tfJumlah) tfJumlah.textContent = akhir;
    }

    document.querySelectorAll('input[name="promo_id"]').forEach(function (radio) {
        radio.addEventListener('change', perbaruiTotal);
    });

    /* ---------- Kirim: validasi ringan, cegah klik ganda ---------- */
    form.addEventListener('submit', function (e) {

        var adaMetode = document.querySelectorAll('input[name="metode_pembayaran_id"]').length > 0;
        var metodeOk  = !!document.querySelector('input[name="metode_pembayaran_id"]:checked');
        var buktiOk   = !!(input.files && input.files[0]);

        if (adaMetode && !metodeOk) {
            e.preventDefault();
            errMet.classList.add('is-show');
            errMet.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (!buktiOk) {
            e.preventDefault();
            galat('');                                     // tampilkan pesan bawaan
            errBkt.classList.add('is-show');
            errBkt.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        tombol.forEach(function (b) {
            b.disabled = true;
            b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        });
    });

    tampilkanMetode();
    perbaruiTotal();
    perbaruiLangkah();
});
</script>

@endsection