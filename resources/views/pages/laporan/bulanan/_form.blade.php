@php
    $isEdit       = isset($data) && $data;
    $periodeValue = substr((string) old('periode', $isEdit ? $data->periode->format('Y-m') : now()->format('Y-m')), 0, 7);
    $statusValue  = old('status', $isEdit ? $data->status : 'Draft');
@endphp

<div class="modern-card-elite mb-4">
    <div class="modern-card-body-elite">

        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="row">

                {{-- KIRI: INFORMASI --}}
                <div class="col-lg-5">

                    <div class="section-label-elite mb-4">
                        <span class="section-label-icon-elite"><i class="fas fa-calendar-day"></i></span>
                        <div>
                            <div class="section-label-title-elite">Informasi Laporan</div>
                            <small class="section-label-subtitle-elite">Periode, status, dan catatan laporan</small>
                        </div>
                    </div>

                    @if ($isEdit)
                        <div class="mb-3">
                            <label class="modern-label-elite">Kode Laporan</label>
                            <input type="text" class="form-control modern-input-elite input-readonly-elite"
                                   value="{{ $data->kode_laporan }}" readonly>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="periode" class="modern-label-elite">
                            Periode Laporan <span class="text-danger">*</span>
                        </label>
                        <div class="modern-input-group-elite">
                            <i class="fas fa-calendar-alt input-icon-elite"></i>
                            <input type="month" name="periode" id="periode"
                                   max="{{ now()->format('Y-m') }}"
                                   class="form-control modern-input-elite @error('periode') is-invalid @enderror"
                                   value="{{ $periodeValue }}" required>
                        </div>
                        @error('periode')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="field-hint-elite d-block mt-1">Data keuangan bulan ini dihitung otomatis.</small>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="modern-label-elite">
                            Status Laporan <span class="text-danger">*</span>
                        </label>
                        <select name="status" id="status"
                                class="form-control modern-input-elite @error('status') is-invalid @enderror" required>
                            <option value="Draft" @selected($statusValue === 'Draft')>Draft</option>
                            <option value="Final" @selected($statusValue === 'Final')>Final</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="modern-label-elite">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="5"
                                  class="form-control modern-input-elite"
                                  placeholder="Catatan laporan (opsional)...">{{ old('catatan', $isEdit ? $data->catatan : '') }}</textarea>
                        @error('catatan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="tips-box-elite">
                        <div class="d-flex">
                            <i class="fas fa-info-circle tips-icon-elite mr-2 mt-1"></i>
                            <div class="small tips-text-elite">
                                <strong>Perhitungan otomatis.</strong> Total pemasukan dan pengeluaran diambil dari transaksi
                                berstatus <strong>Diterima</strong> pada bulan yang dipilih. Laporan yang sudah
                                <strong>Final</strong> tidak diperbarui lagi oleh sistem otomatis.
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KANAN: RINGKASAN --}}
                <div class="col-lg-7">
                    <div class="summary-panel-elite h-100">

                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite"><i class="fas fa-chart-line"></i></span>
                            <div>
                                <div class="section-label-title-elite">Ringkasan Keuangan</div>
                                <small class="section-label-subtitle-elite">Dihitung otomatis dari transaksi pada bulan terpilih</small>
                            </div>
                        </div>

                        <div id="loadingPreview" class="loading-box-elite mb-3">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Mengambil data transaksi...
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-card-elite">
                                    <div class="preview-label-elite"><i class="fas fa-arrow-down mr-1"></i> Total Pemasukan</div>
                                    <div id="previewPemasukan" class="preview-value-elite" style="color:#12865d;">Rp 0</div>
                                    <div class="preview-count-elite"><span id="jumlahPemasukan">0</span> transaksi diterima</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="preview-card-elite">
                                    <div class="preview-label-elite"><i class="fas fa-arrow-up mr-1"></i> Total Pengeluaran</div>
                                    <div id="previewPengeluaran" class="preview-value-elite" style="color:var(--sb-red);">Rp 0</div>
                                    <div class="preview-count-elite"><span id="jumlahPengeluaran">0</span> transaksi diterima</div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="total-box-elite">
                                    <small class="total-box-label-elite"><i class="fas fa-wallet mr-1"></i> Laba Bersih</small>
                                    <span id="previewLaba" class="total-box-value-elite">Rp 0</span>
                                    <div class="preview-count-elite">Total pemasukan dikurangi total pengeluaran.</div>
                                </div>
                            </div>
                        </div>

                        <div id="emptyPreview" class="notice-elite notice-empty-elite mt-3">
                            <i class="fas fa-receipt mr-1"></i>
                            Belum terdapat transaksi keuangan yang diterima pada bulan tersebut.
                        </div>

                    </div>
                </div>

            </div>

            <hr class="elite-divider my-4">

            <div class="d-flex justify-content-end">
                <a href="{{ route('bulanan.index') }}" class="btn btn-light-elite btn-modern-elite mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-gradient-elite btn-modern-elite px-4">
                    <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Laporan' }}
                </button>
            </div>

        </form>

    </div>
</div>