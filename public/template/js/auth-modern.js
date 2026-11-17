/* Login & Daftar: tampilkan password, kekuatan password, cek kecocokan,
   Caps Lock, status memuat pada tombol, dan tagline yang berganti. */
(function () {
    'use strict';
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* tampilkan / sembunyikan password */
    document.querySelectorAll('[data-toggle-pass]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-toggle-pass'));
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            var i = btn.querySelector('i');
            if (i) i.className = show ? 'far fa-eye-slash' : 'far fa-eye';
        });
    });

    /* Caps Lock */
    var pass = document.getElementById('password');
    var caps = document.getElementById('capsHint');
    if (pass && caps) {
        var check = function (e) { caps.hidden = !(e.getModifierState && e.getModifierState('CapsLock')); };
        pass.addEventListener('keyup', check);
        pass.addEventListener('keydown', check);
        pass.addEventListener('blur', function () { caps.hidden = true; });
    }

    /* kekuatan password (hanya di halaman daftar) */
    var meter = document.getElementById('pwMeter');
    var label = document.getElementById('pwLabel');
    var confirm = document.getElementById('password_confirmation');
    var match = document.getElementById('pwMatch');

    function score(v) {
        var s = 0;
        if (v.length >= 8) s++;
        if (v.length >= 12) s++;
        if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
        if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) s++;
        return v.length ? Math.max(1, s) : 0;
    }
    function checkMatch() {
        if (!match || !confirm || !pass) return;
        if (!confirm.value) { match.hidden = true; return; }
        var ok = confirm.value === pass.value;
        match.hidden = false;
        match.className = 'au-match ' + (ok ? 'is-ok' : 'is-bad');
        match.innerHTML = ok ? '<i class="fas fa-check-circle"></i> Password cocok' : '<i class="fas fa-times-circle"></i> Password belum sama';
    }
    if (meter && pass) {
        var names = ['Kekuatan password', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];
        pass.addEventListener('input', function () {
            var s = score(pass.value);
            meter.setAttribute('data-level', s);
            if (label) label.textContent = names[s];
            checkMatch();
        });
    }
    if (confirm) confirm.addEventListener('input', checkMatch);

    /* status memuat saat kirim / pindah ke login sosial */
    document.querySelectorAll('form[data-auth-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('.au-submit');
            if (btn) { btn.classList.add('is-loading'); btn.setAttribute('aria-busy', 'true'); }
        });
    });
    document.querySelectorAll('.au-social-btn').forEach(function (a) {
        a.addEventListener('click', function () {
            var t = a.getAttribute('data-loading-text');
            a.classList.add('is-loading');
            if (t) a.querySelector('span').textContent = t;
        });
    });
    /* tombol "kembali" peramban: pulihkan status tombol */
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        document.querySelectorAll('.is-loading').forEach(function (el) { el.classList.remove('is-loading'); });
    });

    /* tagline berganti */
    var tag = document.getElementById('auTagline');
    if (tag && !reduce) {
        var lines = ['Beauty • Relaxation • Self Care', 'Nyaman • Privat • Menenangkan', 'Perawatan terbaik untuk muslimah'];
        var n = 0;
        setInterval(function () {
            tag.classList.add('is-out');
            setTimeout(function () {
                n = (n + 1) % lines.length;
                tag.textContent = lines[n];
                tag.classList.remove('is-out');
            }, 450);
        }, 3800);
    }
})();