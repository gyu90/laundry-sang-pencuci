<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sang Pencuci</title>

    <link
    rel="icon"
    type="image/png"
    href="{{ asset('images/logo.png') }}"
>

    @vite([
        'resources/css/login.css',
        'resources/js/app.js',
        'resources/css/login-mobile.css',
    ])
</head>

<body>

    <div class="login-page">

        {{-- =========================================
             BAGIAN KIRI - BRANDING
        ========================================== --}}
        <div
    class="login-brand"
    style="background-image: url('{{ asset('images/login-background.png') }}');"
>

            <div class="brand-overlay"></div>

            <div class="brand-content">

                {{-- Logo --}}
                <div class="brand-logo">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo Sang Pencuci"
                    >
                </div>

               <div class="brand-text">

    <h1>
        NYUCI ITU BERAT,<br>
        BIAR KAMI SAJA
    </h1>

    <p>
        Urusan cucian, serahkan kepada kami.
    </p>

    {{-- Keunggulan --}}
    <div class="brand-features">

        {{-- Bersih Maksimal --}}
        <div class="brand-feature">
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M7 9h10v8a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V9Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />
                    <path
                        d="M9 9V6h6v3M8 12h8"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                    <path
                        d="M5 7h14"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <span>
                Bersih<br>
                Maksimal
            </span>
        </div>

        {{-- Hemat Waktu --}}
        <div class="brand-feature">
            <div class="feature-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <circle
                        cx="12"
                        cy="12"
                        r="8"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />
                    <path
                        d="M12 7v5l3 2"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>

            <span>
                Hemat<br>
                Waktu
            </span>
        </div>

        {{-- Pelayanan Terpercaya --}}
        <div class="brand-feature">
            <div class="feature-icon feature-heart">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 20.5S4 15.8 4 9.5C4 6.7 6 5 8.4 5c1.5 0 2.8.8 3.6 2 0.8-1.2 2.1-2 3.6-2C18 5 20 6.7 20 9.5c0 6.3-8 11-8 11Z"/>
                </svg>
            </div>

            <span>
                Pelayanan<br>
                Terpercaya
            </span>
        </div>

    </div>

</div>

            </div>

        </div>


        {{-- =========================================
             BAGIAN KANAN - LOGIN
        ========================================== --}}
        <div class="login-section">

            <div class="login-card">

                {{-- Header --}}
                <div class="login-header">

                    <h2>
                        Selamat Datang
                        <span>👋</span>
                    </h2>

                    <p>
                        Masuk ke akun Sang Pencuci
                    </p>

                </div>


                {{-- Error --}}
                @if ($errors->any())

                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>

                @endif


                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                >

                    @csrf


                    {{-- Username / Nomor HP --}}
                    <div class="form-group">

                        <label for="login">
                            Username atau Nomor HP
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                    />
                                    <circle
                                        cx="12"
                                        cy="7"
                                        r="4"
                                    />
                                </svg>
                            </span>

                            <input
                                type="text"
                                id="login"
                                name="login"
                                value="{{ old('login') }}"
                                placeholder="Masukkan username atau nomor HP"
                                autocomplete="username"
                                required
                                autofocus
                            >

                        </div>

                    </div>


                    {{-- Password --}}
                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">

                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <rect
                                        x="3"
                                        y="11"
                                        width="18"
                                        height="10"
                                        rx="2"
                                    />

                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />

                                </svg>

                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                                aria-label="Tampilkan password"
                            >
                                <svg
                                    id="eyeIcon"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"
                                    />
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                    />
                                </svg>
                            </button>

                        </div>

                    </div>


                    {{-- Remember --}}
                    <div class="login-options">

                        <label class="remember-label">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                            >

                            <span>Ingat saya</span>

                        </label>

                        
                    </div>


                    {{-- Login Button --}}
                    <button
                        type="submit"
                        class="login-button"
                    >
                        <span>MASUK</span>

                        <svg
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line
                                x1="5"
                                y1="12"
                                x2="19"
                                y2="12"
                            />

                            <polyline
                                points="12 5 19 12 12 19"
                            />
                        </svg>

                    </button>

                </form>


               {{-- Footer --}}
<div class="login-footer">

    <p>
        Sistem Manajemen Laundry
    </p>

    <span>
        Sang Pencuci © {{ date('Y') }}
    </span>

</div>


{{-- Dekorasi pojok bawah card --}}
<div class="login-card-decoration">

    <div class="decoration-bubble bubble-one"></div>
    <div class="decoration-bubble bubble-two"></div>

    <div class="login-corner-text">
        <span>Pakaian</span>
        <span>Bersih</span>
        <span>Hidup</span>
        <span>Lebih Nyaman</span>
        <strong>♡</strong>
    </div>

</div>


</div>

            </div>

        </div>

    </div>


    <script>

        function togglePassword() {

            const password =
                document.getElementById('password');

            const eyeIcon =
                document.getElementById('eyeIcon');

            if (password.type === 'password') {

                password.type = 'text';

                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20
                    c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4
                    c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                    <line x1="1" y1="1" x2="23" y2="23" />
                `;

            } else {

                password.type = 'password';

                eyeIcon.innerHTML = `
                    <path
                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"
                    />
                    <circle
                        cx="12"
                        cy="12"
                        r="3"
                    />
                `;

            }

        }

    </script>

</body>

</html>