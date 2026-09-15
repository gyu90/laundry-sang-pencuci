document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('programModal');
    const openButton = document.querySelector('.loyalty-add-button');
    const closeButton = document.getElementById('closeProgramModal');
    const cancelButton = document.getElementById('cancelProgramModal');

    console.log('Loyalty JS aktif');

    // Pastikan elemen utama tersedia
    if (!modal || !openButton) {
        console.log('Modal atau tombol Buat Program tidak ditemukan.');
        return;
    }


    // =========================
    // BUKA MODAL
    // =========================

 openButton.addEventListener('click', () => {

    programForm.reset();

    // Kembalikan action ke route tambah program
    programForm.action = '/staff/loyalty';

    // Method POST
    programFormMethod.value = 'POST';

    programModalTitle.textContent =
        'Tambah Program Loyalitas';

    programSubmitButton.textContent =
        'Simpan Program';

    updateRewardField();

    modal.classList.add('show');
});


    // =========================
    // TUTUP MODAL
    // =========================

    closeButton?.addEventListener('click', () => {
        modal.classList.remove('show');
    });


    // =========================
    // TOMBOL BATAL
    // =========================

    cancelButton?.addEventListener('click', () => {
        modal.classList.remove('show');
    });


    // =========================
    // KLIK DI LUAR MODAL
    // =========================

    modal.addEventListener('click', (event) => {

        if (event.target === modal) {
            modal.classList.remove('show');
        }

    });


    // =====================================================
    // REWARD DINAMIS
    // =====================================================

    const rewardType = document.getElementById('reward_type');
    const rewardValue = document.getElementById('reward_value');
    const rewardValueLabel = document.getElementById('rewardValueLabel');
    const rewardValuePrefix = document.getElementById('rewardValuePrefix');
    const rewardValueHelp = document.getElementById('rewardValueHelp');
    const freeServicePackageGroup =
    document.getElementById('freeServicePackageGroup');

    const freeServicePackage =
    document.getElementById('free_service_package_id');

    const freeServiceName =
    document.getElementById('freeServiceName');

    const freeApplicableServiceId =
    document.getElementById('freeApplicableServiceId');


    // Pastikan elemen reward tersedia
    if (!rewardType || !rewardValue) {
        console.log('Elemen reward tidak ditemukan.');
        return;
    }


    // Fungsi untuk mengatur tampilan reward
   
function updateRewardField() {

    const type = rewardType.value;

    // ==========================================
    // RESET PAKET GRATIS
    // ==========================================

    if (freeServicePackageGroup) {
        freeServicePackageGroup.style.display = 'none';
    }

    if (freeServicePackage) {
        freeServicePackage.disabled = true;
        freeServicePackage.required = false;
    }


    // ==========================================
    // BELUM MEMILIH REWARD
    // ==========================================

    if (type === '') {

        rewardValueLabel.textContent =
            'Nilai Reward';

        rewardValuePrefix.textContent =
            'Rp';

        rewardValue.placeholder =
            'Pilih jenis reward terlebih dahulu';

        rewardValueHelp.textContent =
            'Pilih jenis reward untuk menentukan nilai reward.';

        rewardValue.disabled = true;

        return;
    }


    // ==========================================
    // GRATIS LAYANAN
    // ==========================================

    if (type === 'free_service') {

        // Tampilkan pilihan paket
        if (freeServicePackageGroup) {
            freeServicePackageGroup.style.display = 'block';
        }

        if (freeServicePackage) {
            freeServicePackage.disabled = false;
            freeServicePackage.required = true;
        }


        // Reward value = jumlah gratis
        rewardValueLabel.textContent =
            'Jumlah Gratis Layanan';

        rewardValuePrefix.textContent =
            'Jumlah';

        rewardValue.placeholder =
            'Contoh: 1';

        rewardValueHelp.textContent =
            'Tentukan jumlah paket yang diberikan secara gratis.';
        
        if (applicableService) {
    applicableService.style.display = 'none';
}

if (freeServiceName) {
    freeServiceName.style.display = 'block';
    freeServiceName.value = '';
}

if (freeApplicableServiceId) {
    freeApplicableServiceId.value = '';
}

        rewardValue.min = 1;
        rewardValue.step = 1;
        rewardValue.disabled = false;

        return;
    }


    // ==========================================
    // POTONGAN NOMINAL
    // ==========================================

    if (type === 'discount_nominal') {

        rewardValueLabel.textContent =
            'Nominal Potongan';

        rewardValuePrefix.textContent =
            'Rp';

        rewardValue.placeholder =
            'Contoh: 30000';

        rewardValueHelp.textContent =
            'Customer mendapatkan potongan sebesar nominal yang ditentukan.';

        rewardValue.min = 1000;
        rewardValue.step = 1000;
        rewardValue.disabled = false;

        return;
    }


    // ==========================================
    // POTONGAN PERSENTASE
    // ==========================================

    if (type === 'discount_percentage') {

        rewardValueLabel.textContent =
            'Persentase Potongan';

        rewardValuePrefix.textContent =
            '%';

        rewardValue.placeholder =
            'Contoh: 20';

        rewardValueHelp.textContent =
            'Customer mendapatkan potongan sebesar persentase yang ditentukan.';

        rewardValue.min = 1;
        rewardValue.max = 100;
        rewardValue.step = 1;
        rewardValue.disabled = false;

        return;
    }
}

    // Jalankan ketika jenis reward berubah
    rewardType.addEventListener('change', updateRewardField);
    freeServicePackage?.addEventListener('change', () => {

    const selectedOption =
        freeServicePackage.options[
            freeServicePackage.selectedIndex
        ];

    if (!selectedOption) {
        return;
    }

    const serviceId =
        selectedOption.dataset.serviceId || '';

    const serviceName =
        selectedOption.dataset.serviceName || '';

    if (freeServiceName) {
        freeServiceName.value = serviceName;
    }

    if (freeApplicableServiceId) {
        freeApplicableServiceId.value = serviceId;
    }

});


    // Jalankan sekali ketika halaman pertama kali dibuka
    updateRewardField();




    // =====================================================
