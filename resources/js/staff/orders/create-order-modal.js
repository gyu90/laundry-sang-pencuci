document.addEventListener('DOMContentLoaded', () => {

    const openButton = document.getElementById('openCreateOrderModal');
    const modal = document.querySelector('.order-modal-overlay');
    const addServiceButton = document.getElementById('addOrderService');
    const servicePackages = JSON.parse(modal.dataset.servicePackages || '[]');
    const vouchers = JSON.parse(
    modal.dataset.vouchers || '[]'
);

const customerSelect =
    document.getElementById('customer_id');

const voucherSelect =
    document.getElementById('voucher_id');

// ==========================================
// PENCARIAN CUSTOMER
// ==========================================

const customerSearch =
    document.getElementById('customerSearch');

const customerSearchResults =
    document.getElementById('customerSearchResults');

const customerItems =
    document.querySelectorAll('.customer-search-item');


// ==========================================
// CARI CUSTOMER BERDASARKAN NAMA / NOMOR HP
// ==========================================

customerSearch.addEventListener('input', () => {

    const keyword =
        customerSearch.value
            .toLowerCase()
            .trim();

    customerSearchResults.classList.add('show');

    customerItems.forEach(item => {

        const name =
            item.dataset.customerName
                .toLowerCase();

        const phone =
            item.dataset.customerPhone
                .toLowerCase();

        const match =
            name.includes(keyword) ||
            phone.includes(keyword);

        item.style.display =
            match ? 'flex' : 'none';

    });

});

// ==========================================
// KLIK INPUT CUSTOMER
// ==========================================

customerSearch.addEventListener('focus', () => {

    customerSearchResults.classList.add('show');

});



// ==========================================
// PILIH CUSTOMER
// ==========================================

customerItems.forEach(item => {

    item.addEventListener('click', () => {

        const customerId =
            item.dataset.customerId;

        const customerName =
            item.dataset.customerName;

        const customerPhone =
            item.dataset.customerPhone;


        // Masukkan ID customer ke input hidden
        customerSelect.value =
            customerId;


        // Tampilkan nama + nomor HP
        customerSearch.value =
            `${customerName} - ${customerPhone}`;


        // Tutup hasil pencarian
        customerSearchResults.classList.remove('show');


        // Beritahu sistem bahwa customer berubah
        customerSelect.dispatchEvent(
            new Event('change', {
                bubbles: true
            })
        );

    });

});

customerSelect.addEventListener('change', () => {

    const customerId = customerSelect.value;

    voucherSelect.innerHTML = `
        <option value="">
            Tidak menggunakan voucher
        </option>
    `;

    if (!customerId) {
        updateOrderTotal();
        return;
    }

    const customerVouchers = vouchers.filter(
        voucher =>
            voucher.customer_id == customerId
    );

    customerVouchers.forEach(voucher => {

    const option = document.createElement('option');

    option.value = voucher.id;

    let rewardText = '';

    if (voucher.reward_type === 'discount_nominal') {

        rewardText =
            `Potongan Rp${Number(
                voucher.reward_value
            ).toLocaleString('id-ID')}`;

    }

    else if (
        voucher.reward_type === 'discount_percentage'
    ) {

        rewardText =
            `Potongan ${Number(
                voucher.reward_value
            )}%`;

    }

    else if (
        voucher.reward_type === 'free_service'
    ) {

        const packageData =
            voucher.program?.free_service_package;

        if (packageData) {

            rewardText =
                `Gratis ${packageData.package_name}`;

        } else {

            rewardText =
                'Gratis Layanan';

        }
    }

    option.textContent = rewardText;

    voucherSelect.appendChild(option);
});

    updateOrderTotal();
});


    const closeButton = document.getElementById('closeCreateOrderModal');
    const cancelButton = document.getElementById('cancelCreateOrder');

    console.log('CREATE ORDER JS AKTIF');
    console.log('Tombol:', openButton);
    console.log('Modal:', modal);

    if (!openButton) {
        console.error('Tombol Tambah Order tidak ditemukan');
        return;
    }

    if (!modal) {
        console.error('Modal Order tidak ditemukan');
        return;
    }

function resetOrderModal() {

    const form =
        document.getElementById('createOrderForm');

    const serviceList =
        document.getElementById('orderServiceList');


    // Reset input/select bawaan form
    form.reset();


document
    .querySelectorAll('.payment-status-option')
    .forEach(option => {
        option.classList.remove('active');
    });

document
    .querySelector('.payment-status-option.unpaid')
    ?.classList.add('active');
    
    // ==========================================
    // RESET CUSTOMER
    // ==========================================

    customerSearch.value = '';

    customerSelect.value = '';

    customerSearchResults.classList.remove('show');


    customerItems.forEach(item => {
        item.style.display = 'flex';
    });


    // ==========================================
    // HAPUS SEMUA LAYANAN
    // ==========================================

    serviceList.innerHTML = '';


    // ==========================================
    // RESET VOUCHER
    // ==========================================

    document.getElementById('voucher_id').value = '';


    // ==========================================
    // RESET TOTAL
    // ==========================================

    document.getElementById('orderTotal').textContent =
        'Rp0';

    document.getElementById('orderDiscount').textContent =
        'Rp0';

    document.getElementById('orderFinalTotal').textContent =
        'Rp0';
}

// TOMBOL X

closeButton.addEventListener('click', () => {
    resetOrderModal();
    modal.classList.remove('show');
});



// ==========================================
// STATUS PEMBAYARAN
// ==========================================

const paymentStatusOptions =
    document.querySelectorAll('.payment-status-option');

paymentStatusOptions.forEach(option => {

    option.addEventListener('click', () => {

        // Hapus active dari semua pilihan
        paymentStatusOptions.forEach(item => {
            item.classList.remove('active');
        });

        // Tambahkan active ke pilihan yang diklik
        option.classList.add('active');

        // Centang radio button
        const radio = option.querySelector(
            'input[name="payment_status"]'
        );

        if (radio) {
            radio.checked = true;
        }

    });

});


// TOMBOL BATAL


cancelButton.addEventListener('click', () => {
    resetOrderModal();
    modal.classList.remove('show');
});

    console.log('Tombol Tambah Layanan:', addServiceButton);
    addServiceButton.addEventListener('click', () => {
    console.log('TOMBOL TAMBAH LAYANAN DIKLIK');

    const serviceList = document.getElementById('orderServiceList');

    const serviceRow = document.createElement('div');

    serviceRow.classList.add('order-service-row');

    
serviceRow.innerHTML = `
    <div class="service-field">
        <label>Layanan</label>

        <select
            class="order-service-select"
            required
        >
            <option value="">
                Pilih layanan
            </option>
        </select>
    </div>


    <div class="service-field">
        <label>Paket</label>

        <select
            name="service_package_id[]"
            class="order-package-select"
            required
            disabled
        >
            <option value="">
                Pilih layanan terlebih dahulu
            </option>
        </select>
    </div>


    <div class="service-bottom-row">

        <div class="service-quantity-group">

            <label>Jumlah</label>

            <div class="quantity-control">

                <button
                    type="button"
                    class="quantity-btn quantity-minus"
                    aria-label="Kurangi jumlah"
                >
                    −
                </button>

                <input
                    type="number"
                    name="quantity[]"
                    value="1"
                    min="1"
                    class="service-quantity-input"
                >

                <button
                    type="button"
                    class="quantity-btn quantity-plus"
                    aria-label="Tambah jumlah"
                >
                    +
                </button>

            </div>

        </div>


        <button
            type="button"
            class="order-remove-service"
        >
            Hapus layanan
        </button>

    </div>
`;


const serviceSelect = serviceRow.querySelector('.order-service-select');

const services = JSON.parse(serviceList.dataset.services);
const quantityInput = serviceRow.querySelector('input[name="quantity[]"]');

const minusButton =
    serviceRow.querySelector('.quantity-minus');

const plusButton =
    serviceRow.querySelector('.quantity-plus');


minusButton.addEventListener('click', () => {

    const currentValue =
        Number(quantityInput.value) || 1;

    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        updateOrderTotal();
    }

});


plusButton.addEventListener('click', () => {

    const currentValue =
        Number(quantityInput.value) || 1;

    quantityInput.value = currentValue + 1;

    updateOrderTotal();
});

services.forEach(service => {
    const option = document.createElement('option');

    option.value = service.id;
    option.textContent = service.service_name;

    serviceSelect.appendChild(option);
});


    serviceList.appendChild(serviceRow);
    serviceRow.scrollIntoView({
    behavior: 'smooth',
    block: 'nearest'
});
    const removeButton = serviceRow.querySelector('.order-remove-service');

removeButton.addEventListener('click', () => {
    serviceRow.remove();
    updateOrderTotal();
});

//batas kode pembaharuan ada dibawah
const packageSelect = serviceRow.querySelector('.order-package-select');


// KETIKA LAYANAN DIPILIH
serviceSelect.addEventListener('change', () => {

    const serviceId = serviceSelect.value;

    packageSelect.innerHTML = `
        <option value="">Pilih Paket</option>
    `;

    if (!serviceId) {
        packageSelect.disabled = true;
        updateOrderTotal();
        return;
    }

    const filteredPackages = servicePackages.filter(
        packageItem => packageItem.service_id == serviceId
    );

    filteredPackages.forEach(packageItem => {

        const option = document.createElement('option');

        option.value = packageItem.id;

        option.textContent =
            `${packageItem.package_name} - Rp${Number(packageItem.price).toLocaleString('id-ID')}`;

        packageSelect.appendChild(option);
    });

    packageSelect.disabled = false;

    updateOrderTotal();
});


// KETIKA PAKET DIPILIH
packageSelect.addEventListener('change', () => {

    updateOrderTotal();

});


// KETIKA QUANTITY BERUBAH
quantityInput.addEventListener('input', () => {

    updateOrderTotal();

});

const allRows =
    serviceList.querySelectorAll('.order-service-row');
    
function updateOrderTotal() {


    let total = 0;

    const allRows =
        serviceList.querySelectorAll('.order-service-row');

    allRows.forEach(row => {

        const packageSelect =
            row.querySelector('.order-package-select');

        const quantityInput =
            row.querySelector('input[name="quantity[]"]');

        const selectedPackage =
            servicePackages.find(
                packageItem =>
                    packageItem.id == packageSelect.value
            );

        if (selectedPackage) {

            const quantity =
                Number(quantityInput.value) || 1;

            total +=
                Number(selectedPackage.price) * quantity;
        }
    });


    // ==========================================
// HITUNG VOUCHER
// ==========================================

let discount = 0;

const selectedVoucherId = voucherSelect.value;

if (selectedVoucherId) {

    const selectedVoucher = vouchers.find(
        voucher => voucher.id == selectedVoucherId
    );

    if (selectedVoucher) {

        // ==========================================
        // 1. DISKON NOMINAL
        // ==========================================

        if (
            selectedVoucher.reward_type ===
            'discount_nominal'
        ) {

            discount =
                Number(selectedVoucher.reward_value);

        }

        // ==========================================
        // 2. DISKON PERSENTASE
        // ==========================================

        else if (
            selectedVoucher.reward_type ===
            'discount_percentage'
        ) {

            discount =
                total *
                Number(selectedVoucher.reward_value) /
                100;
        }

        // ==========================================
        // 3. GRATIS LAYANAN
        // ==========================================

        else if (
            selectedVoucher.reward_type ===
            'free_service'
        ) {

            const freePackageId =
            selectedVoucher.program?.free_service_package_id;

            // Cari item order yang menggunakan
            // paket yang digratiskan
            const freePackageRow =
                Array.from(allRows).find(row => {

                    const packageSelect =
                        row.querySelector(
                            '.order-package-select'
                        );

                    return (
                        packageSelect.value ==
                        freePackageId
                    );
                });

            if (freePackageRow) {

                const packageSelect =
                    freePackageRow.querySelector(
                        '.order-package-select'
                    );

                const quantityInput =
                    freePackageRow.querySelector(
                        'input[name="quantity[]"]'
                    );

                const selectedPackage =
                    servicePackages.find(
                        packageItem =>
                            packageItem.id ==
                            packageSelect.value
                    );

                if (selectedPackage) {

                    // Gratis hanya 1 paket
                    discount =
                        Number(selectedPackage.price);
                }
            }
        }

        // Jangan sampai diskon melebihi total
        discount = Math.min(
            discount,
            total
        );
    }
}


    // ==========================================
    // TOTAL AKHIR
    // ==========================================

    const finalTotal =
        total - discount;


    document.getElementById('orderTotal').textContent =
        'Rp' + total.toLocaleString('id-ID');

    document.getElementById('orderDiscount').textContent =
        'Rp' + discount.toLocaleString('id-ID');

    document.getElementById('orderFinalTotal').textContent =
        'Rp' + finalTotal.toLocaleString('id-ID');
}


voucherSelect.addEventListener('change', () => {
    console.log('VOUCHER DIPILIH:', voucherSelect.value);

    updateOrderTotal();
});
    
});

    // BUKA MODAL
    openButton.addEventListener('click', () => {

        console.log('TOMBOL TAMBAH ORDER DIKLIK');

        modal.classList.add('show');

    });

});