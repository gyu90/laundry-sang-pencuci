{{-- ==========================================================
    MODAL KONFIRMASI PERUBAHAN STATUS PAKET
    ========================================================== --}}

<div
    class="modal-overlay package-status-modal"
    id="packageStatusModal"
>
    <div class="modal-container package-status-container">

        {{-- Header --}}
        <div class="modal-header package-status-header">

            <div>
                <h2 id="packageStatusModalTitle">
                    Ubah Status Paket?
                </h2>

                <p id="packageStatusModalDescription">
                    Periksa perubahan status paket sebelum melanjutkan.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                id="closePackageStatusModal"
                aria-label="Tutup"
            >
                &times;
            </button>

        </div>


        {{-- Daftar perubahan --}}
        <div class="package-status-body">

            <div class="package-status-label">
                Paket yang akan diubah
            </div>

            <div
                class="package-status-list"
                id="packageStatusList"
            >
                {{-- Diisi oleh JavaScript --}}
            </div>

        </div>


        {{-- Konfirmasi --}}
        <div class="modal-footer package-status-footer">

            <button
                type="button"
                class="btn-cancel"
                id="cancelPackageStatus"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-confirm-package-status"
                id="confirmPackageStatus"
            >
                Simpan Perubahan
            </button>

        </div>

    </div>
</div>