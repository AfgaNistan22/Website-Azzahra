{{-- Partial form pemasukan manual (dipakai create & edit) --}}
<div class="row">

    {{-- TANGGAL --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="tanggal" class="modern-label-elite">
                Tanggal <span class="text-danger">*</span>
            </label>

            <div class="modern-input-group-elite">

                <i class="fas fa-calendar-alt input-icon-elite"></i>

                <input type="date"
                       name="tanggal"
                       id="tanggal"
                       class="form-control modern-input-elite @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', isset($data) ? $data->tanggal?->format('Y-m-d') : now()->format('Y-m-d')) }}"
                       required>

            </div>

            @error('tanggal')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

    </div>


    {{-- KATEGORI --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="kategori" class="modern-label-elite">
                Kategori <span class="text-danger">*</span>
            </label>

            <div class="modern-input-group-elite">

                <i class="fas fa-tags input-icon-elite"></i>

                <input type="text"
                       name="kategori"
                       id="kategori"
                       class="form-control modern-input-elite @error('kategori') is-invalid @enderror"
                       value="{{ old('kategori', $data->kategori ?? '') }}"
                       placeholder="Contoh: Penjualan Produk"
                       required>

            </div>

            @error('kategori')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

    </div>


    {{-- SUMBER --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="sumber" class="modern-label-elite">Sumber</label>

            <div class="modern-input-group-elite">

                <i class="fas fa-project-diagram input-icon-elite"></i>

                <input type="text"
                       name="sumber"
                       id="sumber"
                       class="form-control modern-input-elite"
                       value="{{ old('sumber', $data->sumber ?? '') }}"
                       placeholder="Contoh: Penjualan Shampoo">

            </div>

        </div>

    </div>


    {{-- NOMINAL --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="nominal_display" class="modern-label-elite">
                Nominal <span class="text-danger">*</span>
            </label>

            <div class="modern-value-group-elite">

                <span class="value-symbol-elite">Rp</span>

                <input type="text"
                       id="nominal_display"
                       class="form-control modern-input-elite value-input-elite @error('nominal') is-invalid @enderror"
                       value="{{ old('nominal') ? number_format(old('nominal'), 0, ',', '.') : (isset($data) ? number_format($data->nominal, 0, ',', '.') : '') }}"
                       placeholder="0"
                       inputmode="numeric"
                       autocomplete="off"
                       required>

                <input type="hidden" name="nominal" id="nominal" value="{{ old('nominal', $data->nominal ?? 0) }}">

            </div>

            @error('nominal')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

    </div>


    {{-- METODE PEMBAYARAN --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="metode_pembayaran" class="modern-label-elite">
                Metode Pembayaran <span class="text-danger">*</span>
            </label>

            <select name="metode_pembayaran"
                    id="metode_pembayaran"
                    class="form-control modern-input-elite @error('metode_pembayaran') is-invalid @enderror"
                    required>

                <option value="">-- Pilih --</option>

                @foreach(['Cash', 'Transfer', 'E-Wallet'] as $m)
                    <option value="{{ $m }}" @selected(old('metode_pembayaran', $data->metode_pembayaran ?? '') === $m)>
                        {{ $m }}
                    </option>
                @endforeach

            </select>

            @error('metode_pembayaran')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

    </div>


    {{-- STATUS --}}
    <div class="col-md-6">

        <div class="form-group-elite mb-3">

            <label for="status" class="modern-label-elite">
                Status <span class="text-danger">*</span>
            </label>

            <select name="status"
                    id="status"
                    class="form-control modern-input-elite @error('status') is-invalid @enderror"
                    required>

                <option value="Diterima" @selected(old('status', $data->status ?? 'Diterima') === 'Diterima')>
                    Diterima
                </option>

                <option value="Dibatalkan" @selected(old('status', $data->status ?? '') === 'Dibatalkan')>
                    Dibatalkan
                </option>

            </select>

            @error('status')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

    </div>


    {{-- KETERANGAN --}}
    <div class="col-12">

        <div class="form-group-elite mb-3">

            <label for="keterangan" class="modern-label-elite">Keterangan</label>

            <textarea name="keterangan"
                      id="keterangan"
                      rows="5"
                      class="form-control modern-input-elite"
                      placeholder="Keterangan tambahan...">{{ old('keterangan', $data->keterangan ?? '') }}</textarea>

        </div>

    </div>

</div>


@push('scripts')
<script>

const nd = document.getElementById('nominal_display');
const nh = document.getElementById('nominal');

nd?.addEventListener('input', function () {

    let n = this.value.replace(/\D/g, '');

    nh.value = n || 0;

    this.value = n ? Number(n).toLocaleString('id-ID') : '';

});

</script>
@endpush