document.addEventListener('DOMContentLoaded', () => {

    // ==========================================================
    // MODAL TAMBAH CUSTOMER
    // ==========================================================

    const addCustomerModal =
        document.getElementById('addCustomerModal');

    const openAddCustomerModal =
        document.getElementById('openAddCustomerModal');

    const closeAddCustomerModal =
        document.getElementById('closeAddCustomerModal');

    const cancelAddCustomer =
        document.getElementById('cancelAddCustomer');

    const addCustomerForm =
        document.getElementById('addCustomerForm');


    // ==========================================================
    // BUKA MODAL
    // ==========================================================

    openAddCustomerModal?.addEventListener('click', () => {

        addCustomerModal?.classList.add('show');

    });


    // ==========================================================
    // TUTUP MODAL
    // ==========================================================

    const closeCustomerModal = () => {

        addCustomerModal?.classList.remove('show');

        // Kosongkan data form ketika modal ditutup
        addCustomerForm?.reset();

    };


    // ==========================================================
    // TOMBOL X
    // ==========================================================

    closeAddCustomerModal?.addEventListener(
        'click',
        closeCustomerModal
    );


    // ==========================================================
    // TOMBOL BATAL
    // ==========================================================

    cancelAddCustomer?.addEventListener(
        'click',
        closeCustomerModal
    );


    // ==========================================================
    // KLIK AREA GELAP DI LUAR MODAL
    // ==========================================================

    addCustomerModal?.addEventListener('click', (event) => {

        if (event.target === addCustomerModal) {

            closeCustomerModal();

        }

    });

});