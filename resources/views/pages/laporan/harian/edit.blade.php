@extends('layouts.app')

@section('title', 'Edit Laporan Harian')

@section('content')

<div class="container-fluid px-4 harian-form-elite">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="modern-header-elite mb-4">

        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">

            <div class="header-icon-elite">
                <i class="fas fa-edit"></i>
            </div>

            <div>
                <h1 class="page-title-elite">Edit Laporan Harian</h1>
                <p class="page-subtitle-elite">Perbarui status, tanggal, dan catatan laporan. Nilai keuangan dihitung otomatis dari transaksi</p>
            </div>

        </div>


        <div class="header-actions position-relative">

            <a href="{{ route('harian.index') }}"
               class="btn btn-outline-elite btn-modern-elite">

                <i class="fas fa-arrow-left mr-2"></i>
                Kembali

            </a>

        </div>

    </div>


    {{-- =========================================================
        CARD FORM
    ========================================================== --}}
    <div class="modern-card-elite mb-4">

        <div class="modern-card-body-elite">

            <form action="{{ route('harian.update', $data->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ========================= --}}
                    {{-- KOLOM KIRI: INFORMASI --}}
                    {{-- ========================= --}}
                    <div class="col-lg-5">

                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                            <div>
                                <div class="section-label-title-elite">Informasi Laporan</div>
                                <small class="section-label-subtitle-elite">Tanggal, status, dan catatan laporan</small>
                            </div>
                        </div>

                        {{-- KODE LAPORAN --}}
                        <div class="form-group-elite mb-3">

                            <label class="modern-label-elite">Kode Laporan</label>

                            <input type="text"
                                   class="form-control modern-input-elite input-readonly-elite"
                                   value="{{ $data->kode_laporan }}"
                                   readonly>

                        </div>



                        {{-- TANGGAL --}}
                        <div class="form-group-elite mb-3">

                            <label for="tanggal" class="modern-label-elite">
                                Tanggal Laporan <span class="text-danger">*</span>
                            </label>

                            <div class="modern-input-group-elite">

                                <i class="fas fa-calendar-alt input-icon-elite"></i>

                                <input type="date"
                                       name="tanggal"
                                       id="tanggal"
                                       class="form-control modern-input-elite @error('tanggal') is-invalid @enderror"
                                       value="{{ old('tanggal', \Illuminate\Support\Carbon::parse($data->tanggal)->format('Y-m-d')) }}"
                                       required>

                            </div>

                            @error('tanggal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror


                        </div>


                        {{-- STATUS --}}
                        <div class="form-group-elite mb-3">

                            <label for="status" class="modern-label-elite">
                                Status Laporan <span class="text-danger">*</span>
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-control modern-input-elite @error('status') is-invalid @enderror"
                                    required>

                                <option value="Draft" {{ old('status', $data->status) === 'Draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option value="Final" {{ old('status', $data->status) === 'Final' ? 'selected' : '' }}>
                                    Final
                                </option>

                            </select>

                            @error('status')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- CATATAN --}}
                        <div class="form-group-elite mb-3">

                            <label for="catatan" class="modern-label-elite">Catatan</label>

                            <textarea name="catatan"
                                      id="catatan"
                                      rows="5"
                                      class="form-control modern-input-elite"
                                      placeholder="Catatan laporan...">{{ old('catatan', $data->catatan) }}</textarea>

                            @error('catatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="tips-box-elite">

                            <div class="d-flex">
                                <i class="fas fa-info-circle tips-icon-elite mr-2 mt-1"></i>
                                <div class="small tips-text-elite">
                                    Total pemasukan, pengeluaran, jumlah transaksi, dan laba bersih akan dihitung ulang otomatis berdasarkan tanggal yang dipilih.
                                </div>
                            </div>

                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- KOLOM KANAN: RINGKASAN --}}
                    {{-- ========================= --}}
                    <div class="col-lg-7">

                        <div class="summary-panel-elite h-100">

                            <div class="section-label-elite mb-4">
                                <span class="section-label-icon-elite">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <div>
                                    <div class="section-label-title-elite">Ringkasan Keuangan</div>
                                    <small class="section-label-subtitle-elite">Dihitung otomatis dari transaksi pada tanggal terpilih</small>
                                </div>
                            </div>

                            <div id="loadingPreview" class="loading-box-elite mb-3">
                                <i class="fas fa-spinner fa-spin mr-1"></i>
                                Mengambil data transaksi...
                            </div>

                            <div id="errorPreview" class="notice-elite notice-error-elite mb-3">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Data keuangan gagal dimuat.
                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <div class="preview-card-elite">
                                        <div class="preview-label-elite">
                                            <i class="fas fa-arrow-down mr-1"></i>
                                            Total Pemasukan
                                        </div>
                                        <div id="previewPemasukan" class="preview-value-elite" style="color:#12865d;">Rp 0</div>
                                        <div class="preview-count-elite">
                                            <span id="jumlahPemasukan">0</span> transaksi diterima
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="preview-card-elite">
                                        <div class="preview-label-elite">
                                            <i class="fas fa-arrow-up mr-1"></i>
                                            Total Pengeluaran
                                        </div>
                                        <div id="previewPengeluaran" class="preview-value-elite" style="color:var(--sb-red);">Rp 0</div>
                                        <div class="preview-count-elite">
                                            <span id="jumlahPengeluaran">0</span> transaksi diterima
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="total-box-elite">
                                        <small class="total-box-label-elite">
                                            <i class="fas fa-wallet mr-1"></i>
                                            Laba Bersih
                                        </small>
                                        <span id="previewLaba" class="total-box-value-elite">Rp 0</span>
                                        <div class="preview-count-elite">Total pemasukan dikurangi total pengeluaran.</div>
                                    </div>
                                </div>

                            </div>


                            <div class="tips-box-elite mt-3">

                                <div class="d-flex">
                                    <i class="fas fa-shield-alt tips-icon-elite mr-2 mt-1"></i>
                                    <div class="small tips-text-elite">
                                        <strong>Data otomatis.</strong>
                                        Nominal pada laporan dihitung langsung dari data <strong>Pemasukan</strong> dan <strong>Pengeluaran</strong>.
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="elite-divider my-4">


                <div class="d-flex justify-content-end">

                    <a href="{{ route('harian.index') }}"
                       class="btn btn-light-elite btn-modern-elite mr-2">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-gradient-elite btn-modern-elite px-4">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/harian.css') }}">
@endpush


@endsection


@push('scripts')
    @include('pages.laporan.harian._preview-script')
@endpush