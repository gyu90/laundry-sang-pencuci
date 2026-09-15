document.addEventListener('DOMContentLoaded', () => {

    const addStaffButton = document.querySelector('.btn-add-staff');
    const modal = document.getElementById('addStaffModal');

    const closeButton = document.getElementById('closeAddStaffModal');
    const cancelButton = document.getElementById('cancelAddStaffModal');

    // Pastikan modal tersembunyi saat halaman pertama dibuka
    if (modal) {
        modal.classList.remove('show');
    }

    // Buka modal
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

    // Tutup modal jika klik area gelap di luar modal
    if (modal) {
        modal.addEventListener('click', (event) => {

            if (event.target === modal) {
                modal.classList.remove('show');
            }

        });
    }

});