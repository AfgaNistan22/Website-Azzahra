@php
    $isEdit        = isset($data) && $data;
    $kategoriValue = old('kategori', $isEdit ? $data->kategori : array_key_first($kategoriForm));

    if (!isset($kategoriForm[$kategoriValue])) {
        $kategoriValue = array_key_first($kategoriForm);
    }

    $mulaiValue   = old('tanggal_mulai', $isEdit && $data->tanggal_mulai ? $data->tanggal_mulai->format('Y-m-d') : '');
    $selesaiValue = old('tanggal_selesai', $isEdit && $data->tanggal_selesai ? $data->tanggal_selesai->format('Y-m-d') : '');
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
                        <span class="section-label-icon-elite"><i class="fas fa-layer-group"></i></span>
                        <div>
                            <div class="section-label-title-elite">Informasi Laporan</div>
                            <small class="section-label-subtitle-elite">Kategori, rentang tanggal, status, dan catatan</small>
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
                        <label for="kategori" class="modern-label-elite">
                            Kategori Laporan <span class="text-danger">*</span>
                        </label>
                        <select name="kategori" id="kategori"
                                class="form-control modern-input-elite @error('kategori') is-invalid @enderror" required>
                            @foreach ($kategoriForm as $kunci => $item)
                                <option value="{{ $kunci }}" @selected($kategoriValue === $kunci)>{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="field-hint-elite d-block mt-1">Laporan menampilkan seluruh data kategori yang dipilih.</small>
                    </div>

                    <div id="blokTanggal">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="modern-label-elite">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                       class="form-control modern-input-elite @error('tanggal_mulai') is-invalid @enderror"
                                       value="{{ $mulaiValue }}">
                                @error('tanggal_mulai')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="modern-label-elite">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                       class="form-control modern-input-elite @error('tanggal_selesai') is-invalid @enderror"
                                       value="{{ $selesaiValue }}">
                                @error('tanggal_selesai')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <small class="field-hint-elite d-block mb-3" style="margin-top:-6px;">
                            Kosongkan kedua tanggal untuk menampilkan semua data.
                        </small>
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
                        <textarea name="catatan" id="catatan" rows="4"
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
                                <strong>Data otomatis.</strong> Jumlah data dan total nominal dihitung langsung dari
                                tabel kategori yang dipilih, dan halaman detail selalu menampilkan data terbaru.
                            </div>
                        </div>
                    </div>

                </div>

                {{-- KANAN: RINGKASAN --}}
                <div class="col-lg-7">
                    <div class="summary-panel-elite h-100">

                        <div class="section-label-elite mb-4">
                            <span class="section-label-icon-elite"><i class="fas fa-chart-pie"></i></span>
                            <div>
                                <div class="section-label-title-elite">Ringkasan Data</div>
                                <small class="section-label-subtitle-elite">Dihitung otomatis dari kategori dan rentang terpilih</small>
                            </div>
                        </div>

                        <div id="loadingPreview" class="loading-box-elite mb-3">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Mengambil data...
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="preview-card-elite">
                                    <div class="preview-label-elite"><i class="fas fa-database mr-1"></i> Jumlah Data</div>
                                    <div id="previewJumlah" class="preview-value-elite" style="color:var(--sb-red-dark);">0</div>
                                    <div class="preview-count-elite">baris pada kategori ini</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3" id="boxNominal">
                                <div class="preview-card-elite">
                                    <div class="preview-label-elite"><i class="fas fa-coins mr-1"></i> <span id="previewNominalLabel">Total Nominal</span></div>
                                    <div id="previewNominal" class="preview-value-elite" style="color:#12865d;">Rp 0</div>
                                    <div class="preview-count-elite">dijumlahkan dari seluruh data</div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="total-box-elite">
                                    <small class="total-box-label-elite"><i class="fas fa-table-columns mr-1"></i> Kolom yang ditampilkan</small>
                                    <div id="daftarKolom"></div>
                                </div>
                            </div>
                        </div>

                        <div id="emptyPreview" class="notice-elite notice-empty-elite mt-3">
                            <i class="fas fa-receipt mr-1"></i> Belum ada data pada kategori dan rentang tanggal tersebut.
                        </div>

                    </div>
                </div>

            </div>

            <hr class="elite-divider my-4">

            <div class="d-flex justify-content-end">
                <a href="{{ route('master.index') }}" class="btn btn-light-elite btn-modern-elite mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-gradient-elite btn-modern-elite px-4">
                    <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Laporan' }}
                </button>
            </div>

        </form>

    </div>
</div>