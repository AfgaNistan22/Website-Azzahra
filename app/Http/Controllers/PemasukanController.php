<?php

namespace App\Http\Controllers;

use App\Http\Requests\PemasukanStoreRequest;
use App\Http\Requests\PemasukanUpdateRequest;
use App\Models\Pemasukan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $query = Pemasukan::with('pembayaran.reservasi.pelanggan')
            ->latest('tanggal')
            ->latest('id');

        if ($request->filled('katakunci')) {
            $keyword = $request->katakunci;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pemasukan', 'like', "%{$keyword}%")
                    ->orWhere('kategori', 'like', "%{$keyword}%")
                    ->orWhere('sumber', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('sumber_jenis') && in_array($request->sumber_jenis, ['Reservasi', 'Manual'], true)) {
            $query->where('sumber_jenis', $request->sumber_jenis);
        }

        if ($request->filled('status') && in_array($request->status, ['Diterima', 'Dibatalkan'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $data = $query->paginate($perPage)->withQueryString();

        $summaryQuery = Pemasukan::query();
        if ($request->filled('tanggal_mulai')) {
            $summaryQuery->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $summaryQuery->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $totalDiterima = (clone $summaryQuery)->where('status', 'Diterima')->sum('nominal');
        $totalDibatalkan = (clone $summaryQuery)->where('status', 'Dibatalkan')->sum('nominal');
        $totalReservasi = (clone $summaryQuery)->where('status', 'Diterima')->where('sumber_jenis', 'Reservasi')->sum('nominal');
        $totalManual = (clone $summaryQuery)->where('status', 'Diterima')->where('sumber_jenis', 'Manual')->sum('nominal');

        return view('pages.pemasukan.index', compact(
            'data',
            'totalDiterima',
            'totalDibatalkan',
            'totalReservasi',
            'totalManual'
        ));
    }

    public function create()
    {
        return view('pages.pemasukan.create');
    }

    public function store(PemasukanStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $pemasukan = Pemasukan::create([
                'kode_pemasukan' => 'TEMP-' . uniqid(),
                'pembayaran_id' => null,
                'sumber_jenis' => 'Manual',
                'tanggal' => $request->validated('tanggal'),
                'kategori' => $request->validated('kategori'),
                'sumber' => $request->validated('sumber'),
                'nominal' => $request->validated('nominal'),
                'metode_pembayaran' => $request->validated('metode_pembayaran'),
                'status' => $request->validated('status'),
                'keterangan' => $request->validated('keterangan'),
            ]);

            $pemasukan->update([
                'kode_pemasukan' => $this->generateKode($pemasukan),
            ]);

            DB::commit();

            return redirect()->route('pemasukan.index')
                ->with('success', 'Pemasukan manual berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan pemasukan manual', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Pemasukan gagal ditambahkan.');
        }
    }

    public function show(Pemasukan $pemasukan)
    {
        $pemasukan->load('pembayaran.reservasi.pelanggan');

        return view('pages.pemasukan.detail', ['data' => $pemasukan]);
    }

    public function edit(Pemasukan $pemasukan)
    {
        // Pemasukan dari reservasi dikendalikan oleh pembayaran.
        if ($pemasukan->sumber_jenis === 'Reservasi') {
            return redirect()->route('pemasukan.show', $pemasukan)
                ->with('warning', 'Pemasukan dari reservasi dikelola otomatis melalui data pembayaran.');
        }

        return view('pages.pemasukan.edit', ['data' => $pemasukan]);
    }

    public function update(PemasukanUpdateRequest $request, Pemasukan $pemasukan)
    {
        if ($pemasukan->sumber_jenis === 'Reservasi') {
            return redirect()->route('pemasukan.show', $pemasukan)
                ->with('warning', 'Pemasukan dari reservasi tidak dapat diedit secara manual.');
        }

        try {
            $pemasukan->update($request->validated());

            return redirect()->route('pemasukan.show', $pemasukan)
                ->with('success', 'Pemasukan manual berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui pemasukan', ['id' => $pemasukan->id, 'error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Pemasukan gagal diperbarui.');
        }
    }

    public function destroy(Pemasukan $pemasukan)
    {
        if ($pemasukan->sumber_jenis === 'Reservasi') {
            return back()->with('warning', 'Pemasukan dari reservasi tidak dapat dihapus dari menu ini.');
        }

        try {
            $pemasukan->delete();

            return redirect()->route('pemasukan.index')
                ->with('success', 'Pemasukan manual berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus pemasukan', ['id' => $pemasukan->id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Pemasukan gagal dihapus.');
        }
    }

    public function laporanPdf(Request $request)
    {
        $query = Pemasukan::with('pembayaran.reservasi.pelanggan')->latest('tanggal')->latest('id');

        if ($request->filled('katakunci')) {
            $keyword = $request->katakunci;
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pemasukan', 'like', "%{$keyword}%")
                    ->orWhere('kategori', 'like', "%{$keyword}%")
                    ->orWhere('sumber', 'like', "%{$keyword}%")
                    ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('sumber_jenis')) {
            $query->where('sumber_jenis', $request->sumber_jenis);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $data = $query->get();
        $totalDiterima = $data->where('status', 'Diterima')->sum('nominal');
        $totalDibatalkan = $data->where('status', 'Dibatalkan')->sum('nominal');

        $pdf = Pdf::loadView('pages.pemasukan.laporan_pdf', compact(
            'data',
            'totalDiterima',
            'totalDibatalkan'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pemasukan-' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    /**
     * Dipanggil dari PembayaranController saat pembayaran menjadi Lunas.
     * Method dibuat idempotent agar satu pembayaran tidak membuat pemasukan ganda.
     */
    public static function syncFromPembayaran($pembayaran): Pemasukan
    {
        $pembayaran->loadMissing('reservasi.pelanggan');

        return DB::transaction(function () use ($pembayaran) {
            $nominal = (int) $pembayaran->jumlah;
            $tanggal = $pembayaran->tanggal_bayar ?? optional($pembayaran->reservasi)->tanggal ?? now()->toDateString();
            $reservasi = $pembayaran->reservasi;

            $pemasukan = Pemasukan::firstOrNew([
                'pembayaran_id' => $pembayaran->id,
            ]);

            $pemasukan->fill([
                'sumber_jenis' => 'Reservasi',
                'tanggal' => $tanggal,
                'kategori' => 'Pembayaran Reservasi',
                'sumber' => $reservasi?->kode_reservasi ?? ('Pembayaran #' . $pembayaran->id),
                'nominal' => $nominal,
                'metode_pembayaran' => $pembayaran->metode_pembayaran,
                'status' => 'Diterima',
                'keterangan' => 'Pemasukan otomatis dari pembayaran reservasi yang telah lunas.',
            ]);

            if (!$pemasukan->exists) {
                $pemasukan->kode_pemasukan = 'TEMP-' . uniqid();
                $pemasukan->save();
                $pemasukan->update([
                    'kode_pemasukan' => self::makeKode($pemasukan),
                ]);
            } else {
                $pemasukan->save();
            }

            return $pemasukan->fresh();
        });
    }

    public static function cancelFromPembayaran($pembayaran): ?Pemasukan
    {
        $pemasukan = Pemasukan::where('pembayaran_id', $pembayaran->id)->first();

        if (!$pemasukan) {
            return null;
        }

        $pemasukan->update([
            'status' => 'Dibatalkan',
            'keterangan' => 'Pemasukan dibatalkan karena status pembayaran tidak lagi Lunas.',
        ]);

        return $pemasukan->fresh();
    }

    private function generateKode(Pemasukan $pemasukan): string
    {
        return self::makeKode($pemasukan);
    }

    private static function makeKode(Pemasukan $pemasukan): string
    {
        $tanggal = Carbon::parse($pemasukan->tanggal)->format('Ymd');

        return 'PMK-' . $tanggal . '-' . str_pad((string) $pemasukan->id, 4, '0', STR_PAD_LEFT);
    }
}
