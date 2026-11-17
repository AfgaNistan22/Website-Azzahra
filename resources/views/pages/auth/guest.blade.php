
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Azzahra Salon & Spa Muslimah')</title>

    <meta name="description"
          content="Salon dan Spa Muslimah dengan pelayanan profesional, nyaman, dan berkualitas.">

    <link rel="icon"
          type="image/png"
          href="{{ asset('template/img/logoicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('template/css/guest.css') }}">

    @stack('styles')
</head>

<body>

    @include('pages.partials.top-header')

    @include('pages.partials.logo-search')
    @include('pages.partials.navbar')

    @yield('content')

    @include('pages.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>

    <script src="{{ asset('template/js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>
