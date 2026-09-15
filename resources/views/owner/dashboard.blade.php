@extends('layouts.owner')

@section('title', 'Dashboard Owner - Sang Pencuci')

@section('content')

    {{-- SUMMARY --}}
    <section class="owner-summary">

        <div class="owner-income-card">
            <span>Omzet hari ini</span>

            <strong>
                Rp{{ number_format($omzetHariIni, 0, ',', '.') }}
            </strong>
        </div>


        <div class="owner-stat-card waiting">
            <span>Antrian</span>
            <strong>{{ $antrian }}</strong>
        </div>


        <div class="owner-stat-card process">
            <span>Diproses</span>
            <strong>{{ $diproses }}</strong>
        </div>


        <div class="owner-stat-card delivery">
            <span>Siap diantar</span>
            <strong>{{ $siapDiantar }}</strong>
        </div>


        <div class="owner-stat-card pickup">
            <span>Siap dijemput</span>
            <strong>{{ $siapDijemput }}</strong>
        </div>

    </section>


    {{-- PESANAN TERBARU --}}
    <section class="owner-dashboard-card">

        <div class="owner-card-header">
            <h2>Pesanan terbaru</h2>

           
        </div>


        <div class="owner-table-wrapper">

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

                    @forelse ($pesananTerbaru as $order)

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

                                @php
                                    $statusClass = match ($order->status) {
                                        'Dalam Antrian' => 'waiting',
                                        'Proses' => 'process',
                                        'Siap Diantar' => 'delivery',
                                        'Siap Dijemput' => 'pickup',
                                        'Selesai' => 'completed',
                                        default => ''
                                    };
                                @endphp

                                <span class="owner-status {{ $statusClass }}">
                                    {{ $order->status }}
                                </span>

                            </td>


                            <td class="owner-amount">
                                Rp{{ number_format($order->final_amount, 0, ',', '.') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="empty-order">
                                Belum ada pesanan.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <a
            href="{{ route('staff.orders.index') }}"
            class="owner-view-orders"
        >
            Lihat semua pesanan ↗
        </a>

    </section>

{{-- PESANAN PER LAYANAN --}}
<section class="owner-dashboard-card">

    <div class="owner-card-header">
        <h2>Pesanan per layanan</h2>
    </div>

    <div class="category-list">

        @forelse ($pesananPerKategori as $service)

            @php
                $percentage = $jumlahMaksimalKategori > 0
                    ? ($service->total / $jumlahMaksimalKategori) * 100
                    : 0;
            @endphp

            <div class="owner-category-item">

                <div class="owner-category-label">

                    <span>
                        {{ $service->service_name }}
                    </span>

                    <strong>
                        {{ $service->total }}
                    </strong>

                </div>

                <div class="owner-progress">

                    <div
                        class="owner-progress-bar"
                        style="width: {{ $percentage }}%;"
                    ></div>

                </div>

            </div>

        @empty

            <p class="empty-category">
                Belum ada data pesanan.
            </p>

        @endforelse

    </div>

</section>

@endsection