{{-- resources/views/pages/partials/auth-art.blade.php
     Panel kiri bertema maroon untuk halaman login & daftar.
     Menerima: $judul (teks), $judulEm (kata bercetak miring), $lead (kalimat pengantar) --}}
<aside class="au-art" aria-hidden="false">

    <div class="au-blob au-blob-1"></div>
    <div class="au-blob au-blob-2"></div>
    <div class="au-blob au-blob-3"></div>
    <span class="au-spark au-spark-1">✦</span>
    <span class="au-spark au-spark-2">✧</span>
    <span class="au-spark au-spark-3">✦</span>
    <i class="fas fa-spa au-flower" aria-hidden="true"></i>

    <a href="{{ url('/') }}" class="au-brand">
        <span class="au-brand-mark"><i class="fas fa-spa" aria-hidden="true"></i></span>
        <span>
            <strong>AZZAHRA</strong>
            <small>Salon &amp; Spa Muslimah</small>
        </span>
    </a>

    <div class="au-art-copy">
        <h2>{{ $judul }} <em>{{ $judulEm }}</em></h2>
        <p>{{ $lead }}</p>

        <ul class="au-perks">
            <li style="--i:0"><i class="far fa-calendar-check" aria-hidden="true"></i><span>Booking perawatan kapan saja</span></li>
            <li style="--i:1"><i class="fas fa-route" aria-hidden="true"></i><span>Lacak status reservasi secara langsung</span></li>
            <li style="--i:2"><i class="fas fa-shield-heart" aria-hidden="true"></i><span>Ruang privat khusus muslimah</span></li>
        </ul>
    </div>

    <p class="au-art-foot"><span id="auTagline">Beauty • Relaxation • Self Care</span></p>

</aside>