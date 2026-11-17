@extends('layouts.app')

@section('title', 'Edit Laporan Master')

@section('content')

<div class="container-fluid px-4 bulanan-elite master-elite">

    <div class="form-header-elite mb-4">
        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left">
            <div class="header-icon-elite"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="page-title-elite">Edit Laporan Master</h1>
                <p class="page-subtitle-elite">Ubah kategori, rentang tanggal, status, atau catatan. Angka dihitung ulang otomatis</p>
            </div>
        </div>

        <div class="header-actions-elite">
            <a href="{{ route('master.index') }}" class="btn btn-outline-elite-onheader">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @include('pages.laporan.master._form', ['action' => route('master.update', $data->id), 'data' => $data])

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/master.css') }}">
@endpush

@endsection

@push('scripts')
    @include('pages.laporan.master._preview-script')
@endpush