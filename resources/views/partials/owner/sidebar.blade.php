<aside class="sidebar">

    <div class="sidebar-logo">

       <div class="logo-icon">
    <img src="{{ asset('images/logo.png') }}" alt="Logo Sang Pencuci">
</div>

        <div>
            <h2>Sang Pencuci</h2>
            <span>Owner Panel</span>
        </div>

    </div>


    <nav class="sidebar-menu">

        {{-- Dashboard Owner --}}
        <a href="{{ route('owner.dashboard') }}"
           class="menu-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">

            <span class="menu-icon">▣</span>

            <span>Dashboard</span>

        </a>


        {{-- Pesanan --}}
        <a href="{{ route('staff.orders.index') }}"
           class="menu-item {{ request()->routeIs('staff.orders.*') ? 'active' : '' }}">

            <span class="menu-icon">▤</span>

            <span>Pesanan</span>

        </a>


        {{-- Pelanggan --}}
        <a href="{{ route('staff.customers.index') }}"
           class="menu-item {{ request()->routeIs('staff.customers.*') ? 'active' : '' }}">

            <span class="menu-icon">♟</span>

            <span>Pelanggan</span>

        </a>


        {{-- Layanan --}}
        <a href="{{ route('staff.services.index') }}"
           class="menu-item {{ request()->routeIs('staff.services.*') ? 'active' : '' }}">

            <span class="menu-icon">◇</span>

            <span>Layanan</span>

        </a>

        {{-- Loyalty --}}
        <a href="{{ route('staff.loyalty.index') }}"
           class="menu-item {{ request()->routeIs('staff.loyalty.*') ? 'active' : '' }}">

            <span class="menu-icon">★</span>

            <span>Loyalty</span>

        </a>


        {{-- Kelola Staff --}}
<a href="{{ route('owner.staff.index') }}"
   class="menu-item {{ request()->routeIs('owner.staff.*') ? 'active' : '' }}">

    <span class="menu-icon">♟</span>

    <span>Kelola Staff</span>

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