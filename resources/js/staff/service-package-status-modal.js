document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('packageStatusModal');
    const list = document.getElementById('packageStatusList');

    const title = document.getElementById('packageStatusModalTitle');
    const description = document.getElementById('packageStatusModalDescription');

    const closeButton = document.getElementById('closePackageStatusModal');
    const cancelButton = document.getElementById('cancelPackageStatus');
    const confirmButton = document.getElementById('confirmPackageStatus');

    if (!modal || !list || !title || !confirmButton) {
        return;
    }

    let pendingSubmit = null;

    window.openPackageStatusModal = (changedPackages, submitCallback) => {

        pendingSubmit = submitCallback;

        list.innerHTML = '';

        const allDeactivate = changedPackages.every(
            (item) =>
                item.originalStatus === '1' &&
                item.currentStatus === '0'
        );

        const allReactivate = changedPackages.every(
            (item) =>
                item.originalStatus === '0' &&
                item.currentStatus === '1'
        );

        if (allDeactivate) {

            title.textContent = 'Nonaktifkan Paket?';

            description.textContent =
                'Periksa paket yang akan dinonaktifkan.';

            confirmButton.textContent = 'Nonaktifkan';

        } else if (allReactivate) {

            title.textContent = 'Aktifkan Paket?';

            description.textContent =
                'Periksa paket yang akan diaktifkan kembali.';

            confirmButton.textContent = 'Aktifkan';

        } else {

            title.textContent =
                'Yakin ingin mengubah status paket?';

            description.textContent =
                'Periksa perubahan status paket sebelum melanjutkan.';

            confirmButton.textContent = 'Simpan Perubahan';
        }


        changedPackages.forEach((item) => {

            const row = document.createElement('div');

            row.classList.add('package-status-item');

            const oldStatusClass =
                item.originalStatus === '1'
                    ? 'active'
                    : 'inactive';

            const newStatusClass =
                item.currentStatus === '1'
                    ? 'active'
                    : 'inactive';

            const oldStatusText =
                item.originalStatus === '1'
                    ? 'Aktif'
                    : 'Nonaktif';

            const newStatusText =
                item.currentStatus === '1'
                    ? 'Aktif'
                    : 'Nonaktif';

            row.innerHTML = `
                <div class="package-status-item-name">
                    ${item.name}
                </div>

                <span class="package-status-chip ${oldStatusClass}">
                    ${oldStatusText}
                </span>

                <span class="package-status-arrow">
                    →
                </span>

                <span class="package-status-chip ${newStatusClass}">
                    ${newStatusText}
                </span>
            `;

            list.appendChild(row);
        });

        modal.classList.add('show');
    };


    const closeModal = () => {

        modal.classList.remove('show');

        pendingSubmit = null;
    };


    closeButton?.addEventListener('click', closeModal);

    cancelButton?.addEventListener('click', closeModal);


    confirmButton?.addEventListener('click', () => {

        if (!pendingSubmit) {
            return;
        }

        const submit = pendingSubmit;

        pendingSubmit = null;

        modal.classList.remove('show');

        submit();
    });


    modal.addEventListener('click', (event) => {

        if (event.target === modal) {
            closeModal();
        }
    });

});