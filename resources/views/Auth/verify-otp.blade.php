<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi OTP - Sang Pencuci</title>

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
             BAGIAN KANAN - VERIFIKASI OTP
        ========================================== --}}
        <div class="login-section">

            <div class="login-card">

                <div class="login-header">

                    <h2>
                        Verifikasi OTP
                    </h2>

                    <p>
                        Masukkan kode OTP yang dikirim ke nomor HP kamu
                    </p>

                </div>


                {{-- Error --}}
                @if ($errors->any())

                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>

                @endif


                {{-- Testing OTP --}}
                @if (session('success'))

                    <div
                        style="
                            margin-bottom: 20px;
                            padding: 12px 14px;
                            border-radius: 10px;
                            background: #ecfdf5;
                            color: #047857;
                            font-size: 13px;
                        "
                    >
                        {{ session('success') }}
                    </div>

                @endif


<form
    method="POST"
    action="{{ route('password.verify.otp') }}"
>

                    @csrf

                    <div class="form-group">

                        <label for="otp">
                            Kode OTP
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
                                        y="4"
                                        width="18"
                                        height="16"
                                        rx="2"
                                    />

                                    <path
                                        d="M7 8h10M7 12h6"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>

                            <input
                                type="text"
                                id="otp"
                                name="otp"
                                inputmode="numeric"
                                maxlength="6"
                                placeholder="Masukkan 6 digit OTP"
                                autocomplete="one-time-code"
                                required
                            >

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="login-button"
                    >
                        <span>VERIFIKASI OTP</span>

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


                <div
                    style="
                        text-align: center;
                        margin-top: 20px;
                    "
                >
                    <a
                        href="{{ route('password.request') }}"
                        class="forgot-password-link"
                    >
                        ← Kirim ulang OTP
                    </a>
                </div>


                <div class="login-footer">

                    <p>
                        Sistem Manajemen Laundry
                    </p>

                    <span>
                        Sang Pencuci © {{ date('Y') }}
                    </span>

                </div>


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