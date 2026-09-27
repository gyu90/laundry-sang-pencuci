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

        // Kosongkan form ketika modal ditutup
        addCustomerForm?.reset();

        // Bersihkan pesan validasi custom
        const phoneInput =
            document.getElementById('phone');

        if (phoneInput) {
            phoneInput.setCustomValidity('');
        }
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


    // ==========================================================
    // VALIDASI NOMOR HP
    // ==========================================================

    const phoneInput =
        document.getElementById('phone');

    if (phoneInput && addCustomerForm) {

        addCustomerForm.addEventListener('submit', (event) => {

            const value = phoneInput.value.trim();

            // Bersihkan pesan sebelumnya
            phoneInput.setCustomValidity('');


            // ==================================================
            // 1. KOSONG
            // ==================================================

            if (value === '') {

                phoneInput.setCustomValidity(
                    'Nomor HP wajib diisi.'
                );

                phoneInput.reportValidity();

                event.preventDefault();

                return;
            }


            // ==================================================
            // 2. MENGANDUNG HURUF / KARAKTER LAIN
            // ==================================================

            if (!/^[0-9]+$/.test(value)) {

                phoneInput.setCustomValidity(
                    'Nomor HP hanya boleh berisi angka.'
                );

                phoneInput.reportValidity();

                event.preventDefault();

                return;
            }


            // ==================================================
            // 3. KURANG DARI 10 DIGIT
            // ==================================================

            if (value.length < 10) {

                phoneInput.setCustomValidity(
                    'Nomor HP minimal 10 digit.'
                );

                phoneInput.reportValidity();

                event.preventDefault();

                return;
            }


            // ==================================================
            // 4. LEBIH DARI 15 DIGIT
            // ==================================================

            if (value.length > 15) {

                phoneInput.setCustomValidity(
                    'Nomor HP maksimal 15 digit.'
                );

                phoneInput.reportValidity();

                event.preventDefault();

                return;
            }


            // ==================================================
            // 5. VALID
            // ==================================================

            phoneInput.setCustomValidity('');

        });


        // ======================================================
        // HAPUS PESAN SAAT USER MULAI MENGETIK LAGI
        // ======================================================

        phoneInput.addEventListener('input', () => {

            phoneInput.setCustomValidity('');

        });

    }


    // ==========================================================
    // LIVE SEARCH CUSTOMER
    // ==========================================================

    const customerSearchForm =
        document.querySelector('.customer-search-form');

    const customerSearchInput =
        document.querySelector('.customer-search-input');


    if (customerSearchForm && customerSearchInput) {

        let searchTimeout;


        customerSearchInput.addEventListener('input', () => {

            // Batalkan pencarian sebelumnya
            clearTimeout(searchTimeout);


            const searchValue =
                customerSearchInput.value.trim();


            // Tunggu 300ms setelah user berhenti mengetik
            searchTimeout = setTimeout(() => {

                const url = new URL(
                    customerSearchForm.action,
                    window.location.origin
                );


                // ==================================================
                // MASUKKAN KATA PENCARIAN
                // ==================================================

                if (searchValue !== '') {

                    url.searchParams.set(
                        'search',
                        searchValue
                    );

                } else {

                    url.searchParams.delete('search');

                }


                // ==================================================
                // AMBIL HASIL DARI LARAVEL
                // ==================================================

                fetch(url.toString(), {

                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }

                })

                    .then(response => {

                        if (!response.ok) {

                            throw new Error(
                                'Gagal mengambil data customer.'
                            );

                        }

                        return response.text();

                    })

                    .then(html => {

                        // ==================================================
                        // PARSE HTML HASIL PENCARIAN
                        // ==================================================

                        const parser =
                            new DOMParser();

                        const doc =
                            parser.parseFromString(
                                html,
                                'text/html'
                            );


                        // ==================================================
                        // AMBIL TABEL BARU
                        // ==================================================

                        const newTableWrapper =
                            doc.querySelector(
                                '.customer-table-wrapper'
                            );


                        const currentTableWrapper =
                            document.querySelector(
                                '.customer-table-wrapper'
                            );


                        // ==================================================
                        // AMBIL FOOTER BARU
                        // ==================================================

                        const newFooter =
                            doc.querySelector(
                                '.customer-card-footer'
                            );


                        const currentFooter =
                            document.querySelector(
                                '.customer-card-footer'
                            );


                        // ==================================================
                        // UPDATE TABEL
                        // ==================================================

                        if (
                            newTableWrapper &&
                            currentTableWrapper
                        ) {

                            currentTableWrapper.innerHTML =
                                newTableWrapper.innerHTML;

                        }


                        // ==================================================
                        // UPDATE PAGINATION
                        // ==================================================

                        if (
                            newFooter &&
                            currentFooter
                        ) {

                            currentFooter.innerHTML =
                                newFooter.innerHTML;

                        }


                        // ==================================================
                        // UPDATE URL TANPA RELOAD
                        // ==================================================

                        window.history.replaceState(
                            {},
                            '',
                            url.toString()
                        );

                    })

                    .catch(error => {

                        console.error(
                            'Live search customer error:',
                            error
                        );

                    });

            }, 300);

        });

    }


     // ==========================================================
    // EDIT CUSTOMER
    // ==========================================================

    const customerTableWrapper =
        document.querySelector('.customer-table-wrapper');

    customerTableWrapper?.addEventListener('click', (event) => {

        const editButton =
            event.target.closest('.customer-edit-button');

        if (!editButton) {
            return;
        }

        const customerId =
            editButton.dataset.customerId;

        console.log('Edit customer ID:', customerId);

        fetch(`/staff/customers/${customerId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {

                if (!response.ok) {
                    throw new Error(
                        'Gagal mengambil data customer.'
                    );
                }

                return response.json();
            })
            .then(customer => {

                console.log(
                    'Data customer:',
                    customer
                );

                // Di sini nanti kita isi modal Edit

            })
            .catch(error => {

                console.error(
                    'Edit customer error:',
                    error
                );

            });

    });

});

