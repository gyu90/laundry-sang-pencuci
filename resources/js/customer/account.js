document.addEventListener('DOMContentLoaded', () => {

    // ==========================================================
    // MODAL CUSTOMER / AKUN YANG SUDAH ADA
    // ==========================================================

    const openAddButton =
        document.getElementById('openAddCustomerModal');

    const editButtons =
        document.querySelectorAll('.customer-edit-button');

    const modal =
        document.getElementById('addCustomerModal');

    const closeButton =
        document.getElementById('closeAddCustomerModal');

    const cancelButton =
        document.getElementById('cancelAddCustomer');

    const modalTitle =
        document.getElementById('customerModalTitle');

    const modalDescription =
        document.getElementById('customerModalDescription');

    const submitButton =
        document.getElementById('customerSubmitButton');

    const passwordFields =
        document.getElementById('customerPasswordFields');

    const nameInput =
        document.getElementById('name');

    const phoneInput =
        document.getElementById('phone');

    const emailInput =
        document.getElementById('email');

    const addressInput =
        document.getElementById('address');

    const form =
        document.getElementById('addCustomerForm');


    // ==========================================================
    // MODAL CUSTOMER
    // ==========================================================

    if (modal && form) {

        // ======================================================
        // BUKA MODAL
        // ======================================================

        const openModal = () => {

            modal.classList.add('show');

            document.body.style.overflow = 'hidden';

        };


        // ======================================================
        // TUTUP MODAL
        // ======================================================

        const closeModal = () => {

            modal.classList.remove('show');

            document.body.style.overflow = '';

        };


        // ======================================================
        // MODE TAMBAH
        // ======================================================

        const setAddMode = () => {

            modalTitle.textContent = 'Tambah Customer';

            modalDescription.textContent =
                'Daftarkan pelanggan baru ke dalam sistem.';

            submitButton.textContent =
                'Simpan Customer';

            passwordFields.style.display = 'block';

            form.action = '/staff/customers';

            form.reset();

            openModal();

        };


        // ======================================================
        // TOMBOL TAMBAH
        // ======================================================

        openAddButton?.addEventListener(
            'click',
            setAddMode
        );


        // ======================================================
        // TUTUP MODAL
        // ======================================================

        closeButton?.addEventListener(
            'click',
            closeModal
        );

        cancelButton?.addEventListener(
            'click',
            closeModal
        );


        // ======================================================
        // EDIT CUSTOMER
        // ======================================================

        editButtons.forEach(button => {

            button.addEventListener('click', async () => {

                const customerId =
                    button.dataset.customerId;

                if (!customerId) {
                    return;
                }

                try {

                    const response = await fetch(
                        `/staff/customers/${customerId}/edit`
                    );

                    if (!response.ok) {

                        throw new Error(
                            'Data customer gagal diambil.'
                        );

                    }

                    const customer =
                        await response.json();


                    // ==========================================
                    // MODE EDIT
                    // ==========================================

                    modalTitle.textContent =
                        'Edit Customer';

                    modalDescription.textContent =
                        'Perbarui data pelanggan yang tersimpan dalam sistem.';

                    submitButton.textContent =
                        'Simpan Perubahan';


                    // ==========================================
                    // ISI DATA
                    // ==========================================

                    nameInput.value =
                        customer.name ?? '';

                    phoneInput.value =
                        customer.phone ?? '';

                    emailInput.value =
                        customer.email ?? '';

                    addressInput.value =
                        customer.address ?? '';


                    // ==========================================
                    // PASSWORD DISEMBUNYIKAN
                    // ==========================================

                    passwordFields.style.display =
                        'none';


                    // ==========================================
                    // ACTION FORM
                    // ==========================================

                    form.action =
                        `/staff/customers/${customerId}`;


                    // ==========================================
                    // METHOD PUT
                    // ==========================================

                    let methodInput =
                        form.querySelector(
                            'input[name="_method"]'
                        );

                    if (!methodInput) {

                        methodInput =
                            document.createElement('input');

                        methodInput.type = 'hidden';

                        methodInput.name = '_method';

                        form.appendChild(methodInput);

                    }

                    methodInput.value = 'PUT';


                    // ==========================================
                    // BUKA MODAL
                    // ==========================================

                    openModal();

                } catch (error) {

                    console.error(error);

                    alert(
                        'Data customer tidak dapat dimuat.'
                    );

                }

            });

        });


        // ======================================================
        // ESC CUSTOMER MODAL
        // ======================================================

        document.addEventListener('keydown', event => {

            if (
                event.key === 'Escape' &&
                modal.classList.contains('show')
            ) {

                closeModal();

            }

        });

    }


    // ==========================================================
    // AMBIL SENDIRI
    // ==========================================================

    const pickupButton =
        document.getElementById('openPickupConfirm');

    const pickupModal =
        document.getElementById('pickupConfirmModal');

    const closePickupModalButton =
        document.getElementById('closePickupConfirmModal');

    const cancelPickupButton =
        document.getElementById('cancelPickupConfirm');

    const confirmPickupButton =
        document.getElementById('confirmPickup');

    const pickupForm =
        document.getElementById('pickupOrderForm');


    // ==========================================================
    // CEK MODAL AMBIL SENDIRI
    // ==========================================================

    if (
        pickupButton &&
        pickupModal &&
        pickupForm
    ) {

        // ======================================================
        // BUKA MODAL
        // ======================================================

        const openPickupModal = () => {

            pickupModal.classList.add('show');

            document.body.style.overflow = 'hidden';

        };


        // ======================================================
        // TUTUP MODAL
        // ======================================================

        const closePickupModal = () => {

            pickupModal.classList.remove('show');

            document.body.style.overflow = '';

        };


        // ======================================================
        // TOMBOL AMBIL SENDIRI
        // ======================================================

        pickupButton.addEventListener(
            'click',
            openPickupModal
        );


        // ======================================================
        // CLOSE
        // ======================================================

        closePickupModalButton?.addEventListener(
            'click',
            closePickupModal
        );


        // ======================================================
        // BATAL
        // ======================================================

        cancelPickupButton?.addEventListener(
            'click',
            closePickupModal
        );


        // ======================================================
        // YA, AMBIL SENDIRI
        // ======================================================

        confirmPickupButton?.addEventListener(
            'click',
            () => {

                pickupForm.submit();

            }
        );


        // ======================================================
        // ESC
        // ======================================================

        document.addEventListener('keydown', event => {

            if (
                event.key === 'Escape' &&
                pickupModal.classList.contains('show')
            ) {

                closePickupModal();

            }

        });

    }

});

