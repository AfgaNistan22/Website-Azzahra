{{-- =========================================================
    HEADER HALAMAN PUBLIK (REDESIGN v2)
    Guest, Admin, dan Pelanggan
    Konsep: header terang yang tenang, hijau pinus sebagai aksi,
    tipografi serif untuk suara brand, menu akun rapi.
========================================================= --}}

@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $defaultPhoto = asset('template/img/undraw_profile.svg');
    $authUser     = auth()->user();

    if ($authUser) {
        $roleName = $authUser->role?->name
            ?? match ((int) $authUser->role_id) {
                1       => 'Admin',
                2       => 'Pelanggan',
                default => 'Member',
            };

        $isAdmin = method_exists($authUser, 'isAdmin')
            ? (bool) $authUser->isAdmin()
            : (int) $authUser->role_id === 1;

        // Foto bisa berupa URL penuh (login Google, dll) atau path storage
        $photoUrl = $defaultPhoto;
        if (!empty($authUser->photo)) {
            $photoUrl = Str::startsWith($authUser->photo, ['http://', 'https://'])
                ? $authUser->photo
                : Storage::url($authUser->photo);
        }

        $firstName    = Str::of(trim($authUser->name))->before(' ')->toString();
        $profileUrl   = Route::has('profile')         ? route('profile')         : url('/profile');
        $passwordUrl  = Route::has('password.change') ? route('password.change') : url('/change');
        $dashboardUrl = Route::has('dashboard')       ? route('dashboard')       : url('/dashboard');
    }
@endphp

<header class="ph">
    <div class="container ph-inner">

        <p class="ph-tagline">
            <i class="fas fa-spa"></i>
            <span>Perawatan muslimah yang nyaman dan berkualitas</span>
        </p>

        <div class="ph-actions">
            @guest
                <a class="ph-link" href="{{ route('login') }}">Masuk</a>

                @if (Route::has('register'))
                    <a class="ph-btn" href="{{ route('register') }}">Daftar</a>
                @endif
            @else
                <div class="ph-user">

                    <button type="button"
                            class="ph-toggle"
                            id="publicUserDropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            aria-controls="publicUserMenu">
                        <span class="ph-toggle-text">
                            <span class="ph-toggle-label">Halo, {{ $authUser->name }}</span>
                            <span class="ph-toggle-role">{{ $roleName }}</span>
                        </span>
                        <img class="ph-avatar ph-avatar-sm"
                             src="{{ $photoUrl }}"
                             alt=""
                             data-fallback="{{ $defaultPhoto }}">
                        <span class="sr-only visually-hidden">Menu akun {{ $authUser->name }}</span>
                    </button>

                    {{-- Backdrop hanya dipakai di layar kecil (bottom sheet) --}}
                    <div class="ph-backdrop" id="publicUserBackdrop" hidden></div>

                    <div class="ph-menu"
                         id="publicUserMenu"
                         role="menu"
                         aria-labelledby="publicUserDropdown"
                         hidden>

                        <div class="ph-menu-head">
                            <img class="ph-avatar ph-avatar-lg"
                                 src="{{ $photoUrl }}"
                                 alt="Foto profil {{ $authUser->name }}"
                                 data-fallback="{{ $defaultPhoto }}">
                            <div class="ph-menu-id">
                                <div class="ph-menu-name">{{ $authUser->name }}</div>
                                <div class="ph-menu-email">{{ $authUser->email }}</div>
                            </div>
                            <span class="ph-role">{{ $roleName }}</span>
                        </div>

                        <div class="ph-menu-list">

                            <a class="ph-item" role="menuitem" href="{{ $profileUrl }}">
                                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="12" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M4 21c.8-4 4-6 8-6s7.2 2 8 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                <span>Profil saya</span>
                            </a>

                            <a class="ph-item" role="menuitem" href="{{ $passwordUrl }}">
                                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M8 11V8a4 4 0 0 1 8 0v3" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                <span>Ubah password</span>
                            </a>

                            @if ($isAdmin)
                                <a class="ph-item" role="menuitem" href="{{ $dashboardUrl }}">
                                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6v-9h-6v9Zm0-16v5h6V4h-6Z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                                    <span>Dashboard admin</span>
                                </a>
                            @endif
                        </div>

                        <form class="ph-menu-foot" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="ph-item ph-item-danger" role="menuitem">
                                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M10 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4M15 8l4 4-4 4M19 12H9" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Keluar</span>
                            </button>
                        </form>

                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>

@once
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@1,6..72,400&display=swap">

