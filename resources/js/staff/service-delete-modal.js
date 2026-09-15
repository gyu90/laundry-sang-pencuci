document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('deleteServiceModal');

    const closeButton = document.getElementById(
        'closeDeleteServiceModal'
    );

    const cancelButton = document.getElementById(
        'cancelDeleteService'
    );

    const deleteForm = document.getElementById(
        'deleteServiceForm'
    );

    const serviceNameElement = document.getElementById(
        'deleteServiceName'
    );


    // ======================================================
    // MODAL ERROR
    // ======================================================

    const errorModal = document.getElementById(
        'deleteServiceErrorModal'
    );

    const closeErrorButton = document.getElementById(
        'closeDeleteServiceErrorModal'
    );

    const understandErrorButton = document.getElementById(
        'closeDeleteServiceError'
    );


    // ======================================================
    // VALIDASI MODAL HAPUS
    // ======================================================

    if (
        !modal ||
        !closeButton ||
        !cancelButton ||
        !deleteForm
    ) {
        console.error(
            'Modal hapus layanan tidak ditemukan.'
        );

        return;
    }


    // ======================================================
    // MEMBUKA MODAL HAPUS
    // ======================================================

    window.openDeleteServiceModal = (
        serviceId,
        serviceName
    ) => {

        serviceNameElement.textContent = serviceName;

        deleteForm.action =
            `/staff/services/${serviceId}`;

        modal.classList.add('show');
    };


    // ======================================================
    // MENUTUP MODAL HAPUS
    // ======================================================

    function closeDeleteModal() {

        modal.classList.remove('show');

    }


    // ======================================================
    // TOMBOL X
    // ======================================================

    closeButton.addEventListener('click', () => {

        closeDeleteModal();

    });


    // ======================================================
    // TOMBOL BATAL
    // ======================================================

    cancelButton.addEventListener('click', () => {

        closeDeleteModal();

    });


    // ======================================================
    // MENUTUP MODAL ERROR
    // ======================================================

    function closeDeleteErrorModal() {

    if (errorModal) {

        errorModal.classList.remove('show');

    }

    }


    // ======================================================
    // TOMBOL X MODAL ERROR
    // ======================================================

    if (closeErrorButton) {

    closeErrorButton.addEventListener('click', () => {

        closeDeleteErrorModal();

    });

}


if (understandErrorButton) {

    understandErrorButton.addEventListener('click', () => {

        closeDeleteErrorModal();

    });

}


if (errorModal) {

    errorModal.addEventListener('click', (event) => {

        if (event.target === errorModal) {

            closeDeleteErrorModal();

        }

    });

}


    // ======================================================
    // TOMBOL MENGERTI
    // ======================================================

    if (understandErrorButton) {

        understandErrorButton.addEventListener('click', () => {

            closeDeleteErrorModal();

        });

    }


    // ======================================================
    // KLIK AREA LUAR MODAL ERROR
    // ======================================================

    if (errorModal) {

        errorModal.addEventListener('click', (event) => {

            if (event.target === errorModal) {

                closeDeleteErrorModal();

            }

        });

    }


    // ======================================================
    // KLIK AREA LUAR MODAL HAPUS
    // ======================================================

    modal.addEventListener('click', (event) => {

        if (event.target === modal) {

            closeDeleteModal();

        }

    });

});