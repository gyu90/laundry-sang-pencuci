@extends('layouts.panel')

@section('content')

@vite([
    'resources/css/app.css',
    'resources/css/staff/customers.css',
    'resources/css/staff/customer-detail.css',
])

<div class="customer-detail-page">

    {{-- ==========================================================
        HEADER DETAIL CUSTOMER
    =========================================================== --}}

    <div class="customer-detail-header">

        <div>
            <h1>Detail Customer</h1>

            <p>
                Informasi pelanggan dan riwayat pesanan.
            </p>
        </div>

        <a
            href="{{ route('staff.customers.index') }}"
            class="customer-detail-back-button"
        >
            Kembali
        </a>

    </div>


    {{-- ==========================================================
        INFORMASI CUSTOMER
    =========================================================== --}}

    <div class="customer-detail-card">

        <div class="customer-detail-card-header">

            <div>
                <h2>
                    {{ $customer->name }}
                </h2>

                <p>
                    Informasi pelanggan
                </p>
            </div>

        </div>


        <div class="customer-detail-info">

            {{-- NOMOR HP --}}
            <div class="customer-detail-info-item">

                <span class="customer-detail-info-label">
                    No. HP
                </span>

                <strong class="customer-detail-info-value">
                    {{ $customer->user?->phone ?? '-' }}
                </strong>

            </div>


            {{-- EMAIL --}}
            <div class="customer-detail-info-item">

                <span class="customer-detail-info-label">
                    Email
                </span>

                <strong class="customer-detail-info-value">
                    {{ $customer->email ?? '-' }}
                </strong>

            </div>


            {{-- ALAMAT --}}
            <div class="customer-detail-info-item">

                <span class="customer-detail-info-label">
                    Alamat
                </span>

                <strong class="customer-detail-info-value">
                    {{ $customer->address ?? '-' }}
                </strong>

            </div>


{{-- LOKASI --}}
<div class="customer-detail-info-item">

    <span class="customer-detail-info-label">
        Lokasi
    </span>

    @if ($customer->maps_link)

        <a
            href="{{ $customer->maps_link }}"
            target="_blank"
            rel="noopener noreferrer"
            class="customer-detail-location-link"
        >
            Lihat lokasi di Google Maps
        </a>

    @else

        <strong class="customer-detail-info-value">
            Lokasi belum tersedia
        </strong>

    @endif

</div>


            {{-- TERDAFTAR --}}
            <div class="customer-detail-info-item">

                <span class="customer-detail-info-label">
                    Terdaftar
                </span>

                <strong class="customer-detail-info-value">
                    {{ $customer->registered_at?->format('d/m/Y') ?? '-' }}
                </strong>

            </div>


            {{-- DIBUAT OLEH --}}
            <div class="customer-detail-info-item">

                <span class="customer-detail-info-label">
                    Ditambahkan Oleh
                </span>

                <strong class="customer-detail-info-value">
                    {{ $customer->createdByStaff?->name ?? '-' }}
                </strong>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        FILTER RIWAYAT ORDER
    =========================================================== --}}

    <div class="customer-detail-card">

        <div class="customer-detail-card-header">

            <div>
                <h2>
                    Riwayat Pesanan
                </h2>

                <p>
                    Daftar pesanan yang pernah dilakukan customer.
                </p>
            </div>

        </div>


<form
    method="GET"
    action="{{ route('staff.customers.show', $customer) }}"
    class="customer-order-filter"
>

    {{-- CARI NOMOR ORDER --}}
    <div class="customer-order-filter-group customer-order-filter-order">

        <label for="order_number">
            Nomor Order
        </label>

        <input
            type="text"
            id="order_number"
            name="order_number"
            value="{{ request('order_number') }}"
            placeholder="Contoh: ORD-0043"
        >

    </div>


    {{-- TANGGAL MULAI --}}
    <div class="customer-order-filter-group">

        <label for="start_date">
            Tanggal Mulai
        </label>

        <input
            type="date"
            id="start_date"
            name="start_date"
            value="{{ request('start_date') }}"
        >

    </div>


    {{-- TANGGAL SAMPAI --}}
    <div class="customer-order-filter-group">

        <label for="end_date">
            Tanggal Sampai
        </label>

        <input
            type="date"
            id="end_date"
            name="end_date"
            value="{{ request('end_date') }}"
        >

    </div>


    {{-- TOMBOL CARI --}}
    <button
        type="submit"
        class="customer-order-filter-button customer-order-filter-search"
    >
        Cari
    </button>


    {{-- RESET --}}
    <a
        href="{{ route('staff.customers.show', $customer) }}"
        class="customer-order-filter-button customer-order-filter-reset"
    >
        Reset
    </a>

</form>

    </div>


    {{-- ==========================================================
        DAFTAR ORDER
    =========================================================== --}}

    <div class="customer-detail-card">

        <div class="customer-detail-card-header">

            <div>
                <h2>
                    Daftar Pesanan
                </h2>

                <p>
                    Menampilkan pesanan milik
                    <strong>{{ $customer->name }}</strong>.
                </p>
            </div>

        </div>


        <div class="customer-order-table-wrapper">

            <table class="customer-order-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nomor Order</th>

                        <th>Tanggal</th>

                        <th>Layanan</th>

                        <th>Jumlah</th>

                        <th>Total</th>

                        <th>Status</th>

                        <th>Staff</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($orders as $index => $order)

                        <tr>

                            {{-- NOMOR --}}
                            <td>
                                {{ $orders->firstItem() + $index }}
                            </td>


{{-- NOMOR ORDER --}}
<td>
    <strong class="customer-order-number">
        ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
    </strong>
</td>


                            {{-- TANGGAL --}}
                            <td>
                                {{ $order->order_date?->format('d/m/Y H:i') ?? '-' }}
                            </td>


                            {{-- LAYANAN --}}
                            <td>

                                @forelse ($order->items as $item)

                                    <div class="customer-order-service">

                                        <strong>
                                            {{ $item->servicePackage?->package_name ?? '-' }}
                                        </strong>

                                    </div>

                                @empty

                                    <span>-</span>

                                @endforelse

                            </td>


                            {{-- JUMLAH --}}
                            <td>

                                @forelse ($order->items as $item)

                                    <div>
                                        {{ $item->quantity }}
                                    </div>

                                @empty

                                    <span>-</span>

                                @endforelse

                            </td>


                            {{-- TOTAL --}}
                            <td>
                                Rp
                                {{ number_format(
                                    $order->final_amount,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span class="customer-order-status">
                                    {{ $order->status }}
                                </span>

                            </td>


                            {{-- STAFF --}}
                            <td>
                                {{ $order->createdByStaff?->name ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="customer-order-empty"
                            >
                                Belum ada pesanan untuk customer ini.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ======================================================
            PAGINATION
        ======================================================= --}}

        @if ($orders->hasPages())

            <div class="customer-detail-pagination">

                {{ $orders->onEachSide(1)->links('pagination::simple-tailwind') }}

            </div>

        @endif

    </div>

</div>

@endsection