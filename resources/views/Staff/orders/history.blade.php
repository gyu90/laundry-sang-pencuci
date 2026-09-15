@extends('layouts.panel')

@section('title', 'Riwayat Pemesanan')

@push('styles')
    @vite('resources/css/staff/history.css')
@endpush

@section('content')

<div class="history-page">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="history-header">

        <div>
            <h1>Riwayat Pemesanan</h1>
            <p>Daftar pesanan laundry yang telah selesai.</p>
        </div>

        <a
            href="{{ route('staff.orders.index') }}"
            class="back-button"
        >
            ← Kembali ke Pesanan
        </a>

    </div>


    {{-- =========================
        FILTER
    ========================== --}}
    <div class="history-filter">

        <form
            method="GET"
            action="{{ route('staff.orders.history') }}"
        >

            {{-- Pencarian nomor order --}}
            <div class="search-wrapper">

                <img
    src="{{ asset('images/search.png') }}"
    alt="Cari"
    class="search-icon"
>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nomor order..."
                >

            </div>


            {{-- Filter --}}
            <div class="filter-row">

                {{-- Bulan --}}
                <div class="filter-group">

                    <label for="month">
                        Bulan
                    </label>

                    <select
                        name="month"
                        id="month"
                    >

                        <option value="">
                            Semua Bulan
                        </option>

                        @foreach ([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ] as $number => $name)

                            <option
                                value="{{ $number }}"
                                @selected(request('month') == $number)
                            >
                                {{ $name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Tahun --}}
                <div class="filter-group">

                    <label for="year">
                        Tahun
                    </label>

                    <select
                        name="year"
                        id="year"
                    >

                        <option value="">
                            Semua Tahun
                        </option>

                        @for ($year = now()->year; $year >= 2025; $year--)

                            <option
                                value="{{ $year }}"
                                @selected(request('year') == $year)
                            >
                                {{ $year }}
                            </option>

                        @endfor

                    </select>

                </div>


                {{-- Tanggal Mulai --}}
<div class="filter-group">

    <label for="date_from">
        Tanggal Mulai
    </label>

    <input
        type="date"
        name="date_from"
        id="date_from"
        value="{{ request('date_from') }}"
    >

</div>


{{-- Tanggal Sampai --}}
<div class="filter-group">

    <label for="date_to">
        Tanggal Sampai
    </label>

    <input
        type="date"
        name="date_to"
        id="date_to"
        value="{{ request('date_to') }}"
    >

</div>


                {{-- Tombol --}}
                <div class="filter-actions">

                    <button
                        type="submit"
                        class="filter-button"
                    >
                        Cari
                    </button>

                    <a
                        href="{{ route('staff.orders.history') }}"
                        class="reset-button"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    <div class="history-card">

    @if ($monthlyOrders->isEmpty())

        {{-- Tidak ada data --}}
        <div class="empty-history">

            <div class="empty-icon">
                ◷
            </div>

            <h3>
                Tidak ada riwayat pemesanan
            </h3>

            <p>
                Belum ada pesanan selesai yang sesuai
                dengan pencarian atau filter.
            </p>

        </div>

    @else

        @foreach ($monthlyOrders as $month => $monthOrders)

            @php
                $monthDate = \Carbon\Carbon::createFromFormat(
                    'Y-m',
                    $month
                );
            @endphp

            <div class="history-month-card">

                {{-- =========================
                    HEADER BULAN
                ========================== --}}
                <div class="history-month-header">

                    <div class="history-month-title">

                        <div class="history-month-icon">
                            <img
                                src="{{ asset('images/calendar.png') }}"
                                alt="Kalender"
                            >
                        </div>

                        <div>

                            <h2>
                                {{ $monthDate->translatedFormat('F Y') }}
                            </h2>

                            <p>
                                {{ $monthOrders->total() }}
                                pesanan selesai
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =========================
                    TABEL
                ========================== --}}
                <div class="history-orders-wrapper">

                    {{-- HEADER TABEL --}}
                    <div class="history-order history-order-header">

                        <div>No</div>
                        <div>Nomor Order</div>
                        <div>Nama Pelanggan</div>
                        <div>Jasa yang Dipesan</div>
                        <div>Total</div>
                        <div>Diskon</div>
                        <div>Total Pembayaran</div>
                        <div>Status</div>

                    </div>


                    {{-- DATA --}}
                    @foreach ($monthOrders as $index => $order)

                        <div class="history-order">

                            {{-- NO --}}
                            <div>
                                {{ $monthOrders->firstItem() + $index }}
                            </div>


                            {{-- NOMOR ORDER --}}
                            <div class="history-order-number">

                                <strong>
                                    ORD-{{ str_pad(
                                        $order->id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}
                                </strong>

                                <span>
                                    {{ $order->order_date->format('d M Y') }}
                                </span>

                                <small>
                                    {{ $order->order_date->format('H:i') }}
                                </small>

                            </div>


                            {{-- CUSTOMER --}}
                            <div class="history-order-customer">

                                <strong>
                                    {{ $order->customer->name ?? '-' }}
                                </strong>

                                @if ($order->customer->user?->phone)

                                    <span>
                                        {{ $order->customer->user->phone }}
                                    </span>

                                @endif

                            </div>


                            {{-- LAYANAN --}}
                            <div class="history-order-service">

                                @foreach ($order->items as $item)

                                    <div class="history-service-item">

                                        <span class="history-service-dot"></span>

                                        <span>
                                            {{ $item->servicePackage->package_name ?? '-' }}
                                            × {{ $item->quantity }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>


                            {{-- TOTAL --}}
                            <div class="history-order-total">

                                Rp {{ number_format(
                                    $order->total_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>


                            {{-- DISKON --}}
                            <div class="history-order-discount">

                                @if ($order->discount_amount > 0)

                                    Rp {{ number_format(
                                        $order->discount_amount,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                @else

                                    -

                                @endif

                            </div>


                            {{-- TOTAL PEMBAYARAN --}}
                            <div class="history-order-final">

                                Rp {{ number_format(
                                    $order->final_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>


                            {{-- STATUS --}}
                            <div>

                                <span class="history-status">
                                    ✓ Selesai
                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- =========================
                    PAGINATION BULAN
                ========================== --}}
                <div class="history-month-footer">

                    <div class="history-pagination-info">

                        Menampilkan
                        {{ $monthOrders->firstItem() }}
                        -
                        {{ $monthOrders->lastItem() }}
                        dari
                        {{ $monthOrders->total() }}
                        pesanan

                    </div>


                    @if ($monthOrders->hasPages())

                        <div class="history-pagination">

                            {{ $monthOrders->onEachSide(1)->links('pagination::simple-tailwind') }}

                        </div>

                    @endif

                </div>

            </div>

        @endforeach

    @endif

</div>

</div>

@endsection