// EDIT PROGRAM LOYALITAS
// =====================================================

const editButtons =
    document.querySelectorAll('.loyalty-edit-button');

const programForm =
    document.getElementById('programForm');

const programModalTitle =
    document.getElementById('programModalTitle');

const programSubmitButton =
    document.getElementById('programSubmitButton');

const programFormMethod =
    document.getElementById('programFormMethod');

const programName =
    document.getElementById('program_name');

const minTransaction =
    document.getElementById('min_transaction_amount');

const applicableService =
    document.getElementById('applicable_service_id');

const description =
    document.getElementById('description');

const startDate =
    document.getElementById('period_start_date');

const endDate =
    document.getElementById('period_end_date');


// =====================================================
// KLIK EDIT
// =====================================================

editButtons.forEach(button => {

    button.addEventListener('click', () => {

        const id =
            button.dataset.id;

        const name =
            button.dataset.name;

        const programDescription =
            button.dataset.description;

        const minTransactionValue =
            button.dataset.minTransaction;

        const rewardTypeValue =
            button.dataset.rewardType;

        const rewardValueData =
            button.dataset.rewardValue;

        const serviceId =
            button.dataset.serviceId;

        const freeServicePackageId =
            button.dataset.freeServicePackageId;

        const startDateValue =
            button.dataset.startDate;

        const endDateValue =
            button.dataset.endDate;


        // ==========================================
        // UBAH JUDUL MODAL
        // ==========================================

        programModalTitle.textContent =
            'Edit Program Loyalitas';


        // ==========================================
        // UBAH ACTION FORM
        // ==========================================

        programForm.action =
            `/staff/loyalty/${id}`;


        // ==========================================
        // METHOD PUT
        // ==========================================

        programFormMethod.value =
            'PUT';


        // ==========================================
        // UBAH TOMBOL
        // ==========================================

        programSubmitButton.textContent =
            'Simpan Perubahan';


        // ==========================================
        // ISI DATA PROGRAM
        // ==========================================

        programName.value =
            name;

        minTransaction.value =
            minTransactionValue;

        description.value =
            programDescription === 'null'
                ? ''
                : programDescription;

        applicableService.value =
            serviceId || '';

        startDate.value =
            startDateValue;

        endDate.value =
            endDateValue;


        // ==========================================
        // REWARD
        // ==========================================

        rewardType.value =
            rewardTypeValue;

        rewardValue.value =
            rewardValueData;

        freeServicePackage.value =
        freeServicePackageId || '';


        // Jalankan ulang tampilan reward
        updateRewardField();


        // ==========================================
        // BUKA MODAL
        // ==========================================

        modal.classList.add('show');

    });

});

});