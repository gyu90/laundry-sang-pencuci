{{-- Modal Konfirmasi Status Staff --}}
<div
    id="staffStatusConfirmationModal"
    class="owner-modal-overlay"
>

    <div class="owner-modal">

        {{-- Header --}}
        <div class="owner-modal-header">

            <div>
                <h2 id="staffStatusModalTitle">
                    Konfirmasi Status Staff
                </h2>

                <p id="staffStatusModalMessage">
                    Apakah Anda yakin ingin mengubah status staff ini?
                </p>
            </div>

            <button
                type="button"
                id="closeStaffStatusModal"
                class="owner-modal-close"
            >
                ×
            </button>

        </div>


        {{-- Footer --}}
        <div class="owner-modal-footer">

            <button
                type="button"
                id="cancelStaffStatusModal"
                class="owner-btn owner-btn-cancel"
            >
                Batal
            </button>

            <button
                type="button"
                id="confirmStaffStatusModal"
                class="owner-btn owner-btn-save"
            >
                Konfirmasi
            </button>

        </div>

    </div>

</div>