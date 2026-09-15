{{-- ==========================================================
    MODAL KONFIRMASI AKTIFKAN KEMBALI LAYANAN
    ========================================================== --}}

<div
    class="modal-overlay service-reactivate-modal"
    id="reactivateServiceModal"
>
    <div class="modal-container service-reactivate-container">

        {{-- Header --}}
        <div class="modal-header">

            <div>
                <h2>Aktifkan Layanan?</h2>

                <p>
                    Apakah Anda yakin ingin mengaktifkan kembali
                    "<span id="reactivateServiceName"></span>"?
                </p>
            </div>

            {{-- Tombol X --}}
            <button
                type="button"
                class="modal-close"
                id="closeReactivateServiceModal"
                aria-label="Tutup"
            >
                &times;
            </button>

        </div>


        {{-- Footer --}}
        <form
            method="POST"
            id="reactivateServiceForm"
        >

            @csrf
            @method('PUT')

            <div class="modal-footer">

                {{-- Batal --}}
                <button
                    type="button"
                    class="btn-cancel"
                    id="cancelReactivateService"
                >
                    Batal
                </button>

                {{-- Konfirmasi --}}
                <button
                    type="submit"
                    class="btn-reactivate-service"
                >
                    Aktifkan Layanan
                </button>

            </div>

        </form>

    </div>
</div>