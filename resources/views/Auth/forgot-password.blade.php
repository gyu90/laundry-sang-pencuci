<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password - Sang Pencuci</title>

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}"
    >

    @vite([
        'resources/css/login.css',
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

                    <div class="brand-features">

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
             BAGIAN KANAN - FORGOT PASSWORD
        ========================================== --}}
        <div class="login-section">

            <div class="login-card">

                {{-- Header --}}
                <div class="login-header">

                    <h2>
                        Lupa Password?
                    </h2>

                    <p>
                        Masukkan nomor HP yang terdaftar
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
    action="{{ route('password.send.otp') }}"
>

                    @csrf

                    <div class="form-group">

                        <label for="phone">
                            Nomor HP
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
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2
                                        19.79 19.79 0 0 1-8.63-3.07
                                        19.5 19.5 0 0 1-6-6
                                        19.79 19.79 0 0 1-3.07-8.67
                                        A2 2 0 0 1 4.11 2h3
                                        a2 2 0 0 1 2 1.72
                                        12.84 12.84 0 0 0 .7 2.81
                                        2 2 0 0 1-.45 2.11L8.09 9.91
                                        a16 16 0 0 0 6 6l1.27-1.27
                                        a2 2 0 0 1 2.11-.45
                                        12.84 12.84 0 0 0 2.81.7
                                        A2 2 0 0 1 22 16.92z"
                                    />
                                </svg>
                            </span>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Masukkan nomor HP"
                                autocomplete="tel"
                                required
                            >

                        </div>

                    </div>


                    {{-- Button --}}
                    <button
                        type="submit"
                        class="login-button"
                    >
                        <span>KIRIM OTP</span>

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


                {{-- Kembali ke Login --}}
                <div
                    style="
                        text-align: center;
                        margin-top: 20px;
                    "
                >
                    <a
                        href="{{ route('login') }}"
                        class="forgot-password-link"
                    >
                        ← Kembali ke Login
                    </a>
                </div>


                {{-- Footer --}}
                <div class="login-footer">

                    <p>
                        Sistem Manajemen Laundry
                    </p>

                    <span>
                        Sang Pencuci © {{ date('Y') }}
                    </span>

                </div>


                {{-- Dekorasi --}}
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

</body>

</html>