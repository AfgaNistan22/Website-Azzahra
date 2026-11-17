@extends('layouts.app')

@section('title', 'Buat Laporan Bulanan')

@section('content')

<div class="container-fluid px-4 bulanan-elite">

    <div class="form-header-elite mb-4">
        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left">
            <div class="header-icon-elite"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h1 class="page-title-elite">Buat Laporan Bulanan</h1>
                <p class="page-subtitle-elite">Buat rekap keuangan bulanan Azzahra Salon dan Spa Muslimah berdasarkan transaksi pemasukan dan pengeluaran</p>
            </div>
        </div>

        <div class="header-actions-elite">
            <a href="{{ route('bulanan.index') }}" class="btn btn-outline-elite-onheader">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @include('pages.laporan.bulanan._form', ['action' => route('bulanan.store'), 'data' => null])

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/bulanan.css') }}">
@endpush

@endsection

@push('scripts')
    @include('pages.laporan.bulanan._preview-script')
@endpush