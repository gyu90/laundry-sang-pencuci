document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // ELEMENT MODAL
    // ==========================================

    const modal =
        document.getElementById('paymentValidationModal');

    const closeButton =
        document.getElementById('closePaymentValidationModal');

    const cancelButton =
        document.getElementById('cancelPaymentValidation');

    const orderNumber =
        document.getElementById('paymentOrderNumber');

    const customerName =
        document.getElementById('paymentCustomerName');

    const serviceList =
        document.getElementById('paymentServiceList');

    const voucherStatus =
        document.getElementById('paymentVoucherStatus');

    const voucherDiscountRow =
        document.getElementById('paymentVoucherDiscountRow');

    const voucherDiscount =
        document.getElementById('paymentVoucherDiscount');

    const finalTotal =
        document.getElementById('paymentFinalTotal');

    const currentStatus =
        document.getElementById('paymentCurrentStatus');

    const paymentForm =
         document.getElementById('paymentValidationForm');
    // ==========================================
    // CEK ELEMENT
    // ==========================================

    if (!modal) {
        console.log('Modal validasi pembayaran tidak ditemukan.');
        return;
    }


    // ==========================================
    // DATA ORDER
    // ==========================================

    const paymentButtons =
        document.querySelectorAll('.payment-confirm-btn');


    // ==========================================
    // FORMAT RUPIAH
    // ==========================================

    function formatRupiah(value) {

        return 'Rp' + Number(value || 0)
            .toLocaleString('id-ID');

    }


    // ==========================================
    // BUKA MODAL
    // ==========================================

    paymentButtons.forEach(button => {

        button.addEventListener('click', () => {

            const orderId =
                button.dataset.orderId;

            paymentForm.action =
            `/staff/orders/${orderId}/mark-paid`;
            console.log(
                'Payment order diklik:',
                orderId
            );



            // Ambil data order dari JSON
            const orderData =
                JSON.parse(button.dataset.order);


            // ==========================================
            // NOMOR ORDER
            // ==========================================

            orderNumber.textContent =
                orderData.order_number;


            // ==========================================
            // CUSTOMER
            // ==========================================

            customerName.textContent =
                orderData.customer_name;


            // ==========================================
            // DETAIL LAYANAN
            // ==========================================

            serviceList.innerHTML = '';


            if (
                orderData.items &&
                orderData.items.length > 0
            ) {

                orderData.items.forEach(item => {

                    const serviceItem =
                        document.createElement('div');

                    serviceItem.classList.add(
                        'payment-service-item'
                    );


                    serviceItem.innerHTML = `

                        <div class="payment-service-name">

                            <strong>
                                ${item.package_name}
                            </strong>

                            <span>
                                × ${item.quantity}
                            </span>

                        </div>

                        <div class="payment-service-price">
                            ${formatRupiah(item.subtotal)}
                        </div>

                    `;


                    serviceList.appendChild(
                        serviceItem
                    );

                });

            } else {

                serviceList.innerHTML = `

                    <div class="payment-service-item">

                        <div class="payment-service-name">

                            <strong>
                                Tidak ada layanan
                            </strong>

                        </div>

                    </div>

                `;

            }


            // ==========================================
            // VOUCHER
            // ==========================================

            if (orderData.has_voucher) {

                voucherStatus.textContent =
                    'Digunakan';

                voucherDiscountRow.style.display =
                    'flex';

                voucherDiscount.textContent =
                    formatRupiah(
                        orderData.discount_amount
                    );

            } else {

                voucherStatus.textContent =
                    'Tidak digunakan';

                voucherDiscountRow.style.display =
                    'none';

            }


            // ==========================================
            // TOTAL PEMBAYARAN
            // ==========================================

            finalTotal.textContent =
                formatRupiah(
                    orderData.final_amount
                );


            // ==========================================
            // STATUS PEMBAYARAN
            // ==========================================

            currentStatus.textContent =
                orderData.payment_status;

            currentStatus.classList.remove(
                'paid',
                'unpaid'
            );


            if (
                orderData.payment_status ===
                'Lunas'
            ) {

                currentStatus.classList.add(
                    'paid'
                );

            } else {

                currentStatus.classList.add(
                    'unpaid'
                );

            }


            // ==========================================
            // TAMPILKAN MODAL
            // ==========================================

            modal.classList.add('show');

        });

    });


    // ==========================================
    // TUTUP MODAL
    // ==========================================

    closeButton?.addEventListener('click', () => {

        modal.classList.remove('show');

    });


    cancelButton?.addEventListener('click', () => {

        modal.classList.remove('show');

    });


    // ==========================================
    // KLIK DI LUAR MODAL
    // ==========================================

    modal.addEventListener('click', event => {

        if (event.target === modal) {

            modal.classList.remove('show');

        }

    });

});