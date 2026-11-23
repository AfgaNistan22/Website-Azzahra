{{--
    Halaman admin Galeri: resources/views/admin/galeri/index.blade.php
    Layout memakai layouts.app (sesuaikan bila layout admin Anda berbeda).
    Cocok dengan GaleriAdminController yang ada: tidak perlu mengubah controller atau route.
--}}
@extends('layouts.app')

@section('title', 'Galeri')

@section('content')

@php
    // alamat foto: aman untuk nama berkas lama tanpa folder
    $srcFoto = function ($foto) {
        if (empty($foto)) return null;
        return asset('storage/' . (\Illuminate\Support\Str::contains($foto, '/') ? $foto : 'foto_galeri/' . $foto));
    };

    $kosong = 'data:image/svg+xml;utf8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="64"><rect width="90" height="64" fill="#f3e9e4"/>'
        . '<text x="45" y="37" font-size="10" text-anchor="middle" fill="#9c8a8d" font-family="sans-serif">Tidak ada foto</text></svg>'
    );
@endphp

<style>
    /* =========================================================
       ADMIN GALERI. Prefix: gla-
    ========================================================= */
    .gla { --gla-red: #e01c1d; --gla-wine: #7c1013; --gla-gold: #d9ac4f; --gla-ink: #3a1214; --gla-line: #eadfe1; --gla-tint: #fbf3f4; --gla-muted: #7a6a6f; }

    .gla-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 14px; margin-bottom: 22px; }
    .gla-head h1 { margin: 0 0 4px; color: var(--gla-wine); font-size: 1.55rem; font-weight: 700; }
    .gla-head p { margin: 0; color: var(--gla-muted); font-size: .9rem; }
    .gla-head-actions { display: flex; flex-wrap: wrap; gap: 8px; }

    .gla-card { margin-bottom: 24px; overflow: hidden; border: 1px solid var(--gla-line); border-radius: 16px; background: #fff; box-shadow: 0 6px 22px rgba(124, 16, 19, .06); }
    .gla-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 22px; border-bottom: 1px solid var(--gla-line); background: var(--gla-tint); }
    .gla-card-head h2 { margin: 0; color: var(--gla-wine); font-size: 1rem; font-weight: 700; }
    .gla-card-head small { color: var(--gla-muted); }
    .gla-card-body { padding: 22px; }

    .gla .btn-gla { border: 0; border-radius: 10px; background: linear-gradient(135deg, var(--gla-red), var(--gla-wine)); color: #fff; font-weight: 600; transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease; }
    .gla .btn-gla:hover:not(:disabled) { color: #fff; transform: translateY(-1px); box-shadow: 0 10px 20px -10px rgba(124, 16, 19, .8); }
    .gla .btn-gla:disabled { opacity: .45; cursor: not-allowed; }
    .gla .btn-gla-line { border: 1px solid var(--gla-line); border-radius: 10px; background: #fff; color: var(--gla-wine); font-weight: 600; }
    .gla .btn-gla-line:hover { border-color: var(--gla-wine); background: var(--gla-wine); color: #fff; }

    .gla .form-control { border-color: var(--gla-line); border-radius: 10px; }
    .gla .form-control:focus { border-color: var(--gla-red); box-shadow: 0 0 0 .2rem rgba(224, 28, 29, .12); }
    .gla label.gla-lbl { margin-bottom: 6px; color: var(--gla-ink); font-size: .8rem; font-weight: 700; }

    /* ---------- area tarik dan lepas ---------- */
    .gla-file-input { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
    .gla-drop {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; width: 100%; margin: 0; padding: 30px 18px; text-align: center; cursor: pointer;
        border: 2px dashed #e3c9cd; border-radius: 16px; background: var(--gla-tint); color: var(--gla-ink); transition: border-color .25s ease, background .25s ease, transform .25s ease;
    }
    .gla-drop:hover, .gla-drop.is-over { border-color: var(--gla-red); background: #fdeff1; }
    .gla-drop.is-over { transform: scale(1.01); }
    .gla-drop:focus-within { outline: 3px solid rgba(217, 172, 79, .55); outline-offset: 2px; }
    .gla-drop i { display: grid; width: 54px; height: 54px; place-items: center; margin-bottom: 4px; border-radius: 50%; background: #fff; color: var(--gla-red); font-size: 22px; box-shadow: 0 6px 16px -8px rgba(124, 16, 19, .6); }
    .gla-drop strong { font-size: 1rem; }
    .gla-drop u { color: var(--gla-red); text-decoration: none; font-weight: 700; }
    .gla-drop small { color: var(--gla-muted); }

    .gla-err { margin-top: 12px; padding: 10px 14px; border: 1px solid #f0c1c8; border-radius: 10px; background: #fdeff2; color: #8a1b2e; font-size: .85rem; }
    .gla-err ul { margin: 0; padding-left: 18px; }
    .gla-err[hidden] { display: none; }

    .gla-preview { display: grid; grid-template-columns: repeat(auto-fill, minmax(112px, 1fr)); gap: 12px; margin-top: 16px; }
    .gla-preview:empty { display: none; }
    .gla-pv { position: relative; overflow: hidden; border: 1px solid var(--gla-line); border-radius: 12px; background: #fff; }
    .gla-pv img { display: block; width: 100%; aspect-ratio: 1 / 1; object-fit: cover; }
    .gla-pv span { display: block; padding: 6px 8px; overflow: hidden; color: var(--gla-muted); font-size: .7rem; text-overflow: ellipsis; white-space: nowrap; }
    .gla-pv button { position: absolute; top: 6px; right: 6px; display: grid; width: 24px; height: 24px; place-items: center; padding: 0; border: 0; border-radius: 50%; background: rgba(42, 10, 11, .75); color: #fff; font-size: 11px; line-height: 1; cursor: pointer; }
    .gla-pv button:hover { background: var(--gla-red); }

    .gla-fields { display: grid; grid-template-columns: 1.1fr 1.4fr .8fr auto; align-items: end; gap: 14px; margin-top: 20px; }

    /* ---------- tabel ---------- */
    .gla-table { margin: 0; }
    .gla-table thead th { padding: 12px 14px; border-top: 0; border-bottom: 1px solid var(--gla-line); background: #fff; color: var(--gla-muted); font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap; }
    .gla-table tbody td { padding: 14px; border-top: 1px solid #f3eaeb; vertical-align: middle; }
    .gla-table tbody tr { transition: background .2s ease; }
    .gla-table tbody tr:hover { background: #fffafa; }
    .gla-table tbody tr.is-dirty { background: #fffbef; box-shadow: inset 3px 0 0 var(--gla-gold); }
    .gla-table tbody tr.is-selected { background: #fdf2f4 !important; }
    .gla-check-col { width: 44px; text-align: center; vertical-align: middle !important; padding: 12px 8px !important; }
    .gla-check-box { width: 17px; height: 17px; cursor: pointer; accent-color: var(--gla-red); vertical-align: middle; }
    .gla-bulk-actions { display: inline-flex; align-items: center; gap: 8px; }
    .gla-bulk-pill { padding: 4px 10px; border-radius: 20px; background: #fdeff2; color: #8a1b2e; font-size: .78rem; font-weight: 700; border: 1px solid #f0c1c8; }
    .gla-table .form-control-sm { height: 38px; border-radius: 9px; }

    .gla-thumb { position: relative; display: block; width: 92px; }
    .gla-thumb img { display: block; width: 92px; height: 66px; object-fit: cover; border-radius: 10px; border: 1px solid var(--gla-line); }
    .gla-ganti { display: inline-flex; align-items: center; gap: 5px; margin: 6px 0 0; padding: 0; border: 0; background: none; color: var(--gla-red); font-size: .72rem; font-weight: 700; cursor: pointer; }
    .gla-ganti:hover { text-decoration: underline; }
    .gla-ganti-nama { display: block; max-width: 92px; overflow: hidden; color: var(--gla-muted); font-size: .68rem; text-overflow: ellipsis; white-space: nowrap; }
    .gla-ganti-nama:empty { display: none; }

    .gla-status.form-control-sm { font-weight: 700; }
    .gla-status[data-s="Aktif"] { border-color: #bfe3cc; background-color: #eaf6ee; color: #17603a; }
    .gla-status[data-s="Nonaktif"] { border-color: #e3d4d7; background-color: #f4eeef; color: #7a6a6f; }

    .gla-aksi { display: flex; align-items: center; justify-content: flex-end; gap: 6px; white-space: nowrap; }
    .gla-aksi .btn { border-radius: 9px; font-size: .8rem; font-weight: 600; }
    .gla-aksi form { margin: 0; }

    .gla-empty { padding: 50px 20px; text-align: center; color: var(--gla-muted); }
    .gla-empty i { display: grid; width: 60px; height: 60px; place-items: center; margin: 0 auto 12px; border-radius: 50%; background: var(--gla-tint); color: var(--gla-red); font-size: 22px; }
    .gla-empty strong { display: block; margin-bottom: 4px; color: var(--gla-ink); }

    .gla-foot { padding: 14px 22px; border-top: 1px solid var(--gla-line); background: var(--gla-tint); }
    .gla-foot:empty { display: none; }

    @media (max-width: 991.98px) { .gla-fields { grid-template-columns: 1fr 1fr; } .gla-fields .gla-submit { grid-column: 1 / -1; } }
    @media (max-width: 575.98px) { .gla-card-body { padding: 16px; } .gla-fields { grid-template-columns: 1fr; } .gla-submit .btn { width: 100%; } }
    @media (prefers-reduced-motion: reduce) { .gla-drop, .gla .btn-gla, .gla-table tbody tr { transition: none; } }
</style>

<div class="container-fluid gla">

    {{-- ================= JUDUL ================= --}}
    <div class="gla-head">
        <div>
            <h1>Galeri Foto</h1>
            <p>Kelola foto yang tampil di halaman Galeri. Hanya foto berstatus <strong>Aktif</strong> yang terlihat pengunjung.</p>
        </div>

        <div class="gla-head-actions">
            <a href="{{ route('galeri.index') }}" target="_blank" rel="noopener" class="btn btn-sm btn-gla-line">
                <i class="fas fa-external-link-alt mr-1 me-1"></i> Lihat halaman publik
            </a>
        </div>
    </div>


    {{-- ================= PESAN ================= --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px">
            <i class="fas fa-check-circle mr-1 me-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px">
            <i class="fas fa-exclamation-triangle mr-1 me-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert" style="border-radius:12px">
            <strong>Ada yang perlu diperbaiki:</strong>
            <ul class="mb-0 pl-3 ps-3">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ================= UNGGAH ================= --}}
    <div class="gla-card">
        <div class="gla-card-head">
            <h2><i class="fas fa-cloud-upload-alt mr-2 me-2"></i>Tambah foto</h2>
            <small>Boleh banyak sekaligus</small>
        </div>

        <div class="gla-card-body">

            <form action="{{ route('galeri-admin.store') }}" method="POST" enctype="multipart/form-data" id="glaUpload" novalidate>
                @csrf

                <label for="foto" class="gla-drop" id="glaDrop">
                    <i class="fas fa-images" aria-hidden="true"></i>
                    <strong>Tarik foto ke sini</strong>
                    <span>atau <u>pilih dari komputer</u></span>
                    <small>JPG, PNG, atau WEBP · maksimal 4 MB per foto · 20 foto per unggahan</small>
                </label>
                <input type="file" name="foto[]" id="foto" class="gla-file-input" accept=".jpg,.jpeg,.png,.webp" multiple>

                <div class="gla-err" id="glaErr" role="alert" hidden></div>

                <div class="gla-preview" id="glaPreview" aria-live="polite"></div>

                <div class="gla-fields">

                    <div>
                        <label class="gla-lbl" for="kategori">Kategori</label>
                        <input type="text" name="kategori" id="kategori" class="form-control" list="daftarKategori" maxlength="60" placeholder="Contoh: Ruang Perawatan" value="{{ old('kategori') }}">
                        <datalist id="daftarKategori">
                            @foreach ($kategori as $k)
                                <option value="{{ $k }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="gla-lbl" for="judul">Judul <span class="text-muted font-weight-normal fw-normal">(opsional)</span></label>
                        <input type="text" name="judul" id="judul" class="form-control" maxlength="150" placeholder="Berlaku untuk semua foto di unggahan ini" value="{{ old('judul') }}">
                    </div>

                    <div>
                        <label class="gla-lbl" for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="gla-submit">
                        <button type="submit" class="btn btn-gla px-4" id="glaKirim" disabled>
                            <i class="fas fa-upload mr-1 me-1"></i> <span id="glaKirimTeks">Unggah</span>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>


    {{-- ================= DAFTAR ================= --}}
    <div class="gla-card">
        <div class="gla-card-head">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <h2><i class="fas fa-th-large mr-2 me-2"></i>Semua foto</h2>
                <small>{{ $galeri->total() }} foto</small>
            </div>

            {{-- Form & Aksi Hapus Massal --}}
            <div class="gla-bulk-actions" id="glaBulkToolbar">
                <span id="glaLabelTerpilih" class="gla-bulk-pill" style="display: none;">
                    <i class="fas fa-check-square mr-1 me-1"></i> <span id="glaJmlTerpilih">0</span> foto dipilih
                </span>

                <form action="{{ route('galeri-admin.hapus-massal') }}" method="POST" id="formHapusMassal" style="margin: 0; display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnHapusMassal" class="btn btn-sm btn-danger text-white shadow-sm" style="display: none; border-radius: 9px; font-weight: 600;">
                        <i class="far fa-trash-alt mr-1 me-1"></i> Hapus Foto Terpilih (<span id="btnHapusCount">0</span>)
                    </button>
                </form>
            </div>
        </div>

        @if ($galeri->count() > 0)

            <div class="table-responsive">
                <table class="table gla-table">
                    <thead>
                        <tr>
                            <th class="gla-check-col">
                                <input type="checkbox" id="glaPilihSemua" class="gla-check-box" title="Pilih Semua Foto di Halaman Ini" aria-label="Pilih Semua">
                            </th>
                            <th style="width:128px">Foto</th>
                            <th>Judul</th>
                            <th style="min-width:160px">Kategori</th>
                            <th style="width:96px">Urutan</th>
                            <th style="width:140px">Status</th>
                            <th style="width:190px" class="text-right text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($galeri as $g)
                            @php $fid = 'u' . $g->id; @endphp

                            <tr data-form="{{ $fid }}" data-id="{{ $g->id }}">

                                <td class="gla-check-col">
                                    <input type="checkbox" name="ids[]" value="{{ $g->id }}" class="gla-check-box gla-check-item" form="formHapusMassal" aria-label="Pilih foto {{ $g->judul ?: 'ID '.$g->id }}">
                                </td>

                                <td>
                                    {{-- form ubah per baris (kolom lain terhubung lewat atribut form=) --}}
                                    <form action="{{ route('galeri-admin.update', $g->id) }}" method="POST" id="{{ $fid }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <a href="{{ $srcFoto($g->foto) ?: '#' }}" target="_blank" rel="noopener" class="gla-thumb" title="Buka foto penuh">
                                        <img src="{{ $srcFoto($g->foto) ?: $kosong }}" alt="{{ $g->judul ?: 'Foto galeri' }}" data-awal="{{ $srcFoto($g->foto) ?: $kosong }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $kosong }}'">
                                    </a>

                                    <label class="gla-ganti">
                                        <i class="fas fa-sync-alt" aria-hidden="true"></i> Ganti foto
                                        <input form="{{ $fid }}" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" class="gla-file-input">
                                    </label>
                                    <span class="gla-ganti-nama"></span>
                                </td>

                                <td>
                                    <input form="{{ $fid }}" type="text" name="judul" value="{{ $g->judul }}" class="form-control form-control-sm" maxlength="150" placeholder="Tanpa judul" aria-label="Judul">
                                </td>

                                <td>
                                    <input form="{{ $fid }}" type="text" name="kategori" value="{{ $g->kategori }}" class="form-control form-control-sm" list="daftarKategori" maxlength="60" placeholder="Tanpa kategori" aria-label="Kategori">
                                </td>

                                <td>
                                    <input form="{{ $fid }}" type="number" name="urutan" value="{{ $g->urutan }}" class="form-control form-control-sm" min="0" max="65535" aria-label="Urutan">
                                </td>

                                <td>
                                    <select form="{{ $fid }}" name="status" class="form-control form-control-sm gla-status" data-s="{{ $g->status }}" aria-label="Status">
                                        <option value="Aktif" {{ $g->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Nonaktif" {{ $g->status === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </td>

                                <td>
                                    <div class="gla-aksi">
                                        <button form="{{ $fid }}" type="submit" class="btn btn-sm btn-gla gla-simpan" disabled>
                                            <i class="fas fa-save mr-1 me-1"></i> Simpan
                                        </button>

                                        <form action="{{ route('galeri-admin.destroy', $g->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus foto ini dari galeri?\nFoto yang dihapus tidak bisa dikembalikan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus foto" title="Hapus">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="gla-foot">
                {{ $galeri->links() }}
            </div>

        @else

            <div class="gla-empty">
                <i class="far fa-images" aria-hidden="true"></i>
                <strong>Belum ada foto</strong>
                Unggah foto pertama lewat kotak di atas.
            </div>

        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       1. Unggah: tarik dan lepas, pratinjau, pemeriksaan di browser
    ========================================================= */
    var MAKS_FOTO = 20;
    var MAKS_BYTE = 4 * 1024 * 1024;
    var TIPE_OK   = ['image/jpeg', 'image/png', 'image/webp'];

    var form    = document.getElementById('glaUpload');
    var input   = document.getElementById('foto');
    var drop    = document.getElementById('glaDrop');
    var preview = document.getElementById('glaPreview');
    var kotakErr = document.getElementById('glaErr');
    var tombol  = document.getElementById('glaKirim');
    var teksTombol = document.getElementById('glaKirimTeks');

    var berkas = [];
    var urlSementara = [];

    function ukuran(b) {
        if (b >= 1048576) return (b / 1048576).toFixed(1).replace('.', ',') + ' MB';
        return Math.max(1, Math.round(b / 1024)) + ' KB';
    }

    function tampilkanPesan(daftar) {
        if (!daftar.length) { kotakErr.hidden = true; kotakErr.innerHTML = ''; return; }

        var ul = document.createElement('ul');
        daftar.forEach(function (t) {
            var li = document.createElement('li');
            li.textContent = t;
            ul.appendChild(li);
        });
        kotakErr.innerHTML = '';
        kotakErr.appendChild(ul);
        kotakErr.hidden = false;
    }

    function sinkron() {
        // samakan isi input dengan daftar berkas yang lolos pemeriksaan
        var dt = new DataTransfer();
        berkas.forEach(function (f) { dt.items.add(f); });
        input.files = dt.files;

        urlSementara.forEach(function (u) { URL.revokeObjectURL(u); });
        urlSementara = [];
        preview.innerHTML = '';

        berkas.forEach(function (f, i) {
            var u = URL.createObjectURL(f);
            urlSementara.push(u);

            var kartu = document.createElement('div');
            kartu.className = 'gla-pv';

            var img = document.createElement('img');
            img.src = u;
            img.alt = '';

            var nama = document.createElement('span');
            nama.textContent = f.name + ' · ' + ukuran(f.size);
            nama.title = f.name;

            var hapus = document.createElement('button');
            hapus.type = 'button';
            hapus.setAttribute('aria-label', 'Buang ' + f.name);
            hapus.innerHTML = '&times;';
            hapus.addEventListener('click', function () {
                berkas.splice(i, 1);
                tampilkanPesan([]);
                sinkron();
            });

            kartu.appendChild(img);
            kartu.appendChild(hapus);
            kartu.appendChild(nama);
            preview.appendChild(kartu);
        });

        tombol.disabled = berkas.length === 0;
        teksTombol.textContent = berkas.length ? 'Unggah ' + berkas.length + ' foto' : 'Unggah';
    }

    function tambah(daftarFile) {
        var pesan = [];

        Array.prototype.forEach.call(daftarFile, function (f) {
            if (TIPE_OK.indexOf(f.type) === -1) { pesan.push(f.name + ': bukan JPG, PNG, atau WEBP.'); return; }
            if (f.size > MAKS_BYTE)             { pesan.push(f.name + ': ' + ukuran(f.size) + ', melebihi 4 MB.'); return; }

            var kembar = berkas.some(function (x) { return x.name === f.name && x.size === f.size && x.lastModified === f.lastModified; });
            if (kembar) return;

            if (berkas.length >= MAKS_FOTO) {
                if (pesan.indexOf('Maksimal ' + MAKS_FOTO + ' foto per unggahan.') === -1) pesan.push('Maksimal ' + MAKS_FOTO + ' foto per unggahan.');
                return;
            }
            berkas.push(f);
        });

        tampilkanPesan(pesan);
        sinkron();
    }

    if (form && input && drop) {

        input.addEventListener('change', function () { tambah(input.files); });

        ['dragenter', 'dragover'].forEach(function (nm) {
            drop.addEventListener(nm, function (e) { e.preventDefault(); drop.classList.add('is-over'); });
        });
        ['dragleave', 'drop'].forEach(function (nm) {
            drop.addEventListener(nm, function (e) { e.preventDefault(); drop.classList.remove('is-over'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files) tambah(e.dataTransfer.files);
        });

        form.addEventListener('submit', function (e) {
            if (!berkas.length) {
                e.preventDefault();
                tampilkanPesan(['Pilih minimal satu foto dulu.']);
                return;
            }
            tombol.disabled = true;
            teksTombol.textContent = 'Mengunggah...';
        });
    }


    /* =========================================================
       2. Tabel: tombol Simpan baru aktif bila baris diubah
    ========================================================= */
    document.querySelectorAll('tr[data-form]').forEach(function (baris) {
        var id = baris.getAttribute('data-form');
        var kontrol = document.querySelectorAll('[form="' + id + '"]:not(button)');
        var simpan = baris.querySelector('.gla-simpan');
        var status = baris.querySelector('.gla-status');
        var img = baris.querySelector('.gla-thumb img');
        var namaBerkas = baris.querySelector('.gla-ganti-nama');

        kontrol.forEach(function (k) {
            if (k.type !== 'file') k.setAttribute('data-awal', k.value);
        });

        function periksa() {
            var berubah = false;

            kontrol.forEach(function (k) {
                if (k.type === 'file') { if (k.files && k.files.length) berubah = true; }
                else if (k.value !== k.getAttribute('data-awal')) berubah = true;
            });

            baris.classList.toggle('is-dirty', berubah);
            if (simpan) simpan.disabled = !berubah;
        }

        kontrol.forEach(function (k) {
            k.addEventListener('input', periksa);
            k.addEventListener('change', function () {

                if (k === status) status.setAttribute('data-s', status.value);

                if (k.type === 'file') {
                    var f = k.files && k.files[0];

                    if (f && (TIPE_OK.indexOf(f.type) === -1 || f.size > MAKS_BYTE)) {
                        alert('Foto harus JPG, PNG, atau WEBP dan maksimal 4 MB.');
                        k.value = '';
                    }

                    f = k.files && k.files[0];
                    if (f) {
                        img.src = URL.createObjectURL(f);
                        namaBerkas.textContent = f.name;
                    } else {
                        img.src = img.getAttribute('data-awal');
                        namaBerkas.textContent = '';
                    }
                }

                periksa();
            });
        });
    });

    /* =========================================================
       3. Pilihan Massal (Bulk Delete): pilih banyak foto & hapus bersamaan
    ========================================================= */
    var cbPilihSemua = document.getElementById('glaPilihSemua');
    var listCbItem   = document.querySelectorAll('.gla-check-item');
    var labelTerpilih = document.getElementById('glaLabelTerpilih');
    var jmlTerpilih  = document.getElementById('glaJmlTerpilih');
    var btnHapus     = document.getElementById('btnHapusMassal');
    var btnHapusCount = document.getElementById('btnHapusCount');
    var formHapus    = document.getElementById('formHapusMassal');

    function updatePilihanMassal() {
        var checkedItems = document.querySelectorAll('.gla-check-item:checked');
        var count = checkedItems.length;

        listCbItem.forEach(function (cb) {
            var tr = cb.closest('tr');
            if (tr) {
                tr.classList.toggle('is-selected', cb.checked);
            }
        });

        if (count > 0) {
            if (jmlTerpilih) jmlTerpilih.textContent = count;
            if (btnHapusCount) btnHapusCount.textContent = count;
            if (labelTerpilih) labelTerpilih.style.display = 'inline-block';
            if (btnHapus) btnHapus.style.display = 'inline-block';
        } else {
            if (labelTerpilih) labelTerpilih.style.display = 'none';
            if (btnHapus) btnHapus.style.display = 'none';
        }

        if (cbPilihSemua) {
            cbPilihSemua.checked = (count > 0 && count === listCbItem.length);
            cbPilihSemua.indeterminate = (count > 0 && count < listCbItem.length);
        }
    }

    if (cbPilihSemua) {
        cbPilihSemua.addEventListener('change', function () {
            var state = cbPilihSemua.checked;
            listCbItem.forEach(function (cb) {
                cb.checked = state;
            });
            updatePilihanMassal();
        });
    }

    listCbItem.forEach(function (cb) {
        cb.addEventListener('change', updatePilihanMassal);
    });

    if (formHapus) {
        formHapus.addEventListener('submit', function (e) {
            var checkedItems = document.querySelectorAll('.gla-check-item:checked');
            if (checkedItems.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu foto galeri yang ingin dihapus.');
                return false;
            }

            var konfirmasi = confirm('Yakin ingin menghapus ' + checkedItems.length + ' foto terpilih dari galeri?\nFoto yang dihapus tidak bisa dikembalikan.');
            if (!konfirmasi) {
                e.preventDefault();
                return false;
            }

            window.__glaMenyimpan = true;

            // Bersihkan hidden inputs lama
            formHapus.querySelectorAll('input[name="ids[]"]').forEach(function (el) { el.remove(); });

            // Suntikkan ID yang dipilih ke dalam form
            checkedItems.forEach(function (cb) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                formHapus.appendChild(input);
            });

            if (btnHapus) {
                btnHapus.disabled = true;
                btnHapus.innerHTML = '<i class="fas fa-spinner fa-spin mr-1 me-1"></i> Menghapus...';
            }
        });
    }

    // beri peringatan bila ada perubahan baris yang belum disimpan
    window.addEventListener('beforeunload', function (e) {
        if (document.querySelector('tr.is-dirty') && !window.__glaMenyimpan) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    document.querySelectorAll('tr[data-form] form, #glaUpload, #formHapusMassal').forEach(function (f) {
        f.addEventListener('submit', function (e) { if (!e.defaultPrevented) window.__glaMenyimpan = true; });
    });
});
</script>

@endsection