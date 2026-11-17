@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-lg mb-4 overflow-hidden">

        <div class="card-body text-white"
            style="background:linear-gradient(135deg,#dc3545,#b02a37);">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-history me-2"></i>
                        History Aktivitas
                    </h2>

                    <p class="mb-0 opacity-75">
                        Monitoring seluruh aktivitas admin dan perubahan data sistem
                    </p>

                </div>

                <div class="ml-5">

                    <div class="rounded-4 p-3 d-inline-block">

                        <h2 class="fw-bold mb-0">
                            {{ $data->total() }}
                        </h2>

                        <small>
                            Total Aktivitas
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- STAT --}}
    <div class="row g-3 mb-4">

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-danger">
                <i class="fas fa-history stat-icon"></i>
                <h3>{{ $totalAktivitas }}</h3>
                <span>Total</span>
            </div>
        </div>

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-success">
                <i class="fas fa-sign-in-alt stat-icon"></i>
                <h3>{{ $totalLogin }}</h3>
                <span>Login</span>
            </div>
        </div>

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-secondary">
                <i class="fas fa-sign-out-alt stat-icon"></i>
                <h3>{{ $totalLogout }}</h3>
                <span>Logout</span>
            </div>
        </div>

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-primary">
                <i class="fas fa-plus-circle stat-icon"></i>
                <h3>{{ $totalTambah }}</h3>
                <span>Tambah</span>
            </div>
        </div>

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-warning">
                <i class="fas fa-edit stat-icon"></i>
                <h3>{{ $totalEdit }}</h3>
                <span>Edit</span>
            </div>
        </div>

        <div class="col-lg col-md-4 col-6">
            <div class="stat-card stat-info">
                <i class="fas fa-calendar-day stat-icon"></i>
                <h3>{{ $todayCount }}</h3>
                <span>Hari Ini</span>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4 filter-card">
        <div class="card-body">
            <form method="GET">
                <div class="row align-items-end g-3">
                    <!-- Cari -->
                    <div class="col-md-3">
                        <label class="form-label">CARI</label>
                        <input type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Nama user / Modul / ID"
                            value="{{ request('keyword') }}">
                    </div>


                    <!-- Aktivitas -->
                    <div class="col-md-2 aktivitas-filter">
                        <label class="form-label">
                            AKTIVITAS
                        </label>

                        <select name="aktivitas" class="form-select">
                            <option value="">Semua</option>
                            <option value="Login" {{ request('aktivitas')=='Login'?'selected':'' }}>
                                Login
                            </option>
                            <option value="Logout" {{ request('aktivitas')=='Logout'?'selected':'' }}>
                                Logout
                            </option>
                            <option value="Tambah Data" {{ request('aktivitas')=='Tambah Data'?'selected':'' }}>
                                Tambah Data
                            </option>
                            <option value="Edit Data" {{ request('aktivitas')=='Edit Data'?'selected':'' }}>
                                Edit Data
                            </option>
                            <option value="Hapus Data" {{ request('aktivitas')=='Hapus Data'?'selected':'' }}>
                                Hapus Data
                            </option>
                        </select>
                    </div>


                    <!-- Tanggal Awal -->
                    <div class="col-md-2">
                        <label class="form-label">TANGGAL AWAL</label>
                        <input type="date"
                            name="tanggal_awal"
                            class="form-control"
                            value="{{ request('tanggal_awal') }}">
                    </div>


                    <!-- Tanggal Akhir -->
                    <div class="col-md-2">
                        <label class="form-label">TANGGAL AKHIR</label>
                        <input type="date"
                            name="tanggal_akhir"
                            class="form-control"
                            value="{{ request('tanggal_akhir') }}">
                    </div>


                    <!-- Button -->
                    <div class="col-md-3">
                        <div class="d-flex gap-2">

                            <button class="btn btn-danger filter-btn">
                                <i class="fas fa-search"></i>
                                Filter
                            </button>

                            <a href="{{ route('history.index') }}"
                            class="btn btn-secondary reset-btn">
                                Reset
                            </a>

                        </div>
                    </div>


                </div>

            </form>

        </div>
    </div>
    {{-- CARD HISTORY --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fas fa-clock text-danger me-2"></i>

                    Riwayat Aktivitas

                </h5>

                <span class="badge bg-danger px-3 py-2 text-white">

                    {{ $data->count() }} Data

                </span>

            </div>

        </div>

        <div class="card-body p-4">

            <div class="history-scroll">

                @forelse($data as $item)

                    @php

                        $badge = match($item->aktivitas){

                            'Login'       => 'success',
                            'Logout'      => 'secondary',
                            'Tambah Data' => 'primary',
                            'Edit Data'   => 'warning',
                            'Hapus Data'  => 'danger',
                            default       => 'dark'
                        };

                        $icon = match($item->aktivitas){

                            'Login'       => 'fa-sign-in-alt',
                            'Logout'      => 'fa-sign-out-alt',
                            'Tambah Data' => 'fa-plus-circle',
                            'Edit Data'   => 'fa-edit',
                            'Hapus Data'  => 'fa-trash',
                            default       => 'fa-history'
                        };

                    @endphp

                    <div class="timeline-item">

                        <div class="timeline-icon bg-{{ $badge }}">

                            <i class="fas {{ $icon }}"></i>

                        </div>

                        <div class="timeline-content">

                            <div class="card border-0 shadow-sm activity-card">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        {{-- USER --}}
                                        <div class="col-lg-3">

                                            <div class="d-flex align-items-center">

                                                <div class="avatar-circle">

                                                    {{ strtoupper(substr($item->user_name,0,1)) }}

                                                </div>

                                                <div>

                                                    <div class="fw-bold">

                                                        {{ $item->user_name }}

                                                    </div>

                                                    <small class="text-muted">

                                                        Administrator

                                                    </small>

                                                </div>

                                            </div>

                                        </div>

                                        {{-- AKTIVITAS --}}
                                        <div class="col-lg-3 mt-3 mt-lg-0 text-white">

                                            <span class="badge bg-{{ $badge }} px-3 py-2">

                                                <i class="fas {{ $icon }} me-1"></i>

                                                {{ $item->aktivitas }}

                                            </span>

                                        </div>

                                        {{-- MODUL --}}
                                        <div class="col-lg-2 mt-3 mt-lg-0">

                                            <span class="badge bg-light text-dark border px-3 py-2">

                                                {{ $item->modul }}

                                            </span>

                                        </div>

                                        {{-- REFERENSI --}}
                                        <div class="col-lg-2 mt-3 mt-lg-0">

                                            @if($item->data_id)

                                                <span class="fw-bold text-primary">

                                                    #{{ $item->data_id }}

                                                </span>

                                            @else

                                                <span class="text-muted">

                                                    -

                                                </span>

                                            @endif

                                        </div>

                                        {{-- WAKTU --}}
                                        <div class="col-lg-2 mt-3 mt-lg-0 text-lg-end">

                                            <div class="fw-bold">

                                                {{ \Carbon\Carbon::parse($item->waktu)
                                                    ->timezone('Asia/Jakarta')
                                                    ->format('H:i:s') }}

                                            </div>

                                            <small class="text-muted">

                                                {{ \Carbon\Carbon::parse($item->waktu)
                                                    ->diffForHumans() }}

                                            </small>

                                        </div>

                                    </div>

                                    <hr class="my-3">

                                    <small class="text-muted">

                                        <i class="far fa-calendar-alt me-1"></i>

                                        {{ \Carbon\Carbon::parse($item->waktu)
                                            ->timezone('Asia/Jakarta')
                                            ->translatedFormat('d F Y') }}

                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="fas fa-history fa-4x text-muted mb-3"></i>

                        <h4 class="text-muted">

                            Belum Ada Aktivitas

                        </h4>

                        <p class="text-muted">

                            Aktivitas sistem akan muncul di sini

                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $data->links() }}
    </div>

