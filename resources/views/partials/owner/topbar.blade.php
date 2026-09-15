<header class="topbar">

    <div class="topbar-right">

        <div class="staff-info">

            {{-- Avatar Owner --}}
            <div class="staff-avatar">
                {{ strtoupper(substr(
                    auth()->user()->staff?->name
                    ?? auth()->user()->username
                    ?? 'O',
                    0,
                    1
                )) }}
            </div>


            {{-- Informasi Owner --}}
            <div>

                <strong>
                    {{ auth()->user()->staff?->name
                        ?? auth()->user()->username
                        ?? 'Owner' }}
                </strong>

                <span>
                    Owner
                </span>

            </div>

        </div>

    </div>

</header>