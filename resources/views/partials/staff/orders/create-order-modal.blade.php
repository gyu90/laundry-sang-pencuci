    <div
    id="createOrderModal"
    class="order-modal-overlay"
    data-service-packages='@json($servicePackages)'
    data-vouchers='@json($vouchers)'
>

    <div class="order-modal">

        {{-- HEADER --}}
        <div class="order-modal-header">

            <div class="order-modal-title-wrap">

                <div class="order-modal-cart-icon">
                    <img
                        src="{{ asset('images/cart.png') }}"
                        alt="Order"
                    >
                </div>

                <div>
                    <h2>Tambah Order</h2>

                    <p>
                        Buat order baru untuk customer.
                    </p>
                </div>

            </div>

            <button
                type="button"
                class="order-modal-close"
                id="closeCreateOrderModal"
            >
                ×
            </button>

        </div>


        {{-- FORM --}}
        <form
            id="createOrderForm"
            method="POST"
            action="{{ route('staff.orders.store') }}"
        >

            @csrf

            {{-- BODY --}}
            <div class="order-modal-body">

                {{-- CUSTOMER --}}
<div class="order-customer-section">

    <div class="order-section-header">

        <div class="order-section-icon customer-icon">

            <img
                src="{{ asset('images/customer.png') }}"
                alt=""
            >

        </div>

        <h3>Customer</h3>

    </div>


    <div class="customer-search-wrapper">

        <img
            src="{{ asset('images/search.png') }}"
            class="customer-search-icon"
            alt=""
        >

        <input
            type="text"
            id="customerSearch"
            class="customer-search-input"
            placeholder="Cari nama atau nomor HP..."
            autocomplete="off"
        >

        <input
            type="hidden"
            name="customer_id"
            id="customer_id"
        >


        {{-- Hasil pencarian customer --}}
        <div
            id="customerSearchResults"
            class="customer-search-results"
        >

            @foreach ($customers as $customer)

                <button
                    type="button"
                    class="customer-search-item"
                    data-customer-id="{{ $customer->id }}"
                    data-customer-name="{{ $customer->name }}"
                    data-customer-phone="{{ $customer->user->phone ?? '' }}"
                >

                    <span class="customer-search-name">
                        {{ $customer->name }}
                    </span>

                    <span class="customer-search-phone">
                        {{ $customer->user->phone ?? 'Nomor HP belum tersedia' }}
                    </span>

                </button>

            @endforeach

        </div>

    </div>

</div>

{{-- LAYANAN --}}
<div class="order-service-section">

    <div class="order-section-header">

        <div class="order-section-icon service-icon">
            <img
                src="{{ asset('images/service.png') }}"
                alt=""
            >
        </div>

        <h3>Layanan</h3>

    </div>


    {{-- Daftar layanan dibuat oleh JavaScript --}}
    <div
        id="orderServiceList"
        class="order-service-list"
        data-services='@json($services)'
    >
    </div>


    <button
        type="button"
        id="addOrderService"
        class="order-add-service"
    >

        <span class="order-add-service-plus">
            +
        </span>

        <span>
            Tambah layanan
        </span>

    </button>

</div>
{{-- STATUS --}}
<div class="order-form-group">

    <label for="order_status">
        Status
    </label>

    <div class="order-form-group">

    <select id="order_status" disabled>
        <option selected>Dalam Antrian</option>
    </select>

</div>

</div>


{{-- STATUS PEMBAYARAN --}}
<div class="order-form-group">

    <label>
        Status pembayaran
    </label>

    <div class="payment-status-options">

        <label class="payment-status-option unpaid active">
            <input
                type="radio"
                name="payment_status"
                value="Belum Lunas"
                checked
            >

            <span class="payment-status-dot"></span>

            <span class="payment-status-text">
                Belum bayar
            </span>
        </label>


        <label class="payment-status-option paid">
            <input
                type="radio"
                name="payment_status"
                value="Lunas"
            >

            <span class="payment-status-dot"></span>

            <span class="payment-status-text">
                Sudah bayar
            </span>
        </label>

    </div>

</div>
                {{-- VOUCHER --}}
                <div class="order-form-group">

                    <label for="voucher_id">
                        Voucher
                    </label>

                    <select
                        name="applied_voucher_id"
                        id="voucher_id"
                    >

                        <option value="">
                            Tidak menggunakan voucher
                        </option>

                    </select>

                    <div class="order-voucher-info">
                        Voucher yang tersedia untuk customer
                        akan ditampilkan di sini.
                    </div>

                </div>


                {{-- RINGKASAN --}}
                <div class="order-summary">

                    <div class="order-summary-row">

                        <span>Total</span>

                        <strong id="orderTotal">
                            Rp0
                        </strong>

                    </div>


                    <div class="order-summary-row">

                        <span>Potongan Voucher</span>

                        <strong id="orderDiscount">
                            Rp0
                        </strong>

                    </div>


                    <div class="order-summary-row order-summary-total">

                        <span>Total Akhir</span>

                        <strong id="orderFinalTotal">
                            Rp0
                        </strong>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="order-modal-footer">

                <button
                    type="button"
                    id="cancelCreateOrder"
                    class="order-btn order-btn-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="order-btn order-btn-save"
                >
                    Simpan Order
                </button>

            </div>

        </form>

    </div>

</div>

