document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('orderStatusFilter');
    const resetButton = document.getElementById('orderFilterReset');
    const tableRows = document.querySelectorAll('.order-table-row');

    if (!searchInput || !statusFilter || tableRows.length === 0) {
        return;
    }

    function applyFilters() {

        const keyword = searchInput.value
            .trim()
            .toLowerCase();

        const selectedStatus = statusFilter.value;

        tableRows.forEach(row => {

            const rowText = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status || '';

            const searchMatch =
                rowText.includes(keyword);

            const statusMatch =
                selectedStatus === '' ||
                rowStatus === selectedStatus;

            row.style.display =
                searchMatch && statusMatch ? '' : 'none';
        });
    }

    // Search saat tekan Enter
    searchInput.addEventListener('keydown', (event) => {

        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();

        applyFilters();
    });

    // Filter status langsung saat dipilih
    statusFilter.addEventListener('change', () => {
        applyFilters();
    });


    resetButton.addEventListener('click', () => {

    searchInput.value = '';
    statusFilter.value = '';

    applyFilters();

});

});