@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>

            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">

                Pembayaran

            </h1>

            <p class="mb-0 text-muted">

                Tambah pembayaran reservasi pelanggan

            </p>

        </div>


        <a href="{{ route('pembayaran.index') }}"
           class="btn btn-secondary shadow-sm">

            <i class="fas fa-arrow-left mr-1"></i>

            Kembali

        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle mr-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif


    {{-- ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle mr-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif


    {{-- VALIDATION ERROR --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>

                Terdapat kesalahan:

            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>

                        {{ $error }}

                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">

        {{-- FORM --}}
        <div class="col-lg-8">

            <div class="card shadow mb-4">

                <div class="card-header py-3">

                    <h6 class="m-0 font-weight-bold text-danger">

                        <i class="fas fa-money-bill-wave mr-1"></i>

                        Form Pembayaran

                    </h6>

                </div>


                <div class="card-body">

                    <form action="{{ route('pembayaran.store') }}"
                          method="POST">

                        @csrf


                        {{-- RESERVASI --}}
                        <div class="form-group">

                            <label for="reservasi_id">

                                Kode Reservasi

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select name="reservasi_id"
                                    id="reservasi_id"
                                    class="form-control @error('reservasi_id') is-invalid @enderror"
                                    required>

                                <option value="">

                                    -- Pilih Reservasi --

                                </option>


                                @foreach($reservasi as $item)

                                    @php

                                        $perawatans =
                                            $item->perawatans;

                                        if (
                                            $perawatans->isEmpty() &&
                                            $item->perawatan
                                        ) {
                                            $perawatans = collect([
                                                $item->perawatan
                                            ]);
                                        }


                                        $totalHarga = 0;


                                        foreach ($perawatans as $perawatan) {

                                            $jumlah =
                                                $perawatan->pivot->jumlah
                                                ?? 1;

                                            $harga =
                                                $perawatan->pivot->harga
                                                ?? $perawatan->harga;

                                            $totalHarga +=
                                                $harga * $jumlah;
                                        }

                                    @endphp


                                    <option
                                        value="{{ $item->id }}"

                                        data-pelanggan="{{ $item->pelanggan->nama ?? '-' }}"

                                        data-perawatan="{{
                                            $perawatans
                                                ->pluck('nama')
                                                ->implode(', ')
                                        }}"

                                        data-tanggal="{{
                                            $item->tanggal
                                            ? \Carbon\Carbon::parse(
                                                $item->tanggal
                                            )->format('d-m-Y')
                                            : '-'
                                        }}"

                                        data-jam="{{
                                            $item->jam_mulai
                                            ? \Carbon\Carbon::parse(
                                                $item->jam_mulai
                                            )->format('H:i')
                                            : '-'
                                        }} - {{
                                            $item->jam_selesai
                                            ? \Carbon\Carbon::parse(
                                                $item->jam_selesai
                                            )->format('H:i')
                                            : '-'
                                        }}"

                                        data-pegawai="{{ $item->pegawai->nama ?? '-' }}"

                                        data-harga="{{ $totalHarga }}"

                                        @selected(
                                            old(
                                                'reservasi_id',
                                                optional(
                                                    $reservasi->firstWhere(
                                                        'kode_reservasi',
                                                        $kodeReservasi
                                                    )
                                                )->id
                                            ) == $item->id
                                        )
                                    >

                                        {{ $item->kode_reservasi }}

                                        —

                                        {{ $item->pelanggan->nama ?? '-' }}

                                    </option>

                                @endforeach

                            </select>


                            @error('reservasi_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- DETAIL RESERVASI --}}
                        <div id="detailReservasi"
                             class="p-3 mb-4"
                             style="
                                background:#f8f9fa;
                                border:1px solid #e3e6f0;
                                border-radius:10px;
                             ">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Pelanggan

                                    </div>

                                    <div id="detailPelanggan"
                                         class="detail-value">

                                        -

                                    </div>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Perawatan

                                    </div>

                                    <div id="detailPerawatan"
                                         class="detail-value">

                                        -

                                    </div>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Tanggal

                                    </div>

                                    <div id="detailTanggal"
                                         class="detail-value">

                                        -

                                    </div>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Waktu

                                    </div>

                                    <div id="detailJam"
                                         class="detail-value">

                                        -

                                    </div>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Pegawai / Terapis

                                    </div>

                                    <div id="detailPegawai"
                                         class="detail-value">

                                        -

                                    </div>

                                </div>


                                <div class="col-md-6 mb-3">

                                    <div class="detail-label">

                                        Total Pembayaran

                                    </div>

                                    <div id="detailHarga"
                                         class="detail-value text-success"
                                         style="font-size:18px;">

                                        Rp 0

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- TANGGAL BAYAR --}}
                        <div class="form-group">

                            <label for="tanggal_bayar">

                                Tanggal Pembayaran

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input type="date"
                                   name="tanggal_bayar"
                                   id="tanggal_bayar"
                                   class="form-control @error('tanggal_bayar') is-invalid @enderror"
                                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                                   required>

                            @error('tanggal_bayar')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- METODE --}}
                        <div class="form-group">

                            <label for="metode_pembayaran">

                                Metode Pembayaran

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select name="metode_pembayaran"
                                    id="metode_pembayaran"
                                    class="form-control @error('metode_pembayaran') is-invalid @enderror"
                                    required>

                                <option value="">

                                    -- Pilih Metode Pembayaran --

                                </option>

                                <option value="Cash"
                                    @selected(old('metode_pembayaran') === 'Cash')>

                                    Cash

                                </option>

                                <option value="Transfer"
                                    @selected(old('metode_pembayaran') === 'Transfer')>

                                    Transfer

                                </option>

                                <option value="E-Wallet"
                                    @selected(old('metode_pembayaran') === 'E-Wallet')>

                                    E-Wallet

                                </option>

                            </select>


                            @error('metode_pembayaran')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- CATATAN --}}
                        <div class="form-group">

                            <label for="catatan">

                                Catatan

                            </label>

                            <textarea name="catatan"
                                      id="catatan"
                                      rows="4"
                                      class="form-control @error('catatan') is-invalid @enderror"
                                      placeholder="Catatan pembayaran (opsional)">{{ old('catatan') }}</textarea>


                            @error('catatan')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-end">

                            <a href="{{ route('pembayaran.index') }}"
                               class="btn btn-secondary mr-2">

                                <i class="fas fa-times mr-1"></i>

                                Batal

                            </a>


                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fas fa-save mr-1"></i>

                                Simpan Pembayaran

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- INFORMASI --}}
        <div class="col-lg-4">

            <div class="card shadow mb-4">

                <div class="card-header py-3">

                    <h6 class="m-0 font-weight-bold text-danger">

                        <i class="fas fa-info-circle mr-1"></i>

                        Informasi Pembayaran

                    </h6>

                </div>


                <div class="card-body">

                    <p class="text-muted mb-3">

                        Pembayaran dilakukan berdasarkan total harga seluruh perawatan yang terdapat pada reservasi.

                    </p>


                    <div class="alert alert-info">

                        <i class="fas fa-shield-alt mr-1"></i>

                        Nominal pembayaran ditentukan otomatis berdasarkan total harga perawatan.

                    </div>


                    <div class="alert alert-warning mb-0">

                        <i class="fas fa-exclamation-triangle mr-1"></i>

                        Satu reservasi hanya dapat memiliki satu data pembayaran.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@push('styles')

