@extends('layouts.panel')

@section('title', 'Layanan - Staff')

@push('styles')
@vite([
    'resources/css/staff/services.css',
    'resources/css/staff/service-delete-modal.css',
    'resources/css/staff/service-deactivate-modal.css',
    'resources/css/staff/service-reactivate-modal.css',
    'resources/css/staff/service-package-deactivate-modal.css'
])
@endpush

@section('content')

    {{-- Header halaman layanan --}}
<div class="page-header">

    <div>
        <h1>Layanan</h1>
        <p>Daftar layanan dan paket laundry yang tersedia.</p>
    </div>

    <div class="page-header-actions">

        <div class="service-search-wrapper">
            <span class="service-search-icon">⌕</span>

            <input
                type="text"
                id="serviceSearch"
                class="service-search-input"
                placeholder="Cari layanan..."
                autocomplete="off"
            >
        </div>

        <button
            type="button"
            class="btn-add-service"
            id="openAddServiceModal"
        >
            + Tambah Layanan
        </button>

    </div>

</div>

    {{-- 
        Perulangan untuk mengambil setiap layanan
        yang dikirim dari ServiceController.
    --}}
    <div class="services-container">

        @foreach ($services as $service)

            {{-- Card untuk satu layanan --}}
            <div class="service-card">

                {{-- =====================================================
     HEADER LAYANAN
     Berisi nama layanan dan menu tindakan (⋮)
     ===================================================== --}}
@php
    /*
     * Jika Staff mengupload gambar,
     * gunakan gambar tersebut.
     *
     * Jika image NULL,
     * gunakan gambar default berdasarkan nama layanan.
     */
    if ($service->image) {

        $serviceIcon = $service->image;

    } else {

        $serviceName = strtolower($service->service_name);

        if (str_contains($serviceName, 'kiloan')) {
            $serviceIcon = 'washing-machine.png';
        } elseif (
            str_contains($serviceName, 'delicates') ||
            str_contains($serviceName, 'dry cleaning')
        ) {
            $serviceIcon = 'shirt.png';
        } elseif (str_contains($serviceName, 'extra service')) {
            $serviceIcon = 'sparkles.png';
        } else {
            $serviceIcon = 'laundry basket.png';
        }
    }
@endphp

<div class="service-card-header">

    {{-- Ikon dan nama layanan --}}
    <div class="service-card-title">

        <div class="service-icon">
            <img
                src="{{ asset('images/' . $serviceIcon) }}"
                alt="{{ $service->service_name }}"
            >
        </div>

        <div class="service-name-wrapper">
    <h2>
        {{ $service->service_name }}
    </h2>

    @if (!$service->is_active)
        <span class="service-status-badge">
            Nonaktif
        </span>
    @endif
</div>

    </div>

    {{-- Menu tindakan layanan --}}
    <div class="service-menu">

        <button
            type="button"
            class="service-menu-button"
            onclick="toggleServiceMenu({{ $service->id }})"
            aria-label="Menu layanan"
        >
            ⋮
        </button>

        <div
            id="service-menu-{{ $service->id }}"
            class="service-menu-dropdown"
        >

            {{-- Edit layanan --}}
            <button
                type="button"
                class="service-menu-item edit-service-button"
                data-service-id="{{ $service->id }}"
            >
                <span>✏</span>
                <span>Edit layanan</span>
            </button>

            @if ($service->is_active)

    {{-- Nonaktifkan layanan --}}
    <button
        type="button"
        class="service-menu-item"
        onclick="openDeactivateServiceModal(
            {{ $service->id }},
            @js($service->service_name)
        )"
    >
        <span>⏸</span>
        <span>Nonaktifkan layanan</span>
    </button>

@else

    {{-- Aktifkan kembali layanan --}}
    <button
        type="button"
        class="service-menu-item"
        onclick="openReactivateServiceModal(
    {{ $service->id }},
    @js($service->service_name)
)"
    >
        <span>▶</span>
        <span>Aktifkan layanan</span>
    </button>

@endif

           <button
    type="button"
    class="service-menu-item service-menu-danger"
    onclick="openDeleteServiceModal(
        {{ $service->id }},
        @js($service->service_name)
    )"
>
    <span>🗑</span>
    <span>Hapus layanan</span>
</button>

        </div>

    </div>

</div>

{{-- Daftar paket layanan --}}
<div class="service-packages">

    @foreach ($service->servicePackages as $package)

        <div class="package-item">

            <div class="package-info">
    <h3>{{ $package->package_name }}</h3>

    @if (!$package->is_active)
        <span class="package-status-badge">
            Nonaktif
        </span>
    @endif
</div>

<div class="package-price">
    Rp {{ number_format($package->price, 0, ',', '.') }}
</div>

        </div>

    @endforeach

</div>
            </div>

        @endforeach

    </div>


{{-- ==========================================================
    MODAL TAMBAH LAYANAN
    ========================================================== --}}

<div class="modal-overlay" id="addServiceModal">

    <div class="modal-container">

        {{-- Header modal --}}
        <div class="modal-header">

           <div>
    <h2 id="serviceModalTitle">Tambah Layanan</h2>

    <p id="serviceModalDescription">
        Tambahkan layanan beserta paket yang tersedia.
    </p>
</div>

            {{-- Tombol menutup modal --}}
            <button type="button" class="modal-close" id="closeAddServiceModal">
                &times;
            </button>

        </div>


        {{-- Form tambah layanan --}}
