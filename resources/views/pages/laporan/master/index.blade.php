@extends('layouts.app')

@section('title', 'Laporan Master')

@section('content')

<div class="container-fluid px-4 bulanan-elite master-elite">

    {{-- HEADER --}}
    <div class="form-header-elite mb-4">
        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left">
            <div class="header-icon-elite"><i class="fas fa-layer-group"></i></div>
            <div>
                <h4 class="fw-bold mb-1 text-white">Laporan Master</h4>
                <p class="text-white-50 mb-0 small">Laporan seluruh data sesuai kategori yang dipilih</p>
            </div>
        </div>

        <div class="header-actions-elite">
            <a href="{{ route('laporan_master.laporan.pdf', request()->query()) }}" target="_blank"
               class="btn btn-outline-elite-onheader">
                <i class="fas fa-file-pdf mr-1"></i> Laporan PDF
            </a>

            <a href="{{ route('master.create') }}" class="btn btn-header-solid-elite">
                <i class="fas fa-plus mr-1"></i> Buat Laporan
            </a>
        </div>
    </div>


    {{-- RINGKASAN --}}
    <div class="row mb-2">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-maroon-elite"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($jumlahLaporan, 0, ',', '.') }}</div>
                    <div class="mini-stat-label-elite">Jumlah Laporan</div>
                    <div class="cell-sub-elite">Sesuai filter yang aktif</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-gold-elite"><i class="fas fa-pen"></i></div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($jumlahDraft, 0, ',', '.') }}</div>
                    <div class="mini-stat-label-elite">Draft</div>
                    <div class="cell-sub-elite">Laporan belum final</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-success-elite"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($jumlahFinal, 0, ',', '.') }}</div>
                    <div class="mini-stat-label-elite">Final</div>
                    <div class="cell-sub-elite">Laporan sudah final</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="mini-stat-elite">
                <div class="mini-stat-icon-elite icon-info-elite"><i class="fas fa-folder-open"></i></div>
                <div>
                    <div class="mini-stat-value-elite">{{ number_format($jumlahKategori, 0, ',', '.') }}</div>
                    <div class="mini-stat-label-elite">Kategori Dipakai</div>
                    <div class="cell-sub-elite">Kategori berbeda pada laporan</div>
                </div>
            </div>
        </div>

    </div>


    {{-- FILTER --}}
    <div class="card-elite mb-4">
        <div class="card-body p-4">

            <div class="section-head-elite mb-3">
                <div class="section-icon-elite"><i class="fas fa-filter"></i></div>
                <div>
                    <h6 class="section-title-elite">Filter Laporan</h6>
                    <small class="section-subtitle-elite">Cari berdasarkan kode, kategori, atau status</small>
                </div>
            </div>

            <form method="GET" action="{{ route('master.index') }}" class="mt-3">
                <div class="row">

                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="katakunci" class="form-label-elite">Kata Kunci</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-search"></i></span>
                            <input type="text" name="katakunci" id="katakunci" class="form-control-elite"
                                   value="{{ request('katakunci') }}" placeholder="Kode / catatan...">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="kategori" class="form-label-elite">Kategori</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-folder-open"></i></span>
                            <select name="kategori" id="kategori" class="form-control-elite">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoriList as $kunci => $item)
                                    <option value="{{ $kunci }}" @selected(request('kategori') === $kunci)>{{ $item['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="status" class="form-label-elite">Status</label>
                        <div class="input-group-elite">
                            <span class="input-icon-box-elite"><i class="fas fa-toggle-on"></i></span>
                            <select name="status" id="status" class="form-control-elite">
                                <option value="">Semua Status</option>
                                <option value="Draft" @selected(request('status') === 'Draft')>Draft</option>
                                <option value="Final" @selected(request('status') === 'Final')>Final</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="per_page" class="form-label-elite">Data</label>
                        <div class="input-group-elite">
                            <select name="per_page" id="per_page" class="form-control-elite" style="padding-left:.85rem;">
                                @foreach ([5, 10, 25, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('master.index') }}" class="btn btn-outline-elite px-4 mr-2">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-gradient-elite px-4">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- TABEL --}}
    <div class="card-elite mb-4">

        <div class="table-card-header-elite">
            <div class="section-head-elite">
                <div class="section-icon-elite"><i class="fas fa-list"></i></div>
                <div>
                    <h6 class="section-title-elite">Daftar Laporan Master</h6>
                    <small class="section-subtitle-elite">Setiap laporan menampilkan seluruh data satu kategori</small>
                </div>
            </div>

            <span class="badge-pill-elite badge-maroon-elite">{{ $data->total() }} data</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-elite" width="100%" cellspacing="0" style="min-width:900px;">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Periode</th>
                            <th>Jumlah Data</th>
                            <th>Total Nominal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $item)

                            @php
                                $periode = ($item->tanggal_mulai || $item->tanggal_selesai)
                                    ? ($item->tanggal_mulai?->format('d/m/Y') ?? '...') . ' - ' . ($item->tanggal_selesai?->format('d/m/Y') ?? 'sekarang')
                                    : 'Semua data';
                            @endphp

                            <tr>
                                <td class="nowrap-elite"><strong class="money-total-elite">{{ $item->kode_laporan }}</strong></td>

                                <td>
                                    <span class="kategori-pill-elite">
                                        <i class="fas {{ $item->kategori_ikon }}"></i> {{ $item->kategori_label }}
                                    </span>
                                    @unless ($item->kategori_ada)
                                        <div class="cell-sub-elite">Kategori tidak tersedia</div>
                                    @endunless
                                </td>

                                <td class="nowrap-elite">{{ $periode }}</td>

                                <td class="nowrap-elite">
                                    <strong class="money-total-elite">{{ number_format($item->jumlah_data, 0, ',', '.') }}</strong>
                                </td>

                                <td class="nowrap-elite">
                                    @if ($item->kolom_nominal)
                                        <strong class="money-total-elite">Rp {{ number_format($item->total_nominal, 0, ',', '.') }}</strong>
                                        <div class="cell-sub-elite">{{ $item->kolom_nominal }}</div>
                                    @else
                                        <span class="text-muted-elite">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->status === 'Final')
                                        <span class="badge-pill-elite badge-active-elite"><i class="fas fa-check mr-1"></i> {{ $item->status }}</span>
                                    @else
                                        <span class="badge-pill-elite badge-warning-elite"><i class="fas fa-pen mr-1"></i> {{ $item->status }}</span>
                                    @endif
                                </td>

                                <td class="text-center nowrap-elite">
                                    <a href="{{ route('master.show', $item->id) }}" class="btn-action-elite action-view-elite"
                                       title="Detail" aria-label="Lihat detail laporan"><i class="fas fa-eye"></i></a>

                                    <a href="{{ route('master.edit', $item->id) }}" class="btn-action-elite action-edit-elite"
                                       title="Edit" aria-label="Edit laporan"><i class="fas fa-edit"></i></a>

                                    <form action="{{ route('master.destroy', $item->id) }}" method="POST" class="d-inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-elite action-delete-elite"
                                                title="Hapus" aria-label="Hapus laporan"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state-elite">
                                        <div class="empty-icon-elite"><i class="fas fa-layer-group"></i></div>
                                        <h6>Belum ada laporan master</h6>
                                        <p>Buat laporan untuk menampilkan seluruh data sebuah kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if ($data->hasPages())
                <div class="table-footer-elite">
                    <div class="text-muted-elite small">
                        Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data
                    </div>
                    <div class="pagination-elite">{{ $data->links() }}</div>
                </div>
            @endif
        </div>

    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/master.css') }}">
@endpush

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.form-hapus').forEach(function (form) {

    form.addEventListener('submit', function (e) {

        e.preventDefault();

        Swal.fire({
            title: 'Hapus laporan?',
            text: 'Data laporan master akan dihapus. Data aslinya tidak terpengaruh.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e01c1d',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

});

@if (session('success'))
Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')), confirmButtonColor: '#e01c1d' });
@endif

@if (session('error'))
Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('error')), confirmButtonColor: '#e01c1d' });
@endif
</script>

@endpush