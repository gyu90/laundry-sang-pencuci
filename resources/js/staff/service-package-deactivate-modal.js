document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('deactivatePackageModal');
    const form = document.getElementById('deactivatePackageForm');
    const packageName = document.getElementById('deactivatePackageName');

    const closeButton = document.getElementById('closeDeactivatePackageModal');
    const cancelButton = document.getElementById('cancelDeactivatePackage');

    if (!modal || !form || !packageName) {
        return;
    }

    // Membuka modal Nonaktifkan Paket
    window.openDeactivatePackageModal = (packageId, name) => {

        packageName.textContent = name;

        form.action = `/staff/service-packages/${packageId}/deactivate`;

        modal.classList.add('show');
    };

    // Menutup modal
    const closeModal = () => {
        modal.classList.remove('show');
    };

    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);

    // Tutup ketika klik area di luar modal
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

});