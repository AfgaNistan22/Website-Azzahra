<script>
document.addEventListener('DOMContentLoaded', function () {

    const kategori        = document.getElementById('kategori');
    const tanggalMulai    = document.getElementById('tanggal_mulai');
    const tanggalSelesai  = document.getElementById('tanggal_selesai');
    const blokTanggal     = document.getElementById('blokTanggal');
    const loading         = document.getElementById('loadingPreview');
    const empty           = document.getElementById('emptyPreview');
    const emptyDefault    = empty.innerHTML;
    const previewJumlah   = document.getElementById('previewJumlah');
    const previewNominal  = document.getElementById('previewNominal');
    const nominalLabel    = document.getElementById('previewNominalLabel');
    const boxNominal      = document.getElementById('boxNominal');
    const daftarKolom     = document.getElementById('daftarKolom');

    const previewUrl   = @json(route('laporan_master.preview'));
    const kategoriForm = @json($kategoriForm);

    // Cegah respons lama menimpa respons baru
    let requestId = 0;

    function formatRupiah(angka) {
        const n = Number(angka || 0);
        return (n < 0 ? '-Rp ' : 'Rp ') + Math.abs(n).toLocaleString('id-ID');
    }

    function aturBlokTanggal() {
        const info = kategoriForm[kategori.value];
        const ada  = info ? info.ada_tanggal : false;

        blokTanggal.style.display = ada ? 'block' : 'none';

        if (!ada) {
            tanggalMulai.value   = '';
            tanggalSelesai.value = '';
        }
    }

    function loadPreview() {

        if (!kategori.value) {
            return;
        }

        const current = ++requestId;

        loading.style.display = 'block';
        empty.style.display   = 'none';
        empty.innerHTML       = emptyDefault;

        const params = new URLSearchParams({ kategori: kategori.value });

        if (tanggalMulai.value)   { params.set('tanggal_mulai', tanggalMulai.value); }
        if (tanggalSelesai.value) { params.set('tanggal_selesai', tanggalSelesai.value); }

        fetch(previewUrl + '?' + params.toString(), {
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

            previewJumlah.textContent = Number(data.jumlah_data).toLocaleString('id-ID');

            if (data.kolom_nominal) {
                boxNominal.style.display = 'block';
                nominalLabel.textContent = 'Total ' + data.kolom_nominal;
                previewNominal.textContent = formatRupiah(data.total_nominal);
            } else {
                boxNominal.style.display = 'none';
            }

            daftarKolom.innerHTML = '';

            (data.kolom || []).forEach(function (nama) {
                const chip = document.createElement('span');
                chip.className = 'chip-kolom-elite';
                chip.textContent = nama;
                daftarKolom.appendChild(chip);
            });

            if (Number(data.jumlah_data) === 0) {
                empty.style.display = 'block';
            }

        })
        .catch(function (error) {

            if (current !== requestId) {
                return;
            }

            console.error(error);

            empty.style.display = 'block';
            empty.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Data gagal dimuat.';

        })
        .finally(function () {

            if (current === requestId) {
                loading.style.display = 'none';
            }

        });
    }

    kategori.addEventListener('change', function () {
        aturBlokTanggal();
        loadPreview();
    });

    tanggalMulai.addEventListener('change', loadPreview);
    tanggalSelesai.addEventListener('change', loadPreview);

    aturBlokTanggal();
    loadPreview();

});
</script>