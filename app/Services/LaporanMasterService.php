<?php

namespace App\Services;

use App\Models\LaporanMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class LaporanMasterService
{
    /** Kolom yang tidak pernah ditampilkan otomatis. */
    private const KECUALIKAN_DEFAULT = [
        'id', 'password', 'remember_token', 'api_token', 'token',
        'foto', 'gambar', 'created_at', 'updated_at', 'deleted_at', 'email_verified_at',
    ];

    /** Kata pada nama kolom yang dianggap nominal uang. */
    private const KATA_UANG = [
        'nominal', 'harga', 'tarif', 'biaya', 'gaji', 'diskon', 'potongan', 'pajak',
        'tunjangan', 'bonus', 'upah', 'honor', 'saldo', 'komisi', 'total',
    ];

    /** Cache per request supaya struktur tabel tidak dibaca berulang. */
    private ?array $kategoriCache = null;
    private array $metaCache = [];

    /**
     * DAFTAR KATEGORI. Tambah, hapus, atau sesuaikan di sini.
     *
     * Opsi per kategori (semuanya opsional):
     *   'kolom'      => ['nama' => 'Nama', 'cabang.nama' => 'Cabang']  kolom + judul (titik = relasi)
     *   'kecualikan' => ['kolom_yang_disembunyikan']
     *   'tanggal'    => 'nama_kolom_tanggal'   kolom untuk rentang tanggal
     *   'urut'       => ['nama', 'asc']
     */
    public function daftarKategori(): array
    {
        if ($this->kategoriCache !== null) {
            return $this->kategoriCache;
        }

        $semua = [
            'pelanggan'          => ['label' => 'Pelanggan',          'ikon' => 'fa-users',           'model' => \App\Models\Pelanggan::class],
            'pegawai'            => ['label' => 'Pegawai',            'ikon' => 'fa-user-tie',        'model' => \App\Models\Pegawai::class],
            'cabang'             => ['label' => 'Cabang',             'ikon' => 'fa-store',           'model' => \App\Models\Cabang::class],
            'kategori_perawatan' => ['label' => 'Kategori Perawatan', 'ikon' => 'fa-tags',            'model' => \App\Models\KategoriPerawatan::class],
            'perawatan'          => ['label' => 'Perawatan',          'ikon' => 'fa-spa',             'model' => \App\Models\Perawatan::class],
            'reservasi'          => ['label' => 'Reservasi',          'ikon' => 'fa-calendar-check',  'model' => \App\Models\Reservasi::class],
            'pembayaran'         => ['label' => 'Pembayaran',         'ikon' => 'fa-cash-register',   'model' => \App\Models\Pembayaran::class],
            'pemasukan'          => ['label' => 'Pemasukan',          'ikon' => 'fa-arrow-down',      'model' => \App\Models\Pemasukan::class],
            'pengeluaran'        => ['label' => 'Pengeluaran',        'ikon' => 'fa-arrow-up',        'model' => \App\Models\Pengeluaran::class],
            'gaji'               => ['label' => 'Gaji',               'ikon' => 'fa-money-bill-wave', 'model' => \App\Models\Gaji::class],
            'pajak'              => ['label' => 'Pajak',              'ikon' => 'fa-percent',         'model' => \App\Models\Pajak::class],
        ];

        return $this->kategoriCache = array_filter($semua, fn ($k) => class_exists($k['model']));
    }

    public function kategori(string $kunci): ?array
    {
        return $this->daftarKategori()[$kunci] ?? null;
    }

    /** Apakah kategori ini punya kolom tanggal (untuk rentang tanggal laporan). */
    public function adaKolomTanggal(string $kunci): bool
    {
        $kat = $this->kategori($kunci);

        return $kat ? (bool) $this->meta($kat)['kolomTanggal'] : false;
    }

    /**
     * Sudah ada laporan dengan kategori + rentang tanggal yang sama?
     * Kategori tanpa kolom tanggal selalu dianggap "semua data".
     */
    public function sudahAda(
        string $kategori,
        ?string $mulai = null,
        ?string $selesai = null,
        ?int $abaikanId = null
    ): bool {
        if (!$this->adaKolomTanggal($kategori)) {
            $mulai   = null;
            $selesai = null;
        }

        return LaporanMaster::query()
            ->where('kategori', $kategori)
            ->when($mulai, fn ($q) => $q->whereDate('tanggal_mulai', $mulai), fn ($q) => $q->whereNull('tanggal_mulai'))
            ->when($selesai, fn ($q) => $q->whereDate('tanggal_selesai', $selesai), fn ($q) => $q->whereNull('tanggal_selesai'))
            ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
            ->exists();
    }

    /**
     * Info kolom satu kategori.
     */
    public function meta(array $kat): array
    {
        $kunciCache = md5(serialize($kat));

        if (isset($this->metaCache[$kunciCache])) {
            return $this->metaCache[$kunciCache];
        }

        $model    = new $kat['model'];
        $adaKolom = Schema::getColumnListing($model->getTable());

        if (!empty($kat['kolom'])) {
            $kolom = $kat['kolom'];
        } else {
            $kecualikan = array_merge(self::KECUALIKAN_DEFAULT, $kat['kecualikan'] ?? []);
            $kolom      = [];

            foreach ($adaKolom as $c) {
                if (!in_array($c, $kecualikan, true)) {
                    $kolom[$c] = $this->labelKolom($c);
                }
            }
        }

        $kolomTanggal = $kat['tanggal'] ?? collect([
            'tanggal', 'tgl', 'tanggal_reservasi', 'tanggal_bayar', 'tanggal_transaksi', 'created_at',
        ])->first(fn ($c) => in_array($c, $adaKolom, true));

        $kunciKolom = array_keys($kolom);

        $kolomUang = collect($kunciKolom)
            ->filter(fn ($k) => !str_contains($k, '.') && in_array($k, $adaKolom, true) && $this->kolomUang($k))
            ->take(3)
            ->values()
            ->all();

        $relasi = collect($kunciKolom)
            ->filter(fn ($k) => str_contains($k, '.'))
            ->map(fn ($k) => Str::before($k, '.'))
            ->unique()
            ->values()
            ->all();

        return $this->metaCache[$kunciCache] = [
            'kolom'        => $kolom,
            'adaKolom'     => $adaKolom,
            'kolomTanggal' => $kolomTanggal,
            'kolomUang'    => $kolomUang,
            'relasi'       => $relasi,
        ];
    }

    /**
     * Query seluruh data kategori (opsional dibatasi rentang tanggal). Tanpa urutan.
     */
    public function query(array $kat, array $meta, ?string $mulai = null, ?string $selesai = null): Builder
    {
        $query = $kat['model']::query();

        if (!empty($meta['relasi'])) {
            $query->with($meta['relasi']);
        }

        if ($meta['kolomTanggal']) {
            if ($mulai) {
                $query->whereDate($meta['kolomTanggal'], '>=', $mulai);
            }

            if ($selesai) {
                $query->whereDate($meta['kolomTanggal'], '<=', $selesai);
            }
        }

        return $query;
    }

    public function urutkan(Builder $query, array $kat, array $meta): Builder
    {
        if (!empty($kat['urut'])) {
            return $query->orderBy($kat['urut'][0], $kat['urut'][1] ?? 'asc');
        }

        if (in_array('nama', $meta['adaKolom'], true)) {
            return $query->orderBy('nama');
        }

        if ($meta['kolomTanggal']) {
            $query->orderByDesc($meta['kolomTanggal']);
        }

        return $query->orderByDesc((new $kat['model'])->getKeyName());
    }

    /**
     * Jumlah tiap kolom nominal dari seluruh hasil query.
     *
     * @return array<string, float>
     */
    public function totalUang(Builder $query, array $meta): array
    {
        $hasil = [];

        foreach ($meta['kolomUang'] as $c) {
            $hasil[$c] = (float) (clone $query)->sum($c);
        }

        return $hasil;
    }

    /**
     * Ringkasan untuk disimpan/preview: jumlah data dan total nominal (kolom nominal pertama).
     */
    public function ringkasan(string $kunci, ?string $mulai = null, ?string $selesai = null): array
    {
        $kat = $this->kategori($kunci);

        if (!$kat) {
            throw new \InvalidArgumentException("Kategori '{$kunci}' tidak dikenal.");
        }

        $meta  = $this->meta($kat);
        $query = $this->query($kat, $meta, $mulai, $selesai);

        $totalUang    = $this->totalUang($query, $meta);
        $kolomPertama = array_key_first($totalUang);

        return [
            'jumlah_data'   => (clone $query)->count(),
            'kolom_nominal' => $kolomPertama ? $meta['kolom'][$kolomPertama] : null,
            'total_nominal' => $kolomPertama ? (int) round($totalUang[$kolomPertama]) : 0,
            'kolom'         => array_values($meta['kolom']),
            'ada_tanggal'   => (bool) $meta['kolomTanggal'],
            'kolom_tanggal' => $meta['kolomTanggal'],
        ];
    }

    /* ======================================================================
     | OTOMATIS
     ====================================================================== */

    /**
     * Buat laporan untuk satu kategori + rentang tanggal, atau perbarui angkanya kalau sudah ada.
     * Laporan berstatus Final tidak disentuh lagi.
     */
    public function buatAtauPerbarui(
        string $kategori,
        ?string $mulai = null,
        ?string $selesai = null,
        string $status = 'Draft',
        ?string $catatan = null
    ): LaporanMaster {
        // Kategori tanpa kolom tanggal selalu "semua data"
        if (!$this->adaKolomTanggal($kategori)) {
            $mulai   = null;
            $selesai = null;
        }

        $ringkas = $this->ringkasan($kategori, $mulai, $selesai);

        $angka = [
            'jumlah_data'   => $ringkas['jumlah_data'],
            'kolom_nominal' => $ringkas['kolom_nominal'],
            'total_nominal' => $ringkas['total_nominal'],
        ];

        return DB::transaction(function () use ($kategori, $mulai, $selesai, $status, $catatan, $angka) {
            $laporan = LaporanMaster::query()
                ->where('kategori', $kategori)
                ->when($mulai, fn ($q) => $q->whereDate('tanggal_mulai', $mulai), fn ($q) => $q->whereNull('tanggal_mulai'))
                ->when($selesai, fn ($q) => $q->whereDate('tanggal_selesai', $selesai), fn ($q) => $q->whereNull('tanggal_selesai'))
                ->lockForUpdate()
                ->first();

            if (!$laporan) {
                return LaporanMaster::create(array_merge($angka, [
                    'kode_laporan'    => LaporanMaster::generateKode(),
                    'kategori'        => $kategori,
                    'tanggal_mulai'   => $mulai,
                    'tanggal_selesai' => $selesai,
                    'status'          => $status,
                    'catatan'         => $catatan,
                ]));
            }

            if ($laporan->status === 'Final') {
                return $laporan;
            }

            $laporan->update(array_merge($angka, ['status' => $status]));

            return $laporan;
        });
    }

    /**
     * Buatkan laporan Draft "semua data" untuk kategori yang belum punya.
     * Dikunci supaya dua permintaan bersamaan tidak membuat laporan ganda.
     *
     * @return int jumlah laporan yang dibuat
     */
    public function sinkronkan(): int
    {
        $lock = null;

        try {
            $lock = Cache::lock('laporan-master-sinkron', 15);

            if (!$lock->get()) {
                return 0; // proses lain sedang membuat laporan
            }
        } catch (Throwable $e) {
            $lock = null; // driver cache tidak mendukung lock: lanjut tanpa lock
        }

        try {
            return $this->prosesSinkron();
        } finally {
            if ($lock) {
                $lock->release();
            }
        }
    }

    private function prosesSinkron(): int
    {
        $sudahAda = LaporanMaster::query()
            ->whereNull('tanggal_mulai')
            ->whereNull('tanggal_selesai')
            ->pluck('kategori')
            ->unique()
            ->all();

        $dibuat = 0;

        foreach (array_keys($this->daftarKategori()) as $kunci) {
            if (in_array($kunci, $sudahAda, true)) {
                continue;
            }

            try {
                $this->buatAtauPerbarui($kunci);
                $dibuat++;
            } catch (Throwable $e) {
                Log::warning('Gagal membuat laporan master otomatis.', [
                    'kategori' => $kunci,
                    'message'  => $e->getMessage(),
                ]);
            }
        }

        return $dibuat;
    }

    /**
     * Perbarui angka tersimpan semua laporan Draft.
     *
     * @return int jumlah laporan yang diperbarui
     */
    public function segarkanDraft(): int
    {
        $jumlah = 0;

        LaporanMaster::query()
            ->where('status', 'Draft')
            ->chunkById(100, function ($kumpulan) use (&$jumlah) {
                foreach ($kumpulan as $l) {
                    if (!$this->kategori($l->kategori)) {
                        continue;
                    }

                    try {
                        $r = $this->ringkasan(
                            $l->kategori,
                            $l->tanggal_mulai?->toDateString(),
                            $l->tanggal_selesai?->toDateString()
                        );

                        $l->update([
                            'jumlah_data'   => $r['jumlah_data'],
                            'kolom_nominal' => $r['kolom_nominal'],
                            'total_nominal' => $r['total_nominal'],
                        ]);

                        $jumlah++;
                    } catch (Throwable $e) {
                        Log::warning('Gagal menyegarkan laporan master.', [
                            'id'      => $l->id,
                            'message' => $e->getMessage(),
                        ]);
                    }
                }
            });

        return $jumlah;
    }

    /* ======================================================================
     | FORMAT TAMPILAN
     ====================================================================== */

    /**
     * Satu baris model menjadi daftar sel terformat, urut sesuai kolom.
     *
     * @return array<int, array{t: string, k: ?string}>
     */
    public function barisUntukTampil($row, array $kolom): array
    {
        $sel = [];

        foreach (array_keys($kolom) as $key) {
            $sel[] = $this->formatCell($key, data_get($row, $key));
        }

        return $sel;
    }

    /**
     * 't' = teks, 'k' = jenis tampilan: null (biasa), 'money', atau class badge untuk status.
     */
    private function formatCell(string $key, $nilai): array
    {
        $nama = Str::lower(Str::afterLast($key, '.'));

        if ($nilai === null || $nilai === '') {
            return ['t' => '-', 'k' => null];
        }

        if ($nilai instanceof \DateTimeInterface) {
            return ['t' => $this->formatTanggal(Carbon::instance($nilai)), 'k' => null];
        }

        if (is_bool($nilai)) {
            return ['t' => $nilai ? 'Ya' : 'Tidak', 'k' => null];
        }

        if (!is_scalar($nilai)) {
            return ['t' => (string) json_encode($nilai, JSON_UNESCAPED_UNICODE), 'k' => null];
        }

        $teks = trim((string) $nilai);

        if (preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}(:\d{2})?)?$/', $teks)) {
            try {
                return ['t' => $this->formatTanggal(Carbon::parse($teks)), 'k' => null];
            } catch (Throwable $e) {
                // Tanggal tidak valid (mis. 2026-13-45): tampilkan apa adanya
                return ['t' => $teks, 'k' => null];
            }
        }

        if (str_starts_with($nama, 'is_') && in_array($teks, ['0', '1'], true)) {
            return ['t' => $teks === '1' ? 'Ya' : 'Tidak', 'k' => null];
        }

        if ($nama === 'status') {
            $v = Str::lower($teks);

            $kelas = match (true) {
                in_array($v, ['diterima', 'final', 'aktif', 'lunas', 'selesai', 'berhasil', 'dikonfirmasi', 'terkonfirmasi', 'hadir'], true)
                    => 'badge-active-elite',
                in_array($v, ['draft', 'pending', 'menunggu', 'proses', 'belum lunas', 'dijadwalkan'], true)
                    => 'badge-warning-elite',
                default => 'badge-maroon-elite',
            };

            return ['t' => $teks, 'k' => $kelas];
        }

        // Angka berawalan 0 (mis. nomor HP) dibiarkan apa adanya
        if (is_numeric($teks) && !preg_match('/^0\d/', $teks)) {
            if (str_contains($nama, 'persen')) {
                return ['t' => rtrim(rtrim(number_format((float) $teks, 2, ',', '.'), '0'), ',') . '%', 'k' => null];
            }

            if ($this->kolomUang($nama)) {
                return ['t' => 'Rp ' . number_format((float) $teks, 0, ',', '.'), 'k' => 'money'];
            }
        }

        return ['t' => $teks, 'k' => null];
    }

    private function formatTanggal(Carbon $c): string
    {
        return $c->format('H:i:s') === '00:00:00' ? $c->format('d/m/Y') : $c->format('d/m/Y H:i');
    }

    private function labelKolom(string $kolom): string
    {
        return Str::of($kolom)->replace('_', ' ')->title()->toString();
    }

    private function kolomUang(string $nama): bool
    {
        $nama = Str::lower($nama);

        if (str_ends_with($nama, '_id') || str_contains($nama, 'persen')) {
            return false;
        }

        foreach (self::KATA_UANG as $kata) {
            if (str_contains($nama, $kata)) {
                return true;
            }
        }

        return false;
    }
}