<form
    action="{{ route('staff.services.store') }}"
    method="POST"
    id="addServiceForm"
    enctype="multipart/form-data"
>

    @csrf

    {{-- Akan diisi PUT ketika mode EDIT --}}
    <input
        type="hidden"
        name="_method"
        id="serviceFormMethod"
        value=""
    >

    {{-- ID layanan ketika mode EDIT --}}
    <input
        type="hidden"
        name="service_id"
        id="serviceId"
        value=""
    >
    {{-- ==================================================
        ISI FORM YANG DAPAT DIGULIR
        ================================================== --}}

    <div class="modal-form-body">

            {{-- ==================================================
                NAMA LAYANAN
                ================================================== --}}

            <div class="form-group">

                <label for="service_name">
                    Nama Layanan
                </label>

                <input
                    type="text"
                    name="service_name"
                    id="service_name"
                    placeholder="Contoh: Laundry Satuan"
                    value="{{ old('service_name') }}"
                    required
                >

            </div>

           {{-- ==================================================
    GAMBAR LAYANAN
    ================================================== --}}

<div class="form-group">

    <label for="service_image">
        Gambar Layanan
    </label>

    {{-- Preview gambar layanan --}}
    <div
        id="serviceImagePreview"
        class="service-image-preview"
        hidden
    >

        <img
            id="serviceImagePreviewImage"
            src=""
            alt="Preview gambar layanan"
        >

        <span id="serviceImagePreviewText"></span>

    </div>

    {{-- Input gambar --}}
    <input
        type="file"
        name="image"
        id="service_image"
        accept=".jpg,.jpeg,.png,.svg"
    >

    <small class="form-help">
        Opsional. Format JPG, JPEG, PNG, atau SVG. Maksimal 5 MB.
    </small>

</div>


            {{-- ==================================================
                DAFTAR PAKET
                ================================================== --}}

            <div class="package-section">

                <div class="package-section-header">

                    <div>
                        <h3>Paket Layanan</h3>
                        <p>Tambahkan paket yang tersedia untuk layanan ini.</p>
                    </div>

                </div>


                {{-- Container tempat baris paket dibuat --}}
                <div id="packageContainer">

                    {{-- Paket pertama --}}
                    <div class="package-row">

                        {{-- Nama paket --}}
                        <div class="form-group">

                            <label>
                                Nama Paket
                            </label>

                            <input
                                type="text"
                                name="packages[0][package_name]"
                                placeholder="Contoh: Cuci Kemeja"
                                required
                            >

                        </div>


                        {{-- Harga --}}
                        <div class="form-group">

                            <label>
                                Harga
                            </label>

                            <input
                                type="number"
                                name="packages[0][price]"
                                placeholder="15000"
                                min="0"
                                required
                            >

                        </div>


                        {{-- Tombol hapus paket --}}
                        <button
                            type="button"
                            class="btn-remove-package"
                            title="Hapus paket"
                        >
                            &times;
                        </button>

                    </div>

                </div>

                


                {{-- Tombol menambah paket --}}
                <button
                    type="button"
                    class="btn-add-package"
                    id="addPackageButton"
                >
                    + Tambah Paket
                </button>

            </div>

</div>
            {{-- Footer modal --}}
            <div class="modal-footer">

                {{-- Membatalkan proses --}}
                <button
                    type="button"
                    class="btn-cancel"
                    id="cancelAddService"
                >
                    Batal
                </button>


                {{-- Menyimpan layanan dan paket --}}
                <button
    type="submit"
    class="btn-save-service"
    id="saveServiceButton"
>
    Simpan Layanan
</button>
            </div>

        </form>

    </div>

</div>

@include('staff.services.modals.delete')
@include('staff.services.modals.deactivate')
@include('staff.services.modals.reactivate')
@include('staff.services.modals.deactivate-package')

{{-- ==========================================================
    MODAL KONFIRMASI HAPUS PAKET
    ========================================================== --}}
<div class="modal-overlay" id="deletePackageModal">

    <div class="modal-container">

        {{-- Header modal --}}
        <div class="modal-header">
            <div>
                <h2>Hapus Paket?</h2>

                <p id="deletePackageMessage">
    Apakah Anda yakin ingin menghapus
    "<span id="deletePackageName"></span>"?
</p>
            </div>

            {{-- Tombol X --}}
            <button
                type="button"
                class="modal-close"
                id="closeDeletePackageModal"
            >
                &times;
            </button>
        </div>

        {{-- Footer modal --}}
        <div class="modal-footer">

            {{-- Membatalkan penghapusan --}}
            <button
                type="button"
                class="btn-cancel"
                id="cancelDeletePackage"
            >
                Batal
            </button>

            {{-- Mengonfirmasi penghapusan --}}
            <button
                type="button"
                class="btn-delete-package"
                id="confirmDeletePackage"
            >
                Hapus Paket
            </button>

        </div>

    </div>

</div>

    {{-- Data service untuk JavaScript --}}
    <script>
        const servicesData = @json($services);
    </script>

@push('scripts')
    @vite('resources/js/staff/service-delete-modal.js')
    @vite('resources/js/staff/service-deactivate-modal.js')
    @vite('resources/js/staff/service-reactivate-modal.js')
    @vite('resources/js/staff/service-package-deactivate-modal.js')
    @vite('resources/js/staff/service-package-status-modal.js')
@endpush

@endsection
