@extends('layouts.panel')
@section('content')
@vite([
    'resources/css/staff/loyalty.css',
    'resources/js/staff/loyalty.js'
])
<div class="loyalty-page">

    {{-- HEADER --}}
<div class="loyalty-header">

    <div>
        <h1>Program Loyalitas</h1>

        <p>
            Kelola program loyalitas dan voucher customer.
        </p>
    </div>

    <button type="button" class="loyalty-add-button">
        + Buat Program
    </button>

</div>

    </div>


    {{-- DAFTAR PROGRAM --}}
    <div class="loyalty-program-list">

        @forelse ($programs as $program)

            <div class="loyalty-program-card">

                <div class="loyalty-program-card-header">

                    <div>
                        <h3>
                            {{ $program->name }}
                        </h3>

                        <p>
                            {{ $program->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>

                    @if ($program->is_active)
                        <span class="loyalty-status active">
                            Aktif
                        </span>
                    @else
                        <span class="loyalty-status inactive">
                            Tidak Aktif
                        </span>
                    @endif

                </div>


                <div class="loyalty-program-info">

                    <div>
                        <span>Minimal Transaksi</span>

                        <strong>
                            Rp{{ number_format(
                                $program->min_transaction_amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>
                    </div>


                    <div>
    <span>Periode Program</span>

    <strong>
        {{ $program->start_date->format('d M Y') }}
        -
        {{ $program->end_date->format('d M Y') }}
    </strong>
</div>


                    <div>
    <span>Reward</span>

    <strong>
        @if ($program->reward_type === 'discount_percentage')
            Diskon {{ number_format($program->reward_value, 0, ',', '.') }}%
        @elseif ($program->reward_type === 'discount_nominal')
            Diskon Rp{{ number_format($program->reward_value, 0, ',', '.') }}
        @elseif ($program->reward_type === 'free_service')
            Gratis Layanan
        @endif
    </strong>
</div>

                </div>


               <div class="loyalty-program-card-footer">

    <span>
        Layanan:
        {{ $program->applicableService->service_name ?? 'Semua Layanan' }}
    </span>

    <div class="loyalty-program-actions">

        {{-- DETAIL --}}
        <a
            href="{{ route('staff.loyalty.show', $program->id) }}"
            class="loyalty-detail-button"
        >
            Detail
        </a>

        {{-- EDIT --}}
        <button
            type="button"
            class="loyalty-edit-button"
            data-free-service-package-id="{{ $program->free_service_package_id }}"
            data-id="{{ $program->id }}"
            data-name="{{ $program->name }}"
            data-description="{{ $program->description }}"
            data-min-transaction="{{ $program->min_transaction_amount }}"
            data-reward-type="{{ $program->reward_type }}"
            data-reward-value="{{ $program->reward_value }}"
            data-service-id="{{ $program->applicable_service_id }}"
            data-start-date="{{ $program->start_date->format('Y-m-d') }}"
            data-end-date="{{ $program->end_date->format('Y-m-d') }}"
        >
            Edit
        </button>

    </div>

</div>

            </div>

        @empty

            <div class="loyalty-empty">

                <h3>
                    Belum ada program loyalitas
                </h3>

                <p>
                    Buat program loyalitas pertama untuk customer.
                </p>

            </div>

        @endforelse

    </div>

</div>

{{-- MODAL TAMBAH PROGRAM --}}
<div id="programModal" class="loyalty-modal">

    <div class="loyalty-modal-content">

        {{-- HEADER --}}
        <div class="loyalty-modal-header">

            <div>
    <h2 id="programModalTitle">
        Tambah Program Loyalitas
    </h2>

    <p id="programModalDescription">
        Tentukan syarat dan reward untuk program loyalitas.
    </p>
</div>

<button
    type="button"
    class="loyalty-modal-close"
    id="closeProgramModal"
>
    &times;
</button>

        </div>


        {{-- FORM --}}
        <form
    id="programForm"
    action="{{ route('staff.loyalty.store') }}"
    method="POST"
>
    @csrf

    <input
        type="hidden"
        name="_method"
        id="programFormMethod"
        value="POST"
    >


            {{-- Nama Program --}}
            <div class="loyalty-form-group">

                <label for="program_name">
                    Nama Program
                </label>

                <input
                    type="text"
                    id="program_name"
                    name="name"
                    placeholder="Contoh: Gratis Cuci Lipat 10x"
                >

            </div>


            {{-- Minimal Transaksi --}}
            <div class="loyalty-form-group">

                <label for="min_transaction_amount">
                    Minimal Nilai Transaksi
                </label>

                <div class="loyalty-input-prefix">

                    <span>Rp</span>

                    <input
                        type="number"
                        id="min_transaction_amount"
                        name="min_transaction_amount"
                        min="0"
                        placeholder="Contoh: 500000"
                    >

                </div>

                <small>
                    Customer akan memperoleh voucher setelah memenuhi
                    minimal nilai transaksi yang ditentukan.
                </small>

            </div>


            {{-- Jenis Reward --}}
            <div class="loyalty-form-group">

                <label for="reward_type">
                    Jenis Reward
                </label>

                <select
                    id="reward_type"
                    name="reward_type"
                >

                    <option value="">
                        Pilih jenis reward
                    </option>

                    <option value="free_service">
                        Gratis Layanan
                    </option>

                    <option value="discount_nominal">
                        Potongan Nominal
                    </option>

                    <option value="discount_percentage">
                        Potongan Persentase
                    </option>

                </select>

            </div>

            {{-- Paket Layanan Gratis --}}
<div
    class="loyalty-form-group"
    id="freeServicePackageGroup"
    style="display: none;"
>

    <label for="free_service_package_id">
        Paket Layanan Gratis
    </label>

    <select
        id="free_service_package_id"
        name="free_service_package_id"
    >

        <option value="">
            Pilih paket layanan
        </option>

        @foreach ($servicePackages as $package)

            <option
    value="{{ $package->id }}"
    data-service-id="{{ $package->service_id }}"
    data-service-name="{{ $package->service->service_name ?? 'Layanan' }}"
>
    {{ $package->service->service_name ?? 'Layanan' }}
    - {{ $package->package_name }}
    - Rp{{ number_format($package->price, 0, ',', '.') }}
</option>

        @endforeach

    </select>

    <small>
        Pilih paket yang akan diberikan secara gratis kepada customer.
    </small>

</div>


            {{-- Nilai Reward --}}
            <div class="loyalty-form-group">

                <label for="reward_value" id="rewardValueLabel">
                    Nilai Reward
                </label>

                <div class="loyalty-input-prefix">

                    <span id="rewardValuePrefix">
                        Rp
                    </span>

                    <input
                        type="number"
                        id="reward_value"
                        name="reward_value"
                        min="0"
                        placeholder="Pilih jenis reward terlebih dahulu"
                        disabled
                    >

                </div>

                <small id="rewardValueHelp">
                    Pilih jenis reward untuk menentukan nilai reward.
                </small>

            </div>


           {{-- Layanan --}}
<div class="loyalty-form-group">

    <label for="applicable_service_id">
        Layanan yang Berlaku
    </label>

    {{-- Tampilan khusus Gratis Layanan --}}
    <input
        type="text"
        id="freeServiceName"
        value=""
        readonly
        style="display: none;"
    >

    {{-- Nilai yang dikirim ke controller --}}
    <input
        type="hidden"
        id="freeApplicableServiceId"
        name="applicable_service_id"
        value=""
    >

    {{-- Pilihan layanan untuk reward diskon --}}
    <select
        id="applicable_service_id"
    >

        <option value="">
            Semua Layanan
        </option>

        @foreach ($services as $service)

            <option value="{{ $service->id }}">
                {{ $service->service_name }}
            </option>

        @endforeach

    </select>

</div>


            {{-- Keterangan --}}
            <div class="loyalty-form-group">

                <label for="description">
                    Keterangan
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Contoh: Berlaku untuk semua jenis layanan."
                ></textarea>

            </div>


            {{-- Periode --}}
            <div class="loyalty-date-row">

                <div class="loyalty-form-group">

                    <label for="period_start_date">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="period_start_date"
                        name="period_start_date"
                    >

                </div>


                <div class="loyalty-form-group">

                    <label for="period_end_date">
                        Tanggal Berakhir
                    </label>

                    <input
                        type="date"
                        id="period_end_date"
                        name="period_end_date"
                    >

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="loyalty-modal-footer">

                <button
                    type="button"
                    class="loyalty-btn-cancel"
                    id="cancelProgramModal"
                >
                    Batal
                </button>

                <button
    type="submit"
    class="loyalty-btn-save"
    id="programSubmitButton"
>
    Simpan Program
</button>

            </div>

        </form>

    </div>

</div>           

@endsection