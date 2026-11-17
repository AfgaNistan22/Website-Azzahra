<footer class="azzahra-footer">
    <div class="footer-top-decoration"></div>

    <div class="container">
        <div class="row gy-5 footer-main">

            {{-- BRAND --}}
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="footer-brand">
                    <span class="footer-brand-name">AZZAHRA</span>
                </a>

                <p class="footer-description">
                    Salon & Spa Muslimah yang menghadirkan pelayanan
                    profesional, nyaman, bersih, dan berkualitas
                    untuk setiap pelanggan.
                </p>

                <div class="footer-brand-tagline">
                    <span></span>
                    Muslimah Beauty & Relaxation
                    <span></span>
                </div>

                <div class="footer-socials">
                    <a href="#" class="footer-social"
                       aria-label="Instagram" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="#" class="footer-social"
                       aria-label="Facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a href="https://wa.me/6281226511910"
                       class="footer-social"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="WhatsApp" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- MENU --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-heading">Jelajahi</h5>
                <span class="footer-heading-line"></span>

                <ul class="footer-links">
                    <li>
                        <a href="{{ route('home') }}">
                            <i class="fas fa-angle-right"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#layanan">
                            <i class="fas fa-angle-right"></i>
                            Layanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#about">
                            <i class="fas fa-angle-right"></i>
                            Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#testimonial">
                            <i class="fas fa-angle-right"></i>
                            Testimoni
                        </a>
                    </li>
                </ul>
            </div>

            {{-- JAM OPERASIONAL --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Jam Operasional</h5>
                <span class="footer-heading-line"></span>

                <ul class="footer-hours">
                    <li>
                        <div class="footer-hour-day">
                            <i class="far fa-clock"></i>
                            <span>Senin – Jumat</span>
                        </div>
                        <span class="footer-hour-time">09.00 – 20.00 WIB</span>
                    </li>

                    <li>
                        <div class="footer-hour-day">
                            <i class="far fa-clock"></i>
                            <span>Sabtu</span>
                        </div>
                        <span class="footer-hour-time">09.00 – 21.00 WIB</span>
                    </li>

                    <li>
                        <div class="footer-hour-day">
                            <i class="far fa-clock"></i>
                            <span>Minggu</span>
                        </div>
                        <span class="footer-hour-time">09.00 – 18.00 WIB</span>
                    </li>
                </ul>

                <div class="footer-open-note">
                    <span class="footer-status-dot"></span>
                    Siap menyambut Anda
                </div>
            </div>

            {{-- KONTAK --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Hubungi Kami</h5>
                <span class="footer-heading-line"></span>

                <ul class="footer-contact">
                    <li>
                        <span class="footer-contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <div>
                            <small>Lokasi Kami</small>
                            <p>Yogyakarta, DI Yogyakarta</p>
                        </div>
                    </li>

                    <li>
                        <span class="footer-contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </span>
                        <div>
                            <small>Telepon / WhatsApp</small>
                            <a href="https://wa.me/6281226511910"
                               target="_blank"
                               rel="noopener noreferrer">
                                (+62) 812-2651-1910
                            </a>
                        </div>
                    </li>

                    <li>
                        <span class="footer-contact-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <div>
                            <small>Email</small>
                            <a href="mailto:info@azzahra.com">
                                info@azzahra.com
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        {{-- FOOTER BOTTOM --}}
        <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <p>
                    &copy; {{ date('Y') }}
                    <strong>Azzahra Salon & Spa Muslimah.</strong>
                    All Rights Reserved.
                </p>

                <p class="footer-bottom-note">
                    <i class="fas fa-spa"></i>
                    Kecantikan dalam kenyamanan dan privasi
                </p>
            </div>
        </div>
    </div>
</footer>

{{-- FLOATING WHATSAPP --}}
<a href="https://wa.me/6281226511910"
   class="azzahra-whatsapp"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Hubungi Azzahra melalui WhatsApp"
   title="Hubungi Kami via WhatsApp">
    <i class="fab fa-whatsapp"></i>
    <span class="whatsapp-tooltip">Konsultasi & Reservasi</span>
</a>

{{-- BACK TO TOP --}}
<button id="topBtn"
        class="azzahra-top-btn"
        type="button"
        aria-label="Kembali ke atas"
        title="Kembali ke atas">
    <i class="fas fa-arrow-up"></i>
</button>