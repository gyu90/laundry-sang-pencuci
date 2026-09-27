document.addEventListener('DOMContentLoaded', () => {

    // ==========================================================
    // MODAL TAMBAH STAFF
    // ==========================================================

    const addStaffButton =
        document.querySelector('.btn-add-staff');

    const modal =
        document.getElementById('addStaffModal');

    const closeButton =
        document.getElementById('closeAddStaffModal');

    const cancelButton =
        document.getElementById('cancelAddStaffModal');


    // Pastikan modal Tambah Staff tersembunyi
    // saat halaman pertama dibuka

    if (modal) {
        modal.classList.remove('show');
    }


    // Buka modal Tambah Staff

    if (addStaffButton && modal) {

        addStaffButton.addEventListener('click', () => {

            modal.classList.add('show');

        });

    }


    // Tutup modal melalui tombol X

    if (closeButton && modal) {

        closeButton.addEventListener('click', () => {

            modal.classList.remove('show');

        });

    }


    // Tutup modal melalui tombol Batal

    if (cancelButton && modal) {

        cancelButton.addEventListener('click', () => {

            modal.classList.remove('show');

        });

    }


    // Tutup modal jika klik area gelap
    // di luar modal

    if (modal) {

        modal.addEventListener('click', (event) => {

            if (event.target === modal) {

                modal.classList.remove('show');

            }

        });

    }



    // ==========================================================
    // MODAL KONFIRMASI STATUS STAFF
    // ==========================================================

    const statusModal =
        document.getElementById(
            'staffStatusConfirmationModal'
        );



    const statusCloseButton =
        document.getElementById(
            'closeStaffStatusModal'
        );

    const statusCancelButton =
        document.getElementById(
            'cancelStaffStatusModal'
        );

    const statusConfirmButton =
        document.getElementById(
            'confirmStaffStatusModal'
        );

    const statusModalTitle =
        document.getElementById(
            'staffStatusModalTitle'
        );

    const statusModalMessage =
        document.getElementById(
            'staffStatusModalMessage'
        );


    // Menyimpan form staff yang sedang diproses

    let pendingStatusForm = null;



    // ==========================================================
    // CARI SEMUA FORM AKTIF / NONAKTIFKAN STAFF
    // ==========================================================

    const statusForms =
    document.querySelectorAll(
        '.staff-status-form'
    );

        console.log('STATUS MODAL:', statusModal);
        console.log('STATUS FORMS:', statusForms);


    statusForms.forEach(form => {

        form.addEventListener('submit', (event) => {

            // Jangan langsung submit
            event.preventDefault();


            // Simpan form yang sedang diproses

            pendingStatusForm = form;


            // Ambil tombol dari form

            const button =
                form.querySelector('button');


            // Cek apakah sedang mengaktifkan staff

            const isActivating =
                button &&
                button.classList.contains(
                    'btn-staff-enable'
                );



            // ==================================================
            // JIKA AKAN MENGAKTIFKAN STAFF
            // ==================================================

            if (isActivating) {

                if (statusModalTitle) {

                    statusModalTitle.textContent =
                        'Aktifkan Staff';

                }

                if (statusModalMessage) {

                    statusModalMessage.textContent =
                        'Apakah Anda yakin ingin mengaktifkan staff ini?';

                }

                if (statusConfirmButton) {

                    statusConfirmButton.textContent =
                        'Aktifkan';

                }

            }



            // ==================================================
            // JIKA AKAN MENONAKTIFKAN STAFF
            // ==================================================

            else {

                if (statusModalTitle) {

                    statusModalTitle.textContent =
                        'Nonaktifkan Staff';

                }

                if (statusModalMessage) {

                    statusModalMessage.textContent =
                        'Apakah Anda yakin ingin menonaktifkan staff ini?';

                }

                if (statusConfirmButton) {

                    statusConfirmButton.textContent =
                        'Nonaktifkan';

                }

            }



            // Tampilkan modal konfirmasi

            if (statusModal) {

                statusModal.classList.add('show');

            }

        });

    });



    // ==========================================================
    // KONFIRMASI STATUS
    // ==========================================================

    if (statusConfirmButton) {

        statusConfirmButton.addEventListener(
            'click',
            () => {

                if (pendingStatusForm) {

                    pendingStatusForm.submit();

                }

            }
        );

    }



    // ==========================================================
    // TUTUP MODAL STATUS MELALUI TOMBOL X
    // ==========================================================

    if (statusCloseButton) {

        statusCloseButton.addEventListener(
            'click',
            () => {

                if (statusModal) {

                    statusModal.classList.remove(
                        'show'
                    );

                }

                pendingStatusForm = null;

            }
        );

    }



    // ==========================================================
    // TUTUP MODAL STATUS MELALUI TOMBOL BATAL
    // ==========================================================

    if (statusCancelButton) {

        statusCancelButton.addEventListener(
            'click',
            () => {

                if (statusModal) {

                    statusModal.classList.remove(
                        'show'
                    );

                }

                pendingStatusForm = null;

            }
        );

    }



    // ==========================================================
    // TUTUP MODAL STATUS KETIKA KLIK AREA GELAP
    // ==========================================================

    if (statusModal) {

        statusModal.addEventListener(
            'click',
            (event) => {

                if (event.target === statusModal) {

                    statusModal.classList.remove(
                        'show'
                    );

                    pendingStatusForm = null;

                }

            }
        );

    }

});