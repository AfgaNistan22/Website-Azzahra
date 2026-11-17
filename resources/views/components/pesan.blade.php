{{-- SUCCESS --}}
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
@endif


{{-- ERROR --}}
@if (Session::has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(Session::get('error'))
    });
</script>
@endif

{{-- VALIDATION ERROR --}}
@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: `{!! implode('<br>', $errors->all()) !!}`
    });
</script>
@endif
