<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}"
    >

    <title>
        @yield('title', 'Owner - Sang Pencuci')
    </title>

    @vite([
        'resources/css/owner.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>

<body>

    <div class="owner-layout">

        {{-- Sidebar Owner --}}
        @include('partials.owner.sidebar')

        {{-- Konten utama --}}
        <main class="main-content">

            {{-- Topbar Owner --}}
            @include('partials.owner.topbar')

            {{-- Isi halaman --}}
            @yield('content')

        </main>

    </div>

    @stack('scripts')

</body>

</html>