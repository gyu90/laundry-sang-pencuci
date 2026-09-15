{{-- ==========================================================
    MODAL KONFIRMASI HAPUS LAYANAN
    ========================================================== --}}

<div
    class="modal-overlay service-delete-modal"
    id="deleteServiceModal"
>
    <div class="modal-container service-delete-container">

        {{-- Header --}}
        <div class="modal-header">

            <div>
                <h2>Hapus Layanan?</h2>

                <p>
                    Apakah Anda yakin ingin menghapus
                    "<span id="deleteServiceName"></span>"?
                </p>
            </div>

            {{-- Tombol X --}}
            <button
                type="button"
                class="modal-close"
                id="closeDeleteServiceModal"
                aria-label="Tutup"
            >
                &times;
            </button>

        </div>


        {{-- Footer --}}
        <form
            method="POST"
            id="deleteServiceForm"
        >

            @csrf
            @method('DELETE')

            <div class="modal-footer">

                {{-- Batal --}}
                <button
                    type="button"
                    class="btn-cancel"
                    id="cancelDeleteService"
                >
                    Batal
                </button>

                {{-- Konfirmasi --}}
                <button
                    type="submit"
                    class="btn-delete-service"
                >
                    Hapus Layanan
                </button>

            </div>

        </form>

    </div>
</div>


{{-- ==========================================================
    MODAL GAGAL HAPUS LAYANAN
    ========================================================== --}}

<div
    class="modal-overlay service-delete-modal @if(session('error')) show @endif"
    id="deleteServiceErrorModal"
>
    <div class="modal-container service-delete-container">

        {{-- Header --}}
        <div class="modal-header">

            <div>
                <h2>Penghapusan Gagal</h2>

                <p>
                    Layanan tidak dapat dihapus.
                </p>
            </div>

            {{-- Tombol X --}}
            <button
                type="button"
                class="modal-close"
                id="closeDeleteServiceErrorModal"
                aria-label="Tutup"
            >
                &times;
            </button>

        </div>


        {{-- Footer --}}
        <div class="modal-footer">

            <button
                type="button"
                class="btn-cancel"
                id="closeDeleteServiceError"
            >
                Mengerti
            </button>

        </div>

    </div>
</div>