<style>

.card {
    border-radius: 15px !important;
}

.card-header {
    min-height: 60px;
    display: flex;
    align-items: center;
}

.detail-label {
    font-size: 12px;
    font-weight: 600;
    color: #858796;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.detail-value {
    font-size: 15px;
    color: #343a40;
    font-weight: 500;
}

.form-control {
    border-radius: 7px;
}

.btn {
    border-radius: 7px;
}

.alert {
    border-radius: 10px;
}

</style>

@endpush


@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(angka) {

        angka = parseInt(angka) || 0;

        return 'Rp ' + angka.toLocaleString('id-ID');

    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN DETAIL RESERVASI
    |--------------------------------------------------------------------------
    */

    function tampilkanDetail() {

        const selected =
            $('#reservasi_id option:selected');


        if (!selected.val()) {

            $('#detailPelanggan').text('-');

            $('#detailPerawatan').text('-');

            $('#detailTanggal').text('-');

            $('#detailJam').text('-');

            $('#detailPegawai').text('-');

            $('#detailHarga').text('Rp 0');

            return;

        }


        $('#detailPelanggan').text(
            selected.data('pelanggan') || '-'
        );


        $('#detailPerawatan').text(
            selected.data('perawatan') || '-'
        );


        $('#detailTanggal').text(
            selected.data('tanggal') || '-'
        );


        $('#detailJam').text(
            selected.data('jam') || '-'
        );


        $('#detailPegawai').text(
            selected.data('pegawai') || '-'
        );


        $('#detailHarga').text(
            formatRupiah(
                selected.data('harga')
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE RESERVASI
    |--------------------------------------------------------------------------
    */

    $('#reservasi_id').on(
        'change',
        tampilkanDetail
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    tampilkanDetail();

});

</script>

@endpush

@endsection