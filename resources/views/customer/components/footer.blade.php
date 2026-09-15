<footer class="customer-footer">

    {{-- =====================================================
         DEKORASI BUBBLE
         ===================================================== --}}
    <div class="customer-footer-bubbles customer-footer-bubbles-left">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="customer-footer-bubbles customer-footer-bubbles-right">
        <span></span>
        <span></span>
        <span></span>
    </div>


    {{-- =====================================================
         FOOTER CONTENT
         ===================================================== --}}
    <div class="customer-footer-inner">

        {{-- =================================================
             BRAND
             ================================================= --}}
        <div class="customer-footer-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Sang Pencuci"
                class="customer-footer-logo"
            >

            <h2>
                SANG PENCUCI
            </h2>

            <p class="customer-footer-slogan">
                Nyuci itu berat, biar kami saja.
            </p>

            <p class="customer-footer-description">
                Serahkan urusan cucianmu kepada Sang Pencuci.
                Bersih, rapi, dan praktis tanpa perlu repot.
            </p>


            {{-- SOCIAL MEDIA --}}
            <div class="customer-footer-social">

                {{-- Instagram --}}
                <a
                    href="#"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="customer-footer-social-link"
                    aria-label="Instagram Sang Pencuci"
                >
                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="3"
                            width="18"
                            height="18"
                            rx="5"
                            ry="5"
                        ></rect>

                        <circle
                            cx="12"
                            cy="12"
                            r="4"
                        ></circle>

                        <circle
                            cx="17.5"
                            cy="6.5"
                            r="1"
                            class="social-dot"
                        ></circle>
                    </svg>

                    <span>Instagram</span>
                </a>


                {{-- WhatsApp --}}
                <a
                    href="#"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="customer-footer-social-link"
                    aria-label="WhatsApp Sang Pencuci"
                >
                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            d="M20.5 11.5a8.5 8.5 0 0 1-12.4 7.6L3.5 20.5l1.4-4.3A8.5 8.5 0 1 1 20.5 11.5Z"
                        ></path>

                        <path
                            d="M8.5 8.5c.2-.4.4-.4.7-.4h.5c.2 0 .4.1.5.4l.7 1.6c.1.2.1.4 0 .6l-.5.7c.7 1.3 1.7 2.3 3 3l.7-.5c.2-.1.4-.1.6 0l1.6.7c.3.1.4.3.4.5v.5c0 .3 0 .5-.4.7-.4.2-1 .3-1.5.2-1.2-.2-2.7-1-4.2-2.4-1.4-1.4-2.2-2.9-2.4-4.1-.1-.5 0-1.1.2-1.5Z"
                        ></path>
                    </svg>

                    <span>WhatsApp</span>
                </a>

            </div>


            {{-- CALLOUT --}}
            <div class="customer-footer-social-note">
                <span class="customer-footer-social-arrow">
                    ↗
                </span>

                <span>
                    Terhubung dengan kami!
                </span>
            </div>

        </div>


        {{-- =================================================
             LOKASI
             ================================================= --}}
        <div class="customer-footer-location">

            <h2>
                Lokasi Kami
            </h2>

            <div class="customer-footer-location-line"></div>

            <p class="customer-footer-location-description">
                Temukan lokasi Sang Pencuci dan kunjungi kami
                untuk kebutuhan laundry kamu.
            </p>


            {{-- MAP --}}
            <a
                href="#"
                target="_blank"
                rel="noopener noreferrer"
                class="customer-footer-map"
                aria-label="Lihat lokasi Sang Pencuci di Google Maps"
            >

                <div class="customer-footer-map-content">

                    <div class="customer-footer-map-pin">
                        <span></span>
                    </div>

                    <div class="customer-footer-map-info">

                        <strong>
                            Sang Pencuci
                        </strong>

                        <span>
                            Lihat lokasi di Google Maps
                        </span>

                    </div>

                </div>

            </a>


            {{-- ALAMAT --}}
            <div class="customer-footer-address">

                <div class="customer-footer-address-icon">

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            d="M12 21s7-6.2 7-12A7 7 0 1 0 5 9c0 5.8 7 12 7 12Z"
                        ></path>

                        <circle
                            cx="12"
                            cy="9"
                            r="2.5"
                        ></circle>

                    </svg>

                </div>

                <p>
                    Alamat Sang Pencuci
                </p>

            </div>


            {{-- PENUTUP LOKASI --}}
            <p class="customer-footer-location-closing">
                Kami siap melayani kebutuhan laundry Anda
                dengan sepenuh hati.
            </p>

        </div>

    </div>


    {{-- =====================================================
         GELOMBANG
         ===================================================== --}}
    <div class="customer-footer-waves" aria-hidden="true">

        <div class="customer-footer-wave customer-footer-wave-1"></div>

        <div class="customer-footer-wave customer-footer-wave-2"></div>

        <div class="customer-footer-wave customer-footer-wave-3"></div>

    </div>


    {{-- =====================================================
         FOOTER BOTTOM
         ===================================================== --}}
    <div class="customer-footer-bottom">

        <span>
            © {{ date('Y') }} Sang Pencuci. All rights reserved.
        </span>

        <span>
            Nyuci itu berat, biar kami saja.
        </span>

    </div>

</footer>