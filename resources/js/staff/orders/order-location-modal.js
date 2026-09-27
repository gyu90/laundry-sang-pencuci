/* ==========================================================
   ORDER LOCATION MODAL
   ========================================================== */

document.addEventListener('DOMContentLoaded', () => {

    const modal = document.querySelector('#orderLocationModal');

    if (!modal) {
        return;
    }

const closeButtons = modal.querySelectorAll(
    '#closeOrderLocationModal, #closeOrderLocationModalButton'
);

const customerName = modal.querySelector(
    '#orderLocationCustomerName'
);

const customerPhone = modal.querySelector(
    '#orderLocationCustomerPhone'
);

const customerAddress = modal.querySelector(
    '#orderLocationCustomerAddress'
);

const mapsButton = modal.querySelector(
    '#orderLocationMapsLink'
);

const mapsWrapper = modal.querySelector(
    '#orderLocationMapsWrapper'
);

    /* ======================================================
       OPEN MODAL
       ====================================================== */

    document.addEventListener('click', (event) => {

        const locationButton = event.target.closest(
            '[data-order-location-button]'
        );

        if (!locationButton) {
            return;
        }


        /* ==============================================
           AMBIL DATA DARI BUTTON
           ============================================== */

const name =
    locationButton.dataset.customerName || '-';

const phone =
    locationButton.dataset.customerPhone || '';

const address =
    locationButton.dataset.customerAddress || '';

const mapsLink =
    locationButton.dataset.mapsLink || '';


        /* ==============================================
           CUSTOMER NAME
           ============================================== */

        if (customerName) {
            customerName.textContent = name;
        }

        // ==============================================
// CUSTOMER PHONE / WHATSAPP
// ==============================================

if (customerPhone) {

    customerPhone.textContent = phone || '-';

    if (phone.trim() !== '') {

        let whatsappNumber = phone.replace(/\D/g, '');

        if (whatsappNumber.startsWith('0')) {
            whatsappNumber =
                '62' + whatsappNumber.substring(1);
        }

        customerPhone.href =
            `https://wa.me/${whatsappNumber}`;

        customerPhone.target = '_blank';
        customerPhone.rel = 'noopener noreferrer';

    } else {

        customerPhone.removeAttribute('href');
        customerPhone.removeAttribute('target');
        customerPhone.removeAttribute('rel');

    }
}


        /* ==============================================
           CUSTOMER ADDRESS
           ============================================== */

        if (customerAddress) {

            if (address.trim() !== '') {
                customerAddress.textContent = address;
            } else {
                customerAddress.textContent =
                    'Alamat belum tersedia.';
            }

        }


        /* ==============================================
           GOOGLE MAPS
           HANYA MUNCUL JIKA ADA MAPS LINK
           ============================================== */

       if (mapsButton && mapsWrapper) {
    if (mapsLink.trim() !== '') {
        mapsButton.href = mapsLink;
        mapsButton.target = '_blank';
        mapsButton.rel = 'noopener noreferrer';

        mapsWrapper.style.display = 'block';
        mapsButton.style.display = 'inline-flex';
    } else {
        mapsButton.removeAttribute('href');
        mapsButton.removeAttribute('target');
        mapsButton.removeAttribute('rel');

        mapsWrapper.style.display = 'none';
        mapsButton.style.display = 'none';
    }
}

        /* ==============================================
           SHOW MODAL
           ============================================== */

        modal.classList.add('show');

        document.body.style.overflow = 'hidden';

    });


    /* ======================================================
       CLOSE MODAL
       ====================================================== */

    const closeModal = () => {

        modal.classList.remove('show');

        document.body.style.overflow = '';

    };


    /* ======================================================
       CLOSE BUTTON
       ====================================================== */

    closeButtons.forEach((button) => {

        button.addEventListener(
            'click',
            closeModal
        );

    });


    /* ======================================================
       CLOSE WHEN CLICK OUTSIDE
       ====================================================== */

    modal.addEventListener('click', (event) => {

        if (event.target === modal) {
            closeModal();
        }

    });


    /* ======================================================
       CLOSE WITH ESCAPE
       ====================================================== */

    document.addEventListener('keydown', (event) => {

        if (
            event.key === 'Escape' &&
            modal.classList.contains('show')
        ) {
            closeModal();
        }

    });

});