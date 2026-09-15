@extends('layouts.customer')

@section('page_title', 'Akun Saya')

@section('content')

@push('scripts')
    @vite('resources/js/customer/account.js')
@endpush
<section class="account-page">

    <div class="account-container">

        {{-- =====================================================
             GREETING
        ====================================================== --}}
       <div class="account-greeting">

    <h1>
        Halo, {{ $customer?->name ?? $user->username }} 👋
    </h1>

    <p>
        Selamat datang kembali di Sang Pencuci
    </p>

</div>

    {{-- =====================================================
     PESANAN AKTIF
====================================================== --}}
<section class="account-active-order">

    <div class="active-order-header">

        <div>

            @if($activeOrder)

                <span class="active-order-label">
                    PESANAN AKTIF · #{{ $activeOrder->id }}
                </span>

                <h2>
                    @foreach($activeOrder->items as $item)
                        {{ $item->servicePackage?->package_name ?? 'Layanan' }}@if(!$loop->last), @endif
                    @endforeach
                </h2>

                <span class="active-order-count">
                    {{ $activeOrder->items->count() }} layanan
                </span>

            @else

                <span class="active-order-label">
                    PESANAN AKTIF
                </span>

                <h2>
                    Tidak ada pesanan aktif
                </h2>

                <span class="active-order-count">
                    0 layanan
                </span>

            @endif

        </div>

    </div>


          {{-- =====================================================
     PROGRESS PESANAN
====================================================== --}}
@php
    $statusOrder = [
        'Dalam Antrian',
        'Proses',
        'Tahap Pengantaran',
        'Selesai',
    ];

    $currentIndex = null;

    if ($activeOrder) {

        if ($activeOrder->status === 'Siap Dijemput') {
            $currentIndex = 2;
        }

        elseif ($activeOrder->status === 'Siap Diantar') {
            $currentIndex = 2;
        }

        else {
            $currentIndex = array_search(
                $activeOrder->status,
                $statusOrder
            );
        }
    }
@endphp

<div class="order-progress">

    @foreach($statusOrder as $index => $step)

        @php
            $isCompleted = $currentIndex !== null
                && $index < $currentIndex;

            $isCurrent = $currentIndex !== null
                && $index === $currentIndex;
        @endphp

        <div class="order-progress-step
            {{ $isCompleted ? 'completed' : '' }}
            {{ $isCurrent ? 'current' : '' }}"
        >

            <div class="order-progress-dot">
                @if($isCompleted)
                    ✓
                @endif
            </div>

<span>
    @if($index === 2)

        @if($activeOrder?->status === 'Siap Diantar')
            Dalam Proses Pengantaran
        @else
            Siap Dijemput
        @endif

    @else
        {{ $step }}
    @endif
</span>


        </div>

        @if(!$loop->last)

            <div class="order-progress-line
                {{ $currentIndex !== null && $index < $currentIndex ? 'completed' : '' }}"
            ></div>

        @endif

    @endforeach

</div>
           
@if(
    $activeOrder &&
    $activeOrder->status === 'Siap Dijemput' &&
    !$activeOrder->pickup_requested &&
    !$activeOrder->delivery_requested
)

    <div class="active-order-action">

        <h3>
            Pesanan sudah siap!
            Mau diambil sendiri atau diantar?
        </h3>

        <div class="delivery-action-list">

<button
    type="button"
    id="openDeliveryRequest"
    class="delivery-action-button delivery-action-primary"
>
    Minta diantar
</button>

            <form
    id="pickupOrderForm"
    action="{{ route('customer.order.pickup', $activeOrder->id) }}"
    method="POST"
>
    @csrf

    <button
        type="button"
        id="openPickupConfirm"
        class="delivery-action-button delivery-action-secondary"
    >
        Ambil sendiri
    </button>
</form>

        </div>

    </div>

@endif

        </section>


        {{-- =====================================================
     MODAL KONFIRMASI AMBIL SENDIRI
====================================================== --}}
<div
    id="pickupConfirmModal"
    class="account-modal"
