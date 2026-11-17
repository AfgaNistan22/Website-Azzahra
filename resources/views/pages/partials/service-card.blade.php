{{-- resources/views/pages/partials/service-card.blade.php
     Kartu layanan untuk halaman cari. Menerima: $service --}}
@php
    $fotoLayanan = !empty($service->foto)
        ? asset('storage/' . $service->foto)
        : asset('template/img/service-default.png');
@endphp

<article class="sr-card"
         data-nama="{{ \Illuminate\Support\Str::lower($service->nama) }}"
         data-cari="{{ \Illuminate\Support\Str::lower($service->nama . ' ' . optional($service->kategori)->nama . ' ' . $service->deskripsi) }}"
         data-harga="{{ (int) $service->harga }}"
         data-durasi="{{ (int) $service->durasi }}">

    <a href="{{ route('layanan.detail', $service->id) }}" class="sr-card-img" tabindex="-1" aria-hidden="true">
        <img src="{{ $fotoLayanan }}"
             alt=""
             loading="lazy"
             onerror="this.onerror=null;this.src='{{ asset('template/img/service-default.png') }}';">

        @if($service->kategori)
            <span class="sr-card-chip"><i class="fa-solid fa-spa"></i> {{ $service->kategori->nama }}</span>
        @endif
    </a>

    <div class="sr-card-body">
        <h3>{{ $service->nama }}</h3>

        <p class="sr-card-desc">
            {{ $service->deskripsi ?: 'Nikmati pengalaman perawatan dengan suasana nyaman dan pelayanan profesional dari Azzahra.' }}
        </p>

        <div class="sr-card-meta">
            <span class="sr-card-time"><i class="fa-regular fa-clock"></i> {{ $service->durasi ?: '-' }}{{ $service->durasi ? ' menit' : '' }}</span>

            <span class="sr-card-price"><small>Rp</small>{{ number_format($service->harga, 0, ',', '.') }}</span>
        </div>

        <a href="{{ route('layanan.detail', $service->id) }}" class="bkl-btn bkl-btn-maroon sr-card-btn">
            Lihat Layanan
            <i class="fa-solid fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
        </a>
    </div>
</article>