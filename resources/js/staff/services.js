/**
 * ==========================================================
 * MODAL TAMBAH LAYANAN
 * ==========================================================
 *
 * File ini menangani interaksi pada halaman layanan Staff.
 *
 * Fungsi utama:
 * 1. Membuka modal tambah layanan.
 * 2. Menutup modal.
 * 3. Menambahkan baris paket.
 * 4. Menghapus baris paket.
 */

document.addEventListener('DOMContentLoaded', () => {

    /**
     * Mengambil elemen modal.
     */
    const modal = document.getElementById('addServiceModal');


    /**
     * Tombol untuk membuka modal.
     */
    const openButton = document.getElementById('openAddServiceModal');


    /**
     * Tombol X untuk menutup modal.
     */
    const closeButton = document.getElementById('closeAddServiceModal');


    /**
     * Tombol Batal.
     */
    const cancelButton = document.getElementById('cancelAddService');


    /**
     * Container tempat paket akan ditambahkan.
     */
    const packageContainer = document.getElementById('packageContainer');


    /**
     * Tombol Tambah Paket.
     */
    const addPackageButton = document.getElementById('addPackageButton');
    // Pencarian layanan dan paket
const serviceSearch = document.getElementById('serviceSearch');

serviceSearch?.addEventListener('input', () => {

    const keyword = serviceSearch.value
        .trim()
        .toLowerCase();

    document.querySelectorAll('.service-card').forEach((card) => {

        const serviceName =
            card.querySelector('.service-card-header h2')
                ?.textContent
                .trim()
                .toLowerCase() ?? '';

        const packageNames = Array.from(
            card.querySelectorAll('.package-info h3')
        ).map((packageElement) =>
            packageElement.textContent
                .trim()
                .toLowerCase()
        );

        const serviceMatches =
            serviceName.includes(keyword);

        const packageMatches =
            packageNames.some((packageName) =>
                packageName.includes(keyword)
            );

        card.style.display =
            serviceMatches || packageMatches
                ? ''
                : 'none';

    });

});


    // Tombol edit layanan
const editServiceButtons = document.querySelectorAll('.edit-service-button');

editServiceButtons.forEach(button => {

    button.addEventListener('click', () => {

        // Mengambil ID layanan dari tombol
        const serviceId = button.dataset.serviceId;

        console.log('EDIT DIKLIK');
        console.log('ID layanan:', serviceId);

        // Menjalankan fungsi edit layanan
        window.editService(serviceId);
    });

});

// Judul modal
const modalTitle = modal?.querySelector('.modal-header h2');

// Tombol submit modal
const modalSubmitButton = modal?.querySelector('button[type="submit"]');

// Input nama layanan
const serviceNameInput = modal?.querySelector('input[name="service_name"]');

// Input gambar layanan
const serviceImageInput =
    document.getElementById('service_image');

// Container preview gambar
const serviceImagePreview =
    document.getElementById('serviceImagePreview');

// Gambar pada preview
const serviceImagePreviewImage =
    document.getElementById('serviceImagePreviewImage');

// Teks pada preview
const serviceImagePreviewText =
    document.getElementById('serviceImagePreviewText');

// ID layanan saat mode EDIT
const serviceIdInput = document.getElementById('serviceId');

// Method form saat mode EDIT
const serviceFormMethod = document.getElementById('serviceFormMethod');

// Form tambah/edit layanan
const serviceForm = document.getElementById('addServiceForm');

    /**
     * Menampilkan preview gambar yang sedang digunakan.
     */
    const showServiceImagePreview = (imageName) => {

        if (!serviceImagePreview) {
            return;
        }

        if (imageName) {

            serviceImagePreview.hidden = false;

            if (serviceImagePreviewImage) {
                serviceImagePreviewImage.src =
                    `/images/${imageName}`;

                serviceImagePreviewImage.hidden = false;
            }

            if (serviceImagePreviewText) {
                serviceImagePreviewText.textContent =
                    'Gambar layanan saat ini';
            }

        } else {

            serviceImagePreview.hidden = false;

            if (serviceImagePreviewImage) {
                serviceImagePreviewImage.src = '';
                serviceImagePreviewImage.hidden = true;
            }

            if (serviceImagePreviewText) {
                serviceImagePreviewText.textContent =
                    'Layanan ini menggunakan gambar default.';
            }
        }
    };


    /**
     * Menyembunyikan dan mereset preview gambar.
     */
    const resetServiceImagePreview = () => {

        if (serviceImagePreview) {
            serviceImagePreview.hidden = true;
        }

        if (serviceImagePreviewImage) {
            serviceImagePreviewImage.src = '';
            serviceImagePreviewImage.hidden = false;
        }

        if (serviceImagePreviewText) {
            serviceImagePreviewText.textContent = '';
        }
    };


    /**
     * Preview gambar baru ketika Staff memilih file.
     */
    serviceImageInput?.addEventListener('change', () => {

        const file = serviceImageInput.files?.[0];

        if (!file) {
            return;
        }

        const imageUrl = URL.createObjectURL(file);

        if (serviceImagePreview) {
            serviceImagePreview.hidden = false;
        }

        if (serviceImagePreviewImage) {
            serviceImagePreviewImage.src = imageUrl;
            serviceImagePreviewImage.hidden = false;
        }

        if (serviceImagePreviewText) {
            serviceImagePreviewText.textContent =
                'Preview gambar baru';
        }
    });

// Mengecek perubahan status paket saat form disimpan
serviceForm?.addEventListener('submit', (event) => {

    const statusSelects = packageContainer.querySelectorAll(
        'select[name*="[is_active]"]'
    );

    const changedPackages = [];

    statusSelects.forEach((statusSelect) => {

        const originalStatus =
            statusSelect.dataset.originalActive;

        const currentStatus =
            statusSelect.value;

        if (originalStatus !== currentStatus) {

            const packageRow =
                statusSelect.closest('.package-row');

            const packageNameInput =
                packageRow?.querySelector(
                    'input[name*="[package_name]"]'
                );

            changedPackages.push({
                id: packageRow?.querySelector(
                    'input[name*="[id]"]'
                )?.value ?? null,

                name: packageNameInput?.value ?? 'Paket',

                originalStatus: originalStatus,

                currentStatus: currentStatus,
            });
        }
    });


    // Tidak ada perubahan status
    if (changedPackages.length === 0) {
        return;
    }


    // Tahan submit untuk meminta konfirmasi
    event.preventDefault();


    openPackageStatusModal(
        changedPackages,
        () => {
            serviceForm.submit();
        }
    );

});

// Modal konfirmasi hapus paket
const deletePackageModal = document.getElementById('deletePackageModal');

// Pesan konfirmasi
const deletePackageMessage = document.getElementById('deletePackageMessage');

// Tombol Batal
const cancelDeletePackage = document.getElementById('cancelDeletePackage');

// Tombol X pada modal
const closeDeletePackageModal = document.getElementById('closeDeletePackageModal');

// Tombol Hapus Paket
const confirmDeletePackage = document.getElementById('confirmDeletePackage');

// Menyimpan baris paket yang sedang dipilih untuk dihapus
let packageRowToDelete = null;

packageContainer.addEventListener('click', (event) => {

    const removeButton = event.target.closest('.btn-remove-package');

    if (!removeButton) {
        return;
    }

    const packageRow = removeButton.closest('.package-row');

    if (!packageRow) {
        return;
    }

    // Simpan row yang ingin dihapus
    packageRowToDelete = packageRow;

    // Ambil nama paket
    const packageNameInput = packageRow.querySelector(
        'input[name*="[package_name]"]'
    );

    const packageName = packageNameInput
        ? packageNameInput.value
        : 'paket ini';

    // Tampilkan nama paket pada modal konfirmasi
    const deletePackageName =
        document.getElementById('deletePackageName');

    if (deletePackageName) {
        deletePackageName.textContent = packageName;
    }

    // Tampilkan modal konfirmasi
    deletePackageModal.classList.add('show');
});

cancelDeletePackage?.addEventListener('click', () => {

    // Batalkan paket yang sedang dipilih
    packageRowToDelete = null;

    // Tutup modal konfirmasi
    deletePackageModal.classList.remove('show');
});

closeDeletePackageModal?.addEventListener('click', () => {

    // Batalkan paket yang sedang dipilih
    packageRowToDelete = null;

    // Tutup modal konfirmasi
    deletePackageModal.classList.remove('show');
});

confirmDeletePackage?.addEventListener('click', () => {

    // Pastikan ada paket yang sedang dipilih
    if (!packageRowToDelete) {
        return;
    }

    // Ambil ID paket dari hidden input
    const packageIdInput = packageRowToDelete.querySelector(
        'input[name*="[id]"]'
    );

    if (!packageIdInput || !packageIdInput.value) {
        console.error('ID paket tidak ditemukan.');
        return;
    }

    const packageId = packageIdInput.value;

    // Cari CSRF token dari form utama
    const token = document.querySelector(
        '#addServiceForm input[name="_token"]'
    )?.value;

    if (!token) {
        console.error('CSRF token tidak ditemukan.');
        return;
    }

    // Buat form untuk mengirim DELETE ke Laravel
    const form = document.createElement('form');

    form.method = 'POST';
    form.action = `/staff/service-packages/${packageId}`;

    form.innerHTML = `
        <input type="hidden" name="_token" value="${token}">
        <input type="hidden" name="_method" value="DELETE">
    `;

    document.body.appendChild(form);

    // Kirim permintaan hapus
    form.submit();
});

    /**
     * Menghentikan script apabila
     * elemen modal tidak ditemukan.
     *
     * Ini membuat JS lebih aman ketika
     * file ini tidak sengaja dimuat di halaman lain.
     */
    if (!modal) {
        return;
    }

    /*
 * ======================================================
 * EDIT LAYANAN
 * ======================================================
 */




   openButton?.addEventListener('click', () => {

    // Membuka modal
    modal.classList.add('show');

    // Mengembalikan judul modal
    if (modalTitle) {
        modalTitle.textContent = 'Tambah Layanan';
    }


// Mengosongkan nama layanan
if (serviceNameInput) {
    serviceNameInput.value = '';
}

// Mengosongkan input gambar
if (serviceImageInput) {
    serviceImageInput.value = '';
}

// Mengembalikan preview gambar
resetServiceImagePreview();

// Mengembalikan paket awal
packageContainer.innerHTML = '';


    // Reset index karena ini layanan baru
    packageIndex = 1;

    // Membuat satu paket kosong
    const packageRow = document.createElement('div');

    packageRow.classList.add('package-row');

    packageRow.innerHTML = `
        <div class="form-group">

            <label>
                Nama Paket
            </label>

            <input
                type="text"
                name="packages[0][package_name]"
                placeholder="Contoh: Cuci Kemeja"
                required
            >

        </div>

        <div class="form-group">

            <label>
                Harga
            </label>

            <input
                type="number"
                name="packages[0][price]"
                placeholder="15000"
                min="0"
                required
            >

        </div>

        <button
            type="button"
            class="btn-remove-package"
            title="Hapus paket"
        >
            &times;
        </button>
    `;

    packageContainer.appendChild(packageRow);

});


    /**
     * ======================================================
     * MENUTUP MODAL
     * ======================================================
     */
    const closeModal = () => {

        modal.classList.remove('show');

    };


    /**
     * Tombol X.
     */
    closeButton?.addEventListener('click', closeModal);


    /**
     * Tombol Batal.
     */
    cancelButton?.addEventListener('click', closeModal);


    /**
     * Menutup modal ketika user
     * mengklik area gelap di luar modal.
     */
    modal.addEventListener('click', (event) => {

        if (event.target === modal) {

            closeModal();

        }

    });


    /**
     * ======================================================
     * MENAMBAHKAN PAKET
     * ======================================================
     */

    let packageIndex = 1;


    addPackageButton?.addEventListener('click', () => {

        /**
         * Membuat elemen div baru
         * untuk satu paket.
         */
        const packageRow = document.createElement('div');

        packageRow.classList.add('package-row');


        /**
         * Isi HTML untuk paket baru.
         *
         * packageIndex digunakan agar setiap
         * paket memiliki index berbeda.
         */
        packageRow.innerHTML = `
            <div class="form-group">

                <label>
                    Nama Paket
                </label>

                <input
                    type="text"
                    name="packages[${packageIndex}][package_name]"
                    placeholder="Contoh: Cuci Kemeja"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Harga
                </label>

                <input
                    type="number"
                    name="packages[${packageIndex}][price]"
                    placeholder="15000"
                    min="0"
                    required
                >

            </div>


            <button
                type="button"
                class="btn-remove-package"
                title="Hapus paket"
            >
                &times;
            </button>
        `;


        /**
         * Memasukkan paket baru
         * ke dalam container.
         */
        packageContainer.appendChild(packageRow);


        /**
         * Index dinaikkan agar paket berikutnya
         * menggunakan index yang berbeda.
         */
        packageIndex++;

    });


    /**
     * ======================================================
     * MENGHAPUS PAKET
     * ======================================================
     *
     * Event delegation digunakan karena
     * tombol hapus dibuat secara dinamis.
     */
    packageContainer?.addEventListener('click', (event) => {

        /**
         * Memastikan yang diklik
         * adalah tombol hapus paket.
         */
        if (
            event.target.classList.contains('btn-remove-package')
        ) {

            /**
             * Mengambil baris paket
             * yang memiliki tombol tersebut.
             */
            const packageRow = event.target.closest('.package-row');


            /**
             * Jangan biarkan semua paket dihapus.
             *
             * Minimal harus ada satu paket.
             */
            const packageRows =
                packageContainer.querySelectorAll('.package-row');


            if (packageRows.length > 1) {

                

            }

        }

    });

        /*
     * ======================================================
     * MENU TITIK TIGA LAYANAN
     * ======================================================
     *
     * Digunakan untuk membuka menu tindakan
     * pada masing-masing layanan.
     *
     * Isi menu:
     * 1. Edit layanan
     * 2. Nonaktifkan layanan
     *
     * Setiap layanan memiliki ID yang berbeda,
     * sehingga menu yang dibuka hanya menu
     * milik layanan yang dipilih.
     */


    /*
     * ======================================================
     * MEMBUKA / MENUTUP MENU LAYANAN
     * ======================================================
     */
    window.toggleServiceMenu = (serviceId) => {

        /*
         * Mengambil menu berdasarkan ID layanan.
         *
         * Contoh:
         * serviceId = 1
         *
         * maka JavaScript akan mencari:
         * #service-menu-1
         */
        const menu = document.getElementById(
            `service-menu-${serviceId}`
        );


        /*
         * Jika menu tidak ditemukan,
         * hentikan fungsi.
         */
        if (!menu) {
            return;
        }


        /*
         * Menutup menu layanan lain
         * sebelum membuka menu yang dipilih.
         */
        document
            .querySelectorAll('.service-menu-dropdown')
            .forEach((item) => {

                if (item !== menu) {
                    item.classList.remove('show');
                }

            });


        /*
         * Membuka atau menutup menu
         * layanan yang sedang dipilih.
         */
        menu.classList.toggle('show');

    };


    /*
     * ======================================================
     * MENUTUP MENU KETIKA KLIK DI LUAR
     * ======================================================
     *
     * Jika Staff mengklik area lain di halaman,
     * dropdown akan otomatis ditutup.
     */
    document.addEventListener('click', (event) => {

        /*
         * Mengecek apakah klik terjadi
         * di dalam area menu layanan.
         */
        if (!event.target.closest('.service-menu')) {

            document
                .querySelectorAll('.service-menu-dropdown')
                .forEach((menu) => {

                    menu.classList.remove('show');

                });

        }

    });


    /*
     * ======================================================
     * EDIT LAYANAN
     * ======================================================
     *
     * Untuk sementara kita hanya menerima ID layanan.
     *
     * Modal Edit Layanan akan kita bangun
     * pada tahap berikutnya.
     */
    window.editService = (serviceId) => {

    console.log('EDIT DIKLIK');
    console.log('ID layanan:', serviceId);

    // Mencari data layanan berdasarkan ID
    const service = servicesData.find(
        service => service.id == serviceId
    );

    // Jika data tidak ditemukan
    if (!service) {
        console.error('Data layanan tidak ditemukan.');
        return;
    }

// Menyimpan ID layanan yang sedang diedit
serviceIdInput.value = service.id;

// Mengubah method form menjadi PUT
serviceFormMethod.value = 'PUT';

// Mengubah action form menuju layanan yang sedang diedit
serviceForm.action = `/staff/services/${service.id}`;

    // Membuka modal
    modal.classList.add('show');

    // Mengubah judul modal
    if (modalTitle) {
        modalTitle.textContent = 'Edit Layanan';
    }

// Mengisi nama layanan
if (serviceNameInput) {
    serviceNameInput.value = service.service_name;
}

// Reset input gambar
if (serviceImageInput) {
    serviceImageInput.value = '';
}

// Menampilkan gambar layanan yang sedang digunakan
showServiceImagePreview(service.image);

// Menghapus seluruh paket yang ada
packageContainer.innerHTML = '';

    // Mengambil paket milik layanan
    const packages = service.service_packages ?? [];

    // Membuat baris untuk setiap paket
    packages.forEach((servicePackage, index) => {

        const packageRow = document.createElement('div');

        packageRow.classList.add('package-row');

       packageRow.innerHTML = `

    <input
        type="hidden"
        name="packages[${index}][id]"
        value="${servicePackage.id}"
    >

    <div class="form-group">
        <label>
            Nama Paket
        </label>

        <input
            type="text"
            name="packages[${index}][package_name]"
            value="${servicePackage.package_name ?? ''}"
            required
        >
    </div>


                        <div class="form-group">

                <label>
                    Harga
                </label>

                <input
                    type="number"
                    name="packages[${index}][price]"
                    value="${servicePackage.price ?? 0}"
                    min="0"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Status
                </label>

                <select
    name="packages[${index}][is_active]"
    data-original-active="${servicePackage.is_active ? '1' : '0'}"
    required
>
                    <option
                        value="1"
                        ${servicePackage.is_active ? 'selected' : ''}
                    >
                        Aktif
                    </option>

                    <option
                        value="0"
                        ${!servicePackage.is_active ? 'selected' : ''}
                    >
                        Nonaktif
                    </option>
                </select>

            </div>


            <button
                type="button"
                class="btn-remove-package"
                title="Hapus paket"
            >
                &times;
            </button>
        `;

        packageContainer.appendChild(packageRow);

    });

    // Mengecek perubahan status paket
packageContainer.querySelectorAll(
    'select[name*="[is_active]"]'
).forEach((statusSelect) => {

    statusSelect.addEventListener('change', () => {

        const originalStatus =
            statusSelect.dataset.originalActive;

        const currentStatus =
            statusSelect.value;

        console.log('Status awal:', originalStatus);
        console.log('Status sekarang:', currentStatus);

    });

});

// PENTING:
// Index paket baru harus dimulai setelah paket yang sudah ada

    packageIndex = packages.length;

    // Mengubah tombol submit
    if (modalSubmitButton) {
        modalSubmitButton.textContent = 'Simpan Perubahan';
    }

};


   /*
 * ======================================================
 * HAPUS LAYANAN
 * ======================================================
 */
window.deactivateService = (serviceId) => {

    const confirmed = confirm(
        'Apakah Anda yakin ingin menghapus layanan ini?'
    );

    if (!confirmed) {
        return;
    }

    const form = document.createElement('form');

    form.method = 'POST';
    form.action = `/staff/services/${serviceId}`;

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    );

    if (!csrfToken) {
        console.error('CSRF token tidak ditemukan.');
        return;
    }

    /*
     * CSRF token
     */
    const tokenInput = document.createElement('input');

    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken.getAttribute('content');

    form.appendChild(tokenInput);

    /*
     * Laravel method spoofing untuk DELETE
     */
    const methodInput = document.createElement('input');

    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(methodInput);

    document.body.appendChild(form);

    form.submit();
};

});