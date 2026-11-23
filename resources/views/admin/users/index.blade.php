@extends('layouts.app')

@section('title', 'Kelola Akun Pengguna')

@section('content')

<div class="container-fluid customer-page-elite">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="modern-header-elite mb-4">
        <div class="header-ornament-1"></div>
        <div class="header-ornament-2"></div>

        <div class="header-left position-relative">
            <div class="header-icon-elite" style="background: linear-gradient(135deg, #7c1013, #e01c1d); color: #fff;">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <h1 class="page-title-elite" style="color: #7c1013;">Kelola Akun Pengguna</h1>
                <p class="page-subtitle-elite">Manajemen akun member & admin, reset kata sandi, dan bantuan lupa password</p>
            </div>
        </div>

        <div class="header-actions position-relative">
            <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-elite btn-modern-elite">
                <i class="fas fa-user mr-2"></i> Data Pelanggan
            </a>
            <a href="{{ url('/register') }}" target="_blank" class="btn btn-gradient-elite btn-modern-elite">
                <i class="fas fa-user-plus mr-2"></i> Buat Akun Baru
            </a>
        </div>
    </div>


    {{-- =========================================================
        NOTIFIKASI / FLASH ALERT
    ========================================================== --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:12px; border-left: 5px solid #15803d;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius:12px; border-left: 5px solid #b91c1c;">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    {{-- JIKA BARU SAJA RESET PASSWORD --}}
    @if (session('reset_info'))
        @php $info = session('reset_info'); @endphp
        <div class="card mb-4 shadow border-0" style="border-radius: 16px; background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 2px solid #fdba74 !important;">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <span class="badge badge-warning px-3 py-2 text-dark font-weight-bold mb-2" style="font-size: 12px; border-radius: 20px;">
                            <i class="fas fa-key mr-1"></i> Kata Sandi Berhasil Direset
                        </span>
                        <h4 class="font-weight-bold text-dark mb-1">
                            Akun: {{ $info['nama'] }} ({{ $info['email'] }})
                        </h4>
                        <p class="text-muted mb-2">Berikan kata sandi sementara berikut kepada pengguna yang bersangkutan:</p>
                        
                        <div class="d-inline-flex align-items-center bg-white px-3 py-2 rounded shadow-sm border">
                            <span class="font-weight-bold mr-2 text-danger" style="font-size: 1.1rem; letter-spacing: 1px;" id="copyPassText">
                                {{ $info['password_baru'] }}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1" onclick="copyPassword('{{ $info['password_baru'] }}')">
                                <i class="far fa-copy mr-1"></i> Salin
                            </button>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end gap-2">
                        @if ($info['link_wa'])
                            <a href="{{ $info['link_wa'] }}" target="_blank" rel="noopener" class="btn btn-success shadow px-4 py-2" style="border-radius: 12px; font-weight: 700;">
                                <i class="fab fa-whatsapp fa-lg mr-2"></i> Kirim Password via WhatsApp
                            </a>
                            <small class="text-muted">Ke nomor: {{ $info['no_wa'] }}</small>
                        @else
                            <span class="badge badge-secondary px-3 py-2">Nomor WhatsApp belum terdaftar</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- =========================================================
        STATISTIK KPI CARDS
    ========================================================== --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 14px; border-left: 4px solid #7c1013 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #7c1013;">Total Pengguna</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="text-gray-300"><i class="fas fa-users fa-2x"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 14px; border-left: 4px solid #15803d !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pelanggan / Member</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['pelanggan'] }}</div>
                    </div>
                    <div class="text-gray-300"><i class="fas fa-user-check fa-2x"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 14px; border-left: 4px solid #b45309 !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Administrator</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['admin'] }}</div>
                    </div>
                    <div class="text-gray-300"><i class="fas fa-user-shield fa-2x"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 14px; border-left: 4px solid #2563eb !important;">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Login via Google/Sosial</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['google'] }}</div>
                    </div>
                    <div class="text-gray-300"><i class="fab fa-google fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>


    {{-- =========================================================
        FILTER & PENCARIAN
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <form action="{{ route('users.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-5 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="cari" class="form-control border-left-0" placeholder="Cari nama, email, atau no. telepon..." value="{{ $cari }}">
                    </div>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="role" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Semua Role --</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}" {{ $roleId == $r->id ? 'selected' : '' }}>
                                {{ ucfirst($r->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved (Aktif)</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected (Nonaktif)</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-danger text-white btn-block" style="background:#7c1013; border:0; border-radius:10px;">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    @if ($cari || $roleId || $status)
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ml-1" title="Reset filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>


    {{-- =========================================================
        TABEL DATA PENGGUNA
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold" style="color: #7c1013;">
                <i class="fas fa-list mr-2"></i> Daftar Akun Pengguna
            </h5>
            <small class="text-muted">Total: {{ $users->total() }} akun</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead style="background: #fbf3f4; color: #7c1013;">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Pengguna</th>
                        <th>Kontak / WhatsApp</th>
                        <th>Role</th>
                        <th>Metode Login</th>
                        <th>Status</th>
                        <th>Terdaftar</th>
                        <th class="text-right" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        @php
                            $noHp = $u->phone ?? $u->pelanggan?->no_telepon ?? null;
                            $avatar = $u->photo 
                                ? asset('storage/' . $u->photo) 
                                : ($u->avatar ?: asset('template/img/undraw_profile.svg'));
                        @endphp
                        <tr>
                            <td class="font-weight-bold text-muted">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $avatar }}" alt="{{ $u->name }}" class="rounded-circle mr-3" style="width: 42px; height: 42px; object-fit: cover; border: 1px solid #eadfe1;">
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $u->name }}</div>
                                        <small class="text-muted">{{ $u->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($noHp)
                                    <span class="text-dark font-weight-bold">{{ $noHp }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', str_starts_with($noHp, '0') ? '62'.substr($noHp,1) : $noHp) }}" target="_blank" class="text-success ml-1" title="Chat WhatsApp">
                                        <i class="fab fa-whatsapp fa-lg"></i>
                                    </a>
                                @else
                                    <span class="text-muted font-italic">- Belum ada -</span>
                                @endif
                            </td>
                            <td>
                                @if ($u->role_id == 1)
                                    <span class="badge badge-danger text-white px-2 py-1" style="background:#7c1013;">Administrator</span>
                                @else
                                    <span class="badge badge-success px-2 py-1" style="background:#15803d; color:#fff;">Pelanggan (Member)</span>
                                @endif
                            </td>
                            <td>
                                @if ($u->provider === 'google')
                                    <span class="badge badge-light border text-dark px-2 py-1"><i class="fab fa-google text-danger mr-1"></i> Google</span>
                                @elseif ($u->provider === 'facebook')
                                    <span class="badge badge-light border text-dark px-2 py-1"><i class="fab fa-facebook text-primary mr-1"></i> Facebook</span>
                                @else
                                    <span class="badge badge-light border text-muted px-2 py-1"><i class="fas fa-envelope mr-1"></i> Email & Password</span>
                                @endif
                            </td>
                            <td>
                                @if ($u->status === 'approved')
                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                @elseif ($u->status === 'pending')
                                    <span class="badge badge-warning text-dark px-2 py-1">Pending</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}</small>
                            </td>
                            <td class="text-right">
                                <div class="btn-group" role="group">
                                    {{-- TOMBOL RESET PASSWORD --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#modalReset{{ $u->id }}" title="Reset Kata Sandi Pengguna">
                                        <i class="fas fa-key mr-1"></i> Reset Pass
                                    </button>

                                    {{-- TOMBOL UBAH STATUS --}}
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Ubah Status">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <span class="dropdown-header text-uppercase font-weight-bold" style="font-size: 10px;">Ubah Status Akun</span>
                                            <form action="{{ route('users.status', $u->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="dropdown-item text-success"><i class="fas fa-check mr-2"></i> Set Aktif (Approved)</button>
                                            </form>
                                            <form action="{{ route('users.status', $u->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-ban mr-2"></i> Nonaktifkan (Rejected)</button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- TOMBOL HAPUS --}}
                                    @if ($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Akun">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- MODAL RESET PASSWORD PER USER --}}
                                <div class="modal fade" id="modalReset{{ $u->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $u->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                            <form action="{{ route('users.reset-password', $u->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header text-white" style="background: linear-gradient(135deg, #7c1013, #e01c1d); border-radius: 16px 16px 0 0;">
                                                    <h5 class="modal-title font-weight-bold" id="modalLabel{{ $u->id }}">
                                                        <i class="fas fa-key mr-2"></i> Reset Kata Sandi Pengguna
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-4 text-left">
                                                    <p class="mb-3 text-muted">
                                                        Anda akan mengatur ulang kata sandi untuk akun <strong>{{ $u->name }}</strong> ({{ $u->email }}).
                                                    </p>

                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-bold text-dark">Kata Sandi Baru (Opsional)</label>
                                                        <input type="text" name="password_baru" class="form-control" placeholder="Biarkan kosong untuk buat otomatis (misal: Azzahra1234#)">
                                                        <small class="form-text text-muted">Jika dikosongkan, sistem akan mengacak password sementara secara otomatis.</small>
                                                    </div>

                                                    @if ($noHp)
                                                        <div class="alert alert-info py-2 px-3 small mb-0" style="border-radius: 8px;">
                                                            <i class="fab fa-whatsapp mr-1 text-success"></i> Pengguna memiliki no WhatsApp: <strong>{{ $noHp }}</strong>. Setelah direset, sistem akan menyediakan tombol untuk langsung meneruskannya via WA.
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning py-2 px-3 small mb-0" style="border-radius: 8px;">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i> Nomor telepon pengguna belum diisi di profil.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer bg-light" style="border-radius: 0 0 16px 16px;">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger text-white font-weight-bold px-4" style="background: #7c1013; border: 0;">
                                                        <i class="fas fa-check mr-1"></i> Eksekusi Reset
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3 d-block text-gray-300"></i>
                                Tidak ada data pengguna yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} akun</small>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<script>
function copyPassword(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Kata sandi sementara berhasil disalin ke clipboard: ' + text);
        });
    } else {
        var input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        alert('Kata sandi sementara berhasil disalin: ' + text);
    }
}
</script>

@endsection
