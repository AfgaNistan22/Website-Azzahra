    {{-- TOPBAR --}}
<nav class="navbar navbar-expand navbar-light topbar-elite mb-4 static-top"
     style="z-index: 1035 !important; position: relative;">

    {{-- Sidebar Toggle Mobile --}}
    <button id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle mr-3"
            type="button"
            aria-label="Buka sidebar">
        <i class="fa fa-bars"></i>
    </button>

    {{-- Topbar Navbar --}}
    <ul class="navbar-nav ml-auto align-items-center">

        @auth
        @php
            $user = auth()->user();

            // Ambil nama role dari relasi role.
            $roleName = $user->role?->name
                ?? ($user->role_id == 1 ? 'Admin'
                : ($user->role_id == 2 ? 'Pelanggan' : 'Member'));

            // Foto profil.
            $photoUrl = $user->photo
                ? \Illuminate\Support\Facades\Storage::url($user->photo)
                : asset('template/img/undraw_profile.svg');
        @endphp

        <li class="nav-item dropdown no-arrow ml-3">

            {{-- User Toggle --}}
            <a class="nav-link dropdown-toggle d-flex align-items-center user-toggle"
               href="#"
               id="userDropdown"
               role="button"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">

                <span class="mr-3 d-none d-lg-flex flex-column text-right">
                    <span class="user-name">
                        Halo, 
                        {{ $user->name }}
                    </span>

                    <span class="user-role">
                        {{ $roleName }}
                    </span>
                </span>

                <span class="avatar-ring">
                    <img
                        class="img-profile rounded-circle"
                        src="{{ $photoUrl }}"
                        alt="Foto profil {{ $user->name }}"
                        style="width: 38px; height: 38px; object-fit: cover;"
                        onerror="this.onerror=null; this.src='{{ asset('template/img/undraw_profile.svg') }}';">
                </span>
            </a>

            {{-- Dropdown Menu --}}
            <div class="dropdown-menu dropdown-menu-right shadow-lg animated--grow-in elite-dropdown"
                 aria-labelledby="userDropdown">

                {{-- Header --}}
                <div class="dropdown-header-elite">
                    <div class="header-name">
                        {{ $user->name }}
                    </div>

                    <div class="header-email">
                        {{ $user->email }}
                    </div>

                    <div class="header-role">
                        {{ $roleName }}
                    </div>
                </div>

                {{-- Profile --}}
                <a class="dropdown-item elite-item"
                   href="{{ url('/profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                    Profile
                </a>

                {{-- Ubah Password --}}
                <a class="dropdown-item elite-item"
                   href="{{ url('/change') }}">
                    <i class="fas fa-key fa-sm fa-fw mr-2"></i>
                    Ubah Password
                </a>

                {{-- Kelola Akun User (khusus admin) --}}
                @if ($user->isAdmin())
                    <a class="dropdown-item elite-item {{ request()->routeIs('users.*') ? 'active-item' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog fa-sm fa-fw mr-2"></i>
                        Kelola Akun User
                    </a>
                @endif

                {{-- Kelola Galeri (khusus admin) --}}
                @if ($user->isAdmin())
                    <a class="dropdown-item elite-item {{ request()->routeIs('galeri-admin.*') ? 'active-item' : '' }}"
                       href="{{ route('galeri-admin.index') }}">
                        <i class="fas fa-images fa-sm fa-fw mr-2"></i>
                        Kelola Galeri
                    </a>
                @endif

                {{-- Beranda --}}
                @if ($user->isAdmin())
                    <a class="dropdown-item elite-item"
                       href="{{ url('/home') }}">
                        <i class="fas fa-home fa-sm fa-fw mr-2"></i>
                        Beranda
                    </a>
                @endif

                <div class="dropdown-divider"></div>

                {{-- Form Logout --}}
                <form id="logout-form"
                      action="{{ route('logout') }}"
                      method="POST"
                      class="d-none">
                    @csrf
                </form>

                {{-- Tombol Logout --}}
                <a href="#"
                   class="dropdown-item elite-item elite-item-danger"
                   onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                    Logout
                </a>

            </div>
        </li>
        @endauth

    </ul>
