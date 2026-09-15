<header class="topbar">

    {{-- 
        Bagian informasi staff.
        Bagian ini bersifat umum dan akan tampil
        di setiap halaman staff.
    --}}
    <div class="topbar-right">

        <div class="staff-info">

            {{-- Avatar staff --}}
            <div class="staff-avatar">
                {{ strtoupper(substr(auth()->user()->username ?? 'S', 0, 1)) }}
            </div>

            {{-- Informasi nama dan tipe user --}}
            <div>

                <strong>
                    {{ auth()->user()->staff?->name ?? auth()->user()->username ?? 'Staff' }}
                </strong>

                <span>
                    {{ ucfirst(auth()->user()->user_type ?? 'Staff') }}
                </span>

            </div>

        </div>

    </div>

</header>