// ==========================================================
// MODAL PERMINTAAN BERHASIL
// ==========================================================

const pickupSuccessModal =
    document.getElementById('pickupSuccessModal');

const closePickupSuccessModalButton =
    document.getElementById('closePickupSuccessModal');

const confirmPickupSuccess =
    document.getElementById('confirmPickupSuccess');


// ==========================================================
// CEK MODAL
// ==========================================================

if (pickupSuccessModal) {

    // ======================================================
    // TUTUP MODAL
    // ======================================================

    const closePickupSuccessModal = () => {

        pickupSuccessModal.classList.remove('show');

        document.body.style.overflow = '';

    };


    // ======================================================
    // CLOSE BUTTON
    // ======================================================

    closePickupSuccessModalButton?.addEventListener(
        'click',
        closePickupSuccessModal
    );


    // ======================================================
    // TOMBOL OKE
    // ======================================================

    confirmPickupSuccess?.addEventListener(
        'click',
        closePickupSuccessModal
    );


    // ======================================================
    // ESC
    // ======================================================

    document.addEventListener('keydown', event => {

        if (
            event.key === 'Escape' &&
            pickupSuccessModal.classList.contains('show')
        ) {

            closePickupSuccessModal();

        }

    });

}

// ==========================================================
// MINTA DIANTAR
// ==========================================================

const deliveryButton =
    document.getElementById('openDeliveryRequest');

const deliveryConfirmModal =
    document.getElementById('deliveryConfirmModal');

const closeDeliveryConfirmModalButton =
    document.getElementById('closeDeliveryConfirmModal');

const cancelDeliveryConfirmButton =
    document.getElementById('cancelDeliveryConfirm');

const confirmDeliveryRequestButton =
    document.getElementById('confirmDeliveryRequest');

const deliveryAddressModal =
    document.getElementById('deliveryAddressModal');

const closeDeliveryAddressModalButton =
    document.getElementById('closeDeliveryAddressModal');

const cancelDeliveryAddressButton =
    document.getElementById('cancelDeliveryAddress');

const openAddressEditButton =
    document.getElementById('openAddressEdit');


// ==========================================================
// CEK MINTA DIANTAR
// ==========================================================

if (deliveryButton) {

    deliveryButton.addEventListener('click', () => {

        /*
         * Alamat diambil langsung dari tampilan
         * Account Profile.
         */
const addressElement =
    document.getElementById('customerAddress');

const address =
    addressElement?.dataset.address?.trim() ?? '';

const mapsInput =
    document.getElementById('accountMapsLink');

const mapsLink =
    mapsInput?.value?.trim() ?? '';


// ==================================================
// CEK ALAMAT + GOOGLE MAPS
// ==================================================

const hasAddress =
    address && address !== '-';

const hasMapsLink =
    mapsLink !== '';


// ==================================================
// DATA LOKASI BELUM LENGKAP
// ==================================================

if (!hasAddress || !hasMapsLink) {

    deliveryAddressModal?.classList.add('show');

    document.body.style.overflow = 'hidden';

    return;
}


// ==================================================
// ALAMAT + MAPS SUDAH LENGKAP
// ==================================================

deliveryConfirmModal?.classList.add('show');

document.body.style.overflow = 'hidden';

    });

}


