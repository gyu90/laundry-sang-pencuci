@extends('layouts.panel')

@section('content')


@vite([
    'resources/css/app.css',
    'resources/css/staff/orders.css',
    'resources/css/staff/order-services.css',
    'resources/js/app.js',
    'resources/js/staff/orders/create-order-modal.js',
    'resources/js/staff/orders/order-status.js',
    'resources/js/staff/orders/payment-validation-modal.js',
    'resources/js/staff/orders/order-filter.js',
    'resources/css/staff/order-location-modal.css',
    'resources/js/staff/orders/order-location-modal.js'
])

<div class="order-page">

    {{-- Header --}}
    <div class="order-page-header">

    <div>
        <h1>Order</h1>
        <p>Kelola pesanan laundry yang masih dalam proses.</p>
    </div>

    <div class="order-page-header-actions">

        <button
            type="button"
            id="openCreateOrderModal"
            class="btn-add-order"
        >
            + Tambah Order
        </button>

        <a
            href="{{ route('staff.orders.history') }}"
            class="history-button"
            title="Riwayat Pemesanan"
        >
            <span class="history-icon">↺</span>
            <span>Riwayat</span>
        </a>

    </div>

</div>

    {{-- Card Order --}}
    <div class="order-card">

       <div class="order-card-header">

    <div class="order-card-title">

        <div class="order-card-icon">
            <img
                src="{{ asset('images/order.png') }}"
                alt="Order"
            >
        </div>

        <div>
            <h2>Daftar Order</h2>
            <p>Pesanan yang masih membutuhkan proses staff.</p>
        </div>

    </div>

    <div class="order-card-tools">

        <div class="order-search-wrapper">
            <img
                src="{{ asset('images/search.png') }}"
                alt="Cari"
                class="order-search-icon"
            >

            <input
    type="text"
    id="orderSearch"
    class="order-search-input"
    placeholder="Cari order, nama, atau layanan..."
    autocomplete="off"
>
        </div>

<select
    id="orderStatusFilter"
    name="status"
    class="order-filter-select"
>
    <option value="" {{ request('status') == '' ? 'selected' : '' }}>
        Semua Status
    </option>

    <option value="Dalam Antrian" {{ request('status') == 'Dalam Antrian' ? 'selected' : '' }}>
        Dalam Antrian
    </option>

    <option value="Proses" {{ request('status') == 'Proses' ? 'selected' : '' }}>
        Proses
    </option>

    <option value="Siap Dijemput" {{ request('status') == 'Siap Dijemput' ? 'selected' : '' }}>
        Siap Dijemput
    </option>

    <option value="Siap Diantar" {{ request('status') == 'Siap Diantar' ? 'selected' : '' }}>
        Siap Diantar
    </option>

    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
        Selesai
    </option>
</select>


        <button
    type="button"
    id="orderFilterReset"
    class="order-filter-reset"
>
    Reset
</button>

    </div>

</div>


        {{-- PESAN HASIL PENCARIAN KOSONG --}}
        <div
            id="orderSearchNoResult"
            class="order-search-no-result"
            style="display: none;"
        >
            Pesanan atas nama <strong id="orderSearchKeyword"></strong> tidak ditemukan.
        </div>

        <div class="order-table-wrapper">

            <table class="order-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Permintaan</th>
                        <th>Diterima Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($orders as $index => $order)

                        <tr
    class="order-table-row"
    data-status="{{ $order->status }}"
>

                            {{-- No --}}
                            <td>
                                {{ $index + 1 }}
                            </td>


                           {{-- Nomor Order --}}
<td>
    <div class="order-number-cell">

        <strong>
            ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </strong>

        <span class="order-date">
            {{ $order->order_date->format('d M Y') }}
        </span>

        <span class="order-time">
            {{ $order->order_date->format('H:i') }}
        </span>

    </div>
</td>


                           {{-- Customer --}}
<td>
    <div class="customer-table-cell">

        <strong>
            {{ $order->customer->name }}
        </strong>

        @if ($order->customer->user?->phone)
            <span>
                {{ $order->customer->user->phone }}
            </span>
        @endif

    </div>
</td>


                           {{-- Layanan --}}
<td>

    <div class="service-table-cell">

        @foreach ($order->items as $item)

            <div class="service-table-item">
                <span class="service-table-dot"></span>

                <span>
                    {{ $item->servicePackage->package_name }}
                    × {{ $item->quantity }}
                </span>
            </div>

        @endforeach

    </div>

</td>


                            {{-- Total --}}
<td>
    Rp {{ number_format($order->final_amount, 0, ',', '.') }}
</td>


