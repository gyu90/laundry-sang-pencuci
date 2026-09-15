document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('reactivateServiceModal');
    const form = document.getElementById('reactivateServiceForm');
    const serviceName = document.getElementById('reactivateServiceName');

    const closeButton = document.getElementById('closeReactivateServiceModal');
    const cancelButton = document.getElementById('cancelReactivateService');

    if (!modal || !form || !serviceName) {
        return;
    }

    // Membuka modal
    window.openReactivateServiceModal = (serviceId, name) => {
        serviceName.textContent = name;

        form.action = `/staff/services/${serviceId}/reactivate`;

        modal.classList.add('show');
    };

    // Menutup modal
    const closeModal = () => {
        modal.classList.remove('show');
    };

    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);

    // Klik area di luar modal
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
});