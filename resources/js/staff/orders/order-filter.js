document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('orderSearch');
    const statusFilter = document.getElementById('orderStatusFilter');
    const resetButton = document.getElementById('orderFilterReset');

    const tableRows = document.querySelectorAll('.order-table-row');

if (!searchInput || !statusFilter) {
    return;
}

    const tableWrapper = document.querySelector('.order-table-wrapper');
    const tableFooter = document.querySelector('.order-card-footer');

    // Elemen informasi pagination
    const paginationInfo =
        document.querySelector('.order-pagination-info');

    // Simpan jumlah data awal
    const totalInitialRows = tableRows.length;

    // Elemen pesan hasil pencarian
    const noResultMessage =
        document.getElementById('orderSearchNoResult');

    const noResultKeyword =
        document.getElementById('orderSearchKeyword');

    function updatePaginationInfo(totalRows) {

        if (!paginationInfo) {
            return;
        }

        if (totalRows === 0) {
            paginationInfo.textContent =
                'Tidak ada pesanan';
            return;
        }

        paginationInfo.textContent =
            `Menampilkan 1–${totalRows} dari ${totalRows} pesanan`;
    }

function applyFilters() {

    const keyword = searchInput.value.trim();
    const keywordLower = keyword.toLowerCase();

    let searchResultFound = false;
    let visibleRows = 0;

    tableRows.forEach(row => {

        const rowText = row.textContent.toLowerCase();

        const searchMatch =
            rowText.includes(keywordLower);

        if (searchMatch) {
            searchResultFound = true;
            row.style.display = '';
            visibleRows++;
        } else {
            row.style.display = 'none';
        }

    });

    /*
     * Jika ada keyword pencarian dan
     * tidak ada data yang cocok,
     * sembunyikan tabel dan tampilkan pesan.
     */
    if (keyword !== '' && !searchResultFound) {

        if (tableWrapper) {
            tableWrapper.style.display = 'none';
        }

        if (tableFooter) {
            tableFooter.style.display = 'none';
        }

        if (noResultKeyword) {
            noResultKeyword.textContent = `"${keyword}"`;
        }

        if (noResultMessage) {
            noResultMessage.style.display = 'block';
        }

    } else {

        /*
         * Jika ada hasil atau keyword kosong,
         * tampilkan kembali tabel.
         */

        if (tableWrapper) {
            tableWrapper.style.display = '';
        }

        if (tableFooter) {
            tableFooter.style.display = '';
        }

        if (noResultMessage) {
            noResultMessage.style.display = 'none';
        }

        /*
         * Update informasi footer
         * berdasarkan hasil pencarian.
         */

        if (keyword !== '') {
            updatePaginationInfo(visibleRows);
        } else {
            updatePaginationInfo(totalInitialRows);
        }

    }
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
    const params = new URLSearchParams(window.location.search);

    if (statusFilter.value) {
        params.set('status', statusFilter.value);
    } else {
        params.delete('status');
    }

    window.location.href =
        `${window.location.pathname}?${params.toString()}`;
});

    // Reset
    resetButton.addEventListener('click', () => {

        searchInput.value = '';
        statusFilter.value = '';

        applyFilters();
    });

});