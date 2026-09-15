document.addEventListener('DOMContentLoaded', () => {

    console.log('================================');
    console.log('ORDER STATUS JS AKTIF');
    console.log('================================');

    const statusModal =
        document.getElementById('statusConfirmationModal');

    const statusSelects =
        document.querySelectorAll('.order-status-select');

    const statusForm =
        document.getElementById('statusConfirmationForm');

    const statusInput =
        document.getElementById('statusConfirmationInput');

    const orderNumber =
        document.getElementById('statusConfirmationOrder');

    const statusValue =
        document.getElementById('statusConfirmationValue');

    const closeButton =
        document.getElementById('closeStatusConfirmationModal');

    const cancelButton =
        document.getElementById('cancelStatusConfirmation');
    
    const unpaidStatusModal =
    document.getElementById('unpaidStatusModal');

const closeUnpaidStatusCloseButton =
    document.getElementById('closeUnpaidStatusModal');

const cancelUnpaidStatus =
    document.getElementById('cancelUnpaidStatus');

const confirmUnpaidStatus =
    document.getElementById('confirmUnpaidStatus');

let unpaidStatusSelect = null;


    console.log('Modal:', statusModal);
    console.log('Jumlah dropdown:', statusSelects.length);
    console.log('Form:', statusForm);
    console.log('Input:', statusInput);
    console.log('Order Number:', orderNumber);
    console.log('Status Value:', statusValue);


    // ==========================================
    // CEK ELEMEN
    // ==========================================

    if (!statusModal) {
        console.error(
            'ERROR: statusConfirmationModal TIDAK DITEMUKAN'
        );
        return;
    }

    if (!statusForm) {
        console.error(
            'ERROR: statusConfirmationForm TIDAK DITEMUKAN'
        );
        return;
    }

    if (!statusInput) {
        console.error(
            'ERROR: statusConfirmationInput TIDAK DITEMUKAN'
        );
        return;
    }

    if (statusSelects.length === 0) {
        console.error(
            'ERROR: .order-status-select TIDAK DITEMUKAN'
        );
        return;
    }

    console.log(
        'SEMUA ELEMEN STATUS DITEMUKAN'
    );


// ==========================================
// DROPDOWN STATUS
// ==========================================

let pendingStatusSelect = null;
let pendingPaymentStatus = null;

statusSelects.forEach((select, index) => {

    console.log(
        'Memasang event dropdown nomor:',
        index + 1,
        select
    );

    select.addEventListener('change', () => {

        console.log('----------------------------');
        console.log('STATUS DIPILIH');
        console.log('----------------------------');

        const selectedStatus =
            select.value;

        const orderId =
            select.dataset.orderId;

        const selectedOrderNumber =
            select.dataset.orderNumber;

        const paymentStatus =
            select.dataset.paymentStatus;

        console.log(
            'Status:',
            selectedStatus
        );

        console.log(
            'Order ID:',
            orderId
        );

        console.log(
            'Order Number:',
            selectedOrderNumber
        );

        console.log(
            'Payment Status:',
            paymentStatus
        );

        if (!selectedStatus) {
            console.log(
                'Tidak ada status dipilih.'
            );

            return;
        }

        // Simpan order yang sedang diproses
        pendingStatusSelect = select;
        pendingPaymentStatus = paymentStatus;

        // ==========================================
        // ISI INFORMASI MODAL KONFIRMASI
        // ==========================================

        orderNumber.textContent =
            selectedOrderNumber;

        statusValue.textContent =
            selectedStatus;

        statusInput.value =
            selectedStatus;

        // ==========================================
        // TENTUKAN URL
        // ==========================================

        statusForm.action =
            `/staff/orders/${orderId}/status`;

        console.log(
            'Action form:',
            statusForm.action
        );

        // ==========================================
        // BUKA MODAL KONFIRMASI TERLEBIH DAHULU
        // ==========================================

        statusModal.classList.add('show');

        console.log(
            'MODAL KONFIRMASI DIBUKA'
        );
    });
});


// ==========================================
// SUBMIT MODAL KONFIRMASI
// ==========================================

statusForm.addEventListener('submit', (event) => {

    const selectedStatus =
        statusInput.value;

    // ==========================================
    // JIKA SELESAI + BELUM LUNAS
    // ==========================================

    if (
        selectedStatus === 'Selesai'
        && pendingPaymentStatus !== 'Lunas'
    ) {

        event.preventDefault();

        console.log(
            'Pembayaran belum lunas.'
        );

        // Tutup modal konfirmasi
        statusModal.classList.remove('show');

        // Tampilkan modal pembayaran belum lunas
        unpaidStatusModal?.classList.add('show');

        return;
    }

    // ==========================================
    // JIKA LUNAS / STATUS LAIN
    // BIARKAN FORM SUBMIT
    // ==========================================

    console.log(
        'Status dapat diproses.'
    );
});
    // ==========================================
    // TUTUP MODAL
    // ==========================================

    closeButton?.addEventListener(
        'click',
        () => {
            closeModal();
        }
    );


    cancelButton?.addEventListener(
        'click',
        () => {
            closeModal();
        }
    );


    statusModal.addEventListener(
        'click',
        (event) => {

            if (event.target === statusModal) {
                closeModal();
            }

        }
    );

function closeUnpaidStatusModal() {
    unpaidStatusModal?.classList.remove('show');

    if (unpaidStatusSelect) {
        unpaidStatusSelect.value = '';
        unpaidStatusSelect = null;
    }
}

closeUnpaidStatusCloseButton?.addEventListener(
    'click',
    closeUnpaidStatusModal
);

cancelUnpaidStatus?.addEventListener(
    'click',
    closeUnpaidStatusModal
);

unpaidStatusModal?.addEventListener(
    'click',
    (event) => {
        if (event.target === unpaidStatusModal) {
            closeUnpaidStatusModal();
        }
    }
);

confirmUnpaidStatus?.addEventListener(
    'click',
    () => {

        closeUnpaidStatusModal();

        // Notifikasi custom akan kita tampilkan di sini
    }
);


    function closeModal() {

        statusModal.classList.remove('show');

        statusInput.value = '';

        console.log(
            'Modal ditutup'
        );

    }

});