// ==========================================================
// TUTUP MODAL KONFIRMASI DIANTAR
// ==========================================================

const closeDeliveryConfirmModal = () => {

    deliveryConfirmModal?.classList.remove('show');

    document.body.style.overflow = '';

};


closeDeliveryConfirmModalButton?.addEventListener(
    'click',
    closeDeliveryConfirmModal
);


cancelDeliveryConfirmButton?.addEventListener(
    'click',
    closeDeliveryConfirmModal
);


// ==========================================================
// TUTUP MODAL ALAMAT
// ==========================================================

const closeDeliveryAddressModal = () => {

    deliveryAddressModal?.classList.remove('show');

    document.body.style.overflow = '';

};


closeDeliveryAddressModalButton?.addEventListener(
    'click',
    closeDeliveryAddressModal
);


cancelDeliveryAddressButton?.addEventListener(
    'click',
    closeDeliveryAddressModal
);


// ==========================================================
// ISI ALAMAT
// ==========================================================

openAddressEditButton?.addEventListener(
    'click',
    () => {

        // Tutup modal alamat
        deliveryAddressModal?.classList.remove('show');

        document.body.style.overflow = '';

        // Buka modal edit akun yang sudah ada
        document
            .getElementById('openAccountEdit')
            ?.click();

    }
);


// ==========================================================
// ESC — MODAL DIANTAR
// ==========================================================

document.addEventListener('keydown', event => {

    if (
        event.key !== 'Escape'
    ) {
        return;
    }


    if (
        deliveryConfirmModal?.classList.contains('show')
    ) {

        closeDeliveryConfirmModal();

        return;

    }


    if (
        deliveryAddressModal?.classList.contains('show')
    ) {

        closeDeliveryAddressModal();

    }

});


// ==========================================================
// MODAL FEEDBACK PERMINTAAN PENGANTARAN
// ==========================================================

const deliverySuccessModal =
    document.getElementById('deliverySuccessModal');

const closeDeliverySuccessButton =
    document.getElementById('closeDeliverySuccess');

const deliverySuccessOverlay =
    document.getElementById('deliverySuccessOverlay');

const closeDeliverySuccessModal = () => {
    deliverySuccessModal?.classList.remove('show');
    deliverySuccessModal?.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
};

closeDeliverySuccessButton?.addEventListener(
    'click',
    closeDeliverySuccessModal
);

deliverySuccessOverlay?.addEventListener(
    'click',
    closeDeliverySuccessModal
);

if (window.deliverySuccess && deliverySuccessModal) {
    deliverySuccessModal.classList.add('show');
    deliverySuccessModal.setAttribute(
        'aria-hidden',
        'false'
    );
    document.body.style.overflow = 'hidden';
}

// ==========================================================
// MODAL EDIT AKUN CUSTOMER
// ==========================================================

const openAccountEditButton =
    document.getElementById('openAccountEdit');

const accountEditModal =
    document.getElementById('accountEditModal');

const closeAccountEditButton =
    document.getElementById('closeAccountEdit');

const cancelAccountEditButton =
    document.getElementById('cancelAccountEdit');

const accountModalOverlay =
    document.getElementById('accountModalOverlay');


// ==========================================================
// BUKA MODAL EDIT AKUN
// ==========================================================

if (openAccountEditButton && accountEditModal) {

    openAccountEditButton.addEventListener(
        'click',
        () => {

            accountEditModal.classList.add('show');

            accountEditModal.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow = 'hidden';

        }
    );

}


// ==========================================================
// TUTUP MODAL EDIT AKUN
// ==========================================================

const closeAccountEditModal = () => {

    accountEditModal?.classList.remove('show');

    accountEditModal?.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.style.overflow = '';

};


closeAccountEditButton?.addEventListener(
    'click',
    closeAccountEditModal
);


cancelAccountEditButton?.addEventListener(
    'click',
    closeAccountEditModal
);


accountModalOverlay?.addEventListener(
    'click',
    closeAccountEditModal
);


// ==========================================================
// ESC — MODAL EDIT AKUN
// ==========================================================

document.addEventListener('keydown', event => {

    if (
        event.key === 'Escape' &&
        accountEditModal?.classList.contains('show')
    ) {

        closeAccountEditModal();

    }

});