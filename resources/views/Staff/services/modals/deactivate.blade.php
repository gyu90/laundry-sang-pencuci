{{-- ==========================================================
    MODAL KONFIRMASI NONAKTIFKAN LAYANAN
    ========================================================== --}}

<div
    class="modal-overlay service-deactivate-modal"
    id="deactivateServiceModal"
>
    <div class="modal-container service-deactivate-container">

        {{-- Header --}}
        <div class="modal-header">

            <div>
                <h2>Nonaktifkan Layanan?</h2>

                <p>
                    Apakah Anda yakin ingin menonaktifkan
                    "<span id="deactivateServiceName"></span>"?
                </p>
            </div>

            {{-- Tombol X --}}
            <button
                type="button"
                class="modal-close"
                id="closeDeactivateServiceModal"
                aria-label="Tutup"
            >
                &times;
            </button>

        </div>


        {{-- Footer --}}
        <form
            method="POST"
            id="deactivateServiceForm"
        >

            @csrf
            @method('PUT')

            <div class="modal-footer">

                {{-- Batal --}}
                <button
                    type="button"
                    class="btn-cancel"
                    id="cancelDeactivateService"
                >
                    Batal
                </button>

                {{-- Konfirmasi --}}
                <button
                    type="submit"
                    class="btn-deactivate-service"
                >
                    Nonaktifkan Layanan
                </button>

            </div>

        </form>

    </div>
</div>