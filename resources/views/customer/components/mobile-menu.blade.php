<div
    class="customer-menu-overlay"
    id="customerMenuOverlay"
></div>


<aside
    class="customer-mobile-menu"
    id="customerMobileMenu"
>

    {{-- DRAWER HEADER --}}
    <div class="customer-mobile-menu-header">

        <div class="customer-mobile-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Sang Pencuci"
            >

            <div>
                <strong>Sang Pencuci</strong>

                <span>
                    @auth
                        Customer
                    @else
                        Selamat datang
                    @endauth
                </span>
            </div>

        </div>


        <button
            type="button"
            class="customer-menu-close"
            id="customerMenuClose"
            aria-label="Tutup menu"
        >
            ×
        </button>

    </div>


    {{-- MENU --}}
    <nav class="customer-mobile-nav">

        <a
            href="{{ url('/') }}"
            class="{{ request()->is('/') ? 'active' : '' }}"
        >
            <span class="mobile-menu-icon">⌂</span>
            <span>Home</span>
        </a>


        <a href="{{ url('/#layanan') }}">
            <span class="mobile-menu-icon">◉</span>
            <span>Layanan</span>
        </a>


         <a href="{{ route('customer.promo') }}">
            <span class="mobile-menu-icon">✦</span>
            <span>Promo</span>
        </a>


        {{-- GUEST --}}
        @guest

            <a href="{{ route('login') }}">
                <span class="mobile-menu-icon">→</span>
                <span>Masuk</span>
            </a>

        @else

            {{-- CUSTOMER LOGIN --}}
            @if(auth()->user()->user_type === 'customer')

                <a
                    href="{{ url('/akun') }}"
                    class="{{ request()->is('akun*') ? 'active' : '' }}"
                >
                    <span class="mobile-menu-icon">○</span>
                    <span>Akun</span>
                </a>

            @endif

        @endguest

    </nav>


    {{-- BOTTOM --}}
    <div class="customer-mobile-menu-bottom">

        @auth

            @if(auth()->user()->user_type === 'customer')

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="customer-mobile-logout"
                    >
                        <span>↪</span>
                        <span>Keluar</span>
                    </button>

                </form>

            @endif

        @else

            <p class="customer-mobile-menu-tagline">
                Nyuci itu berat, biar kami saja.
            </p>

        @endauth

    </div>

</aside>