{{-- Pembayaran --}}
<td>

    @if ($order->payment?->status === 'Lunas')

        <span class="payment-badge paid">
            ✓ Lunas
        </span>

    @else

        <div class="payment-action">

            <span class="payment-badge unpaid">
                Belum Lunas
            </span>

          <button
    type="button"
    class="payment-confirm-btn"

    data-order-id="{{ $order->id }}"

    data-order='{{ json_encode([
        "order_number" => "ORD-" . str_pad($order->id, 4, "0", STR_PAD_LEFT),
        "customer_name" => $order->customer->name,
        "final_amount" => $order->final_amount,
        "discount_amount" => $order->discount_amount,
        "has_voucher" => !is_null($order->applied_voucher_id),
        "payment_status" => $order->payment?->status ?? "Belum Lunas",
        "items" => $order->items->map(function ($item) {
            return [
                "package_name" => $item->servicePackage->package_name,
                "quantity" => $item->quantity,
                "subtotal" => $item->subtotal,
            ];
        })->values()->toArray(),
    ]) }}'
>
    Tandai Lunas
</button>

        </div>

    @endif

</td>


{{-- Status --}}
<td>

    @if ($order->status === 'Dalam Antrian')

        <span class="order-status order-status-pending">
            Dalam Antrian
        </span>

    @elseif ($order->status === 'Proses')

        <span class="order-status order-status-process">
            Proses
        </span>

    @elseif ($order->status === 'Siap Dijemput')

        <span class="order-status order-status-ready">
            Siap Dijemput
        </span>

    @elseif ($order->status === 'Siap Diantar')

        <span class="order-status order-status-delivery">
            Siap Diantar
        </span>

        @elseif ($order->status === 'Selesai')

    <span class="order-status order-status-completed">
        Selesai
    </span>

    @endif

</td>

{{-- Permintaan --}}
<td>
 @if ($order->pickup_requested)

    <span class="order-request order-request-pickup">
        Ambil sendiri
    </span>

@elseif ($order->delivery_requested || $order->status === 'Siap Diantar')

    <div class="order-request-delivery-wrapper">

        @if ($order->delivery_requested)
            <span class="order-request order-request-delivery">
                Minta diantar
            </span>
        @else
            <span class="order-request order-request-delivery">
                Siap diantar
            </span>
        @endif

        <button
            type="button"
            class="order-location-button"
            data-order-location-button
            data-customer-name="{{ $order->customer->name }}"
             data-customer-phone="{{ $order->customer->user->phone ?? '' }}"
            data-customer-address="{{ $order->customer->address ?? '' }}"
            data-maps-link="{{ $order->customer->maps_link ?? '' }}"
        >
            Lihat Lokasi
        </button>

    </div>

@else

    <span class="order-request-empty">
        —
    </span>

@endif
</td>
                            {{-- Staff --}}
                            <td>
                                {{ $order->createdByStaff->name ?? '-' }}
                            </td>


                       {{-- Aksi --}}
<td>

<select
    class="order-status-select"
    data-order-id="{{ $order->id }}"
    data-order-number="ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}"
    data-payment-status="{{ $order->payment?->status ?? 'Belum Lunas' }}"
>

        <option value="">
            Ubah Status
        </option>

        @if ($order->status === 'Dalam Antrian')

            <option value="Proses">
                Proses
            </option>

        @elseif ($order->status === 'Proses')

            <option value="Siap Dijemput">
                Siap Dijemput
            </option>

            <option value="Siap Diantar">
                Siap Diantar
            </option>

        @elseif ($order->status === 'Siap Dijemput')

            <option value="Selesai">
                Selesai
            </option>

        @elseif ($order->status === 'Siap Diantar')

            <option value="Selesai">
                Selesai
            </option>

        @endif

    </select>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="order-empty"
                            >
                                Belum ada order yang perlu diproses.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

         {{-- FOOTER ORDER --}}
        <div class="order-card-footer">

            <div class="order-pagination-info">
                Menampilkan
                {{ $orders->firstItem() ?? 0 }}
                -
                {{ $orders->lastItem() ?? 0 }}
                dari
                {{ $orders->total() }}
                pesanan
            </div>

            @if ($orders->hasPages())

                <div class="order-pagination">

                    {{ $orders->onEachSide(1)->links('pagination::simple-tailwind') }}

                </div>

            @endif

        </div>

    </div>    

</div>

@include('partials.staff.orders.create-order-modal')
@include('partials.staff.orders.payment-validation-modal')
{{-- Modal Konfirmasi Perubahan Status --}}
<div
    id="statusConfirmationModal"
    class="order-status-modal"
