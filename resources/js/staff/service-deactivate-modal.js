document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('deactivateServiceModal');
    const form = document.getElementById('deactivateServiceForm');
    const serviceName = document.getElementById('deactivateServiceName');

    const closeButton = document.getElementById('closeDeactivateServiceModal');
    const cancelButton = document.getElementById('cancelDeactivateService');

    if (!modal || !form || !serviceName) {
        return;
    }

    // Membuka modal
    window.openDeactivateServiceModal = (serviceId, name) => {
        serviceName.textContent = name;

        form.action = `/staff/services/${serviceId}/deactivate`;

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

    // Mengaktifkan kembali layanan
    window.reactivateService = (serviceId, serviceName) => {
        const token = document.querySelector(
            '#addServiceForm input[name="_token"]'
        )?.value;

        if (!token) {
            console.error('CSRF token tidak ditemukan.');
            return;
        }

        const form = document.createElement('form');

        form.method = 'POST';
        form.action = `/staff/services/${serviceId}/reactivate`;

        form.innerHTML = `
            <input type="hidden" name="_token" value="${token}">
            <input type="hidden" name="_method" value="PUT">
        `;

        document.body.appendChild(form);
        form.submit();
    };


});