<style>
/* =========================================================
   TOKENS
   Pearl sebagai latar, hijau pinus untuk aksi, rose hanya aksen kecil
========================================================= */
.ph {
    --ph-bg: #4a1219;
    --ph-bg-dark: #3a0d12;
    --ph-text: #f7ebe6;
    --ph-muted: #d9bfb8;
    --ph-gold: #d4ad5a;
    --ph-gold-light: #e2bf74;
    --ph-gold-ink: #3a0d12;
    --ph-ink: #2b1a1c;
    --ph-soft: #7b6663;
    --ph-line: #eadfd9;
    --ph-pine: #4a1219;
    --ph-pine-dark: #3a0d12;
    --ph-mist: #f7efe9;
    --ph-rose-tint: #fbf3df;
    --ph-surface: #fffdfb;

    position: sticky;
    top: 0;
    z-index: 1040;
    background: var(--ph-bg);
    border-bottom: 2px solid var(--ph-gold);
    color: var(--ph-text);
    font-size: .9rem;
}

.ph-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    min-height: 56px;
}

/* =========================================================
   TAGLINE
========================================================= */
.ph-tagline {
    display: flex;
    flex: 1 1 auto;
    align-items: center;
    gap: 10px;
    min-width: 0;
    margin: 0;
    color: var(--ph-text);
    font-family: "Newsreader", Georgia, "Times New Roman", serif;
    font-size: 1.05rem;
    font-style: italic;
}

.ph-mark {
    flex-shrink: 0;
    color: var(--ph-gold);
}

.ph-tagline span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* =========================================================
   AKSI
========================================================= */
.ph-actions {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    gap: 8px;
}

.ph-link {
    padding: 8px 14px;
    border-radius: 999px;
    color: var(--ph-text);
    font-weight: 600;
    text-decoration: none;
    transition: background .2s ease;
}

.ph-link:hover {
    background: rgba(255, 255, 255, .12);
    color: #fff;
    text-decoration: none;
}

.ph-btn {
    padding: 8px 20px;
    border-radius: 999px;
    background: var(--ph-gold);
    color: var(--ph-gold-ink);
    font-weight: 700;
    text-decoration: none;
    transition: background .2s ease;
}

.ph-btn:hover {
    background: var(--ph-gold-light);
    color: var(--ph-gold-ink);
    text-decoration: none;
}

.ph-link:focus-visible,
.ph-btn:focus-visible,
.ph-toggle:focus-visible,
.ph-item:focus-visible {
    outline: 2px solid var(--ph-gold);
    outline-offset: 2px;
}

.ph-menu .ph-item:focus-visible {
    outline-color: var(--ph-pine);
}

/* =========================================================
   TOMBOL PROFIL
========================================================= */
.ph-user {
    position: relative;
}

.ph-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 4px 6px 4px 12px;
    border: 0;
    border-radius: 999px;
    background: transparent;
    color: var(--ph-text);
    font-family: inherit;
    cursor: pointer;
    transition: background .2s ease;
}

.ph-toggle:hover,
.ph-toggle[aria-expanded="true"] {
    background: rgba(255, 255, 255, .12);
}

.ph-toggle-text {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    min-width: 0;
    line-height: 1.2;
}

