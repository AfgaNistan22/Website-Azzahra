<?php
/**
 * Script Generator Dokumen Spesifikasi Teknis & Analisis Algoritma Penjadwalan
 * Sistem Reservasi Azzahra Salon dan Spa Muslimah
 * 
 * Menggunakan barryvdh/laravel-dompdf / Dompdf engine murni PHP
 */

require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi opsi Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);

// ==========================================
// 1. DEFINISI SVG DIAGRAM 1: USE CASE DIAGRAM
// ==========================================
$svgUseCase = '<svg xmlns="http://www.w3.org/2000/svg" width="680" height="460" viewBox="0 0 680 460">
  <rect width="680" height="460" fill="#ffffff" stroke="#cbd5e1" stroke-width="1" rx="8"/>
  
  <!-- System Boundary -->
  <rect x="150" y="20" width="380" height="420" rx="10" fill="#fcfbfb" stroke="#64172d" stroke-width="1.8" stroke-dasharray="6,4"/>
  <rect x="150" y="20" width="380" height="26" fill="#64172d" rx="8"/>
  <text x="340" y="38" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="11" font-weight="bold" text-anchor="middle">Sistem Informasi Reservasi Azzahra Salon &amp; Spa Muslimah</text>

  <!-- ACTOR 1: Pelanggan (Member) -->
  <circle cx="65" cy="110" r="14" fill="#fdf2f4" stroke="#64172d" stroke-width="2"/>
  <line x1="65" y1="124" x2="65" y2="155" stroke="#64172d" stroke-width="2"/>
  <line x1="42" y1="135" x2="88" y2="135" stroke="#64172d" stroke-width="2"/>
  <line x1="65" y1="155" x2="48" y2="190" stroke="#64172d" stroke-width="2"/>
  <line x1="65" y1="155" x2="82" y2="190" stroke="#64172d" stroke-width="2"/>
  <text x="65" y="208" fill="#64172d" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Pelanggan</text>
  <text x="65" y="220" fill="#64748b" font-family="Helvetica, Arial, sans-serif" font-size="9" text-anchor="middle">(Member Login)</text>

  <!-- ACTOR 2: Tamu (Guest) -->
  <circle cx="65" cy="300" r="14" fill="#f8fafc" stroke="#475569" stroke-width="2"/>
  <line x1="65" y1="314" x2="65" y2="345" stroke="#475569" stroke-width="2"/>
  <line x1="42" y1="325" x2="88" y2="325" stroke="#475569" stroke-width="2"/>
  <line x1="65" y1="345" x2="48" y2="380" stroke="#475569" stroke-width="2"/>
  <line x1="65" y1="345" x2="82" y2="380" stroke="#475569" stroke-width="2"/>
  <text x="65" y="398" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Tamu / Guest</text>
  <text x="65" y="410" fill="#64748b" font-family="Helvetica, Arial, sans-serif" font-size="9" text-anchor="middle">(Tanpa Akun)</text>

  <!-- ACTOR 3: Admin -->
  <circle cx="615" cy="200" r="14" fill="#eff6ff" stroke="#1e40af" stroke-width="2"/>
  <line x1="615" y1="214" x2="615" y2="245" stroke="#1e40af" stroke-width="2"/>
  <line x1="592" y1="225" x2="638" y2="225" stroke="#1e40af" stroke-width="2"/>
  <line x1="615" y1="245" x2="598" y2="280" stroke="#1e40af" stroke-width="2"/>
  <line x1="615" y1="245" x2="632" y2="280" stroke="#1e40af" stroke-width="2"/>
  <text x="615" y="298" fill="#1e40af" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Admin Salon</text>

  <!-- USE CASES -->
  <!-- UC1: Katalog Layanan -->
  <ellipse cx="240" cy="75" rx="65" ry="17" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="240" y="78" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Lihat Katalog &amp; Terapis</text>

  <!-- UC2: Booking Multi-Layanan -->
  <ellipse cx="240" cy="130" rx="72" ry="19" fill="#fdf2f4" stroke="#64172d" stroke-width="1.8"/>
  <text x="240" y="133" fill="#64172d" font-family="Helvetica, Arial, sans-serif" font-size="10" font-weight="bold" text-anchor="middle">Booking Multi-Layanan</text>

  <!-- UC3: Deteksi Interval Bentrok -->
  <ellipse cx="435" cy="130" rx="72" ry="19" fill="#fef2f2" stroke="#b91c1c" stroke-width="1.6"/>
  <text x="435" y="133" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="9.5" font-weight="bold" text-anchor="middle">Deteksi Interval Bentrok</text>

  <!-- UC4: Rekomendasi Slot Greedy -->
  <ellipse cx="435" cy="205" rx="75" ry="20" fill="#fffbeb" stroke="#b45309" stroke-width="1.6"/>
  <text x="435" y="209" fill="#92400e" font-family="Helvetica, Arial, sans-serif" font-size="9.5" font-weight="bold" text-anchor="middle">Rekomendasi Slot Terdekat</text>

  <!-- UC5: Unggah Bukti Bayar -->
  <ellipse cx="240" cy="245" rx="65" ry="17" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="240" y="248" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Unggah Bukti Bayar</text>

  <!-- UC6: Lacak Booking (Guest) -->
  <ellipse cx="240" cy="315" rx="65" ry="17" fill="#ffffff" stroke="#475569" stroke-width="1.3"/>
  <text x="240" y="318" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Lacak Booking (WA/Kode)</text>

  <!-- UC7: Riwayat Booking (Member) -->
  <ellipse cx="240" cy="190" rx="65" ry="17" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="240" y="193" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Lihat Riwayat &amp; Profil</text>

  <!-- UC8: Kelola Data Master (Admin) -->
  <ellipse cx="435" cy="285" rx="68" ry="18" fill="#ffffff" stroke="#1e40af" stroke-width="1.3"/>
  <text x="435" y="288" fill="#1e3a8a" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Kelola Master &amp; Terapis</text>

  <!-- UC9: Konfirmasi & Laporan (Admin) -->
  <ellipse cx="435" cy="365" rx="72" ry="19" fill="#ffffff" stroke="#1e40af" stroke-width="1.3"/>
  <text x="435" y="368" fill="#1e3a8a" font-family="Helvetica, Arial, sans-serif" font-size="9.5" text-anchor="middle">Verifikasi &amp; Cetak PDF</text>

  <!-- CONNECTORS: Member -->
  <line x1="88" y1="135" x2="175" y2="78" stroke="#64172d" stroke-width="1.2"/>
  <line x1="88" y1="135" x2="168" y2="130" stroke="#64172d" stroke-width="1.5"/>
  <line x1="88" y1="135" x2="175" y2="190" stroke="#64172d" stroke-width="1.2"/>
  <line x1="88" y1="135" x2="175" y2="245" stroke="#64172d" stroke-width="1.2"/>

  <!-- CONNECTORS: Tamu -->
  <line x1="88" y1="325" x2="180" y2="85" stroke="#475569" stroke-width="1.2"/>
  <line x1="88" y1="325" x2="190" y2="145" stroke="#475569" stroke-width="1.5"/>
  <line x1="88" y1="325" x2="178" y2="252" stroke="#475569" stroke-width="1.2"/>
  <line x1="88" y1="325" x2="175" y2="315" stroke="#475569" stroke-width="1.2"/>

  <!-- CONNECTORS: INCLUDE & EXTEND -->
  <!-- include from UC2 to UC3 -->
  <line x1="312" y1="130" x2="363" y2="130" stroke="#b91c1c" stroke-width="1.4" stroke-dasharray="3,3"/>
  <polygon points="363,130 353,126 353,134" fill="#b91c1c"/>
  <text x="337" y="123" fill="#b91c1c" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">&lt;&lt;include&gt;&gt;</text>

  <!-- extend from UC4 to UC3 -->
  <line x1="435" y1="185" x2="435" y2="149" stroke="#b45309" stroke-width="1.4" stroke-dasharray="3,3"/>
  <polygon points="435,149 431,159 439,159" fill="#b45309"/>
  <text x="475" y="170" fill="#b45309" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">&lt;&lt;extend&gt;&gt;</text>
  <text x="475" y="180" fill="#b45309" font-family="Helvetica, Arial, sans-serif" font-size="7.5" text-anchor="middle">(kondisi: bentrok &gt;15m)</text>

  <!-- CONNECTORS: Admin -->
  <line x1="592" y1="225" x2="507" y2="135" stroke="#1e40af" stroke-width="1.2"/>
  <line x1="592" y1="225" x2="503" y2="285" stroke="#1e40af" stroke-width="1.3"/>
  <line x1="592" y1="225" x2="507" y2="365" stroke="#1e40af" stroke-width="1.3"/>
</svg>';

