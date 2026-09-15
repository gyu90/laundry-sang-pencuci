@extends('layouts.customer')

@section('content')

<section class="order-history-page">

    <div class="order-history-container">

        {{-- Header --}}
        <div class="order-history-header">

            <div>
                <h1>Riwayat Pemesanan</h1>

                <p>
                    Berikut adalah seluruh riwayat pesanan laundry kamu.
                </p>
            </div>

        </div>


        {{-- Daftar Pesanan --}}
        <div class="order-history-list">

            @forelse($orderHistory as $order)

                <article class="order-history-card">

                    {{-- Informasi Order --}}
                    <div class="order-history-card-info">

                        <h2>
                            #{{ $order->id }}
                        </h2>

                        <p class="order-history-date">
                            {{ $order->order_date->translatedFormat('d F Y') }}
                        </p>

                        <p class="order-history-time">
                            {{ $order->order_date->format('H:i') }}
                        </p>

                    </div>


                    {{-- Daftar Layanan --}}
                    <div class="order-history-services">

                        <h3>Daftar Layanan</h3>

                        @foreach($order->items as $item)

                            <div class="order-history-service">

                                <div class="order-history-service-detail">

                                    <span class="order-history-service-name">
                                        {{ $item->servicePackage->package_name }}
                                    </span>

                                    <span class="order-history-service-unit">
                                        Rp{{ number_format($item->unit_price, 0, ',', '.') }}
                                        × {{ $item->quantity }}
                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- Status + Total --}}
                    <div class="order-history-summary">

                        <span class="order-history-status">
                            {{ $order->status }}
                        </span>

                        <div class="order-history-total">

                            <span>Total Pembayaran</span>

                            <strong>
                                Rp{{ number_format($order->final_amount, 0, ',', '.') }}
                            </strong>

                        </div>

                    </div>

                </article>

            @empty

                <div class="order-history-empty">

                    <h2>Belum ada riwayat pemesanan</h2>

                    <p>
                        Pesanan yang sudah dibuat akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>


@if ($orderHistory->hasPages())

    <div class="order-history-pagination">

        {{-- Previous --}}
        @if ($orderHistory->onFirstPage())
            <span class="pagination-disabled">‹</span>
        @else
            <a href="{{ $orderHistory->previousPageUrl() }}">‹</a>
        @endif


        {{-- Nomor Halaman --}}
        @foreach ($orderHistory->getUrlRange(1, $orderHistory->lastPage()) as $page => $url)

            @if ($page == $orderHistory->currentPage())
                <span class="pagination-active">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}">
                    {{ $page }}
                </a>
            @endif

        @endforeach


        {{-- Next --}}
        @if ($orderHistory->hasMorePages())
            <a href="{{ $orderHistory->nextPageUrl() }}">›</a>
        @else
            <span class="pagination-disabled">›</span>
        @endif

    </div>

@endif

    </div>

</section>

@endsection