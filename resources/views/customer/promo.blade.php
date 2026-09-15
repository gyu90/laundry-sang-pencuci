@extends('layouts.customer')

@section('page_title', 'Promo')

@section('content')

<section class="promo-page">

    {{-- =====================================================
         PROMO HERO
    ===================================================== --}}

    <div class="home-container">

        <div class="promo-hero">

            <div class="promo-hero-content">

                <span class="home-section-label">
                    PROMO &amp; VOUCHER
                </span>

                <h1>
                    Dapatkan voucher
                    <br>
                    <span>berulang kali.</span>
                </h1>

                <p>
                    Nikmati berbagai program promo dari Sang Pencuci.
                    Setiap kali kamu memenuhi syarat yang ditentukan,
                    kamu bisa mendapatkan voucher kembali selama periode
                    promo masih berlangsung.
                </p>

            </div>


            <div class="promo-hero-visual">

                <div class="promo-ticket">

                    <div class="promo-ticket-label">
                        VOUCHER
                    </div>

                    <div class="promo-ticket-title">
                        Untuk Laundry
                        <br>
                        Lebih Hemat
                    </div>

                    <div class="promo-ticket-line"></div>

                    <div class="promo-ticket-bottom">

                        <span>
                            SANG PENCUCI
                        </span>

                        <strong>
                            PROMO
                        </strong>

                    </div>

                </div>

<div class="promo-arrow"></div>
                <div class="promo-hero-checklist">

                    <div class="promo-check-item">
                        <span>✓</span>
                        <p>Transaksi sesuai syarat</p>
                    </div>

                    <div class="promo-check-item">
                        <span>✓</span>
                        <p>Dapatkan voucher</p>
                    </div>

                    <div class="promo-check-item">
                        <span>✓</span>
                        <p>Gunakan untuk laundry</p>
                    </div>

                    <div class="promo-check-item">
                        <span>✓</span>
                        <p>Bisa dapat lagi!</p>
                    </div>

                </div>

        <div class="promo-hero-caption">
        <span>Laundry lebih mudah</span>
        <strong>dengan banyak keuntungan!</strong>
    </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DAFTAR PROMO
    ===================================================== --}}

    <section class="promo-list-section">

        <div class="home-container">

            <div class="promo-list-heading">

                <span class="home-section-label">
                    PROMO TERSEDIA
                </span>

                <h2>
                    Nikmati keuntungan dari Sang Pencuci
                </h2>

                <p>
                    Gunakan promo yang tersedia sesuai dengan ketentuannya.
                </p>

            </div>


            @if ($programs->isNotEmpty())

                <div class="promo-list-grid">

                    @foreach ($programs as $program)

                        <article class="promo-program-card">

                            <div class="promo-program-top">

                                <span class="promo-program-badge">
                                    PROMO
                                </span>

                                <span class="promo-program-period">
                                    {{ $program->start_date->format('d M Y') }}
                                    -
                                    {{ $program->end_date->format('d M Y') }}
                                </span>

                            </div>


                            <div class="promo-program-content">

                                <h3>
                                    {{ $program->name }}
                                </h3>

                                <div class="promo-program-reward">

                                    <span>
                                        Reward
                                    </span>

                                    <strong>

                                        @if ($program->reward_type === 'discount_percentage')

                                            Diskon
                                            {{ rtrim(rtrim(number_format($program->reward_value, 2, ',', '.'), '0'), ',') }}%

                                        @elseif ($program->reward_type === 'discount_nominal')

                                            Diskon
                                            Rp{{ number_format($program->reward_value, 0, ',', '.') }}

@elseif ($program->reward_type === 'free_service')

    @if ($program->freeServicePackage)

        Gratis
        {{ $program->freeServicePackage->package_name }}

        ({{ (int) $program->reward_value }}x)

    @else

        Gratis Layanan

    @endif

                                        @else

                                            Promo Spesial

                                        @endif

                                    </strong>

                                </div>


                                <div class="promo-program-service">

                                    <span>
                                        Berlaku untuk
                                    </span>

                                    <strong>
                                        {{ $program->applicableService->service_name ?? 'Semua Layanan' }}
                                    </strong>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="promo-empty">

                    <h3>
                        Belum ada promo tersedia
                    </h3>

                    <p>
                        Saat ini belum ada program promo yang sedang berlangsung.
                    </p>

                </div>

            @endif

        </div>

    </section>

</section>

@endsection