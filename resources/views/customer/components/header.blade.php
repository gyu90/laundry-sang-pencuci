<header class="customer-header">

    <div class="customer-header-inner">

        {{-- HAMBURGER MOBILE --}}
        <button
            type="button"
            class="customer-hamburger"
            id="customerMenuButton"
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>


        {{-- LOGO --}}
        <a
            href="{{ url('/') }}"
            class="customer-logo-link"
        >
            <img
                src="{{ asset('images/logo2.png') }}"
                alt="Sang Pencuci"
                class="customer-logo"
            >
        </a>


        {{-- DESKTOP NAV --}}
        <nav class="customer-desktop-nav">

            <a
    href="{{ route('customer.home') }}"
    class="{{ request()->routeIs('customer.home') ? 'active' : '' }}"
>
    Home
</a>

            <a href="{{ route('customer.promo') }}">
    Promo
</a>

            @auth

                @if(auth()->user()->user_type === 'customer')

                    <a
                        href="{{ url('/akun') }}"
                        class="{{ request()->is('akun*') ? 'active' : '' }}"
                    >
                        Akun
                    </a>

                @endif

            @endauth

        </nav>


      {{-- HEADER ACTION --}}
<div class="customer-header-action">

    @guest

        {{-- GUEST --}}
        <a
            href="{{ route('login') }}"
            class="customer-login-button"
        >
            Masuk
        </a>

    @else

        @if(auth()->user()->user_type === 'customer')

            {{-- MOBILE SAJA --}}
            <a
                href="{{ url('/akun') }}"
                class="customer-mobile-account"
                aria-label="Akun Saya"
            >
                👤
            </a>

            {{-- LOGOUT CUSTOMER --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
                class="customer-logout-form"
            >
                @csrf

                <button
                    type="submit"
                    class="customer-logout-button"
                >
                    Keluar
                </button>
            </form>

       @elseif(
    auth()->user()->user_type === 'staff'
    && auth()->user()->staff?->role === 'staff'
)

    {{-- KEMBALI KE DASHBOARD STAFF --}}
    <a
        href="{{ route('staff.dashboard') }}"
        class="customer-logout-button"
    >
        Kembali ke Dashboard
    </a>

@elseif(
    auth()->user()->user_type === 'staff'
    && auth()->user()->staff?->role === 'owner'
)

    {{-- KEMBALI KE DASHBOARD OWNER --}}
    <a
        href="{{ route('owner.dashboard') }}"
        class="customer-logout-button"
    >
        Kembali ke Dashboard
    </a>
        @endif

    @endguest

</div>
</header>