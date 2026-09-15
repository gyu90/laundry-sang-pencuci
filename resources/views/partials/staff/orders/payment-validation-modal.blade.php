@vite([
    'resources/css/app.css',
    'resources/css/staff/orders.css',
    'resources/js/app.js',
    'resources/js/staff/orders/create-order-modal.js',
    'resources/js/staff/orders/order-status.js',
    'resources/js/staff/orders/payment-validation-modal.js'
])


<div
    id="paymentValidationModal"
    class="payment-validation-modal"
>

    <div class="payment-validation-modal-content">

        {{-- HEADER --}}
        <div class="payment-validation-modal-header">

            <div>
                <h2>Validasi Pembayaran</h2>

                <p>
                    Periksa detail pembayaran order sebelum mengonfirmasi.
                </p>
            </div>

            <button
                type="button"
                id="closePaymentValidationModal"
                class="payment-validation-modal-close"
            >
                ×
            </button>

        </div>


        {{-- BODY --}}
        <div class="payment-validation-modal-body">

            {{-- INFORMASI ORDER --}}
            <div class="payment-validation-order-info">

                <div>
                    <span>Nomor Order</span>

                    <strong id="paymentOrderNumber">
                        ORD-0000
                    </strong>
                </div>

                <div>
                    <span>Customer</span>

                    <strong id="paymentCustomerName">
                        -
                    </strong>
                </div>

            </div>


            {{-- DETAIL LAYANAN --}}
            <div class="payment-validation-section">

                <h3>Detail Layanan</h3>

                <div
                    id="paymentServiceList"
                    class="payment-service-list"
                >
                    {{-- Detail layanan akan diisi JavaScript --}}
                </div>

            </div>


            {{-- VOUCHER --}}
            <div class="payment-validation-section">

                <h3>Voucher</h3>

                <div class="payment-validation-info-row">

                    <span>Status Voucher</span>

                    <strong id="paymentVoucherStatus">
                        -
                    </strong>

                </div>

                <div
                    id="paymentVoucherDiscountRow"
                    class="payment-validation-info-row"
                    style="display: none;"
                >

                    <span>Potongan Voucher</span>

                    <strong id="paymentVoucherDiscount">
                        Rp0
                    </strong>

                </div>

            </div>


            {{-- TOTAL PEMBAYARAN --}}
            <div class="payment-validation-total">

                <span>Total Pembayaran</span>

                <strong id="paymentFinalTotal">
                    Rp0
                </strong>

            </div>


            {{-- STATUS PEMBAYARAN --}}
            <div class="payment-validation-payment-status">

                <span>Status Pembayaran</span>

                <span
                    id="paymentCurrentStatus"
                    class="payment-status-badge unpaid"
                >
                    Belum Lunas
                </span>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="payment-validation-modal-footer">

            <button
                type="button"
                id="cancelPaymentValidation"
                class="order-btn order-btn-cancel"
            >
                Batal
            </button>

           <div class="payment-validation-modal-footer">

    <form
        id="paymentValidationForm"
        method="POST"
    >

        @csrf
        @method('PUT')

        <button
            type="submit"
            id="confirmPaymentButton"
            class="order-btn order-btn-save"
        >
            Tandai Lunas
        </button>

    </form>

</div>

        </div>

    </div>

</div>