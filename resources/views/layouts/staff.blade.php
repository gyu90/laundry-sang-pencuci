<!DOCTYPE html>
<html lang="id">

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}"
    >

    <title>
        @yield('title', 'Staff - Sang Pencuci')
    </title>

    @if(auth()->user()->role === 'owner')
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

    @if(auth()->user()->role === 'owner')
        @include('partials.owner.sidebar')
    @else
        @include('partials.staff.sidebar')
    @endif

    <main class="main-content">

        @if(auth()->user()->role === 'owner')
            @include('partials.owner.topbar')
        @else
            @include('partials.staff.topbar')
        @endif

        @yield('content')

    </main>

</div>

    @stack('scripts')

</body>

</html>