<div class="px-4 py-8">
    <!-- Welcome Header Section -->
    <div class="mb-12">
        <div class="flex items-center gap-6 mb-2">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center shadow-sm">
                <svg class="w-7 h-7 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9-4.5-9m0 18c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 0a9.004 9.004 0 018.716 6.747M12 3a9.004 9.004 0 00-8.716 6.747m17.432 0c.17.547.261 1.127.261 1.729a9 9 0 01-18 0c0-.602.091-1.182.261-1.729m17.432 0a8.974 8.974 0 01-.182 4.622M4.57 19.342a8.974 8.974 0 01-.182-4.622m0 0a9 9 0 0118 0" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl tracking-tight leading-tight">
                    Dashboard
                </h1>
                <p class="text-sm font-medium text-gray-500 mt-1 italic">Welcome back, <span
                        class="font-bold text-gray-900">{{ $user->name }}</span>. You are managing the authentication
                    portal.</p>
            </div>
        </div>
    </div>

    <!-- Main System Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <!-- Applications Module -->
        <a href="{{ route('admin.apps') }}" class="group premium-card p-10 rounded-[2.5rem]">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black text-primary/10 group-hover:text-primary/20 transition-colors">{{ $stats['total_apps'] }}</span>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3 uppercase">Applications</h3>
                <p class="text-xs font-medium text-slate-400 leading-relaxed">Manage connected applications, configure
                    URLs, and update settings.</p>
            </div>
        </a>

        <!-- User Directory Module -->
        <a href="{{ route('admin.users') }}" class="group premium-card p-10 rounded-[2.5rem]">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black text-primary/10 group-hover:text-primary/20 transition-colors">{{ $stats['total_users'] }}</span>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3 uppercase">User Management</h3>
                <p class="text-xs font-medium text-slate-400 leading-relaxed">Manage user accounts, control application
                    access, and update user details.</p>
            </div>
        </a>

        <!-- RBAC Module -->
        <a href="{{ route('admin.roles') }}" class="group premium-card p-10 rounded-[2.5rem]">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-start mb-8">
                    <div
                        class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div
                        class="px-2.5 py-1 bg-primary-light text-[10px] font-black text-primary rounded-lg uppercase tracking-tight">
                        ACTIVE
                    </div>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3 uppercase">Roles & Permissions</h3>
                <p class="text-xs font-medium text-slate-400 leading-relaxed">Define user roles, manage permissions, and
                    enforce access rules.</p>
            </div>
        </a>

        <!-- Security Ledger Module -->
        <a href="{{ route('admin.audit-logs') }}" class="group premium-card p-10 rounded-[2.5rem]">
            <div class="flex flex-col h-full">
                <div
                    class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3 uppercase">Audit Logs</h3>
                <p class="text-xs font-medium text-slate-400 leading-relaxed">View a history of all system activities,
                    track user actions, and see what changed.</p>
            </div>
        </a>

        <!-- SSO Sessions Module -->
        <a href="{{ route('admin.sso-sessions') }}"
            class="group relative bg-white p-10 rounded-[3rem] border border-gray-100/60 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-14 h-14 rounded-3xl bg-indigo-50 flex items-center justify-center mb-8 border border-indigo-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3">Active Sessions</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">View and manage active login sessions and
                    security tokens.</p>
            </div>
        </a>

        <!-- Dev Portal Module -->
        <a href="{{ route('profile.api-tokens') }}" class="group premium-card p-10 rounded-[2.5rem]">
            <div class="flex flex-col h-full">
                <div
                    class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold tracking-widest mb-3 uppercase">API Tokens</h3>
                <p class="text-xs font-medium text-slate-400 leading-relaxed">Manage API tokens for secure programmatic
                    access to the system.</p>
            </div>
        </a>
    </div>

    <!-- Bottom Secondary Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Stream (Modern Style) -->
        <div
            class="lg:col-span-2 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">Recent Activity</h3>
                <a href="{{ route('admin.audit-logs') }}"
                    class="text-[10px] font-bold text-indigo-500 hover:text-indigo-600 uppercase tracking-widest">View
                    All Logs →</a>
            </div>
            <div class="p-8 flex-1">
                <div class="space-y-6">
                    @forelse($stats['recent_logs'] as $log)
                        <div wire:key="recent-log-{{ $log->id }}"
                            class="flex items-start gap-4 p-4 rounded-2xl hover:bg-gray-50 transition-colors group">
                            <div class="w-2 h-2 rounded-full mt-2 bg-indigo-400 group-hover:scale-125 transition-transform">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-4 mb-0.5">
                                    <p class="text-sm font-bold text-gray-800 truncate uppercase tracking-tight">
                                        {{ $log->action }} on <span class="text-indigo-600">{{ $log->module }}</span>
                                    </p>
                                    <span
                                        class="text-[10px] font-medium text-gray-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-400 truncate">{{ $log->target?->name ?? 'System Resource' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="h-40 flex flex-col items-center justify-center text-gray-300">
                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs font-bold uppercase tracking-widest">No Recent Activity</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Launch Sidebar -->
        <div class="space-y-8">
            <div class="bg-[#0b0e14] rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                <div
                    class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl group-hover:bg-indigo-500/30 transition-colors">
                </div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] mb-4 relative z-10">Quick Connect</h3>
                <p class="text-xs font-medium text-gray-400 mb-6 relative z-10 leading-relaxed">Connect your first
                    application in minutes using our simple SSO setup.</p>
                <a href="{{ route('admin.apps') }}"
                    class="inline-flex items-center px-6 py-3 bg-white text-[#0b0e14] rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-50 transition-colors relative z-10">
                    Connect App
                    <svg class="ml-2 w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-premium">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-6">System Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">SSO Status</span>
                        <span class="flex items-center gap-1.5 text-[10px] font-black text-primary uppercase">
                            <span
                                class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                            OPERATIONAL
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">Token Engine</span>
                        <span class="flex items-center gap-1.5 text-[10px] font-black text-primary uppercase">
                            <span
                                class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                            STABLE
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">Audit System</span>
                        <span class="flex items-center gap-1.5 text-[10px] font-black text-primary uppercase">
                            <span
                                class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                            ACTIVE
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>