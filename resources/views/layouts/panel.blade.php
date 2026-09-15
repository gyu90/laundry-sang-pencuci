<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Sang Pencuci')
    </title>

@if (auth()->user()->staff?->role === 'owner')
    @vite([
        'resources/css/owner.css',
        'resources/js/app.js'
    ])
@else
    @vite([
        'resources/css/staff.css',
        'resources/js/app.js'
    ])
@endif

    @stack('styles')
</head>

<body>

    <div class="staff-layout">

        {{-- Sidebar berdasarkan role --}}
        @if (auth()->user()->staff?->role === 'owner')
            @include('partials.owner.sidebar')
        @else
            @include('partials.staff.sidebar')
        @endif

        {{-- Konten utama --}}
        <main class="main-content">

            {{-- Topbar berdasarkan role --}}
            @if (auth()->user()->staff?->role === 'owner')
                @include('partials.owner.topbar')
            @else
                @include('partials.staff.topbar')
            @endif

            {{-- Isi halaman --}}
            @yield('content')

        </main>

    </div>

    @stack('scripts')

</body>

</html>