// ==========================================
// 2. DEFINISI SVG DIAGRAM 2: ACTIVITY DIAGRAM
// ==========================================
$svgActivity = '<svg xmlns="http://www.w3.org/2000/svg" width="680" height="520" viewBox="0 0 680 520">
  <rect width="680" height="520" fill="#ffffff" stroke="#cbd5e1" stroke-width="1" rx="8"/>

  <!-- SWIMLANES -->
  <!-- Header 1 -->
  <rect x="20" y="20" width="160" height="30" fill="#64172d" rx="4"/>
  <text x="100" y="40" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Pelanggan / Tamu</text>
  <rect x="20" y="50" width="160" height="450" fill="#fffdfd" stroke="#f1d0d8" stroke-width="1"/>

  <!-- Header 2 -->
  <rect x="180" y="20" width="160" height="30" fill="#475569" rx="4"/>
  <text x="260" y="40" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Web Controller / UI</text>
  <rect x="180" y="50" width="160" height="450" fill="#f8fafc" stroke="#cbd5e1" stroke-width="1"/>

  <!-- Header 3 -->
  <rect x="340" y="20" width="180" height="30" fill="#1e3a8a" rx="4"/>
  <text x="430" y="40" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">PenjadwalanReservasi</text>
  <rect x="340" y="50" width="180" height="450" fill="#f0f7ff" stroke="#bfdbfe" stroke-width="1"/>

  <!-- Header 4 -->
  <rect x="520" y="20" width="140" height="30" fill="#0f766e" rx="4"/>
  <text x="590" y="40" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="10.5" font-weight="bold" text-anchor="middle">Database (MySQL)</text>
  <rect x="520" y="50" width="140" height="450" fill="#f0fdfa" stroke="#99f6e4" stroke-width="1"/>

  <!-- ACTIVITY STEPS -->
  <!-- Start Node -->
  <circle cx="100" cy="75" r="9" fill="#1e293b"/>
  <line x1="100" y1="84" x2="100" y2="100" stroke="#1e293b" stroke-width="1.5"/>
  <polygon points="100,103 97,95 103,95" fill="#1e293b"/>

  <!-- Step 1: Input Form -->
  <rect x="35" y="105" width="130" height="42" rx="6" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="100" y="122" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Pilih Perawatan,</text>
  <text x="100" y="135" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" text-anchor="middle">Terapis, Tgl &amp; Jam Mulai</text>

  <!-- Step 1 to Controller -->
  <line x1="165" y1="126" x2="195" y2="126" stroke="#475569" stroke-width="1.3"/>
  <polygon points="198,126 190,123 190,129" fill="#475569"/>

  <!-- Step 2: Controller -->
  <rect x="195" y="108" width="130" height="36" rx="6" fill="#ffffff" stroke="#475569" stroke-width="1.3"/>
  <text x="260" y="124" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Validasi Request &amp;</text>
  <text x="260" y="136" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" text-anchor="middle">Teruskan ke Service</text>

  <!-- Step 2 to Service -->
  <line x1="325" y1="126" x2="355" y2="126" stroke="#1e3a8a" stroke-width="1.3"/>
  <polygon points="358,126 350,123 350,129" fill="#1e3a8a"/>

  <!-- Step 3: Service Hitung D dan E -->
  <rect x="355" y="108" width="150" height="36" rx="6" fill="#ffffff" stroke="#1e3a8a" stroke-width="1.3"/>
  <text x="430" y="123" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Hitung D_total &amp; E</text>
  <text x="430" y="135" fill="#64748b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">E = S_req + sum(d_i * q_i)</text>

  <!-- Query DB -->
  <line x1="505" y1="126" x2="535" y2="126" stroke="#0f766e" stroke-width="1.3"/>
  <polygon points="538,126 530,123 530,129" fill="#0f766e"/>

  <rect x="535" y="108" width="110" height="36" rx="6" fill="#ffffff" stroke="#0f766e" stroke-width="1.3"/>
  <text x="590" y="124" fill="#0f766e" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Query Booking</text>
  <text x="590" y="136" fill="#0f766e" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Aktif Hari Terpilih</text>

  <!-- Return DB to Service -->
  <path d="M 590 144 L 590 175 L 430 175 L 430 190" fill="none" stroke="#0f766e" stroke-width="1.3"/>
  <polygon points="430,193 427,185 433,185" fill="#0f766e"/>

  <!-- Step 4: Interval Algebra Evaluation -->
  <rect x="355" y="195" width="150" height="38" rx="6" fill="#ffffff" stroke="#1e3a8a" stroke-width="1.3"/>
  <text x="430" y="210" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Uji Irisan Interval</text>
  <text x="430" y="222" fill="#64748b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">delta = max(0, min(E)-max(S))</text>

  <!-- Decision Node -->
  <line x1="430" y1="233" x2="430" y2="250" stroke="#1e3a8a" stroke-width="1.3"/>
  <polygon points="430,253 427,245 433,245" fill="#1e3a8a"/>
  
  <polygon points="430,255 455,270 430,285 405,270" fill="#fffbeb" stroke="#b45309" stroke-width="1.4"/>
  <text x="430" y="272" fill="#92400e" font-family="Helvetica, Arial, sans-serif" font-size="7.5" font-weight="bold" text-anchor="middle">&gt; 15 mnt?</text>

  <!-- Branch YES: Bentrok > 15 mnt -->
  <line x1="405" y1="270" x2="325" y2="270" stroke="#b91c1c" stroke-width="1.3"/>
  <polygon points="322,270 330,267 330,273" fill="#b91c1c"/>
  <text x="355" y="264" fill="#b91c1c" font-family="Helvetica, Arial, sans-serif" font-size="7.5" font-weight="bold">Ya (Bentrok)</text>

  <rect x="195" y="252" width="130" height="38" rx="6" fill="#fff1f2" stroke="#b91c1c" stroke-width="1.3"/>
  <text x="260" y="267" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Jalankan Greedy Search</text>
  <text x="260" y="279" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Sliding Window 15 mnt</text>

  <!-- Controller shows suggestions to user -->
  <line x1="195" y1="270" x2="165" y2="270" stroke="#b91c1c" stroke-width="1.3"/>
  <polygon points="162,270 170,267 170,273" fill="#b91c1c"/>

  <rect x="35" y="252" width="130" height="38" rx="6" fill="#ffffff" stroke="#b91c1c" stroke-width="1.3"/>
  <text x="100" y="267" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Pilih 1 dari 3-5 Slot</text>
  <text x="100" y="279" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Rekomendasi Terdekat</text>

  <!-- User selects alternative and returns to Step 1 -->
  <path d="M 100 290 L 100 310 L 25 310 L 25 126 L 35 126" fill="none" stroke="#b91c1c" stroke-width="1.2" stroke-dasharray="3,3"/>

  <!-- Branch NO: Tidak Bentrok (<= 15 mnt) -->
  <line x1="455" y1="270" x2="505" y2="270" stroke="#15803d" stroke-width="1.3"/>
  <text x="475" y="264" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="7.5" font-weight="bold">Tidak</text>
  <path d="M 505 270 L 505 340 L 430 340 L 430 355" fill="none" stroke="#15803d" stroke-width="1.3"/>
  <polygon points="430,358 427,350 433,350" fill="#15803d"/>

  <!-- Step Validasi Sukses -->
  <rect x="355" y="360" width="150" height="34" rx="6" fill="#f0fdf4" stroke="#15803d" stroke-width="1.3"/>
  <text x="430" y="375" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Jadwal Tervalidasi</text>
  <text x="430" y="386" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Status: Tersedia / Tunggu</text>

  <!-- Forward to Controller & User for Payment -->
  <line x1="355" y1="377" x2="165" y2="377" stroke="#15803d" stroke-width="1.3"/>
  <polygon points="162,377 170,374 170,380" fill="#15803d"/>

  <rect x="35" y="360" width="130" height="34" rx="6" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="100" y="375" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Konfirmasi &amp; Unggah</text>
  <text x="100" y="386" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Bukti Pembayaran</text>

  <!-- Submit to DB -->
  <path d="M 100 394 L 100 435 L 535 435" fill="none" stroke="#0f766e" stroke-width="1.3"/>
  <polygon points="538,435 530,432 530,438" fill="#0f766e"/>

  <rect x="535" y="415" width="115" height="40" rx="6" fill="#ffffff" stroke="#0f766e" stroke-width="1.3"/>
  <text x="592" y="430" fill="#0f766e" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Simpan Transaksi &amp;</text>
  <text x="592" y="442" fill="#0f766e" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Snapshot Pivot Data</text>

  <!-- End Node -->
  <line x1="592" y1="455" x2="592" y2="475" stroke="#1e293b" stroke-width="1.3"/>
  <polygon points="592,478 589,470 595,470" fill="#1e293b"/>
  <circle cx="592" cy="488" r="9" fill="none" stroke="#1e293b" stroke-width="2"/>
  <circle cx="592" cy="488" r="5" fill="#1e293b"/>
</svg>';

