@extends('layouts.panel')

@section('content')

@vite([
    'resources/css/app.css',
    'resources/css/staff/customers.css',
    'resources/js/app.js',
    'resources/js/customer/account.js',
    'resources/js/staff/customer-maps.js',
    'resources/js/staff/customers.js'
    

])

<div class="customer-page">

    {{-- Header halaman --}}
    <div class="customer-page-header">

        <div>
            <h1>Customer</h1>
            <p>Kelola data pelanggan laundry.</p>
        </div>

        <button
            type="button"
            class="btn-add-customer"
            id="openAddCustomerModal"
        >
            + Tambah Customer
        </button>

    </div>


    {{-- Tabel Customer --}}
   <div class="customer-card-header">

    <div>
        <h2>Daftar Customer</h2>

        <p>
            Data pelanggan yang terdaftar dalam sistem.
        </p>
    </div>

    {{-- Pencarian Customer --}}
    <form
        method="GET"
        action="{{ route('staff.customers.index') }}"
        class="customer-search-form"
    >

        <div class="customer-search-wrapper">

            <img
    src="{{ asset('images/search.png') }}"
    alt="Cari"
    class="customer-search-icon"
>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau nomor HP..."
                class="customer-search-input"
            >

        </div>

    </form>

</div>


        <div class="customer-table-wrapper">

            <table class="customer-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Maps Lokasi</th>
                        <th>Terdaftar</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($customers as $index => $customer)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                <strong>
                                    {{ $customer->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $customer->user->phone }}
                            </td>


                           <td>
    {{ $customer->address }}
</td>

<td>
    @if ($customer->maps_link)
        <a
            href="{{ $customer->maps_link }}"
            target="_blank"
            rel="noopener noreferrer"
            class="customer-maps-button"
            title="Buka lokasi di Google Maps"
            aria-label="Buka lokasi di Google Maps"
        >
            <span class="customer-maps-icon">
                <span class="customer-maps-icon-pin"></span>
            </span>
        </a>
    @else
        <span class="customer-maps-empty">—</span>
    @endif
</td>

<td>
    {{ $customer->registered_at?->format('d/m/Y') }}
</td>

<td>
    {{ $customer->createdByStaff?->name ?? '-' }}
</td>

<td>
    <div class="customer-action-buttons">

        <a
            href="{{ route('staff.customers.show', $customer) }}"
            class="customer-detail-button"
        >
            Detail
        </a>

        <button
            type="button"
            class="customer-edit-button"
            data-customer-id="{{ $customer->id }}"
        >
            Edit
        </button>

    </div>
</td>


                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="customer-empty"
                            >
                                Belum ada customer yang terdaftar.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- FOOTER CUSTOMER --}}
<div class="customer-card-footer">

    <div class="customer-pagination-info">
        Menampilkan
        {{ $customers->firstItem() ?? 0 }}
        -
        {{ $customers->lastItem() ?? 0 }}
        dari
        {{ $customers->total() }}
        customer
    </div>

    @if ($customers->hasPages())

        <div class="customer-pagination">
            {{ $customers->onEachSide(1)->links('pagination::simple-tailwind') }}
        </div>

    @endif

</div>

    </div>

</div>



{{-- ==========================================================
    MODAL TAMBAH CUSTOMER
    ========================================================== --}}

<div class="modal-overlay" id="addCustomerModal">

    <div class="customer-modal">

        {{-- Header --}}
        <div class="customer-modal-header">

    <div>
        <h2 id="customerModalTitle">
            Tambah Customer
        </h2>

        <p id="customerModalDescription">
            Daftarkan pelanggan baru ke dalam sistem.
        </p>
    </div>


        </div>


        {{-- Form --}}
        <form
    id="addCustomerForm"
    action="{{ route('staff.customers.store') }}"
    method="POST"
>

            @csrf
            @if ($errors->any())
    <div class="customer-form-errors">

        <strong>Data belum dapat disimpan:</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>
@endif

            <div class="customer-modal-body">

                {{-- Nama --}}
                <div class="form-group">

                    <label for="name">
                        Nama Customer
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Contoh: Budi Santoso"
                        required
                    >

                </div>



                {{-- Nomor HP --}}
               <div class="form-group">

    <label for="phone">
        No. HP
    </label>

<input
    type="text"
    id="phone"
    name="phone"
    placeholder="Contoh: 081234567890"
    inputmode="numeric"
    minlength="10"
    maxlength="15"
    pattern="[0-9]+"
    required
    oninvalid="setPhoneValidationMessage(this)"
    oninput="this.setCustomValidity('')"
>

</div>


                {{-- Email --}}
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Contoh: budi@gmail.com"
                    >

                </div>


                {{-- Alamat --}}
                <div class="form-group">

                    <label for="address">
                        Alamat
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Masukkan alamat customer"

                    ></textarea>

                </div>

                {{-- Lokasi Google Maps --}}
<div class="form-group">

    <label for="maps_link">
        Lokasi Google Maps <span>(Opsional)</span>
    </label>

    <input
        type="url"
        id="maps_link"
        name="maps_link"
        placeholder="Tempel link Google Maps"
    >

</div>


                <div id="customerPasswordFields">

    {{-- Password --}}
    <div class="form-group">

        <label for="password">
            Password
        </label>

        <input
    type="text"
    id="password"
    name="password"
    value="pelanggan12345"
    readonly
>

    </div>


    {{-- Konfirmasi Password --}}
    <div class="form-group">

        <label for="password_confirmation">
            Konfirmasi Password
        </label>

        <input
    type="text"
    id="password_confirmation"
    name="password_confirmation"
    value="pelanggan12345"
    readonly
>

    </div>

</div>

            </div>


            {{-- Footer --}}
           <div class="customer-modal-footer">

    <button
        type="button"
        class="customer-btn customer-btn-cancel"
        id="cancelAddCustomer"
    >
        Batal
    </button>

    <button
    type="submit"
    class="customer-btn customer-btn-save"
    id="customerSubmitButton"
>
    Simpan Customer
</button>

</div>

        </form>

    </div>

</div>


@endsection