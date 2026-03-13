<div>
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">System Health Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Real-time metrics and operational status of the Identity Platform.</p>
    </div>

    <!-- Top KPI Level Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Sessions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Active SSO Sessions</span>
                <div class="p-2 bg-emerald-50 text-emerald-500 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 003 11c0-5.523 4.477-10 10-10s10 4.477 10 10a9.985 9.985 0 01-2.017 5.992l.053.09M10 11V7a2 2 0 114 0v4a2 2 0 11-4 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-gray-900">{{ number_format($activeSessions) }}</h3>
                    <p class="text-xs font-semibold text-emerald-500 mt-1 flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 anime-pulse"></span>
                        Live Now
                    </p>
                </div>
            </div>
        </div>

        <!-- Failed Logins -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Failed Logins (24h)</span>
                <div class="p-2 bg-red-50 text-red-500 rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-black text-gray-900">{{ number_format($failedLogins24h) }}</h3>
                    <p class="text-xs font-semibold {{ $failedLogins24h > 50 ? 'text-red-500' : 'text-gray-400' }} mt-1 flex items-center">
                        <span class="mr-1">Attempts Blocked</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- JWT Keys Status -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">JWT Status</span>
                <div class="p-2 {{ $jwtKeysStatus === 'Healthy' ? 'bg-primary/10 text-primary' : 'bg-amber-50 text-amber-500' }} rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex flex-col justify-end">
                <h3 class="text-xl font-black {{ $jwtKeysStatus === 'Healthy' ? 'text-gray-900' : 'text-amber-500' }}">{{ $jwtKeysStatus }}</h3>
                <p class="text-xs font-semibold text-gray-500 mt-1">
                    @if($keyAgeDays !== null)
                        Keys rotated {{ $keyAgeDays }} days ago
                    @else
                        Key files missing!
                    @endif
                </p>
            </div>
        </div>

        <!-- OTP Delivery Rate -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">OTP Delivery (7d)</span>
                <div class="p-2 {{ $otpSuccessRate >= 80 ? 'bg-indigo-50 text-indigo-500' : 'bg-red-50 text-red-500' }} rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div class="w-full">
                    <h3 class="text-3xl font-black text-gray-900">{{ $otpSuccessRate }}<span class="text-xl">%</span></h3>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $otpSuccessRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Modules -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Platform Environment -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="font-bold text-gray-900">Platform Environment</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4 divide-y divide-gray-100">
                    <div class="flex items-center justify-between pb-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Database Sync</dt>
                        <dd class="flex items-center">
                            @if($dbStatus === 'Connected')
                                <span class="px-2 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 uppercase tracking-widest">OK</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-700 uppercase tracking-widest">ERROR</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between py-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Framework</dt>
                        <dd class="text-sm font-bold text-gray-900">Laravel v{{ $laravelVersion }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Runtime</dt>
                        <dd class="text-sm font-bold text-gray-900">PHP v{{ $phpVersion }}</dd>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Auth Driver</dt>
                        <dd class="text-sm font-bold text-gray-900">Session/JWT Hybrid</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Ecosystem Volume -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex items-center">
                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <h3 class="font-bold text-gray-900">Ecosystem Metrics</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-4 divide-y divide-gray-100">
                    <div class="flex items-center justify-between pb-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Total Registered Identities</dt>
                        <dd class="text-lg font-black text-gray-900">{{ number_format($totalUsers) }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Configured Satellites</dt>
                        <dd class="text-lg font-black text-gray-900">{{ number_format($totalApps) }} total <span class="text-xs font-normal text-gray-400 ml-1">({{ $activeApps }} active)</span></dd>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <dt class="text-sm font-semibold text-gray-500 uppercase tracking-widest">SSO Routing</dt>
                        <dd class="flex items-center">
                             <span class="px-2 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 uppercase tracking-widest ring-1 ring-emerald-200">Routing Live</span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
