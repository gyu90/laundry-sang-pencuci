@extends('layouts.panel')

@section('content')

@vite([
    'resources/css/staff/loyalty.css'
])

<div class="loyalty-page">

    {{-- HEADER --}}
    <div class="loyalty-header">

        <div>

            <h1>
                Detail Program Loyalitas
            </h1>

            <p>
                {{ $loyaltyProgram->name }}
            </p>

        </div>

        <a
            href="{{ route('staff.loyalty.index') }}"
            class="loyalty-detail-back-button"
        >
            ← Kembali
        </a>

    </div>


    {{-- INFORMASI PROGRAM --}}
    <div class="loyalty-detail-card">

        <div class="loyalty-detail-card-header">

            <div>

                <h2>
                    {{ $loyaltyProgram->name }}
                </h2>

                <p>
                    {{ $loyaltyProgram->description ?? 'Tidak ada deskripsi.' }}
                </p>

            </div>

            @if ($loyaltyProgram->is_active)

                <span class="loyalty-status active">
                    Aktif
                </span>

            @else

                <span class="loyalty-status inactive">
                    Tidak Aktif
                </span>

            @endif

        </div>


        <div class="loyalty-detail-info">

            <div>
                <span>Minimal Transaksi</span>

                <strong>
                    Rp{{ number_format(
                        $loyaltyProgram->min_transaction_amount,
                        0,
                        ',',
                        '.'
                    ) }}
                </strong>
            </div>


            <div>
                <span>Periode</span>

                <strong>
                    {{ $loyaltyProgram->start_date->format('d M Y') }}
                    -
                    {{ $loyaltyProgram->end_date->format('d M Y') }}
                </strong>
            </div>


            <div>
                <span>Reward</span>

                <strong>

                    @if ($loyaltyProgram->reward_type === 'discount_percentage')

                        Cashback {{ number_format(
                            $loyaltyProgram->reward_value,
                            0,
                            ',',
                            '.'
                        ) }}%

                    @elseif ($loyaltyProgram->reward_type === 'discount_nominal')

                        Cashback Rp{{ number_format(
                            $loyaltyProgram->reward_value,
                            0,
                            ',',
                            '.'
                        ) }}

                    @elseif ($loyaltyProgram->reward_type === 'free_service')

                        Gratis Layanan

                    @endif

                </strong>

            </div>


            <div>
                <span>Layanan</span>

                <strong>
                    {{ $loyaltyProgram->applicableService->service_name ?? 'Semua Layanan' }}
                </strong>
            </div>

        </div>

    </div>


    {{-- PROGRESS CUSTOMER --}}
    <div class="loyalty-detail-card">

        <div class="loyalty-detail-section-header">

            <div>

                <h2>
                    Progress Customer
                </h2>

                <p>
                    Data customer yang sedang mengikuti program ini.
                </p>

            </div>

        </div>


        <div class="loyalty-detail-table-wrapper">

            <table class="loyalty-detail-table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Total Transaksi</th>
                        <th>Progress</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse (
                        $loyaltyProgram->customerLoyaltyTrackings
                        as $index => $tracking
                    )

                        @php

                            $progress = $loyaltyProgram->min_transaction_amount > 0
                                ? min(
                                    100,
                                    ($tracking->total_spent_in_period /
                                    $loyaltyProgram->min_transaction_amount) * 100
                                )
                                : 0;

                        @endphp

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $tracking->customer->name }}
                            </td>

                            <td>
                                Rp{{ number_format(
                                    $tracking->total_spent_in_period,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>

                                <div class="loyalty-progress">

                                    <div class="loyalty-progress-bar">

                                        <div
                                            class="loyalty-progress-fill"
                                            style="width: {{ $progress }}%"
                                        ></div>

                                    </div>

                                    <span>
                                        {{ number_format($progress, 0) }}%
                                    </span>

                                </div>

                            </td>

                            <td>

                                @if ($tracking->is_eligible)

                                    <span class="loyalty-detail-badge success">
                                        Eligible
                                    </span>

                                @else

                                    <span class="loyalty-detail-badge pending">
                                        Belum Memenuhi
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="loyalty-detail-empty"
                            >
                                Belum ada customer yang memiliki
                                tracking pada program ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- VOUCHER --}}
    <div class="loyalty-detail-card">

        <div class="loyalty-detail-section-header">

            <div>

                <h2>
                    Voucher Terbit
                </h2>

                <p>
                    Voucher yang telah diterbitkan dari program ini.
                </p>

            </div>

        </div>


        <div class="loyalty-detail-table-wrapper">

            <table class="loyalty-detail-table">

                <thead>

                    <th>No</th>
<th>Customer</th>
<th>Kode Voucher</th>
<th>Reward</th>
<th>Status</th>
<th>Staff yang Claim</th>
<th>Terbit</th>
<th>Berakhir</th>

                </thead>

                <tbody>

                    @forelse (
                        $loyaltyProgram->vouchers
                        as $index => $voucher
                    )

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $voucher->customer->name }}
                            </td>

                            <td>
                                <strong>
                                    {{ $voucher->code }}
                                </strong>
                            </td>

                            <td>

                                @if ($voucher->reward_type === 'discount_nominal')

                                    Cashback Rp{{ number_format(
                                        $voucher->reward_value,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                @elseif ($voucher->reward_type === 'discount_percentage')

                                    Cashback {{ number_format(
                                        $voucher->reward_value,
                                        0,
                                        ',',
                                        '.'
                                    ) }}%

                                @elseif ($voucher->reward_type === 'free_service')

                                    Gratis Layanan

                                @endif

                            </td>

                            <td>
                                {{ ucfirst($voucher->status) }}
                            </td>

                            <td>
    {{ $voucher->appliedByStaff->name ?? '-' }}
</td>

                            <td>
                                {{ $voucher->issued_at?->format('d M Y H:i') ?? '-' }}
                            </td>

                            <td>
                                {{ $voucher->expiry_at?->format('d M Y H:i') ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="loyalty-detail-empty"
                            >
                                Belum ada voucher yang diterbitkan.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection