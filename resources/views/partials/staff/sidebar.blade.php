<aside class="sidebar">

    <div class="sidebar-logo">

<div class="logo-icon">
    <img src="{{ asset('images/logo.png') }}" alt="Logo Sang Pencuci">
</div>
        <div>
            <h2>Sang Pencuci</h2>
            <span>Staff Panel</span>
        </div>

    </div>


    <nav class="sidebar-menu">

        {{-- Dashboard --}}
        <a href="{{ route('staff.dashboard') }}"
           class="menu-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">

            <span class="menu-icon">▣</span>

            <span>Dashboard</span>

        </a>


        {{-- Pesanan --}}
        <a href="{{ route('staff.orders.index') }}"
           class="menu-item">

            <span class="menu-icon">▤</span>

            <span>Pesanan</span>

        </a>


        {{-- Tambah transaksi --}}
        <a href="#"
           class="menu-item">

            <span class="menu-icon">＋</span>

            <span>Tambah Transaksi</span>

        </a>


        {{-- Pelanggan --}}
        <a href="{{ route('staff.customers.index')}}"
           class="menu-item">

            <span class="menu-icon">♟</span>

            <span>Pelanggan</span>

        </a>


        {{-- Layanan --}}
        <a href="{{ route('staff.services.index')}}"
           class="menu-item">

            <span class="menu-icon">◇</span>

            <span>Layanan</span>

        </a>

    </nav>


    {{-- Logout --}}
    <div class="sidebar-bottom">

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit" class="logout-button">

                <span>↪</span>

                Keluar

            </button>

        </form>

    </div>

</aside>