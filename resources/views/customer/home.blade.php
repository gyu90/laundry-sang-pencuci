@extends('layouts.customer')

@section('page_title', 'Home')

@section('content')



{{-- =====================================================
     HERO
===================================================== --}}
<section class="home-hero">

    {{-- BACKGROUND IMAGE --}}
    <img
        src="{{ asset('images/hero-laundry.png') }}"
        alt="Mesin cuci Sang Pencuci"
        class="home-hero-background"
    >

    {{-- OVERLAY --}}
    <div class="home-hero-overlay"></div>


    {{-- HERO CONTENT --}}
    <div class="home-container home-hero-inner">

<div class="home-hero-content">

 @auth
    <span class="home-hero-greeting">
        Halo, {{ auth()->user()->customer->name }} 👋
    </span>
@else
    <span class="home-eyebrow">
        SANG PENCUCI
    </span>
@endauth

    <h1>
        <span class="hero-line-one">
            Nyuci itu berat,
        </span>

        <span class="hero-line-two">
            biar kami saja.
        </span>
    </h1>


            <p>
                Serahkan urusan cucianmu kepada
                <strong>Sang Pencuci</strong>.
                Bersih, rapi, dan praktis
            </p>
            <p>
                tanpa perlu repot.
            </p>


            <div class="home-hero-actions">

                <a
                    href="#layanan"
                    class="home-button-primary"
                >
                    Lihat Layanan
                </a>

                <a href="{{ route('customer.promo') }}"
   class="home-button-secondary">
    Lihat Promo
</a>

            </div>

        </div>

    </div>

</section>

{{-- =====================================================
     TRUST / BENEFIT
===================================================== --}}
<section class="home-benefit-strip">

    <div class="home-container home-benefit-grid">

        <div class="home-benefit-item">

            <div class="home-benefit-icon">
                ✦
            </div>

            <div>
                <strong>Bersih</strong>
                <span>Perawatan cucian lebih teratur</span>
            </div>

        </div>


        <div class="home-benefit-item">

            <div class="home-benefit-icon">
                ✓
            </div>

            <div>
                <strong>Praktis</strong>
                <span>Pesan tanpa banyak langkah</span>
            </div>

        </div>


        <div class="home-benefit-item">

            <div class="home-benefit-icon">
                ♡
            </div>

            <div>
                <strong>Terpercaya</strong>
                <span>Status pesanan dapat dipantau</span>
            </div>

        </div>

    </div>

</section>

{{-- =====================================================
     LAYANAN
===================================================== --}}
<section
    id="layanan"
    class="home-section home-services"
>

    <div class="home-container">

        <div class="home-section-heading">

            <div>

                <span class="home-section-label">
                    LAYANAN KAMI
                </span>

                <h2>
                    Pilihan layanan Sang Pencuci
                </h2>

                <p>
                    Pilih layanan sesuai kebutuhan cucianmu.
                </p>

            </div>

        </div>


   @php
    $serviceSlides = $services->chunk(4);
@endphp

<div class="home-service-carousel">

    {{-- Tombol sebelumnya --}}
    @if ($serviceSlides->count() > 1)
        <button
            type="button"
            class="home-service-arrow home-service-arrow-prev"
            aria-label="Layanan sebelumnya"
        >
            ‹
        </button>
    @endif


    {{-- Area yang menampilkan slide --}}
    <div class="home-service-viewport">

        <div class="home-service-track">

            @foreach ($serviceSlides as $slide)

                <div class="home-service-slide">

                    @foreach ($slide as $service)

                        <article class="home-service-card">

                            <div class="home-service-image">

                                @php
                                    $serviceImage = $service->image;

                                    if (!$serviceImage) {
                                        $serviceName = strtolower($service->service_name);

                                        if (str_contains($serviceName, 'kiloan')) {
                                            $serviceImage = 'laundry-7-kiloan.png';
                                        } elseif (
                                            str_contains($serviceName, 'delicates') ||
                                            str_contains($serviceName, 'dry clean')
                                        ) {
                                            $serviceImage = 'dry-clean.png';
                                        } elseif (
                                            str_contains($serviceName, 'extra service')
                                        ) {
                                            $serviceImage = 'extra-services.png';
                                        } else {
                                            $serviceImage = 'laundry-7-kiloan.png';
                                        }
                                    }
                                @endphp

                                <img
                                    src="{{ asset('images/' . $serviceImage) }}"
                                    alt="{{ $service->service_name }}"
                                >

                            </div>


                            <div class="home-service-content">

                                <h3>
                                    {{ $service->service_name }}
                                </h3>


                                <div class="home-service-packages">

                                    @foreach ($service->servicePackages as $package)

                                        <div class="home-service-package">

                                            <span class="home-service-package-name">
                                                {{ $loop->iteration }}. {{ $package->package_name }}
                                            </span>

                                            <strong class="home-service-package-price">
                                                Rp{{ number_format($package->price, 0, ',', '.') }}
                                            </strong>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @endforeach

        </div>

    </div>


    {{-- Tombol berikutnya --}}
    @if ($serviceSlides->count() > 1)
        <button
            type="button"
            class="home-service-arrow home-service-arrow-next"
            aria-label="Layanan berikutnya"
        >
            ›
        </button>
    @endif

