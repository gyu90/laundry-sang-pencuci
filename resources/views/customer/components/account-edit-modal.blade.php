{{-- =====================================================
     MODAL EDIT AKUN
====================================================== --}}
<div
    class="account-modal"
    id="accountEditModal"
    aria-hidden="true"
>

    <div
        class="account-modal-overlay"
        id="accountModalOverlay"
    ></div>


    <div
        class="account-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="accountModalTitle"
    >

        {{-- HEADER --}}
        <div class="account-modal-header">

            <div>
                <span class="account-modal-label">
                    PENGATURAN AKUN
                </span>

                <h2 id="accountModalTitle">
                    Edit akun
                </h2>
            </div>

            <button
                type="button"
                class="account-modal-close"
                id="closeAccountEdit"
                aria-label="Tutup"
            >
                ×
            </button>

        </div>


        {{-- FORM --}}
        <form
            class="account-edit-form"
            id="accountEditForm"
            action="{{ route('customer.account.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- NOMOR HP --}}
            <div class="account-form-group">

                <label for="accountPhone">
                    Nomor HP
                </label>

                <input
                    type="tel"
                    id="accountPhone"
                    name="phone"
                    value="{{ $user->phone }}"
                    placeholder="Masukkan nomor HP"
                    required
                >

            </div>


            {{-- ALAMAT --}}
            <div class="account-form-group">

                <label for="accountAddress">
                    Alamat
                </label>

                <textarea
                    id="accountAddress"
                    name="address"
                    rows="3"
                    placeholder="Masukkan alamat"
                    required
                >{{ $customer?->address ?? '' }}</textarea>

            </div>


            {{-- LOKASI GOOGLE MAPS --}}
<div class="account-form-group">

    <label for="accountMapsLink">
        Lokasi Google Maps
    </label>

    <input
        type="url"
        id="accountMapsLink"
        name="maps_link"
        value="{{ $customer?->maps_link ?? '' }}"
        placeholder="Tempel link Google Maps"
    >

    <small>
        Opsional untuk pengaturan akun. Link ini digunakan sebagai lokasi pengantaran.
    </small>

</div>


            {{-- PASSWORD BARU --}}
            <div class="account-form-group">

                <label for="accountPassword">
                    Kata sandi baru
                </label>

                <input
    type="password"
    id="accountPassword"
    name="password"
    placeholder="Kosongkan jika tidak ingin mengubah"
    autocomplete="new-password"
>

                <small>
                    Isi hanya jika ingin mengganti kata sandi.
                </small>

            </div>


            {{-- KONFIRMASI PASSWORD --}}
            <div class="account-form-group">

                <label for="accountPasswordConfirmation">
                    Konfirmasi kata sandi baru
                </label>

                <input
    type="password"
    id="accountPasswordConfirmation"
    name="password_confirmation"
    placeholder="Ulangi kata sandi baru"
    autocomplete="new-password"
>

            </div>


            {{-- ACTION --}}
            <div class="account-modal-actions">

                <button
                    type="button"
                    class="account-modal-button account-modal-cancel"
                    id="cancelAccountEdit"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="account-modal-button account-modal-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>