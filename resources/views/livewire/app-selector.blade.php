<div class="px-6 py-12">
    <!-- Page Header -->
    <div class="max-w-7xl mx-auto mb-16 px-4">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Authorized Applications</h1>
        <p class="mt-2 text-lg text-gray-500 font-medium font-sans">Securely transition your single sign-on session to an authorized satellite service.</p>
    </div>

    @error('access')
        <div
            class="max-w-7xl mx-auto mb-10 mx-4 p-5 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-600">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-wider">Security Exception: {{ $message }}</span>
        </div>
    @enderror

    <!-- Applications Grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-4">
        @foreach($apps as $app)
            @php
                // Map icons/colors based on app slug or name for variety
                $colors = [
                    'bg-indigo-100/50 text-indigo-600',
                    'bg-purple-100/50 text-purple-600',
                    'bg-blue-100/50 text-blue-600',
                    'bg-emerald-100/50 text-emerald-600'
                ];
                $color = $colors[$loop->index % count($colors)];

                // Determine Icon based on name
                $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />';
                if (str_contains(strtolower($app->name), 'shop'))
                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />';
                if (str_contains(strtolower($app->name), 'woo'))
                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />';
                if (str_contains(strtolower($app->name), 'api') || str_contains(strtolower($app->name), 'custom'))
                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />';
            @endphp
            <div
                class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] p-10 flex flex-col items-center text-center transition-all duration-300 {{ $app->has_access ? 'hover:shadow-[0_20px_40px_rgb(0,0,0,0.06)] hover:-translate-y-1' : 'opacity-70 grayscale-[0.8] cursor-not-allowed' }}">

                <!-- Circular Icon Container -->
                <div
                    class="w-20 h-20 rounded-full {{ $app->has_access ? $color : 'bg-gray-100 text-gray-400' }} flex items-center justify-center mb-8 transition-transform group-hover:scale-105">
                    <svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        {!! $icon !!}
                    </svg>
                </div>

                <!-- Text Content -->
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $app->name }}</h3>
                <p class="text-sm font-medium text-gray-400 leading-relaxed mb-10 max-w-[240px]">
                    Authorized satellite service. transition your secure <span class="capitalize">{{ $app->slug }}</span> session via SSO gateway.
                </p>

                <!-- Full Width Action Button -->
                <div class="w-full mt-auto">
                    @if($app->has_access)
                        <button wire:click="selectApp({{ $app->id }})"
                            class="w-full py-4 px-6 bg-[#0b0e14] hover:bg-black text-white rounded-2xl text-sm font-black tracking-tight transition-all active:scale-[0.98] shadow-lg shadow-black/10">
                            Launch Application
                        </button>
                    @else
                        <button disabled
                            class="w-full py-4 px-6 bg-gray-50 text-gray-400 border border-gray-100 rounded-2xl text-sm font-bold tracking-tight cursor-not-allowed">
                            Session Locked
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>