.ph-toggle-label {
    max-width: 180px;
    overflow: hidden;
    font-size: .92rem;
    font-weight: 500;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.ph-toggle-role {
    color: var(--ph-gold);
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.ph-avatar {
    display: block;
    flex-shrink: 0;
    border-radius: 50%;
    object-fit: cover;
    background: var(--ph-mist);
    border: 2px solid var(--ph-gold);
}

.ph-avatar-sm { width: 42px; height: 42px; }
.ph-avatar-lg { width: 48px; height: 48px; }

/* =========================================================
   PANEL MENU
========================================================= */
.ph-menu {
    position: absolute;
    z-index: 2000;
    top: calc(100% + 10px);
    right: 0;
    width: 290px;
    max-width: calc(100vw - 24px);
    overflow: hidden;
    border: 1px solid var(--ph-line);
    border-radius: 14px;
    background: var(--ph-surface);
    color: var(--ph-ink);
    box-shadow: 0 16px 36px rgba(43, 26, 28, .26);
}

.ph-menu[hidden],
.ph-backdrop[hidden] {
    display: none !important;
}

.ph-menu:not([hidden]) {
    animation: ph-pop .16s ease-out;
}

.ph-menu-head {
    background: var(--ph-mist);
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    gap: 4px 12px;
    padding: 16px;
    border-bottom: 1px solid var(--ph-line);
}

.ph-menu-id {
    min-width: 0;
}

.ph-menu-name {
    font-weight: 700;
    line-height: 1.3;
    overflow-wrap: anywhere;
}

.ph-menu-email {
    color: var(--ph-soft);
    font-size: .8rem;
    overflow-wrap: anywhere;
}

.ph-role {
    grid-column: 2;
    justify-self: start;
    padding: 1px 10px;
    border-radius: 999px;
    background: var(--ph-rose-tint);
    border: 1px solid #ecd9a6;
    color: #7a5a1c;
    font-size: .76rem;
    font-weight: 600;
}

.ph-menu-list,
.ph-menu-foot {
    margin: 0;
    padding: 6px;
}

.ph-menu-foot {
    border-top: 1px solid var(--ph-line);
}

.ph-item {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 10px 12px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--ph-ink);
    font-family: inherit;
    font-size: .92rem;
    font-weight: 500;
    text-align: left;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s ease;
}

.ph-item svg {
    flex-shrink: 0;
    color: var(--ph-pine);
}

.ph-item:hover {
    background: var(--ph-mist);
    color: var(--ph-ink);
    text-decoration: none;
}

.ph-item-danger,
.ph-item-danger:hover,
.ph-item-danger svg {
    color: #a4161a;
}

.ph-item-danger:hover {
    background: #fbeaea;
}

@keyframes ph-pop {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes ph-sheet {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}

/* =========================================================
   LAYAR KECIL: MENU JADI BOTTOM SHEET
========================================================= */
@media (max-width: 575.98px) {
    .ph-tagline {
        font-size: .95rem;
    }

    .ph-toggle-label {
        max-width: 96px;
    }

    .ph-backdrop {
        position: fixed;
        z-index: 1999;
        inset: 0;
        background: rgba(43, 26, 28, .5);
    }

    .ph-menu {
        position: fixed;
        top: auto;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        max-width: none;
        padding-bottom: env(safe-area-inset-bottom, 0px);
        border-width: 1px 0 0;
        border-radius: 18px 18px 0 0;
    }

    .ph-menu:not([hidden]) {
        animation: ph-sheet .22s ease-out;
    }

    .ph-menu-head {
        position: relative;
        padding-top: 26px;
    }

    .ph-menu-head::before {
        content: "";
        position: absolute;
        top: 9px;
        left: 50%;
        width: 36px;
        height: 4px;
        border-radius: 4px;
        background: var(--ph-line);
        transform: translateX(-50%);
    }

    .ph-item {
        padding: 13px 12px;
    }

    html.public-menu-lock {
        overflow: hidden;
    }
}

@media (min-width: 576px) {
    .ph-backdrop {
        display: none !important;
    }
}

@media (prefers-reduced-motion: reduce) {
    .ph-menu:not([hidden]) {
        animation: none;
    }

    .ph-chevron,
    .ph-item,
    .ph-toggle,
    .ph-link,
    .ph-btn {
        transition: none;
    }
}
</style>

<script>
(function () {
    const button   = document.getElementById('publicUserDropdown');
    const menu     = document.getElementById('publicUserMenu');
    const backdrop = document.getElementById('publicUserBackdrop');

    if (!button || !menu) return;

    const root = button.parentElement;

    /* Foto cadangan kalau gambar gagal dimuat */
    root.querySelectorAll('img[data-fallback]').forEach(function (img) {
        const useFallback = function () {
            img.removeEventListener('error', useFallback);
            img.src = img.dataset.fallback;
        };
        img.addEventListener('error', useFallback);
        if (img.complete && img.naturalWidth === 0) useFallback();
    });

    const items  = function () { return Array.from(menu.querySelectorAll('[role="menuitem"]')); };
    const isOpen = function () { return !menu.hidden; };

    function openMenu(focusFirst) {
        menu.hidden = false;
        if (backdrop) backdrop.hidden = false;
        button.setAttribute('aria-expanded', 'true');
        if (window.innerWidth < 576) document.documentElement.classList.add('public-menu-lock');
        if (focusFirst) {
            const list = items();
            if (list.length) list[0].focus();
        }
    }

    function closeMenu(returnFocus) {
        if (!isOpen()) return;
        menu.hidden = true;
        if (backdrop) backdrop.hidden = true;
        button.setAttribute('aria-expanded', 'false');
        document.documentElement.classList.remove('public-menu-lock');
        if (returnFocus) button.focus();
    }

    button.addEventListener('click', function (event) {
        event.stopPropagation();
        isOpen() ? closeMenu(false) : openMenu(false);
    });

    button.addEventListener('keydown', function (event) {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (!isOpen()) openMenu(true);
            else items()[0].focus();
        }
    });

    menu.addEventListener('keydown', function (event) {
        const list = items();
        const idx  = list.indexOf(document.activeElement);
        let next   = null;

        if (event.key === 'ArrowDown') next = list[(idx + 1) % list.length];
        if (event.key === 'ArrowUp')   next = list[(idx - 1 + list.length) % list.length];
        if (event.key === 'Home')      next = list[0];
        if (event.key === 'End')       next = list[list.length - 1];

        if (next) {
            event.preventDefault();
            next.focus();
        }
    });

    document.addEventListener('click', function (event) {
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            closeMenu(false);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen()) closeMenu(true);
    });

    root.addEventListener('focusout', function (event) {
        if (isOpen() && event.relatedTarget && !root.contains(event.relatedTarget)) {
            closeMenu(false);
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 576) {
            document.documentElement.classList.remove('public-menu-lock');
        } else if (isOpen()) {
            document.documentElement.classList.add('public-menu-lock');
        }
    });
})();
</script>
@endonce