</div>


{{-- Pagination --}}
@if ($serviceSlides->count() > 1)

    <div class="home-service-pagination">

        @foreach ($serviceSlides as $slide)

            <button
                type="button"
                class="home-service-dot {{ $loop->first ? 'active' : '' }}"
                aria-label="Lihat layanan halaman {{ $loop->iteration }}"
                data-slide="{{ $loop->index }}"
            ></button>

        @endforeach

    </div>

@endif

</div> {{-- home-container --}}
</section> {{-- home-services --}}

{{-- =====================================================
     WHY SANG PENCUCI
===================================================== --}}
<section class="home-section home-about">

    <div class="home-container home-about-inner">

        {{-- VISUAL --}}
        <div class="home-about-visual">

            <div class="home-about-image-wrap">

                <img
                    src="{{ asset('images/why-sang-pencuci.jpeg') }}"
                    alt="Mesin cuci Sang Pencuci"
                    class="home-about-image"
                >

            </div>

        </div>


        {{-- CONTENT --}}
        <div class="home-about-content">

            <span class="home-section-label">
                KENAPA SANG PENCUCI?
            </span>

            <h2>
                Laundry lebih mudah,
                waktumu tetap untuk hal lain.
            </h2>

            <p>
                Sang Pencuci membantu kamu memesan dan memantau
                layanan laundry dengan lebih praktis, sehingga
                urusan cucian tidak perlu mengambil banyak waktu.
            </p>


            <div class="home-about-list">

                <div class="home-about-item">

                 <div class="home-about-item-icon">
    <svg
        viewBox="0 0 48 48"
        width="28"
        height="28"
        aria-hidden="true"
    >
        <!-- Dokumen -->
        <path
            d="M12 5.5h17l7 7v30H12z"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linejoin="round"
        />

        <!-- Lipatan dokumen -->
        <path
            d="M29 5.5v8h7"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linejoin="round"
        />

        <!-- Garis informasi -->
        <path
            d="M18 21h12"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
        />

        <path
            d="M18 27h9"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
        />

        <!-- Lingkaran centang -->
        <circle
            cx="35"
            cy="35"
            r="8"
            fill="currentColor"
        />

        <!-- Centang putih -->
        <path
            d="M31.5 35l2.2 2.2 4.5-5"
            fill="none"
            stroke="#FFFFFF"
            stroke-width="2.2"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
    </svg>
</div>

                    <div>

                        <strong>
                            Layanan jelas
                        </strong>

                        <p>
                            Pilihan layanan dan harga dapat dilihat
                            sebelum melakukan pesanan.
                        </p>

                    </div>

                </div>


                <div class="home-about-item">

                    <div class="home-about-item-icon">
    <svg
        viewBox="0 0 48 48"
        width="28"
        height="28"
        aria-hidden="true"
    >
        <!-- Kotak paket -->
        <path
            d="M10 16.5L24 9l14 7.5v16L24 40l-14-7.5z"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linejoin="round"
        />

        <!-- Garis tengah paket -->
        <path
            d="M10 16.5L24 24l14-7.5"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linejoin="round"
        />

        <!-- Garis vertikal paket -->
        <path
            d="M24 24v16"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            stroke-linejoin="round"
        />

        <!-- Detail bagian atas paket -->
        <path
            d="M17 12.5l14 7.5"
            fill="none"
            stroke="currentColor"
            stroke-width="2.5"
            stroke-linecap="round"
        />

        <!-- Pin lokasi -->
        <path
            d="M36 29
               c-4.2 0-7.5 3.2-7.5 7.1
               c0 5.1 7.5 10.4 7.5 10.4
               s7.5-5.3 7.5-10.4
               c0-3.9-3.3-7.1-7.5-7.1z"
            fill="currentColor"
        />

        <!-- Lubang pin -->
        <circle
            cx="36"
            cy="36"
            r="2.3"
            fill="#FFFFFF"
        />
    </svg>