// ==========================================
// 3. DEFINISI SVG DIAGRAM 3: FLOWCHART ALGORITMA
// ==========================================
$svgFlowchart = '<svg xmlns="http://www.w3.org/2000/svg" width="680" height="580" viewBox="0 0 680 580">
  <rect width="680" height="580" fill="#ffffff" stroke="#cbd5e1" stroke-width="1" rx="8"/>

  <!-- Title banner -->
  <rect x="15" y="15" width="650" height="28" fill="#64172d" rx="6"/>
  <text x="340" y="33" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="11" font-weight="bold" text-anchor="middle">Flowchart Alur Algoritma: Interval Intersection Algebra &amp; Greedy Search</text>

  <!-- START NODE -->
  <rect x="290" y="55" width="100" height="28" rx="14" fill="#1e293b"/>
  <text x="340" y="73" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="10" font-weight="bold" text-anchor="middle">MULAI</text>

  <line x1="340" y1="83" x2="340" y2="98" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,101 337,93 343,93" fill="#1e293b"/>

  <!-- INPUT NODE -->
  <polygon points="230,105 470,105 450,135 210,135" fill="#f8fafc" stroke="#475569" stroke-width="1.3"/>
  <text x="340" y="123" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="9" text-anchor="middle">Input: Tanggal, TerapisID, S_req, Daftar Perawatan { (d_i, q_i) }</text>

  <line x1="340" y1="135" x2="340" y2="150" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,153 337,145 343,145" fill="#1e293b"/>

  <!-- PROCESS 1: Akumulasi D dan E -->
  <rect x="220" y="155" width="240" height="34" rx="4" fill="#ffffff" stroke="#64172d" stroke-width="1.3"/>
  <text x="340" y="170" fill="#64172d" font-family="Helvetica, Arial, sans-serif" font-size="9" font-weight="bold" text-anchor="middle">Kalkulasi D_total = sum(d_i * q_i)</text>
  <text x="340" y="182" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="8.5" text-anchor="middle">Waktu Selesai E_req = S_req + D_total</text>

  <line x1="340" y1="189" x2="340" y2="204" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,207 337,199 343,199" fill="#1e293b"/>

  <!-- PROCESS 2: Query & Evaluasi Overlap -->
  <rect x="200" y="210" width="280" height="42" rx="4" fill="#ffffff" stroke="#1e3a8a" stroke-width="1.3"/>
  <text x="340" y="225" fill="#1e3a8a" font-family="Helvetica, Arial, sans-serif" font-size="9" font-weight="bold" text-anchor="middle">Ambil Jadwal Eksisting Terapis R_k</text>
  <text x="340" y="238" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Hitung: delta_k = max(0, min(E_req, E_k) - max(S_req, S_k))</text>
  <text x="340" y="248" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Tentukan delta_max = max(delta_k)</text>

  <line x1="340" y1="252" x2="340" y2="267" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,270 337,262 343,262" fill="#1e293b"/>

  <!-- DECISION 1: delta_max > 15? -->
  <polygon points="340,272 420,295 340,318 260,295" fill="#fffbeb" stroke="#b45309" stroke-width="1.4"/>
  <text x="340" y="298" fill="#92400e" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">delta_max &gt; 15?</text>

  <!-- BRANCH TIDAK: Jadwal Diterima -->
  <line x1="420" y1="295" x2="520" y2="295" stroke="#15803d" stroke-width="1.3"/>
  <polygon points="523,295 515,292 515,298" fill="#15803d"/>
  <text x="460" y="288" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold">TIDAK (Valid)</text>

  <rect x="525" y="278" width="135" height="34" rx="4" fill="#f0fdf4" stroke="#15803d" stroke-width="1.3"/>
  <text x="592" y="293" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Status = DITERIMA</text>
  <text x="592" y="304" fill="#15803d" font-family="Helvetica, Arial, sans-serif" font-size="7.5" text-anchor="middle">(Tersedia / Tunggu &lt;=15m)</text>

  <!-- Direct to END from Valid -->
  <path d="M 592 312 L 592 525 L 390 525" fill="none" stroke="#15803d" stroke-width="1.3"/>
  <polygon points="387,525 395,522 395,528" fill="#15803d"/>

  <!-- BRANCH YA: Bentrok -> Greedy Module -->
  <line x1="340" y1="318" x2="340" y2="338" stroke="#b91c1c" stroke-width="1.3"/>
  <polygon points="340,341 337,333 343,333" fill="#b91c1c"/>
  <text x="345" y="331" fill="#b91c1c" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold">YA (Bentrok)</text>

  <!-- PROCESS 3: Inisialisasi Greedy -->
  <rect x="210" y="343" width="260" height="34" rx="4" fill="#fef2f2" stroke="#b91c1c" stroke-width="1.3"/>
  <text x="340" y="357" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Aktifkan Greedy Search Module</text>
  <text x="340" y="369" fill="#991b1b" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Set C = [], S_cand = 08:00 (480), step = 15 mnt</text>

  <line x1="340" y1="377" x2="340" y2="392" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,395 337,387 343,387" fill="#1e293b"/>

  <!-- PROCESS 4: Sliding Window Loop -->
  <rect x="180" y="397" width="320" height="44" rx="4" fill="#ffffff" stroke="#475569" stroke-width="1.3"/>
  <text x="340" y="411" fill="#1e293b" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Loop: S_cand + D_total &lt;= 17:00 (1020)</text>
  <text x="340" y="423" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Uji [S_cand, S_cand + D_total) bebas bentrok? Jika YA:</text>
  <text x="340" y="433" fill="#334155" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Hitung dist = |S_cand - S_req| dan masukkan ke himpunan C</text>

  <line x1="340" y1="441" x2="340" y2="456" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,459 337,451 343,451" fill="#1e293b"/>

  <!-- PROCESS 5: Sort & Top-K Selection -->
  <rect x="200" y="461" width="280" height="34" rx="4" fill="#fffbeb" stroke="#b45309" stroke-width="1.3"/>
  <text x="340" y="475" fill="#92400e" font-family="Helvetica, Arial, sans-serif" font-size="8.5" font-weight="bold" text-anchor="middle">Urutkan C Menaik Berdasarkan dist</text>
  <text x="340" y="487" fill="#92400e" font-family="Helvetica, Arial, sans-serif" font-size="8" text-anchor="middle">Ambil Top-3 s.d. Top-5 Slot Terdekat</text>

  <line x1="340" y1="495" x2="340" y2="510" stroke="#1e293b" stroke-width="1.4"/>
  <polygon points="340,513 337,505 343,505" fill="#1e293b"/>

  <!-- END NODE -->
  <rect x="290" y="513" width="100" height="26" rx="13" fill="#1e293b"/>
  <text x="340" y="530" fill="#ffffff" font-family="Helvetica, Arial, sans-serif" font-size="9.5" font-weight="bold" text-anchor="middle">SELESAI</text>
</svg>';

// Encode SVGs to Base64 data URIs
$b64UseCase = 'data:image/svg+xml;base64,' . base64_encode($svgUseCase);
$b64Activity = 'data:image/svg+xml;base64,' . base64_encode($svgActivity);
$b64Flowchart = 'data:image/svg+xml;base64,' . base64_encode($svgFlowchart);

