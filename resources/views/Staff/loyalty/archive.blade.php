@extends('layouts.panel')

@section('title', 'Arsip Program Loyalitas')

@push('styles')
    @vite('resources/css/staff/loyalty.css')
@endpush

@section('content')

<div class="loyalty-page">

    {{-- HEADER --}}
    <div class="loyalty-header">

        <div>
            <h1>Arsip Program Loyalitas</h1>

            <p>
                Daftar program loyalitas yang telah berakhir.
            </p>
        </div>

        <a
            href="{{ route('staff.loyalty.index') }}"
            class="loyalty-detail-back-button"
        >
            ← Kembali
        </a>

    </div>


    {{-- DAFTAR PROGRAM ARSIP --}}
    <div class="loyalty-program-list">

        @forelse ($programs as $program)

            <div class="loyalty-program-card">

                {{-- HEADER CARD --}}
                <div class="loyalty-program-card-header">

                    <div>

                        <h3>
                            {{ $program->name }}
                        </h3>

                        <p>
                            {{ $program->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                    </div>

                    {{-- STATUS --}}
                    <span class="loyalty-status inactive">
                        Berakhir
                    </span>

                </div>


                {{-- INFORMASI PROGRAM --}}
                <div class="loyalty-program-info">

                    {{-- MINIMAL TRANSAKSI --}}
                    <div>

                        <span>
                            Minimal Transaksi
                        </span>

                        <strong>
                            Rp{{ number_format(
                                $program->min_transaction_amount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                    </div>


                    {{-- PERIODE --}}
                    <div>

                        <span>
                            Periode Program
                        </span>

                        <strong>
                            {{ $program->start_date->format('d M Y') }}
                            -
                            {{ $program->end_date->format('d M Y') }}
                        </strong>

                    </div>


                    {{-- REWARD --}}
                    <div>

                        <span>
                            Reward
                        </span>

                        <strong>

                            @if ($program->reward_type === 'discount_percentage')

                                Diskon
                                {{ number_format(
                                    $program->reward_value,
                                    0,
                                    ',',
                                    '.'
                                ) }}%

                            @elseif ($program->reward_type === 'discount_nominal')

                                Diskon Rp{{ number_format(
                                    $program->reward_value,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            @elseif ($program->reward_type === 'free_service')

                                Gratis Layanan

                            @endif

                        </strong>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="loyalty-program-card-footer">

                    <span>
                        Layanan:
                        {{ $program->applicableService->service_name ?? 'Semua Layanan' }}
                    </span>


                    <div class="loyalty-program-actions">

                        {{-- DETAIL --}}
                        <a
                            href="{{ route(
                                'staff.loyalty.show',
                                $program->id
                            ) }}"
                            class="loyalty-detail-button"
                        >
                            Detail
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="loyalty-empty">

                <h3>
                    Belum ada program yang diarsipkan
                </h3>

                <p>
                    Program loyalitas yang sudah melewati
                    tanggal berakhir akan muncul di sini.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection