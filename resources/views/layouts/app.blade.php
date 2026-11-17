<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">


    {{-- =========================================================
        TITLE
    ========================================================== --}}

    <title>Azzahra - Dashboard</title>


    {{-- =========================================================
        META
    ========================================================== --}}

    <meta name="description" content="">

    <meta name="author" content="">


    {{-- =========================================================
        JQUERY
    ========================================================== --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{-- =========================================================
        FAVICON
    ========================================================== --}}

    <link rel="icon"
          type="image/png"
          href="{{ asset('template/img/logoicon.png') }}">


    {{-- =========================================================
        FONT AWESOME
    ========================================================== --}}

    <link
        href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}"
        rel="stylesheet"
        type="text/css">


    {{-- =========================================================
        GOOGLE FONT
    ========================================================== --}}

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
        rel="stylesheet">


    {{-- =========================================================
        CROPPER JS
    ========================================================== --}}

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"
        rel="stylesheet">


    {{-- =========================================================
        SB ADMIN 2 CSS
    ========================================================== --}}

    <link
        href="{{ asset('template/css/sb-admin-2.min.css') }}"
        rel="stylesheet">


    {{-- =========================================================
        CUSTOM CSS GLOBAL
    ========================================================== --}}

    <style>

        .bg-pink {

            background-color: #e83e8c;

        }


        .border-pink {

            border-color: #e83e8c !important;

        }


        /*
        |--------------------------------------------------------------------------
        | CUSTOM SCROLLBAR
        |--------------------------------------------------------------------------
        */

        ::-webkit-scrollbar {

            width: 7px;

            height: 7px;

        }


        ::-webkit-scrollbar-track {

            background: #f1f1f1;

        }


        ::-webkit-scrollbar-thumb {

            background: #b7b9cc;

            border-radius: 10px;

        }


        ::-webkit-scrollbar-thumb:hover {

            background: #858796;

        }


        /*
        |--------------------------------------------------------------------------
        | GLOBAL
        |--------------------------------------------------------------------------
        */

        body {

            font-family: 'Nunito', sans-serif;

        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .btn {

            border-radius: 6px;

        }


        /*
        |--------------------------------------------------------------------------
        | FORM CONTROL
        |--------------------------------------------------------------------------
        */

        .form-control {

            border-radius: 6px;

        }


        /*
        |--------------------------------------------------------------------------
        | CARD
        |--------------------------------------------------------------------------
        */

        .card {

            border-radius: 10px;

        }


        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        .table td,
        .table th {

            vertical-align: middle;

        }


    </style>


    {{-- =========================================================
        STACK CSS DARI HALAMAN
    ========================================================== --}}

    @stack('styles')


</head>


<body id="page-top">


@if (Auth::check())


    {{-- =========================================================
        PAGE WRAPPER
    ========================================================== --}}

    <div id="wrapper">


        {{-- =====================================================
            SIDEBAR
        ====================================================== --}}

        @include('layouts.sidebar')


        {{-- =====================================================
            CONTENT WRAPPER
        ====================================================== --}}

        <div id="content-wrapper"
             class="d-flex flex-column">


            {{-- =================================================
                MAIN CONTENT
            ================================================== --}}

            <div id="content">


                {{-- =================================================
                    NAVBAR
                ================================================== --}}

                @include('layouts.navbar')


                {{-- =================================================
                    PAGE CONTENT
                ================================================== --}}

                <div class="container-fluid">


                    {{-- =============================================
                        GLOBAL MESSAGE
                    ============================================== --}}

                    @include('components.pesan')


                    {{-- =============================================
                        CONTENT DARI HALAMAN
                    ============================================== --}}

                    @yield('content')


                </div>


            </div>


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <footer class="sticky-footer bg-white">


                <div class="container my-auto">


                    <div class="copyright text-center my-auto">


                        <span>

                            Copyright &copy; Azzahra
                            {{ date('Y') }}

                        </span>


                    </div>


                </div>


            </footer>


        </div>


    </div>


    {{-- =========================================================
        SCROLL TO TOP
    ========================================================== --}}

    <a class="scroll-to-top rounded"
       href="#page-top">

        <i class="fas fa-angle-up"></i>

    </a>


    {{-- =========================================================
        LOGOUT MODAL
    ========================================================== --}}

    <div class="modal fade"
         id="logoutModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">


        <div class="modal-dialog"
             role="document">


            <form action="{{ url('/logout') }}"
                  method="POST">


                @csrf


                <div class="modal-content">


                    {{-- HEADER --}}

                    <div class="modal-header">


                        <h5 class="modal-title">

                            Yakin mau keluar?

                        </h5>


                        <button
                            class="close"
                            type="button"
                            data-dismiss="modal"
                            aria-label="Close">

                            <span>

                                &times;

                            </span>

                        </button>


                    </div>


                    {{-- BODY --}}

                    <div class="modal-body">

                        Apakah Anda yakin akan keluar
                        dari aplikasi?

                    </div>


                    {{-- FOOTER --}}

                    <div class="modal-footer">


                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">

                            Kembali

                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fas fa-sign-out-alt mr-1"></i>

                            Logout

                        </button>


                    </div>


                </div>


            </form>


        </div>


    </div>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}


    {{-- BOOTSTRAP --}}

    <script
        src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}">
    </script>


    {{-- JQUERY EASING --}}

    <script
        src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}">
    </script>


    {{-- SB ADMIN 2 --}}

    <script
        src="{{ asset('template/js/sb-admin-2.min.js') }}">
    </script>


    {{-- SWEET ALERT --}}

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>


@else


    {{-- =========================================================
        JIKA BELUM LOGIN
    ========================================================== --}}

    <script>

        window.location.href = "{{ url('/') }}";

    </script>


@endif


{{-- =========================================================
    CROPPER JS
========================================================== --}}

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js">
</script>


{{-- =========================================================
    STACK JAVASCRIPT DARI HALAMAN
========================================================== --}}

@stack('scripts')


</body>

</html>