>

    <div class="order-status-modal-content">

        {{-- Header --}}
        <div class="order-status-modal-header">

            <div>
                <h2>Konfirmasi Perubahan Status</h2>

                <p>
                    Pastikan status order yang dipilih sudah sesuai.
                </p>
            </div>

            <button
                type="button"
                id="closeStatusConfirmationModal"
                class="order-status-modal-close"
            >
                ×
            </button>

        </div>
    




        {{-- Body --}}
        <div class="order-status-modal-body">

            <p>
                Apakah Anda yakin ingin mengubah status
                <strong id="statusConfirmationOrder">
                    ORD-0000
                </strong>
                menjadi:
            </p>

            <div
                id="statusConfirmationValue"
                class="order-status-confirmation-value"
            >
                Selesai
            </div>

        </div>


        {{-- Form --}}
        <form
            id="statusConfirmationForm"
            method="POST"
        >

            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="status"
                id="statusConfirmationInput"
            >


            {{-- Footer --}}
            <div class="order-status-modal-footer">

                <button
                    type="button"
                    id="cancelStatusConfirmation"
                    class="order-btn order-btn-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="order-btn order-btn-save"
                >
                    Ya, Ubah Status
                </button>

            </div>

        </form>

    </div>

</div>

{{-- MODAL VALIDASI STATUS SELESAI --}}
<div
    id="unpaidStatusModal"
    class="unpaid-status-modal"
>

    <div class="unpaid-status-modal-content">

        {{-- Header --}}
        <div class="unpaid-status-modal-header">

            <div class="unpaid-status-modal-title">

                <div class="unpaid-status-modal-icon">
                    !
                </div>

                <h2>Pembayaran Belum Lunas</h2>

            </div>

            <button
                type="button"
                id="closeUnpaidStatusModal"
                class="unpaid-status-modal-close"
            >
                ×
            </button>

        </div>


        {{-- Body --}}
        <div class="unpaid-status-modal-body">

            <p>
                Pesanan ini belum lunas dan belum dapat diubah
                menjadi status <strong>Selesai</strong>.
            </p>

            <p>
                Silakan pastikan pembayaran sudah dikonfirmasi
                terlebih dahulu.
            </p>

        </div>


        {{-- Footer --}}
        <div class="unpaid-status-modal-footer">

            <button
                type="button"
                id="cancelUnpaidStatus"
                class="order-btn order-btn-cancel"
            >
                Batal
            </button>

            <button
                type="button"
                id="confirmUnpaidStatus"
                class="order-btn order-btn-save"
            >
                Oke
            </button>

        </div>

    </div>

</div>

{{-- ==========================================
    MODAL DETAIL PELANGGAN ORDER
========================================== --}}
<div
    id="orderLocationModal"
    class="order-location-modal"
    aria-hidden="true"
>
    <div
        class="order-location-modal-content"
        role="dialog"
        aria-modal="true"
        aria-labelledby="orderLocationModalTitle"
    >

        {{-- HEADER --}}
        <div class="order-location-modal-header">

            <div class="order-location-modal-heading">

                <span class="order-location-modal-label">
                    DETAIL PELANGGAN
                </span>

                <h2 id="orderLocationModalTitle">
                    Informasi Pelanggan
                </h2>

            </div>

            <button
                type="button"
                id="closeOrderLocationModal"
                class="order-location-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>


        {{-- BODY --}}
        <div class="order-location-modal-body">

            {{-- NAMA --}}
            <div class="order-location-detail">

                <div class="order-location-detail-icon"></div>

                <div class="order-location-detail-content">

                    <span class="order-location-detail-label">
                        Nama Pelanggan
                    </span>

                    <strong
                        id="orderLocationCustomerName"
                        class="order-location-detail-value"
                    >
                        -
                    </strong>

                </div>

            </div>


            {{-- NOMOR HP --}}
            <div class="order-location-detail">

               

                <div class="order-location-detail-content">

                    <span class="order-location-detail-label">
                        Nomor WhatsApp
                    </span>

                    <a
                        href="#"
                        id="orderLocationCustomerPhone"
                        class="order-location-phone"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        -
                    </a>

                </div>

            </div>


            {{-- ALAMAT --}}
            <div class="order-location-detail">

                <div class="order-location-detail-icon">
                    
                </div>

                <div class="order-location-detail-content">

                    <span class="order-location-detail-label">
                        Alamat
                    </span>

                    <p
                        id="orderLocationCustomerAddress"
                        class="order-location-address"
                    >
                        -
                    </p>

                </div>

            </div>


            {{-- GOOGLE MAPS --}}
            <div
                id="orderLocationMapsWrapper"
                class="order-location-maps-wrapper"
                style="display: none;"
            >

                <a
                    href="#"
                    id="orderLocationMapsLink"
                    class="order-location-maps-button"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <span>Buka di Google Maps</span>
                </a>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="order-location-modal-footer">

            <button
                type="button"
                id="closeOrderLocationModalButton"
                class="order-location-modal-footer-button"
            >
                Tutup
            </button>

        </div>

    </div>
</div>
@endsection