>
    <div
        class="account-modal-overlay"
        id="pickupConfirmOverlay"
    ></div>

    <div class="account-modal-dialog pickup-confirm-dialog">

        <div class="account-modal-header">

            <div>
                <span class="account-modal-label">
                    PESANAN AKTIF
                </span>

                <h2>
                    Konfirmasi Ambil Sendiri
                </h2>
            </div>

            <button
                type="button"
                id="closePickupConfirmModal"
                class="account-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>

        <div class="pickup-confirm-content">

            <p>
                Apakah Anda yakin ingin mengambil pesanan
                ini sendiri di toko?
            </p>

        </div>

      <div class="account-modal-actions">

    <button
        type="button"
        id="cancelPickupConfirm"
        class="account-modal-button account-modal-cancel"
    >
        Batal
    </button>

    <button
        type="button"
        id="confirmPickup"
        class="account-modal-button account-modal-save"
    >
        Ya, Ambil Sendiri
    </button>

</div>

    </div>
</div>

{{-- =====================================================
     MODAL KONFIRMASI MINTA DIANTAR
====================================================== --}}

@if($activeOrder)
<div
    id="deliveryConfirmModal"
    class="account-modal"
>
    <div
        class="account-modal-overlay"
        id="deliveryConfirmOverlay"
    ></div>

    <div class="account-modal-dialog delivery-confirm-dialog">

        <div class="account-modal-header">

            <div>
                <span class="account-modal-label">
                    PESANAN AKTIF
                </span>

                <h2>
                    Minta Diantar?
                </h2>
            </div>

            <button
                type="button"
                id="closeDeliveryConfirmModal"
                class="account-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>

        <div class="delivery-confirm-content">

            <p>
                Pesanan Anda akan diantarkan ke alamat berikut:
            </p>

            <div class="delivery-address-box">
                <strong>
                    {{ auth()->user()->customer?->address }}
                </strong>
            </div>

            <p>
                Apakah Anda yakin ingin meminta pesanan ini diantar?
            </p>

        </div>

      <div class="account-modal-actions">

    <button
        type="button"
        id="cancelDeliveryConfirm"
        class="account-modal-button account-modal-cancel"
    >
        Batal
    </button>

    <form
        action="{{ route('customer.order.delivery', $activeOrder->id) }}"
        method="POST"
    >
        @csrf

        <button
            type="submit"
            id="confirmDeliveryRequest"
            class="account-modal-button account-modal-save"
        >
            Ya, Minta Diantar
        </button>
    </form>

</div>

    </div>
</div>
@endif

{{-- =====================================================
     MODAL FEEDBACK PERMINTAAN PENGANTARAN
====================================================== --}}
<div
    id="deliverySuccessModal"
    class="account-modal"
    aria-hidden="true"
>
    <div
        class="account-modal-overlay"
        id="deliverySuccessOverlay"
    ></div>

    <div
        class="account-modal-dialog delivery-success-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="deliverySuccessTitle"
    >
        <div class="delivery-success-icon">
            ✓
        </div>

        <span class="account-modal-label">
            PERMINTAAN DITERIMA
        </span>

        <h2 id="deliverySuccessTitle">
            Pengantaran Berhasil Diajukan
        </h2>

        <p>
            Jadwal pengiriman akan dikonfirmasi melalui kontak Anda.
        </p>

        <div class="account-modal-actions">
            <button
                type="button"
                id="closeDeliverySuccess"
                class="account-modal-button account-modal-save"
            >
                Mengerti
            </button>
        </div>
    </div>
</div>

