@extends('layouts.customer')
@vite([
    'resources/css/customer.css',
    'resources/js/customer/navigation.js'
])
@section('content')

{{-- ==========================================
    HERO SECTION
========================================== --}}
<section class="customer-hero">

    <div class="customer-hero-content">

        <div class="hero-text">

            @auth
                <span class="hero-greeting">
                    Halo, {{ auth()->user()->username }} 👋
                </span>
            @else
                <span class="hero-badge">
                    ✦ Laundry lebih praktis
                </span>
            @endauth

            <h1>
                Nyuci itu berat,
                <span>biar kami saja.</span>
            </h1>

            <p>
                Percayakan pakaian kotor kamu kepada
                <strong>Sang Pencuci</strong>.
                Bersih, rapi, dan siap dipakai kembali.
            </p>

            <div class="hero-actions">

                <a href="#layanan" class="hero-primary-button">
                    Lihat Layanan
                </a>

                @guest
                    <a href="{{ route('login') }}" class="hero-secondary-button">
                        Masuk
                    </a>
                @endguest

            </div>

        </div>


        {{-- ILUSTRASI MESIN CUCI --}}
        <div class="hero-visual">

            <div class="hero-circle hero-circle-one"></div>
            <div class="hero-circle hero-circle-two"></div>

            <div class="washing-machine-card">

                <div class="washing-machine-top">

                    <div class="machine-display"></div>

                    <div class="machine-buttons">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                </div>

                <div class="machine-door">
                    <div class="machine-glass">
                        <div class="machine-water"></div>
                        <span class="machine-bubble bubble-one"></span>
                        <span class="machine-bubble bubble-two"></span>
                        <span class="machine-bubble bubble-three"></span>
                    </div>
                </div>

            </div>

            <div class="hero-sparkle sparkle-one">✦</div>
            <div class="hero-sparkle sparkle-two">✦</div>
            <div class="hero-sparkle sparkle-three">✧</div>

        </div>

    </div>

</section>


{{-- ==========================================
    BENEFIT
========================================== --}}
<section class="customer-benefit">

    <div class="benefit-item">
        <div class="benefit-icon">✦</div>
        <div>
            <strong>Bersih</strong>
            <span>Pakaian dicuci dengan baik</span>
        </div>
    </div>

    <div class="benefit-item">
        <div class="benefit-icon">✓</div>
        <div>
            <strong>Praktis</strong>
            <span>Pesan tanpa perlu repot</span>
        </div>
    </div>

    <div class="benefit-item">
        <div class="benefit-icon">♧</div>
        <div>
            <strong>Terpercaya</strong>
            <span>Status pesanan dapat dipantau</span>
        </div>
    </div>

</section>


{{-- ==========================================
    ACTIVE ORDER
========================================== --}}
@auth

    @if(isset($activeOrder) && $activeOrder)

        <section class="customer-section active-order-section">

            <div class="section-heading">

                <div>
                    <span class="section-label">PESANAN KAMU</span>
                    <h2>Sedang Diproses</h2>
                </div>

                <a href="{{ url('/pesanan/' . $activeOrder->id) }}">
                    Lihat Detail
                </a>

            </div>

            <div class="active-order-card">

                <div class="active-order-top">

                    <div>
                        <span class="order-number-label">
                            Nomor Pesanan
                        </span>

                        <strong>
                            #{{ $activeOrder->order_number ?? $activeOrder->id }}
                        </strong>
                    </div>

                    <span class="order-status-badge">
                        {{ $activeOrder->status }}
                    </span>

                </div>

                <div class="order-progress">

                    <div class="progress-step active">
                        <div class="progress-dot">✓</div>
                        <span>Antrian</span>
                    </div>

                    <div class="progress-line"></div>

                    <div class="progress-step">
                        <div class="progress-dot">2</div>
                        <span>Proses</span>
                    </div>

                    <div class="progress-line"></div>

                    <div class="progress-step">
                        <div class="progress-dot">3</div>
                        <span>Siap</span>
                    </div>

                    <div class="progress-line"></div>

                    <div class="progress-step">
                        <div class="progress-dot">4</div>
                        <span>Selesai</span>
                    </div>

                </div>

            </div>

        </section>

    @endif

@endauth


{{-- ==========================================
    LAYANAN
========================================== --}}
<section id="layanan" class="customer-section services-section">

    <div class="section-heading">

        <div>
            <span class="section-label">LAYANAN</span>
            <h2>Pilih layanan kami</h2>
            <p>
                Pilih layanan yang sesuai dengan kebutuhan cucian kamu.
            </p>
        </div>

    </div>


    <div class="service-grid">

        <div class="service-card">

            <div class="service-icon">
                🧺
            </div>

            <div class="service-content">

                <h3>Cuci Kiloan</h3>

                <p>
                    Cocok untuk pakaian sehari-hari
                    dalam jumlah banyak.
                </p>

                <a href="{{ url('/#layanan') }}">
                    Lihat layanan →
                </a>

            </div>

        </div>


        <div class="service-card pink-service">

            <div class="service-icon">
                👕
            </div>

            <div class="service-content">

                <h3>Cuci Satuan</h3>

                <p>
                    Untuk pakaian atau barang tertentu
                    yang membutuhkan penanganan khusus.
                </p>

                <a href="{{ url('/#layanan') }}">
                    Lihat layanan →
                </a>

            </div>

        </div>


        <div class="service-card">

            <div class="service-icon">
                ✨
            </div>

            <div class="service-content">

                <h3>Setrika</h3>

                <p>
                    Pakaian dirapikan agar siap digunakan
                    kembali.
                </p>

                <a href="{{ url('/#layanan') }}">
                    Lihat layanan →
                </a>

            </div>

        </div>

    </div>

</section>


{{-- ==========================================
    PROMO
========================================== --}}
<section id="promo" class="customer-promo-section">

    <div class="promo-card">

        <div class="promo-content">

            <span class="promo-label">
                ✦ PROMO
            </span>

            <h2>
                Cucian banyak?
                <br>
                Dapatkan lebih banyak keuntungan.
            </h2>

            <p>
                Nikmati berbagai promo dan keuntungan
                dari Sang Pencuci.
            </p>

            <a href="#promo" class="promo-button">
                Lihat Promo
            </a>

        </div>


        <div class="promo-decoration">

            <div class="promo-circle"></div>

            <div class="promo-bubble bubble-a"></div>
            <div class="promo-bubble bubble-b"></div>
            <div class="promo-bubble bubble-c"></div>

            <span class="promo-sparkle">✦</span>

        </div>

    </div>

</section>


{{-- ==========================================
    CTA
========================================== --}}
<section class="customer-cta">

    <div class="cta-content">

        <span class="section-label">
            SANG PENCUCI
        </span>

        <h2>
            Biar kami yang urus cucianmu.
        </h2>

        <p>
            Kamu tinggal santai,
            kami yang mencuci.
        </p>

        @guest

            <a href="{{ route('login') }}" class="cta-button">
                Mulai Sekarang
            </a>

        @else

            <a href="#layanan" class="cta-button">
                Lihat Layanan
            </a>

        @endguest

    </div>

</section>

@endsection