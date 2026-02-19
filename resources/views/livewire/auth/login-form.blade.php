<div class="flex min-h-screen flex-col justify-center py-12 px-6 lg:px-8 relative overflow-hidden" x-data="{ isCovering: false, emailLength: 0 }">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 mesh-gradient pointer-events-none"></div>
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-amber-200/20 rounded-full filter blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-200/20 rounded-full filter blur-[120px] animate-pulse animation-delay-2000"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md z-10 text-center relative mb-4">
        <!-- Natural Bear Mascot -->
        <div class="flex justify-center h-48 relative" :class="{ 'is-covering-eyes': isCovering }">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="bear-head">
                <!-- Shadow -->
                <ellipse cx="100" cy="180" rx="40" ry="10" fill="black" fill-opacity="0.05" />
                
                <!-- Body -->
                <path d="M60 140C60 120 75 110 100 110C125 110 140 120 140 140V180H60V140Z" fill="url(#furGradientBody)" />
                
                <!-- Ears -->
                <g class="bear-ear-left">
                    <circle cx="65" cy="55" r="22" fill="#5D4037" />
                    <circle cx="65" cy="55" r="14" fill="#D7CCC8" opacity="0.4" />
                </g>
                <g class="bear-ear-right">
                    <circle cx="135" cy="55" r="22" fill="#5D4037" />
                    <circle cx="135" cy="55" r="14" fill="#D7CCC8" opacity="0.4" />
                </g>

                <!-- Head Main -->
                <circle cx="100" cy="90" r="55" fill="url(#furGradientHead)" />

                <!-- Muzzle -->
                <ellipse cx="100" cy="110" rx="28" ry="22" fill="#D7CCC8" />
                
                <!-- Interactive Face -->
                <g class="mascot-eyes-group" :style="`transform: translate(${Math.min(Math.max(emailLength - 15, -18), 18)}px, 4px)`">
                    <!-- Eyes - They stay visible but arms cover them -->
                    <circle cx="82" cy="85" r="6" fill="#212121" />
                    <circle cx="84" cy="83" r="2" fill="white" />
                    <circle cx="118" cy="85" r="6" fill="#212121" />
                    <circle cx="120" cy="83" r="2" fill="white" />
                    
                    <!-- Nose -->
                    <path d="M92 102C92 98 96 96 100 96C104 96 108 98 108 102C108 106 103 109 100 109C97 109 92 106 92 102Z" fill="#212121" />
                    <path d="M96 112C96 114 98 116 100 116C102 116 104 114 104 112" stroke="#5D4037" stroke-width="2" stroke-linecap="round" />
                </g>

                <!-- Paws - Redesigned for proper coverage -->
                <g class="bear-arm bear-arm-left">
                    <rect x="40" y="130" width="35" height="50" rx="17.5" fill="#5D4037" stroke="#4E342E" stroke-width="1" />
                    <circle cx="57.5" cy="142.5" r="10" fill="#D7CCC8" opacity="0.2" />
                    <!-- Fingers/Pads -->
                    <circle cx="48" cy="135" r="4" fill="#D7CCC8" opacity="0.15" />
                    <circle cx="57.5" cy="132" r="4" fill="#D7CCC8" opacity="0.15" />
                    <circle cx="67" cy="135" r="4" fill="#D7CCC8" opacity="0.15" />
                </g>
                <g class="bear-arm bear-arm-right">
                    <rect x="125" y="130" width="35" height="50" rx="17.5" fill="#5D4037" stroke="#4E342E" stroke-width="1" />
                    <circle cx="142.5" cy="142.5" r="10" fill="#D7CCC8" opacity="0.2" />
                    <!-- Fingers/Pads -->
                    <circle cx="133" cy="135" r="4" fill="#D7CCC8" opacity="0.15" />
                    <circle cx="142.5" cy="132" r="4" fill="#D7CCC8" opacity="0.15" />
                    <circle cx="152" cy="135" r="4" fill="#D7CCC8" opacity="0.15" />
                </g>

                <defs>
                    <linearGradient id="furGradientHead" x1="100" y1="35" x2="100" y2="145" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#795548" />
                        <stop offset="1" stop-color="#5D4037" />
                    </linearGradient>
                    <linearGradient id="furGradientBody" x1="100" y1="110" x2="100" y2="180" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#5D4037" />
                        <stop offset="1" stop-color="#4E342E" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <h2 class="text-4xl font-black tracking-tight text-slate-900 mb-1">
            OneStudio
        </h2>
        <p class="text-slate-500 font-medium text-sm">
            Sign in to your dashboard
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-[420px] z-10">
        <div class="dark-glass p-8 sm:p-10 rounded-[2.5rem] border-0 shadow-2xl ring-1 ring-black/5">
            <form wire:submit="login" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 ml-1 mb-2">Email address</label>
                    <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required
                        placeholder="your@email.com"
                        @input="emailLength = $event.target.value.length"
                        @focus="isCovering = false"
                        class="block w-full rounded-2xl border-0 py-3.5 px-5 glow-input sm:text-sm hover:bg-slate-100 transition-colors @error('email') ring-2 ring-red-500/20 @enderror">
                    @error('email')
                        <p class="mt-2 text-xs font-semibold text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between ml-1 mb-2">
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        <a href="#" class="text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors">Forgot?</a>
                    </div>
                    <input wire:model="password" id="password" name="password" type="password"
                        autocomplete="current-password" required
                        placeholder="••••••••"
                        @focus="isCovering = true"
                        @blur="isCovering = false"
                        class="block w-full rounded-2xl border-0 py-3.5 px-5 glow-input sm:text-sm hover:bg-slate-100 transition-colors @error('password') ring-2 ring-red-500/20 @enderror">
                    @error('password')
                        <p class="mt-2 text-xs font-semibold text-red-500 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="w-full btn-gradient flex justify-center items-center py-4 rounded-2xl shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-transform disabled:opacity-50 disabled:cursor-not-allowed text-base font-bold"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Sign in to account</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Verifying...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-sm text-slate-500 font-medium">
            Don't have an account?
            <a href="#" class="text-amber-600 hover:text-amber-700 font-bold transition-colors">Create one now</a>
        </p>
    </div>

    <style>
        .mascot-eyes-group {
            transition: transform 0.1s ease-out;
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.15; transform: scale(1); }
            50% { opacity: 0.25; transform: scale(1.05); }
        }
    </style>
</div>