</div>

                    <div>

                        <strong>
                            Status pesanan
                        </strong>

                        <p>
                            Perkembangan pesanan dapat dipantau
                            sampai pesanan selesai.
                        </p>

                    </div>

                </div>


                <div class="home-about-item">

                    <div class="home-about-item-icon">
    <svg
        viewBox="0 0 48 48"
        width="28"
        height="28"
        aria-hidden="true"
    >
        <!-- Smartphone -->
        <rect
            x="12"
            y="5"
            width="21"
            height="38"
            rx="3"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
        />

        <!-- Layar -->
        <rect
            x="16"
            y="10"
            width="13"
            height="23"
            rx="1"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        />

        <!-- Tombol bawah -->
        <circle
            cx="22.5"
            cy="38"
            r="1.5"
            fill="currentColor"
        />

        <!-- Efek sentuhan -->
        <circle
            cx="31"
            cy="29"
            r="3"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        />

        <!-- Jari -->
        <path
            d="M31 27
               C29.5 27 28.5 28.2 28.5 29.5
               V34
               L26 31.5
               C25.2 30.7 23.9 30.7 23.2 31.5
               C22.5 32.3 22.6 33.5 23.4 34.2
               L28.5 39.2
               C29.5 40.2 31 40.7 32.5 40.7
               H35
               C38 40.7 40 38.5 40 35.7
               V32
               C40 30.5 38.8 29.3 37.3 29.3
               C36.5 29.3 35.8 29.7 35.3 30.3
               V29
               C35.3 27.8 34.3 27 33.1 27
               C32.2 27 31.5 27.4 31 28.1
               Z"
            fill="currentColor"
        />

        <!-- Gelombang sentuhan -->
        <path
            d="M35 23
               C37 24
               38.5 25.5
               39.5 27.5"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
        />

        <path
            d="M39 21
               C41.2 22.5
               42.8 24.5
               43.5 27"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
        />
    </svg>
</div>

                    <div>

                        <strong>
                            Praktis digunakan
                        </strong>

                        <p>
                            Dilengkapi notifkasi pesanan saat selesai langsung pada anda melaui telepon.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>




{{-- =====================================================
     CARA KERJA
===================================================== --}}
<section class="home-section home-process">

    <div class="home-container">

        <div class="home-section-heading home-process-heading">

            <div>

                <span class="home-section-label">
                    CARA KERJA
                </span>

                <h2>
                    Laundry jadi lebih sederhana
                </h2>

                <p>
                    Tiga langkah sederhana dari pesanan
                    sampai cucian selesai.
                </p>

            </div>

        </div>


      <div class="home-process-image">
    <img
        src="{{ asset('images/cara-kerja-laundry.png') }}"
        alt="Cara kerja layanan Sang Pencuci"
    >
</div>

    </div>

</section>


{{-- =====================================================
     ACTIVE ORDER
     HANYA UNTUK CUSTOMER LOGIN
===================================================== --}}
@auth

    @if(isset($activeOrder) && $activeOrder)

        <section class="home-section home-active-order">

            <div class="home-container">

                <div class="home-active-order-card">

                    <div class="active-order-heading">

                        <span class="home-section-label">
                            PESANAN KAMU
                        </span>

                        <h2>
                            Pesanan sedang diproses
                        </h2>

                        <p>
                            Pantau perkembangan pesanan laundrymu.
                        </p>

                    </div>


                    <div class="active-order-status">

                        <span class="active-order-badge">
                            {{ $activeOrder->status }}
                        </span>

                    </div>


                    <div class="active-order-progress">

                        <div class="order-step active">

                            <span class="order-step-dot">
                                ✓
                            </span>

                            <span>
                                Antrian
                            </span>

                        </div>


                        <div class="order-step-line"></div>


                        <div class="order-step">

                            <span class="order-step-dot">
                                2
                            </span>

                            <span>
                                Proses
                            </span>

                        </div>


                        <div class="order-step-line"></div>


                        <div class="order-step">

                            <span class="order-step-dot">
                                3
                            </span>

                            <span>
                                Siap
                            </span>

                        </div>


                        <div class="order-step-line"></div>


                        <div class="order-step">

                            <span class="order-step-dot">
                                4
                            </span>

                            <span>
                                Selesai
                            </span>

                        </div>

                    </div>


                    <a
                        href="{{ url('/pesanan/' . $activeOrder->id) }}"
                        class="home-active-order-button"
                    >
                        Lihat Detail Pesanan
                    </a>

                </div>

            </div>

        </section>


      

    @endif

@endauth
  @include('customer.components.whatsapp-float')
@endsection