</div>

<style>

    /* STAT */
.stat-card{
    position:relative;
    padding:22px;
    border-radius:18px;
    color:#fff;
    overflow:hidden;
    transition:.3s;
}
.stat-card:hover{transform:translateY(-5px);}
.stat-icon{position:absolute;right:15px;top:15px;font-size:42px;opacity:.15;}

.stat-danger{background:linear-gradient(135deg,#dc3545,#ff6b6b);}
.stat-success{background:linear-gradient(135deg,#198754,#20c997);}
.stat-secondary{background:linear-gradient(135deg,#6c757d,#adb5bd);}
.stat-primary{background:linear-gradient(135deg,#0d6efd,#4dabf7);}
.stat-warning{background:linear-gradient(135deg,#ffc107,#ffd43b);color:#222;}
.stat-info{background:linear-gradient(135deg,#0dcaf0,#66d9ef);color:#222;}

.history-scroll{
    max-height:650px;
    overflow-y:auto;
    padding-right:10px;
}

.history-scroll::-webkit-scrollbar{
    width:8px;
}

.history-scroll::-webkit-scrollbar-thumb{
    background:#dc3545;
    border-radius:20px;
}

.timeline-item{
    position:relative;
    padding-left:80px;
    margin-bottom:25px;
}

.timeline-item:last-child{
    margin-bottom:0;
}

.timeline-item::before{
    content:'';
    position:absolute;
    left:30px;
    top:0;
    bottom:-30px;
    width:3px;
    background:#e9ecef;
}

.timeline-icon{
    position:absolute;
    left:10px;
    top:20px;
    width:42px;
    height:42px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    z-index:2;
    box-shadow:0 4px 10px rgba(0,0,0,.15);
}

.avatar-circle{
    width:45px;
    height:45px;
    border-radius:50%;
    background:linear-gradient(135deg,#dc3545,#b02a37);
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    margin-right:12px;
}

.activity-card{
    border-radius:15px;
    transition:.3s ease;
}

.activity-card:hover{
    transform:translateY(-4px);
    box-shadow:0 .75rem 1.5rem rgba(0,0,0,.15)!important;
}

.card{
    border-radius:18px;
}

@media(max-width:768px){

    .timeline-item{
        padding-left:60px;
    }

    .timeline-item::before{
        left:22px;
    }

    .timeline-icon{
        left:2px;
    }

}

/* FILTER CARD MODERN */
.card.border-0.shadow-sm.mb-4{
    border-radius:18px;
    background:var(--bs-body-bg);
    transition:all .3s ease;
}

/* hover soft lift */
.card.border-0.shadow-sm.mb-4:hover{
    transform:translateY(-2px);
    box-shadow:0 .75rem 1.5rem rgba(0,0,0,.08)!important;
}

/* label lebih clean */
.form-label{
    font-size:.75rem;
    font-weight:600;
    color:var(--bs-secondary-color);
    margin-bottom:6px;
    letter-spacing:.3px;
    text-transform:uppercase;
}

/* input style modern */
.form-control,
.form-select{
    border-radius:12px;
    border:1px solid #e9ecef;
    padding:10px 12px;
    font-size:.9rem;
    transition:.2s ease;
    background:#fff;
}

/* focus state lebih soft */
.form-control:focus,
.form-select:focus{
    border-color:#dc3545;
    box-shadow:0 0 0 .15rem rgba(220,53,69,.15);
}

/* tombol filter utama */
.btn-danger{
    border-radius:12px;
    padding:10px 16px;
    font-weight:600;
    box-shadow:0 6px 18px rgba(220,53,69,.2);
    transition:.25s ease;
}

/* hover tombol filter */
.btn-danger:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 22px rgba(220,53,69,.25);
}

/* tombol reset */
.btn-secondary{
    border-radius:12px;
    padding:10px 16px;
    font-weight:600;
    transition:.25s ease;
}

/* hover reset */
.btn-secondary:hover{
    transform:translateY(-2px);
}

/* spacing lebih rapih antar field */
.row.g-3 > div{
    margin-bottom:4px;
}

/* DARK MODE SUPPORT */
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select{
    background:#1f2430;
    border-color:#2c3446;
    color:#fff;
}

[data-bs-theme="dark"] .form-control:focus,
[data-bs-theme="dark"] .form-select:focus{
    border-color:#ff6b6b;
    box-shadow:0 0 0 .15rem rgba(255,107,107,.15);
}

[data-bs-theme="dark"] .form-label{
    color:#adb5bd;
}

/* RESPONSIVE IMPROVEMENT */
@media(max-width:768px){
    .btn-danger,
    .btn-secondary{
        width:100%;
    }

    .col-md-3,
    .col-md-2{
        margin-bottom:10px;
    }
}

.filter-card .form-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #78839a;
    letter-spacing: .3px;
}

.aktivitas-filter {
    flex: 0 0 auto;
    width: 220px;
}

.filter-card .form-label {
    display: block;
    margin-bottom: 10px !important;
    font-size: 13px;
    font-weight: 600;
    color: #78839a;
    line-height: 1.2;
}


.aktivitas-filter .form-select {
    height: 38px !important;
    min-height: 38px !important;
    padding: 6px 12px;
    font-size: 14px;
    border-radius: 12px;
}

.filter-card .form-control,
.filter-card .form-select {
    height: 40px !important;
    width: 100%;
    border-radius: 12px;
}

</style>

@endsection