</nav>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* =========================================================
   DESIGN TOKENS
========================================================= */

.topbar-elite {
    --sb-red: #e01c1d;
    --sb-red-dark: #7c1013;
    --sb-gold: #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    background: rgba(255, 255, 255, .88);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 4px 24px rgba(124, 16, 19, .08);
    border-bottom: 1px solid rgba(217, 172, 79, .18);
}

/* =========================================================
   USER TOGGLE
========================================================= */

.user-toggle {
    transition: all .25s ease;
    padding: 6px 10px !important;
    border-radius: 12px;
}

.user-toggle:hover,
.user-toggle:focus {
    background: rgba(217, 172, 79, .12);
    text-decoration: none;
}

.user-name {
    font-weight: 600;
    font-size: .85rem;
    color: #5a2a2c;
    line-height: 1.4;
}

.user-role {
    font-size: .68rem;
    color: #b58a32;
    text-transform: uppercase;
    letter-spacing: .06em;
    font-weight: 700;
}

/* =========================================================
   AVATAR
========================================================= */

.avatar-ring {
    display: inline-flex;
    padding: 2px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e01c1d, #7c1013);
    box-shadow: 0 2px 8px rgba(224, 28, 29, .25);
    flex-shrink: 0;
}

.avatar-ring img {
    border: 2px solid #fff;
    display: block;
}

/* =========================================================
   DROPDOWN
========================================================= */

.elite-dropdown {
    border: 1px solid rgba(217, 172, 79, .2);
    border-radius: 14px;
    overflow: hidden;
    padding: .5rem 0;
    min-width: 250px;
    box-shadow: 0 10px 40px rgba(124, 16, 19, .18);
    margin-top: 8px;
}

.dropdown-header-elite {
    position: relative;
    overflow: hidden;
    padding: 16px 18px 14px;
    background:
        radial-gradient(
            circle at 90% 0%,
            rgba(217, 172, 79, .22) 0%,
            transparent 55%
        ),
        linear-gradient(135deg, #e01c1d 0%, #7c1013 100%);
    color: #fff;
    margin-bottom: 6px;
}

.header-name {
    font-weight: 700;
    font-size: .95rem;
    overflow-wrap: anywhere;
}

.header-email {
    font-size: .75rem;
    color: rgba(255, 255, 255, .78);
    overflow-wrap: anywhere;
    margin-top: 3px;
}

.header-role {
    display: inline-block;
    margin-top: 8px;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #fff;
    background: rgba(255, 255, 255, .15);
    border: 1px solid rgba(255, 255, 255, .2);
}

/* =========================================================
   DROPDOWN ITEMS
========================================================= */

.elite-item {
    font-size: .875rem;
    padding: 10px 18px;
    color: #6b4a35;
    transition: all .2s ease;
}

.elite-item i {
    color: #b58a32;
    width: 16px;
    text-align: center;
}

.elite-item:hover,
.elite-item:focus {
    background: rgba(217, 172, 79, .12);
    color: #7c1013;
    padding-left: 22px;
}

.elite-item-danger {
    color: #e01c1d;
}

.elite-item-danger i {
    color: #e01c1d;
}

.elite-item-danger:hover,
.elite-item-danger:focus {
    background: rgba(224, 28, 29, .08);
    color: #7c1013 !important;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 575.98px) {
    .elite-dropdown {
        min-width: 225px;
        margin-right: 5px;
    }

    .topbar-elite {
        padding-left: .75rem;
        padding-right: .75rem;
    }
}
</style>

<script>
function confirmLogout(event) {
    event.preventDefault();

    if (typeof Swal === 'undefined') {
        if (confirm('Apakah Anda yakin ingin logout?')) {
            document.getElementById('logout-form').submit();
        }
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e01c1d',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>