// ==========================================
// 4. KONTEN HTML LENGKAP DOKUMEN AKADEMIS
// ==========================================
$htmlContent = '<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Spesifikasi Teknis dan Algoritma Penjadwalan Azzahra</title>
<style>
  @page {
    margin: 24mm 20mm 24mm 20mm;
    @bottom-right {
      content: counter(page);
    }
  }

  body {
    font-family: Helvetica, Arial, sans-serif;
    color: #1e293b;
    font-size: 10pt;
    line-height: 1.55;
    text-align: justify;
  }

  /* Cover styling */
  .cover-container {
    text-align: center;
    padding-top: 50px;
    padding-bottom: 60px;
  }

  .cover-instansi {
    font-size: 12pt;
    font-weight: bold;
    letter-spacing: 0.05em;
    color: #475569;
    text-transform: uppercase;
    margin-bottom: 25px;
  }

  .cover-line {
    width: 90px;
    height: 3px;
    background-color: #64172d;
    margin: 0 auto 30px auto;
  }

  .cover-title {
    font-size: 19pt;
    font-weight: bold;
    color: #64172d;
    line-height: 1.3;
    margin-bottom: 12px;
  }

  .cover-subtitle {
    font-size: 11pt;
    color: #334155;
    line-height: 1.4;
    margin-bottom: 40px;
    font-style: italic;
  }

  .cover-box {
    border: 1.5px solid #cbd5e1;
    background-color: #f8fafc;
    border-radius: 8px;
    padding: 16px 24px;
    display: inline-block;
    text-align: left;
    margin-bottom: 60px;
    width: 78%;
  }

  .cover-box table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9.5pt;
  }

  .cover-box td {
    padding: 4px 6px;
    vertical-align: top;
  }

  .cover-footer {
    font-size: 10pt;
    font-weight: bold;
    color: #475569;
    margin-top: 40px;
  }

  /* Typography */
  h1 {
    font-size: 14pt;
    color: #64172d;
    border-bottom: 1.5px solid #64172d;
    padding-bottom: 4px;
    margin-top: 24px;
    margin-bottom: 12px;
    text-transform: uppercase;
    page-break-after: avoid;
  }

  h2 {
    font-size: 11.5pt;
    color: #1e3a8a;
    margin-top: 18px;
    margin-bottom: 8px;
    page-break-after: avoid;
  }

  h3 {
    font-size: 10.5pt;
    color: #334155;
    margin-top: 14px;
    margin-bottom: 6px;
    page-break-after: avoid;
  }

  p {
    margin-top: 0;
    margin-bottom: 10px;
    text-indent: 1.5em;
  }

  p.no-indent {
    text-indent: 0;
  }

  ul, ol {
    margin-top: 0;
    margin-bottom: 10px;
    padding-left: 28px;
  }

  li {
    margin-bottom: 4px;
  }

  /* Tables */
  table.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 12px 0 16px 0;
    font-size: 8.5pt;
  }

  table.data-table th, table.data-table td {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
  }

  table.data-table th {
    background-color: #64172d;
    color: #ffffff;
    font-weight: bold;
    text-align: center;
  }

  table.data-table tr:nth-child(even) {
    background-color: #fdf8f6;
  }

  /* Math Callout Box */
  .formula-box {
    background-color: #f8fafc;
    border-left: 3.5px solid #1e3a8a;
    border-right: 1px solid #e2e8f0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 14px;
    margin: 12px 0;
    font-family: "Courier New", Courier, monospace;
    font-size: 9.5pt;
  }

  .formula-title {
    font-family: Helvetica, Arial, sans-serif;
    font-weight: bold;
    color: #1e3a8a;
    font-size: 8.5pt;
    text-transform: uppercase;
    margin-bottom: 5px;
  }

  /* Pseudocode Box */
  .code-box {
    background-color: #1e293b;
    color: #f8fafc;
    border-radius: 6px;
    padding: 12px 16px;
    font-family: "Courier New", Courier, monospace;
    font-size: 8pt;
    line-height: 1.45;
    margin: 14px 0;
    page-break-inside: avoid;
  }

  .code-keyword { color: #f472b6; font-weight: bold; }
  .code-func { color: #38bdf8; font-weight: bold; }
  .code-var { color: #fde047; }
  .code-comment { color: #94a3b8; font-style: italic; }
  .code-num { color: #a7f3d0; }

  /* Diagram Container */
  .figure-container {
    text-align: center;
    margin: 16px 0 20px 0;
    page-break-inside: avoid;
  }

  .figure-img {
    width: 95%;
    height: auto;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 4px;
    background-color: #ffffff;
  }

  .figure-caption {
    font-size: 8.5pt;
    font-weight: bold;
    color: #475569;
    margin-top: 6px;
  }

  .page-break {
    page-break-before: always;
  }

  .highlight-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 8pt;
    font-weight: bold;
  }

  .badge-green { background-color: #dcfce7; color: #15803d; }
  .badge-yellow { background-color: #fef9c3; color: #a16207; }
  .badge-red { background-color: #fee2e2; color: #b91c1c; }
</style>
</head>
<body>

<!-- ========================================================================= -->
<!-- HALAMAN SAMPUL / COVER AKADEMIK -->
<!-- ========================================================================= -->
<div class="cover-container">
  <div class="cover-instansi">Lampiran Spesifikasi Teknis &amp; Landasan Algoritma Skripsi</div>
  <div class="cover-line"></div>
  
  <div class="cover-title">
    DOKUMEN SPESIFIKASI TEKNIS DAN ANALISIS ALGORITMA SISTEM PENJADWALAN RESERVASI MULTI-LAYANAN
  </div>
  
  <div class="cover-subtitle">
    Kajian Formal Model Penjadwalan Berbasis Interval Intersection Algebra dan Greedy Nearest-Slot Search pada Sistem Informasi Salon dan Spa Muslimah Azzahra Yogyakarta
  </div>

  <div class="cover-box">
    <table>
      <tr>
        <td style="width: 32%; font-weight: bold; color: #334155;">Topik Penelitian</td>
        <td style="width: 3%;">:</td>
        <td>Rekayasa Perangkat Lunak &amp; Algoritma Penjadwalan Deterministik</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #334155;">Objek Studi Kasus</td>
        <td>:</td>
        <td>Azzahra Salon &amp; Spa Muslimah Yogyakarta (Est. 2004)</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #334155;">Penyusun / Peneliti</td>
        <td>:</td>
        <td>Afga (Program Studi Teknik Informatika / Ilmu Komputer)</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #334155;">Peruntukan Berkas</td>
        <td>:</td>
        <td>Lampiran Teknis &amp; Rujukan Konseptual Bab 3 (Perancangan) &amp; Bab 4 (Implementasi)</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #334155;">Framework / Bahasa</td>
        <td>:</td>
        <td>Laravel 12.x / PHP 8.2+ / MySQL Relational Engine</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #334155;">Waktu Penyusunan</td>
        <td>:</td>
        <td>Tahun Akademik 2026</td>
      </tr>
    </table>
  </div>

  <div class="cover-footer">
    FAKULTAS TEKNOLOGI INFORMASI / ILMU KOMPUTER<br>
    YOGYAKARTA &mdash; 2026
  </div>
</div>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB I: PENDAHULUAN -->
<!-- ========================================================================= -->
<h1>BAB I: PENDAHULUAN</h1>

<h2>1.1 Latar Belakang dan Konteks Masalah</h2>
<p>
  Industri jasa perawatan tubuh dan kecantikan muslimah, seperti halnya Azzahra Salon dan Spa Muslimah yang telah melayani masyarakat Yogyakarta sejak tahun 2004, memiliki karakteristik operasional yang sarat akan batasan sumber daya (*resource constraints*). Berbeda dengan transaksi ritel barang fisik, transaksi reservasi jasa salon mengikat tiga entitas secara simultan pada dimensi waktu tertentu: ketersediaan terapis wanita yang memiliki sertifikasi/keahlian tertentu, alokasi bilik/ruangan perawatan khusus (*private room* muslimah), serta waktu yang dialokasikan oleh pelanggan.
</p>
<p>
  Pada sistem konvensional atau pencatatan berbasis pembukuan manual, seringkali terjadi kegagalan alokasi jadwal yang berakibat pada timbulnya *double-booking* (bentrok jadwal). Ketika dua orang pelanggan terdaftar pada satu terapis di waktu yang bersamaan atau tumpang tindih, timbul ketidaknyamanan operasional, penumpukan antrean fisik di ruang tunggu, penurunan tingkat kepuasan pelanggan (*customer satisfaction*), hingga pembatalan sepihak (*cancellation*). Di sisi lain, menolak pelanggan secara mentah-mentah saat slot penuh tanpa memberikan opsi alternatif mengakibatkan hilangnya potensi pendapatan salon (*revenue loss*). Oleh sebab itu, dibutuhkan sebuah model komputasi yang deterministik untuk mendeteksi potensi bentrok sekaligus memberikan rekomendasi jadwal alternatif terdekat.
</p>

<h2>1.2 Karakteristik Penjadwalan Multi-Layanan (Multi-Treatment)</h2>
<p>
  Salah satu tantangan matematis dalam sistem reservasi Azzahra adalah keberadaan pemesanan multi-layanan (*multi-treatment*). Dalam satu sesi kunjungan, seorang pelanggan dapat memesan lebih dari satu jenis perawatan (misalnya: *Facial Bio-Herbal*, *Hair Spa Creambath*, dan *Totok Aura*). Masing-masing perawatan memiliki durasi dasar yang berbeda serta kuantitas pemesanan yang fleksibel. Sistem dituntut untuk mengagregasi total waktu secara kontigu (berkesinambungan tanpa jeda kosong di antara perawatan pada terapis yang sama) dan memvalidasi interval waktu akumulatif tersebut terhadap seluruh jadwal reservasi yang sudah terkonfirmasi di database.
</p>

<h2>1.3 Ruang Lingkup dan Hak Akses Pengguna</h2>
<p class="no-indent">Sistem ini melayani tiga kategori aktor dengan kewenangan sebagai berikut:</p>
<ul>
  <li><strong>Pelanggan Terdaftar (Member / Role ID 2):</strong> Pengguna yang memiliki akun terotentikasi. Memiliki hak untuk melihat riwayat pemesanan masa lalu, memantau status booking terdekat secara *real-time* (<span style="font-family: monospace;">/booking-saya</span>), menikmati program promosi eksklusif member, dan memberikan ulasan/testimoni layanan.</li>
  <li><strong>Pelanggan Tamu (Guest / Publik Tanpa Akun):</strong> Konsumen yang ingin melakukan reservasi cepat tanpa keharusan membuat akun terlebih dahulu. Sistem secara transparan mencatat data tamu ke dalam entitas pelanggan dengan kolom <span style="font-family: monospace;">user_id = null</span>, mempertahankan sesi sementara (<span style="font-family: monospace;">booking_tamu</span>), dan menyediakan fitur pelacakan reservasi mandiri (<span style="font-family: monospace;">/booking/lacak</span>) menggunakan kombinasi Kode Reservasi unik dan Nomor WhatsApp aktif.</li>
  <li><strong>Administrator Salon (Admin / Role ID 1):</strong> Pengelola operasional yang memiliki otoritas penuh terhadap manipulasi data master (layanan perawatan, data terapis, jam kerja cabang, tarif), verifikasi pembayaran uang muka/lunas, konfirmasi status reservasi, monitoring dashboard terapis, serta pencetakan laporan berkala format PDF.</li>
</ul>

<h2>1.4 Parameter Operasional Sistem</h2>
<p class="no-indent">Berdasarkan kesepakatan tata kelola operasional Azzahra Salon &amp; Spa, sistem menerapkan parameter batas sebagai berikut:</p>
<ul>
  <li><strong>Jam Buka Operasional ($T_{\text{buka}}$):</strong> Pukul 08:00 WIB (ekivalen dengan menit ke-$480$ sejak tengah malam).</li>
  <li><strong>Jam Tutup Operasional ($T_{\text{tutup}}$):</strong> Pukul 17:00 WIB (ekivalen dengan menit ke-$1020$ sejak tengah malam). Seluruh durasi perawatan reservasi wajib berakhir sebelum atau tepat pada jam tutup.</li>
  <li><strong>Toleransi Waktu Tunggu / Keterlambatan ($\tau$):</strong> Ditetapkan sebesar $15$ menit. Irisan waktu antara dua jadwal yang tidak melebihi 15 menit masih dapat ditoleransi dalam batasan antrean wajar.</li>
  <li><strong>Langkah Diskrit Pencarian Alternatif ($\delta$):</strong> Interval geser sebesar $15$ menit.</li>
  <li><strong>Batas Pembatalan Mandiri:</strong> Pembatalan reservasi oleh pelanggan melalui sistem dibatasi minimal $3$ jam sebelum jam pelaksanaan demi menjaga kepastian kerja terapis.</li>
</ul>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB II: ARSITEKTUR DATA RESERVASI -->
<!-- ========================================================================= -->
<h1>BAB II: ARSITEKTUR DATA DAN POLA DESAIN PENYIMPANAN</h1>

<h2>2.1 Skema Relasional Inti Penjadwalan</h2>
<p>
  Struktur basis data dirancang untuk mendukung integritas referensial dan skalabilitas transaksi. Hubungan antar tabel yang terlibat dalam proses pemesanan disajikan pada tabel di bawah ini:
</p>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 20%;">Entitas / Tabel</th>
      <th style="width: 30%;">Atribut Kunci</th>
      <th style="width: 50%;">Deskripsi dan Peran dalam Penjadwalan</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>reservasi</strong></td>
      <td>id, kode_reservasi, pelanggan_id, pegawai_id, cabang_id, tanggal, jam_mulai, jam_selesai, status, total_harga, sumber</td>
      <td>Tabel utama yang menyimpan relasi waktu transaksi, penugasan pegawai (terapis), status eksekusi (<span style="font-family: monospace;">Menunggu, Dikonfirmasi, Selesai, Dibatalkan</span>), dan asal pemesanan (<span style="font-family: monospace;">member / tamu</span>).</td>
    </tr>
    <tr>
      <td><strong>perawatan</strong></td>
      <td>id, kategori_id, nama, harga, durasi, status, foto</td>
      <td>Katalog master berisi tarif standar dan estimasi durasi dasar pengerjaan untuk masing-masing jenis perawatan (dalam satuan menit).</td>
    </tr>
    <tr>
      <td><strong>reservasi_perawatan</strong><br><em>(Pivot Table)</em></td>
      <td>id, reservasi_id, perawatan_id, jumlah, harga_satuan, durasi_satuan, subtotal_harga, subtotal_durasi</td>
      <td>Tabel perantara relasi *many-to-many* antara reservasi dan perawatan yang mengimplementasikan <strong>Snapshot Pattern</strong>.</td>
    </tr>
    <tr>
      <td><strong>pegawai</strong></td>
      <td>id, cabang_id, nama, spesialisasi, status</td>
      <td>Entitas sumber daya manusia (terapis). Menjadi subjek verifikasi bentrok jadwal pada tanggal pemesanan.</td>
    </tr>
    <tr>
      <td><strong>pembayaran</strong></td>
      <td>id, reservasi_id, metode_pembayaran_id, total_bayar, diskon, jumlah_dibayar, bukti_bayar, status</td>
      <td>Pencatatan konfirmasi finansial (Transfer BCA, BRI, DANA, QRIS) beserta riwayat penagihan dan bukti transaksi.</td>
    </tr>
  </tbody>
</table>

<h2>2.2 Penerapan Snapshot Pattern pada Tabel Pivot</h2>
<p>
  Salah satu kelemahan desain relasional konvensional adalah asumsi bahwa katalog layanan bersifat statis abadi. Pada dunia nyata, tarif layanan ($harga$) dan durasi standar pengerjaan ($durasi$) pada tabel <span style="font-family: monospace;">perawatan</span> bersifat dinamis (*mutable*). Manajemen salon dapat melakukan penyesuaian harga (akibat inflasi atau promosi) atau memperbarui durasi pengerjaan di masa mendatang.
</p>
<p>
  Apabila tabel perantara <span style="font-family: monospace;">reservasi_perawatan</span> hanya menyimpan kunci asing referensial <span style="font-family: monospace;">(reservasi_id, perawatan_id)</span> tanpa merekam nilai transaksi saat pemesanan disepakati, maka akan terjadi distorsi integritas historis (*historical data corruption*). Ketika laporan keuangan bulanan masa lalu dicetak ulang, kalkulasi omset akan berubah mengikuti harga katalog baru, bukan harga yang disepakati saat transaksi terjadi.
</p>
<p class="no-indent">
  Oleh sebab itu, sistem mengimplementasikan <strong>Snapshot Pattern</strong> dengan membekukan nilai atribut pada saat *event* transaksi dibuat:
</p>
<div class="formula-box">
  <div class="formula-title">Pola Snapshot Entitas Pivot:</div>
  harga_satuan   := perawatan.harga (pada detik t_transaksi)<br>
  durasi_satuan  := perawatan.durasi (pada detik t_transaksi)<br>
  subtotal_harga := kuantitas * harga_satuan<br>
  subtotal_durasi:= kuantitas * durasi_satuan
</div>
<p>
  Dengan pendekatan ini, perubahan harga katalog di kemudian hari tidak akan pernah mempengaruhi data transaksi yang sudah tersimpan, sehingga audit keuangan dan rekapitulasi waktu kerja terapis tetap valid secara hukum dan akuntansi.
</p>

<h2>2.3 Formulasi Matematis Akumulasi Multi-Layanan</h2>
<p>
  Ketika seorang pelanggan memilih himpunan perawatan $\mathcal{P} = \{p_1, p_2, \dots, p_n\}$ dengan kuantitas masing-masing $\mathcal{Q} = \{q_1, q_2, \dots, q_n\}$, sistem menghitung total durasi $D_{\text{total}}$ dan total estimasi biaya $P_{\text{total}}$ menggunakan persamaan agregasi:
</p>
<div class="formula-box">
  <div class="formula-title">Persamaan Akumulasi Multi-Layanan:</div>
  D_total = SUM_{i=1}^{n} (durasi_i * q_i)<br>
  P_total = SUM_{i=1}^{n} (harga_i * q_i)<br>
  E_req   = S_req + D_total
</div>
<p>
  Di mana $S_{\text{req}}$ merepresentasikan titik waktu mulai reservasi yang diinginkan pelanggan, dan $E_{\text{req}}$ merepresentasikan titik waktu selesai pengerjaan seluruh rangkaian layanan.
</p>

<h2>2.4 Normalisasi Representasi Temporal (Integer Menit)</h2>
<p>
  Untuk mengeliminasi kesalahan aritmatika akibat *floating-point rounding*, perbedaan zona waktu, maupun anomali detik pada pustaka manipulasi waktu (seperti objek Carbon pada PHP yang secara default mengikutsertakan detik sistem saat ini jika tidak menggunakan format tanda seru (!) eksplisit), seluruh waktu pada sistem dinormalisasi ke dalam bentuk <strong>bilangan bulat menit sejak tengah malam</strong> (*minutes since midnight*):
</p>
<div class="formula-box">
  <div class="formula-title">Fungsi Transformasi Waktu:</div>
  f_menit(Jam, Menit) = (Jam * 60) + Menit,  di mana Jam in [0, 23], Menit in [0, 59]<br>
  f_jam(TotalMenit)   = sprintf("%02d:%02d", TotalMenit div 60, TotalMenit mod 60)
</div>
<p>
  Contoh: Pukul 08:00 WIB dinormalisasi menjadi $8 \times 60 + 0 = 480$. Pukul 17:00 WIB dinormalisasi menjadi $17 \times 60 + 0 = 1020$. Dengan pemetaan ini, seluruh kalkulasi komparasi interval direduksi menjadi operasi aritmatika integer dasar yang sangat cepat dan deterministik.
</p>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB III: SPESIFIKASI ALGORITMA DETEKSI INTERVAL -->
<!-- ========================================================================= -->
<h1>BAB III: SPESIFIKASI ALGORITMA DETEKSI INTERVAL (INTERVAL INTERSECTION ALGEBRA)</h1>

<h2>3.1 Landasan Teoretis: Logika Interval Temporal</h2>
<p>
  Dasar matematis yang digunakan untuk mendeteksi tumpang tindih waktu mengacu pada konsep *Allen’s Interval Algebra*. Suatu jadwal reservasi dimodelkan sebagai interval waktu setengah terbuka (*half-open interval*) pada garis bilangan riil:
</p>
<div class="formula-box">
  I = [S, E) = { t in R | S &lt;= t &lt; E }
</div>
<p>
  Di mana $S$ adalah batas bawah inklusif (jam mulai layanan) dan $E$ adalah batas atas eksklusif (jam selesai layanan). Karakteristik setengah terbuka dipilih karena apabila suatu reservasi $A$ berakhir tepat pukul 10:00 ($E_A = 600$) dan reservasi berikutnya $B$ dimulai tepat pukul 10:00 ($S_B = 600$), kedua reservasi tersebut secara fisik tidak saling mengganggu (*abutting/meeting*), sehingga tidak terjadi bentrok.
</p>

<h2>3.2 Kondisi Irisan Temporal (Intersection Condition)</h2>
<p>
  Diberikan dua buah interval jadwal, yaitu jadwal eksisting terapis $A = [S_A, E_A)$ dan permintaan jadwal baru $B = [S_B, E_B)$. Kedua interval tersebut beririsan secara temporal jika dan hanya jika terdapat titik waktu $t$ yang memenuhi $t \in A$ dan $t \in B$. Kondisi tersebut secara logis dinyatakan melalui proposisi:
</p>
<div class="formula-box">
  <div class="formula-title">Teorema Deteksi Irisan Interval:</div>
  Intersection(A, B) &lt;=&gt; (S_A &lt; E_B) AND (E_A &gt; S_B)
</div>
<p>
  Kondisi ini menjamin bahwa seluruh variasi kemungkinan bentrok (A mendahului B, B mendahului A, A berada di dalam B, atau B mencakup A secara penuh) terdeteksi secara akurat hanya melalui dua evaluasi perbandingan.
</p>

<h2>3.3 Formulasi Besaran Tumpang Tindih (Overlap Magnitude)</h2>
<p>
  Dalam operasional salon nyata, tidak semua irisan waktu serta merta harus digagalkan. Jika irisan hanya berdurasi singkat (misalnya pelanggan baru datang dan menunggu beberapa menit sementara terapis menyelesaikan tahap pengeringan rambut), jadwal masih dapat diakomodasi. Oleh sebab itu, sistem membutuhkan besaran durasi tumpang tindih secara kuantitatif ($\Delta_{\text{overlap}}$):
</p>
<div class="formula-box">
  <div class="formula-title">Persamaan Besaran Tumpang Tindih Temporal:</div>
  Delta_overlap = max( 0,  min(E_A, E_B) - max(S_A, S_B) )
</div>
<p class="no-indent">Keterangan:</p>
<ul>
  <li>$\min(E_A, E_B)$ menentukan titik akhir dari daerah persekutuan dua interval.</li>
  <li>$\max(S_A, S_B)$ menentukan titik awal dari daerah persekutuan dua interval.</li>
  <li>Fungsi $\max(0, \dots)$ memastikan nilai non-negatif apabila kedua interval saling lepas secara terpisah.</li>
</ul>

<h2>3.4 Kaidah Keputusan Sistem (Decision Rules)</h2>
<p>
  Sistem menetapkan nilai ambang batas toleransi keterlambatan/tunggu $\tau = 15$ menit. Status kelayakan reservasi ditentukan berdasarkan fungsi keputusan diskrit:
</p>
<div class="formula-box">
  Status(Delta_overlap) = 
  { "TERSEDIA",                    jika Delta_overlap = 0
  { "DITERIMA_DENGAN_WAKTU_TUNGGU", jika 1 &lt;= Delta_overlap &lt;= 15
  { "BENTROK_DITOLAK",             jika Delta_overlap &gt; 15
</div>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 25%;">Besaran Overlap ($\Delta$)</th>
      <th style="width: 25%;">Status Verifikasi</th>
      <th style="width: 50%;">Tindakan Sistem</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>$\Delta = 0$ menit</td>
      <td><span class="highlight-badge badge-green">TERSEDIA</span></td>
      <td>Jadwal disetujui penuh tanpa catatan. Lanjut ke modul pembayaran.</td>
    </tr>
    <tr>
      <td>$1 \le \Delta \le 15$ menit</td>
      <td><span class="highlight-badge badge-yellow">WAKTU TUNGGU</span></td>
      <td>Jadwal disetujui. Sistem menampilkan notifikasi estimasi waktu tunggu $\Delta$ menit kepada pelanggan sebelum konfirmasi.</td>
    </tr>
    <tr>
      <td>$\Delta > 15$ menit</td>
      <td><span class="highlight-badge badge-red">BENTROK (DITOLAK)</span></td>
      <td>Jadwal ditolak demi menjaga kualitas layanan. Sistem secara otomatis memicu eksekusi <strong>Greedy Nearest-Slot Search</strong> untuk menampilkan slot alternatif.</td>
    </tr>
  </tbody>
</table>

<h2>3.5 Simulasi Kasus Numerik</h2>
<p>
  Misalkan Terapis "Siti" telah memiliki reservasi terkonfirmasi $A$ pada pukul 10:00 - 11:30 WIB. Normalisasi integer: $S_A = 600$, $E_A = 690$ (durasi 90 menit).
</p>
<ul>
  <li><strong>Uji Kasus 1 (Permintaan Baru B: 11:20 - 12:20 WIB):</strong><br>
    Normalisasi: $S_B = 680$, $E_B = 740$.<br>
    Evaluasi irisan: $(600 < 740) \land (690 > 680) \implies \text{True}$.<br>
    Perhitungan magnitudo: $\Delta = \max(0, \min(690, 740) - \max(600, 680)) = 690 - 680 = 10$ menit.<br>
    Keputusan: Karena $\Delta = 10 \le 15$, reservasi <strong>DITERIMA DENGAN WAKTU TUNGGU</strong> (pelanggan menunggu 10 menit).
  </li>
  <li><strong>Uji Kasus 2 (Permintaan Baru C: 10:30 - 11:45 WIB):</strong><br>
    Normalisasi: $S_C = 630$, $E_C = 705$.<br>
    Evaluasi irisan: $(600 < 705) \land (690 > 630) \implies \text{True}$.<br>
    Perhitungan magnitudo: $\Delta = \max(0, \min(690, 705) - \max(600, 630)) = 690 - 630 = 60$ menit.<br>
    Keputusan: Karena $\Delta = 60 > 15$, reservasi <strong>BENTROK (DITOLAK)</strong>. Sistem langsung mencari slot terdekat lain.
  </li>
</ul>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB IV: SPESIFIKASI ALGORITMA GREEDY NEAREST-SLOT SEARCH -->
<!-- ========================================================================= -->
<h1>BAB IV: SPESIFIKASI ALGORITMA GREEDY NEAREST-SLOT SEARCH</h1>

<h2>4.1 Mengapa Disebut "Greedy"?</h2>
<p>
  Dalam teori algoritma ilmu komputer, paradigma <em>Greedy</em> (tamak) adalah teknik perancangan algoritma yang memecahkan masalah optimasi dengan membuat pilihan optimal secara lokal (*local optimum*) pada setiap tahapan keputusan, dengan harapan bahwa rangkaian pilihan lokal tersebut akan menghantarkan pada solusi optimal secara global (*global optimum*).
</p>
<p>
  Pada konteks penjadwalan salon Azzahra, masalah yang diselesaikan adalah: <em>"Bila jam yang diinginkan pelanggan ($S_{\text{awal}}$) bentrok, jam alternatif berapakah yang paling memuaskan pelanggan?"</em>. Secara psikologis dan perilaku konsumen, pelanggan memiliki preferensi waktu yang paling dekat dengan rencana awal kedatangan mereka.
</p>
<div class="formula-box">
  <div class="formula-title">Fungsi Objektif Minimasi Jarak Temporal:</div>
  Minimalkan: f(S_kandidat) = | S_kandidat - S_awal |
</div>
<p>
  Algoritma ini dikategorikan sebagai *Greedy* karena fungsi seleksinya secara langsung memprioritaskan slot kandidat valid yang meminimalkan nilai absolut jarak waktu $|S_{\text{kandidat}} - S_{\text{awal}}|$ secara instan, tanpa perlu melakukan penelusuran graf kombinatorial (*combinatorial backtracking*) yang kompleks.
</p>

<h2>4.2 Mekanisme Penelusuran Diskrit (Sliding Window Temporal)</h2>
<p>
  Algoritma bekerja dengan melakukan pergeseran jendela waktu (*sliding window*) berukuran $D_{\text{total}}$ secara diskrit dengan langkah pergeseran $\delta = 15$ menit:
</p>
<ol>
  <li>Inisialisasi batas awal pencarian pada jam buka salon: $S_{\text{kandidat}} = T_{\text{buka}} = 480$ (08:00 WIB).</li>
  <li>Batas akhir pergeseran dibatasi agar waktu selesai tidak melampaui jam tutup salon: $S_{\text{kandidat}} + D_{\text{total}} \le T_{\text{tutup}} = 1020$ (17:00 WIB).</li>
  <li>Untuk setiap kandidat $S_{\text{kandidat}}$, sistem menguji apakah jendela $[S_{\text{kandidat}}, S_{\text{kandidat}} + D_{\text{total}})$ bebas dari bentrok terhadap seluruh jadwal terapis pada hari itu ($\Delta_{\text{overlap}} \le 15$).</li>
  <li>Jika memenuhi kriteria kelayakan (*feasible*), jarak $|S_{\text{kandidat}} - S_{\text{awal}}|$ dihitung, kemudian pasangan $(S_{\text{kandidat}}, \text{jarak})$ disimpan ke dalam himpunan kandidat layak $\mathcal{C}$.</li>
  <li>Setelah jendela mencapai jam tutup, himpunan $\mathcal{C}$ diurutkan menaik (*ascending*) berdasarkan nilai jarak.</li>
  <li>Sistem mengambil $k$ elemen teratas (antara 3 hingga 5 slot terdekat) sebagai output rekomendasi bagi pengguna.</li>
</ol>

<h2>4.3 Pseudocode Formal Algoritma</h2>
<div class="code-box">
<span class="code-comment">// ============================================================================</span>
<span class="code-comment">// ALGORITMA: Greedy Nearest-Slot Alternative Search</span>
<span class="code-comment">// INPUT    : TerapisID, Tanggal, S_req (menit), D_total (menit), k (jumlah output)</span>
<span class="code-comment">// OUTPUT   : Daftar slot rekomendasi berurutan berdasarkan jarak terkecil</span>
<span class="code-comment">// ============================================================================</span>

<span class="code-keyword">Algorithm</span> <span class="code-func">CariSlotAlternatifGreedy</span>(terapisId, tgl, S_req, D_total, k = 5):
    <span class="code-keyword">Const</span> T_buka  := <span class="code-num">480</span>   <span class="code-comment">// 08:00 WIB</span>
    <span class="code-keyword">Const</span> T_tutup := <span class="code-num">1020</span>  <span class="code-comment">// 17:00 WIB</span>
    <span class="code-keyword">Const</span> step    := <span class="code-num">15</span>    <span class="code-comment">// Langkah diskrit 15 menit</span>
    <span class="code-keyword">Const</span> tau     := <span class="code-num">15</span>    <span class="code-comment">// Toleransi tumpang tindih</span>

    <span class="code-var">jadwalEksisting</span> := Database.<span class="code-func">GetReservations</span>(terapisId, tgl, status != <span class="code-num">"Dibatalkan"</span>)
    <span class="code-var">kandidatLayak</span>   := <span class="code-keyword">new</span> List()

    <span class="code-var">S_cand</span> := T_buka
    <span class="code-keyword">While</span> (S_cand + D_total &lt;= T_tutup) <span class="code-keyword">Do</span>
        <span class="code-var">E_cand</span>    := S_cand + D_total
        <span class="code-var">isBentrok</span> := <span class="code-keyword">false</span>

        <span class="code-comment">// Uji bentrok terhadap seluruh jadwal terapis yang sudah ada</span>
        <span class="code-keyword">ForEach</span> <span class="code-var">R</span> <span class="code-keyword">In</span> <span class="code-var">jadwalEksisting</span> <span class="code-keyword">Do</span>
            <span class="code-var">delta</span> := <span class="code-func">Max</span>(<span class="code-num">0</span>, <span class="code-func">Min</span>(E_cand, R.jam_selesai) - <span class="code-func">Max</span>(S_cand, R.jam_mulai))
            <span class="code-keyword">If</span> (delta &gt; tau) <span class="code-keyword">Then</span>
                <span class="code-var">isBentrok</span> := <span class="code-keyword">true</span>
                <span class="code-keyword">Break</span>  <span class="code-comment">// Early pruning jika salah satu bentrok</span>
            <span class="code-keyword">EndIf</span>
        <span class="code-keyword">EndForEach</span>

        <span class="code-comment">// Seleksi Greedy: Catat slot yang layak dan hitung cost jaraknya</span>
        <span class="code-keyword">If</span> (<span class="code-keyword">Not</span> isBentrok) <span class="code-keyword">Then</span>
            <span class="code-var">jarak</span> := <span class="code-func">Abs</span>(S_cand - S_req)
            kandidatLayak.<span class="code-func">Append</span>({ <span class="code-num">"jam_mulai"</span>: S_cand, <span class="code-num">"jam_selesai"</span>: E_cand, <span class="code-num">"jarak"</span>: jarak })
        <span class="code-keyword">EndIf</span>

        S_cand := S_cand + step
    <span class="code-keyword">EndWhile</span>

    <span class="code-comment">// Urutkan kandidat berdasarkan jarak terdekat (Greedy Sort)</span>
    <span class="code-func">SortAscending</span>(kandidatLayak, <span class="code-keyword">comparator</span> = (a, b) =&gt; a.jarak &lt;=&gt; b.jarak)

    <span class="code-comment">// Kembalikan k slot teratas</span>
    <span class="code-keyword">Return</span> kandidatLayak.<span class="code-func">Take</span>(k)
</div>

<h2>4.4 Analisis Kompleksitas Waktu (Time Complexity - Big O Notation)</h2>
<p>
  Untuk menguji kelayakan algoritma pada lingkungan produksi web, analisis kompleksitas asimptotik dihitung sebagai berikut:
</p>
<ol>
  <li>
    <strong>Jumlah Langkah Window ($M$):</strong> Rentang operasional salon adalah dari pukul 08:00 hingga 17:00 (durasi total $17 - 8 = 9$ jam $= 540$ menit). Dengan langkah pergeseran $\delta = 15$ menit, jumlah total perulangan *sliding window* maksimum adalah:
    $$M = \left\lfloor \frac{T_{\text{tutup}} - T_{\text{buka}} - D_{\text{total}}}{\delta} \right\rfloor + 1 \le \frac{540}{15} = 36 \text{ iterasi}$$
    Secara teori komputasi, karena nilai $T_{\text{buka}}$, $T_{\text{tutup}}$, dan $\delta$ memiliki batas atas konstan pada domain operasional salon, maka $M \le 36 \implies M = O(1)$ dalam konteks operasional harian.
  </li>
  <li>
    <strong>Uji Kelayakan per Slot ($O(N)$):</strong> Misalkan $N$ adalah jumlah reservasi aktif seorang terapis pada tanggal tertentu. Pada salon fisik, $N$ bernilai terbatas (lazimnya $N \le 10$ reservasi per hari). Pada setiap jendela, algoritma membandingkan slot kandidat terhadap $N$ data eksisting. Dalam kasus terburuk (*worst-case*), langkah ini memakan waktu $O(N)$ operasi. Total komparasi untuk $M$ jendela adalah $O(M \cdot N)$.
  </li>
  <li>
    <strong>Tahap Pengurutan Greedy ($O(M \log M)$):</strong> Himpunan kandidat layak yang berhasil dihimpun memiliki ukuran paling banyak $M$ elemen. Mengurutkan himpunan ini menggunakan algoritma *Quicksort* atau *Timsort* (standar pada PHP 8.2) memerlukan waktu $O(M \log M)$. Karena $M \le 36$, $36 \log_2(36) \approx 186$ operasi perbandingan, yang dapat diselesaikan oleh prosesor modern dalam skala sub-milidetik.
  </li>
</ol>
<div class="formula-box">
  <div class="formula-title">Total Kompleksitas Waktu (Worst-Case Time Complexity):</div>
  T(N, M) = O( M * N  +  M * log M )
</div>
<p>
  Karena $M$ dibatasi oleh konstanta waktu buka fisik salon ($M \le 36$), maka secara akademis kompleksitas tereduksi menjadi <strong>$O(N)$</strong> (linear murni terhadap jumlah reservasi harian terapis).
</p>

<h2>4.5 Analisis Kompleksitas Ruang (Space Complexity)</h2>
<p>
  Kebutuhan memori tambahan dari algoritma ini adalah struktur data penampung kandidat layak $\mathcal{C}$. Jumlah elemen yang disimpan paling banyak adalah $M$ buah tupel yang berisi integer sederhana. Oleh karena itu, kompleksitas ruang adalah:
</p>
<div class="formula-box">
  <div class="formula-title">Kompleksitas Ruang (Space Complexity):</div>
  S(M) = O(M)
</div>
<p>
  Ukuran memori yang dialokasikan tidak melebihi 10 Kilobyte pada memori runtime PHP, membuktikan bahwa algoritma ini sangat ringan, stabil, dan ramah terhadap *concurrency* server web.
</p>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB V: PEMODELAN DIAGRAM SISTEM (UML & FLOWCHART) -->
<!-- ========================================================================= -->
<h1>BAB V: PEMODELAN DIAGRAM SISTEM (UML &amp; FLOWCHART)</h1>

<p>
  Pemodelan diagram disusun untuk memvisualisasikan arsitektur fungsional, interaksi alur kerja, dan eksekusi logika algoritma secara komprehensif bagi kebutuhan penulisan Bab 3 dan Bab 4 Skripsi.
</p>

<h2>5.1 Use Case Diagram Sistem Reservasi</h2>
<p>
  Diagram Use Case mendeskripsikan batas sistem (*system boundary*) beserta keterhubungan antara Aktor Pelanggan (Member), Tamu (Guest), dan Admin Salon dengan fungsionalitas sistem. Hubungan <span style="font-family: monospace;">&lt;&lt;include&gt;&gt;</span> menunjukkan bahwa proses "Booking Multi-Layanan" secara wajib memanggil "Deteksi Interval Bentrok", sementara hubungan <span style="font-family: monospace;">&lt;&lt;extend&gt;&gt;</span> menunjukkan bahwa "Rekomendasi Slot Terdekat" hanya dieksekusi secara kondisional apabila kondisi bentrok terpenuhi.
</p>

<div class="figure-container">
  <img src="' . $b64UseCase . '" class="figure-img" alt="Use Case Diagram Azzahra">
  <div class="figure-caption">Gambar 5.1 Use Case Diagram Sistem Reservasi Azzahra Salon &amp; Spa Muslimah</div>
</div>

<div class="page-break"></div>

<h2>5.2 Activity Diagram Pemesanan &amp; Penjadwalan</h2>
<p>
  Activity Diagram berikut menggambarkan koordinasi alur kerja lintas komponen (*swimlanes*): Pelanggan/Tamu, Web Controller, Service Penjadwalan, dan Basis Data MySQL. Diagram mendemonstrasikan bagaimana percabangan bentrok diresolusi hingga penyimpanan transaksi dengan pola Snapshot.
</p>

<div class="figure-container">
  <img src="' . $b64Activity . '" class="figure-img" alt="Activity Diagram Penjadwalan">
  <div class="figure-caption">Gambar 5.2 Activity Diagram Alur Reservasi, Deteksi Interval, dan Penyelesaian Bentrok</div>
</div>

<div class="page-break"></div>

<h2>5.3 Flowchart Detail Algoritma Penjadwalan Terintegrasi</h2>
<p>
  Flowchart berikut memodelkan struktur kontrol detail, pengujian kondisi matematika interval overlap, percabangan status toleransi waktu tunggu, hingga proses iterasi *sliding window* dan pengurutan Greedy.
</p>

<div class="figure-container">
  <img src="' . $b64Flowchart . '" class="figure-img" alt="Flowchart Algoritma">
  <div class="figure-caption">Gambar 5.3 Flowchart Algoritma Deteksi Interval &amp; Greedy Nearest-Slot Search</div>
</div>

<div class="page-break"></div>

<!-- ========================================================================= -->
<!-- BAB VI: KESIMPULAN TEKNIS -->
<!-- ========================================================================= -->
<h1>BAB VI: KESIMPULAN TEKNIS DAN IMPLIKASI PENGEMBANGAN</h1>

<h2>6.1 Keunggulan Pendekatan Algoritma yang Diterapkan</h2>
<p>
  Berdasarkan rancangan teknis dan pengujian formal yang telah dipaparkan pada dokumen ini, integrasi algoritma <em>Interval Intersection Algebra</em> dan <em>Greedy Nearest-Slot Search</em> pada Sistem Reservasi Azzahra Salon dan Spa Muslimah memberikan sejumlah keunggulan komparatif:
</p>
<ol>
  <li>
    <strong>Jaminan Eliminasi Double-Booking (Zero-Conflict Guarantee):</strong> Melalui evaluasi relasi interval temporal deterministik $(S_A < E_B) \land (E_A > S_B)$, sistem secara mutlak mencegah terjadinya dua transaksi berstatus aktif yang tumpang tindih melebihi batas toleransi pada satu terapis yang sama.
  </li>
  <li>
    <strong>Fleksibilitas Operasional Berbasis Realitas Salon:</strong> Ambang batas toleransi $\tau = 15$ menit memberikan kelonggaran operasional yang realistis tanpa mengorbankan kualitas layanan, mencerminkan kondisi lapangan di mana jeda pengerjaan antar pelanggan seringkali memiliki waktu persiapan (*preparation buffer*).
  </li>
  <li>
    <strong>Meningkatkan Retensi Pelanggan (Customer Retention):</strong> Alih-alih menampilkan pesan penolakan yang kaku saat terjadi bentrok, algoritma *Greedy Nearest-Slot* segera menyajikan 3 hingga 5 alternatif slot terbaik yang paling mendekati preferensi waktu awal pengguna, mempermudah pelanggan dalam menentukan jadwal pengganti tanpa harus menebak-nebak jadwal kosong secara manual.
  </li>
  <li>
    <strong>Efisiensi Komputasi Tinggi ($O(N)$):</strong> Kompleksitas waktu linear murni memastikan respon server tetap sangat cepat (&lt; 15 milidetik) pada saat pengecekan jadwal secara *asynchronous* (AJAX/Fetch) di peramban web pengguna, bahkan diakses oleh banyak pengunjung secara bersamaan (*concurrent access*).
  </li>
  <li>
    <strong>Kekokohan Integritas Data (Data Robustness):</strong> Penggunaan normalisasi menit bulat sejak tengah malam dan pola *Snapshot Pattern* pada tabel pivot <span style="font-family: monospace;">reservasi_perawatan</span> menjamin keakuratan laporan keuangan dan audit pengerjaan terapis sepanjang masa.
  </li>
</ol>

<h2>6.2 Rekomendasi Penggunaan Dokumen pada Naskah Skripsi</h2>
<p class="no-indent">
  Dokumen spesifikasi teknis ini dirancang secara khusus untuk dijadikan referensi substantif dalam penulisan draf skripsi Sarjana Komputer:
</p>
<ul>
  <li><strong>Pada Bab 3 (Metodologi Penelitian &amp; Perancangan Sistem):</strong> Seluruh pemodelan matematis (Bab II, III, dan IV dokumen ini) serta diagram UML Use Case dan Activity Diagram (Bab V) dapat langsung disitasi sebagai dasar perancangan sistem dan formulasi logika algoritma.</li>
  <li><strong>Pada Bab 4 (Implementasi &amp; Pengujian Sistem):</strong> Pseudocode formal, implementasi snapshot pattern pada service Laravel (<span style="font-family: monospace;">PenjadwalanReservasi.php</span>), dan hasil analisis kompleksitas waktu Big-O dapat digunakan sebagai pembuktian ilmiah pada subbab pengujian algoritma (*algorithmic efficiency testing*) serta pengujian fungsionalitas sistem.</li>
</ul>

<br><br>
<div style="text-align: right; font-size: 9.5pt; color: #475569;">
  Yogyakarta, 17 November 2026<br>
  <strong>Tim Pengembang &amp; Penulis Skripsi</strong><br><br><br><br>
  ( Afga )
</div>

</body>
</html>';

// Load HTML ke Dompdf
$dompdf->loadHtml($htmlContent);

// Konfigurasi Kertas A4 Portrait
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Tambahkan Penomoran Halaman Otomatis di Footer menggunakan Canvas API Dompdf
$canvas = $dompdf->getCanvas();
// canvas->page_text(x, y, text, font, size, color)
$canvas->page_text(260, 805, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", null, 8.5, [0.35, 0.4, 0.45]);
$canvas->page_text(50, 805, "Azzahra Salon & Spa - Dokumen Spesifikasi Teknis", null, 8, [0.45, 0.5, 0.55]);

// Simpan File PDF
$outputPath = __DIR__ . '/Dokumen_Spesifikasi_Teknis_dan_Algoritma_Azzahra.pdf';
file_put_contents($outputPath, $dompdf->output());

echo "=== GENERASI DOKUMEN PDF BERHASIL ===\n";
echo "Lokasi File : " . $outputPath . "\n";
echo "Ukuran File : " . number_format(filesize($outputPath) / 1024, 2) . " KB\n";
echo "Waktu Selesai: " . date('Y-m-d H:i:s') . "\n";
