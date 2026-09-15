@extends('layouts.owner')

@section('title', 'Kelola Staff')

@push('styles')
    @vite('resources/css/owner/staff.css')
@endpush

@section('content')

<div class="owner-page">

    <div class="owner-page-header">

        <div>
            <h1>Kelola Staff</h1>

            <p>
                Kelola akun staff yang dapat menggunakan sistem.
            </p>
        </div>

        <button
    type="button"
    class="btn-add-staff"
    id="openAddStaffModal"
>
    + Tambah Staff
</button>

    </div>


    <div class="owner-card">

        <div class="owner-card-header">

            <div>
                <h2>Daftar Staff</h2>

                <p>
                    Staff yang terdaftar dalam sistem.
                </p>
            </div>

        </div>


        <div class="owner-table-wrapper">

            <table class="owner-table">

                <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Username</th>
        <th>No. HP</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
</thead>


                <tbody>

                    @forelse ($staffs as $index => $staff)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $staff->name }}
                            </td>

                            <td>
                                {{ $staff->user->username }}
                            </td>

                            <td>
                                {{ $staff->user->phone }}
                            </td>

                            <td>

                                @if ($staff->user->is_active)

                                    <span class="status-active">
                                        Aktif
                                    </span>

                                @else

                                    <span class="status-inactive">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>

                            <td>

    <form
        method="POST"
        action="{{ route('owner.staff.toggleStatus', $staff) }}"
    >

        @csrf
        @method('PATCH')

        @if ($staff->user->is_active)

            <button
                type="submit"
                class="btn-staff-disable"
            >
                Nonaktifkan
            </button>

        @else

            <button
                type="submit"
                class="btn-staff-enable"
            >
                Aktifkan
            </button>

        @endif

    </form>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="owner-empty"
                            >
                                Belum ada staff yang terdaftar.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

{{-- Modal Tambah Staff --}}
<div
    id="addStaffModal"
    class="owner-modal-overlay"
>

    <div class="owner-modal">

        {{-- Header --}}
        <div class="owner-modal-header">

            <div>
                <h2>Tambah Staff</h2>

                <p>
                    Tambahkan akun staff yang dapat menggunakan sistem.
                </p>
            </div>

            <button
                type="button"
                id="closeAddStaffModal"
                class="owner-modal-close"
            >
                ×
            </button>

        </div>


        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('owner.staff.store') }}"
        >

            @csrf

            <div class="owner-modal-body">

                {{-- Nama --}}
                <div class="owner-form-group">

                    <label for="name">
                        Nama Staff
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Contoh: Staff Laundry"
                        value="{{ old('name') }}"
                        required
                    >

                    @error('name')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- Username --}}
                <div class="owner-form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Contoh: staff01"
                        value="{{ old('username') }}"
                        required
                    >

                    @error('username')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- Nomor HP --}}
                <div class="owner-form-group">

                    <label for="phone">
                        Nomor HP
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Contoh: 081234567890"
                        value="{{ old('phone') }}"
                        required
                    >

                    @error('phone')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- Password --}}
                <div class="owner-form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required
                    >

                    @error('password')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>


                {{-- Konfirmasi Password --}}
                <div class="owner-form-group">

                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Masukkan ulang password"
                        required
                    >

                </div>


                {{-- Role --}}
                <div class="owner-form-group">

                    <label>
                        Role
                    </label>

                    <input
                        type="text"
                        value="Staff"
                        disabled
                    >

                    <small>
                        Role staff ditentukan otomatis oleh sistem.
                    </small>

                </div>

            </div>


            {{-- Footer --}}
            <div class="owner-modal-footer">

                <button
                    type="button"
                    id="cancelAddStaffModal"
                    class="owner-btn owner-btn-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="owner-btn owner-btn-save"
                >
                    Simpan Staff
                </button>

            </div>

        </form>

    </div>

</div>
@push('scripts')
    @vite('resources/js/owner/staff/staff.js')
@endpush