@if(session('delivery_success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal =
                document.getElementById('deliverySuccessModal');

            if (modal) {
                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
        });
    </script>
@endif


{{-- =====================================================
     MODAL ALAMAT BELUM DIISI
====================================================== --}}
<div
    id="deliveryAddressModal"
    class="account-modal"
>
    <div
        class="account-modal-overlay"
        id="deliveryAddressOverlay"
    ></div>

    <div class="account-modal-dialog delivery-address-dialog">

        <div class="account-modal-header">

            <div>
                <span class="account-modal-label">
                    PESANAN AKTIF
                </span>

<h2>
    Lokasi Pengantaran Belum Lengkap
</h2>

            </div>

            <button
                type="button"
                id="closeDeliveryAddressModal"
                class="account-modal-close"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>

        <div class="delivery-address-content">

<p>
    Untuk mengirim pesanan ke lokasi Anda, kami membutuhkan
    alamat lengkap dan link Google Maps agar proses pengantaran
    menjadi lebih mudah. Silakan lengkapi keduanya terlebih dahulu.
</p>

        </div>

        <div class="account-modal-actions">

            <button
                type="button"
                id="cancelDeliveryAddress"
                class="account-modal-button account-modal-cancel"
            >
                Batal
            </button>

            <button
                type="button"
                id="openAddressEdit"
                class="account-modal-button account-modal-save"
            >
                Isi Alamat & Lokasi
            </button>

        </div>

    </div>
</div>


{{-- =====================================================
     MODAL PERMINTAAN BERHASIL
====================================================== --}}
@if(session('pickup_success') && $activeOrder)

    @php
        $paymentStatus = $activeOrder->payment?->status ?? 'Belum Lunas';
        $isPaid = $paymentStatus === 'Lunas';
    @endphp

    <div
        id="pickupSuccessModal"
        class="account-modal show"
    >

        <div
            class="account-modal-overlay"
            id="pickupSuccessOverlay"
        ></div>

        <div class="account-modal-dialog pickup-success-dialog">

            <div class="account-modal-header">

                <div>
                    <span class="account-modal-label">
                        PESANAN AKTIF
                    </span>

                    <h2>
                        Permintaan Berhasil
                    </h2>
                </div>

                <button
                    type="button"
                    id="closePickupSuccessModal"
                    class="account-modal-close"
                    aria-label="Tutup"
                >
                    ×
                </button>

            </div>

            <div class="pickup-success-content">

                <p>
                    Pesanan Anda telah ditandai untuk
                    <strong>diambil sendiri</strong>.
                </p>

                <p>
                    Pihak toko akan menunggu kedatangan Anda
                    untuk mengambil pesanan.
                </p>


                @if($isPaid)

                    <div class="pickup-payment-info">

                        <span>
                            Pembayaran
                        </span>

                        <strong>
                            Lunas
                        </strong>

                    </div>

                    <p class="pickup-success-note">
                        Silakan datang ke toko untuk mengambil
                        pesanan Anda.
                    </p>

                @else

                    <div class="pickup-payment-info">

                        <span>
                            Tagihan
                        </span>

                        <strong>
                            Rp{{ number_format($activeOrder->final_amount, 0, ',', '.') }}
                        </strong>

                    </div>

                    <div class="pickup-payment-status">

                        <span>
                            Status pembayaran
                        </span>

                        <strong>
                            Belum Lunas
                        </strong>

                    </div>

                    <p class="pickup-success-note">
                        Silakan melakukan pembayaran saat
                        mengambil pesanan.
                    </p>

                @endif

            </div>

            <div class="account-modal-actions">

                <button
                    type="button"
                    id="confirmPickupSuccess"
                    class="account-modal-button account-modal-save"
                >
                    Oke
                </button>

            </div>

        </div>

    </div>

@endif

        {{-- =====================================================
             RIWAYAT PESANAN
        ====================================================== --}}
        <section class="account-history-card">

            <div class="account-section-header">

                <h2>
                    Riwayat pesanan
                </h2>

            </div>


            <div class="account-history-list">

               <div class="account-history-list">

    @forelse($orderHistory as $order)

        <article class="account-history-item">

            <div class="history-item-main">

                <h3>
                    @foreach($order->items as $item)
                        {{ $item->servicePackage?->package_name ?? 'Layanan' }}@if(!$loop->last), @endif
                    @endforeach
                </h3>

                <span>
                    {{ $order->order_date->translatedFormat('d F Y') }}
                </span>

            </div>

            <div class="history-item-side">

                <strong>
                    Rp{{ number_format($order->final_amount, 0, ',', '.') }}
                </strong>

                <span class="history-status">
                    {{ $order->status }}
                </span>

            </div>

        </article>

    @empty

        <div class="account-history-empty">
            Belum ada riwayat pesanan.
        </div>

    @endforelse

</div>

            </div>


            <a
                href="{{ url('/pesanan-saya') }}"
                class="account-history-button"
            >
                Lihat semua riwayat
                <span>↗</span>
            </a>

        </section>


       <section class="account-profile-card">

    {{-- HEADER --}}
    <div class="account-profile-header">

        <h2>
            Akun saya
        </h2>

        <button
            type="button"
            class="account-profile-edit-main"
            id="openAccountEdit"
        >
            Edit
        </button>

    </div>


    {{-- INFORMASI AKUN --}}
    <div class="account-profile-item">
    <span>Nomor HP</span>
    <strong>{{ auth()->user()->phone }}</strong>
</div>

<div class="account-profile-item">
    <span>Alamat</span>

    <strong
        id="customerAddress"
        data-address="{{ $customer?->address ?? '' }}"
    >
        {{ $customer?->address ?? '-' }}
    </strong>
</div>

<div class="account-profile-item">
    <span>Kata sandi</span>
    <strong>••••••••</strong>
</div>
</section>
@include('customer.components.account-edit-modal')
@endsection