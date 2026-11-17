
<div class="logo-section">
    <div class="container logo-content">

        {{-- LOGO DAN NAMA SALON --}}
        <a href="{{ route('home') }}" class="brand">
            <img
                src="{{ asset('template/img/logoicon.png') }}"
                alt="Logo Azzahra Salon & Spa Muslimah"
                onerror="this.onerror=null; this.src='{{ asset('template/img/service-default.jpg') }}';"
            >

            <div class="brand-text">
                <h1>Azzahra Salon & Spa</h1>
                <p>Muslimah Beauty & Relaxation</p>
            </div>
        </a>

        {{-- PENCARIAN LAYANAN --}}
        <form action="{{ route('home') }}" method="GET" class="search-box">
            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari layanan perawatan..."
                aria-label="Cari layanan perawatan"
            >

            <button type="submit" aria-label="Cari layanan">
                <i class="fas fa-search"></i>
            </button>
        </form>

    </div>
</div>

@push('styles')
<style>
    .logo-section {
        padding: 20px 0;
        background: #fff;
        border-bottom: 1px solid #f3e4e9;
    }

    .logo-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        color: #501322;
        text-decoration: none;
    }

    .brand img {
        width: 65px;
        height: 65px;
        flex-shrink: 0;
        object-fit: contain;
        border-radius: 12px;
    }

    .brand-text h1 {
        margin: 0;
        color: #501322;
        font-size: 23px;
        font-weight: 800;
        line-height: 1.4;
    }

    .brand-text p {
        margin: 3px 0 0;
        color: #9a6877;
        font-size: 12px;
        letter-spacing: .5px;
    }

    .search-box {
        display: flex;
        width: 380px;
        max-width: 100%;
        overflow: hidden;
        border: 1px solid #ead5dc;
        border-radius: 30px;
        background: #fff8fa;
        transition: border-color .2s, box-shadow .2s;
    }

    .search-box:focus-within {
        border-color: #d4145a;
        box-shadow: 0 0 0 3px rgba(212, 20, 90, .08);
    }

    .search-box input {
        width: 100%;
        min-width: 0;
        padding: 13px 18px;
        border: none;
        outline: none;
        background: transparent;
        color: #501322;
        font-size: 13px;
    }

    .search-box input::placeholder {
        color: #a78b94;
    }

    .search-box button {
        width: 50px;
        flex-shrink: 0;
        border: none;
        background: #d4145a;
        color: #fff;
        cursor: pointer;
        transition: background .2s;
    }

    .search-box button:hover {
        background: #8b102e;
    }

    @media (max-width: 767px) {
        .logo-section {
            padding: 16px 0;
        }

        .logo-content {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }

        .brand img {
            width: 52px;
            height: 52px;
        }

        .brand-text h1 {
            font-size: 19px;
        }

        .brand-text p {
            font-size: 11px;
        }

        .search-box {
            width: 100%;
        }
    }
</style>
@endpush
