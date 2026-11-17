<script>
document.addEventListener('DOMContentLoaded', function () {

    const periode            = document.getElementById('periode');
    const loading            = document.getElementById('loadingPreview');
    const empty              = document.getElementById('emptyPreview');
    const previewPemasukan   = document.getElementById('previewPemasukan');
    const previewPengeluaran = document.getElementById('previewPengeluaran');
    const previewLaba        = document.getElementById('previewLaba');
    const jumlahPemasukan    = document.getElementById('jumlahPemasukan');
    const jumlahPengeluaran  = document.getElementById('jumlahPengeluaran');

    const emptyDefault = empty.innerHTML;
    const previewUrl   = @json(route('laporan_bulanan.preview'));

    // Cegah respons lama menimpa respons baru kalau bulan diganti cepat-cepat
    let requestId = 0;

    function formatRupiah(angka) {
        const n = Number(angka || 0);
        return (n < 0 ? '-Rp ' : 'Rp ') + Math.abs(n).toLocaleString('id-ID');
    }

    function loadPreview() {

        if (!periode.value) {
            return;
        }

        const current = ++requestId;

        loading.style.display = 'block';
        empty.style.display   = 'none';
        empty.innerHTML       = emptyDefault;

        fetch(previewUrl + '?periode=' + encodeURIComponent(periode.value), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Gagal mengambil data.');
            }
            return response.json();
        })
        .then(function (data) {

            if (current !== requestId) {
                return;
            }

            if (!data.success) {
                throw new Error(data.message || 'Data gagal dihitung.');
            }

            previewPemasukan.textContent   = formatRupiah(data.total_pemasukan);
            previewPengeluaran.textContent = formatRupiah(data.total_pengeluaran);
            previewLaba.textContent        = formatRupiah(data.laba_bersih);

            jumlahPemasukan.textContent   = data.jumlah_pemasukan;
            jumlahPengeluaran.textContent = data.jumlah_pengeluaran;

            previewLaba.classList.remove('lh-positive', 'lh-negative');

            const laba = Number(data.laba_bersih);

            if (laba > 0) {
                previewLaba.classList.add('lh-positive');
            } else if (laba < 0) {
                previewLaba.classList.add('lh-negative');
            }

            if (Number(data.jumlah_pemasukan) === 0 && Number(data.jumlah_pengeluaran) === 0) {
                empty.style.display = 'block';
            }

        })
        .catch(function (error) {

            if (current !== requestId) {
                return;
            }

            console.error(error);

            empty.style.display = 'block';
            empty.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Data keuangan gagal dimuat.';

        })
        .finally(function () {

            if (current === requestId) {
                loading.style.display = 'none';
            }

        });
    }

    periode.addEventListener('change', loadPreview);

    loadPreview();

});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.form-hapus').forEach(function (form) {

    form.addEventListener('submit', function (e) {

        e.preventDefault();

        Swal.fire({
            title: 'Hapus laporan?',
            text: 'Data laporan bulanan akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e01c1d',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });

    });

});

@if (session('success'))
Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')), confirmButtonColor: '#e01c1d' });
@endif

@if (session('error'))
Swal.fire({ icon: 'error', title: 'Gagal', text: @json(session('error')), confirmButtonColor: '#e01c1d' });
@endif
</script>