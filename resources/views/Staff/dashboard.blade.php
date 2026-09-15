@extends('layouts.staff')

@section('title', 'Dashboard Staff - Sang Pencuci')

@section('page-title', 'Dashboard Staff')

@section('content')

<section class="summary-section">

    <div class="income-card">
        <span>Omzet hari ini</span>

        <strong>
            Rp{{ number_format($todayIncome, 0, ',', '.') }}
        </strong>
    </div>


    <div class="stat-card waiting">
        <span>Antrian</span>

        <strong>
            {{ $waitingOrders }}
        </strong>
    </div>


    <div class="stat-card process">
        <span>Diproses</span>

        <strong>
            {{ $processingOrders }}
        </strong>
    </div>


    <div class="stat-card delivery">
        <span>Siap diantar</span>

        <strong>
            {{ $readyDeliveryOrders }}
        </strong>
    </div>


    <div class="stat-card ready">
        <span>Siap dijemput</span>

        <strong>
            {{ $readyPickupOrders }}
        </strong>
    </div>

</section>


   <section class="dashboard-card">

    <div class="card-header">

        <h2>Pesanan terbaru</h2>

       

    </div>


    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>


            <tbody>

                @forelse ($recentOrders as $order)

                    <tr>

                        <td>
                            {{ $order->customer->name ?? '-' }}
                        </td>


                        <td>

                            @foreach ($order->items as $item)

                                {{ $item->servicePackage->package_name ?? '-' }}
                                × {{ $item->quantity }}

                                @if (!$loop->last)
                                    ,
                                @endif

                            @endforeach

                        </td>


                        <td>

                            @if ($order->status === 'Dalam Antrian')

                                <span class="status waiting">
                                    Antrian
                                </span>

                            @elseif ($order->status === 'Proses')

                                <span class="status process">
                                    Diproses
                                </span>

                            @elseif ($order->status === 'Siap Diantar')

                                <span class="status delivery">
                                    Siap diantar
                                </span>

                            @elseif ($order->status === 'Siap Dijemput')

                                <span class="status ready">
                                    Siap dijemput
                                </span>

                            @elseif ($order->status === 'Selesai')

                                <span class="status completed">
                                    Selesai
                                </span>

                            @endif

                        </td>


                        <td class="amount">

                            Rp{{ number_format(
                                $order->final_amount,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">
                            Belum ada pesanan.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <a
        href="{{ route('staff.orders.index') }}"
        class="view-orders"
    >
        Lihat semua pesanan ↗
    </a>

</section>

{{-- ================= PESANAN PER LAYANAN ================= --}}
<section class="dashboard-card">

    <div class="card-header">
        <h2>Pesanan per layanan</h2>
    </div>

    <div class="category-list">

        @forelse ($serviceOrders as $service)

            @php
                $percentage = $maxServiceOrders > 0
                    ? ($service->total / $maxServiceOrders) * 100
                    : 0;
            @endphp

            <div class="category-item">

                <div class="category-label">

                    <span>
                        {{ $service->service_name }}
                    </span>

                    <strong>
                        {{ $service->total }}
                    </strong>

                </div>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width: {{ $percentage }}%;"
                    ></div>

                </div>

            </div>

        @empty

            <p>
                Belum ada data pesanan.
            </p>

        @endforelse

    </div>

</section>

@endsection

@push('scripts')

    @vite('resources/js/staff/dashboard.js')

@endpush