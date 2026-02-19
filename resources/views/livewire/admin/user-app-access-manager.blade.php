<div class="px-4 py-8 max-w-5xl mx-auto">
    <!-- Navigation / Breadcrumbs -->
    <div class="mb-8">
        <a href="{{ route('admin.users') }}"
            class="inline-flex items-center text-[10px] font-black text-primary uppercase tracking-[0.2em] hover:translate-x-[-4px] transition-transform group">
            <svg class="mr-2 w-3 h-3 group-hover:text-primary-dark" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="3">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            User List
        </a>
    </div>

    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex items-center gap-6">
            <div
                class="w-16 h-16 rounded-2xl bg-primary-light flex items-center justify-center border border-primary/10 shadow-sm relative">
                <span class="text-xl font-black text-primary uppercase">{{ substr($user->name, 0, 1) }}</span>
                <div
                    class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border-4 border-white rounded-full anime-pulse shadow-sm">
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase leading-none">
                    Manage <span class="text-primary">Workspace Access</span>
                </h1>
                <p class="text-sm font-semibold text-slate-500 mt-2">
                    Configure which applications and roles are assigned to <span
                        class="text-slate-900 font-black">{{ $user->name }}</span>.
                </p>
            </div>
        </div>
    </div>

    <!-- Satellites List -->
    <div class="space-y-6">
        @foreach($apps as $app)
            @php
                $accessState = $appAccesses[$app->id] ?? null;
                $hasAccess = $accessState['has_access'] ?? false;
            @endphp
            <div wire:key="app-{{ $app->id }}"
                class="premium-card rounded-[2.5rem] p-8 transition-all duration-300 {{ $hasAccess ? 'border-primary/20 bg-white' : 'opacity-70 grayscale-[0.5] bg-slate-50/50' }}">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <!-- Satellite Identity -->
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 rounded-2xl {{ $hasAccess ? 'bg-primary-light text-primary' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center border border-current/10 transition-colors shadow-sm">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $app->name }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 mt-1.5 tracking-wide uppercase">
                                {{ $app->slug }}.nexus.internal
                            </p>
                        </div>
                    </div>

                    <!-- Authorization Controls -->
                    <div class="flex flex-wrap items-center gap-6">
                        @if($hasAccess)
                            <div class="flex items-center gap-4 animate-in fade-in slide-in-from-right-2 duration-300">
                                <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>
                                <div class="relative group">
                                    <label
                                        class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Assigned
                                        User Role</label>
                                    <select wire:change="updateRole({{ $app->id }}, $event.target.value)"
                                        class="block w-64 pl-4 pr-10 py-3 text-xs font-black text-slate-700 bg-slate-50 border-transparent rounded-xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all cursor-pointer appearance-none">
                                        <option value="" disabled>SELECT A ROLE</option>
                                        <optgroup label="GLOBAL ROLES"
                                            class="text-[10px] font-black uppercase tracking-widest bg-white text-primary">
                                            @foreach($globalRoles as $role)
                                                <option value="{{ $role->id }}" @selected($accessState['role_id'] == $role->id)>
                                                    {{ $role->name }} (Global)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="WORKSPACE-SPECIFIC ROLES"
                                            class="text-[10px] font-black uppercase tracking-widest bg-white text-primary">
                                            @foreach($app->roles as $role)
                                                <option value="{{ $role->id }}" @selected($accessState['role_id'] == $role->id)>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-4 flex items-center pt-6 pointer-events-none text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span
                                class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">Access
                                Disabled</span>
                        @endif

                        <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>

                        <!-- Modern Toggle -->
                        <div class="flex items-center gap-4">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $hasAccess ? 'Enabled' : 'Disabled' }}</span>
                            <button wire:click="toggleAccess({{ $app->id }})" type="button"
                                class="{{ $hasAccess ? 'bg-primary shadow-lg shadow-primary/20' : 'bg-slate-200' }} relative inline-flex h-8 w-14 flex-shrink-0 cursor-pointer rounded-full border-4 border-transparent transition-colors duration-200 ease-in-out focus:outline-none ring-4 ring-transparent hover:ring-primary/10"
                                role="switch" aria-checked="{{ $hasAccess ? 'true' : 'false' }}">
                                <span
                                    class="{{ $hasAccess ? 'translate-x-6' : 'translate-x-0' }} pointer-events-none relative inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out">
                                    <span
                                        class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $hasAccess ? 'opacity-0' : 'opacity-100' }}">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                    </span>
                                    <span
                                        class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $hasAccess ? 'opacity-100' : 'opacity-0' }}">
                                        <div
                                            class="w-2 h-2 rounded-full bg-primary shadow-[0_0_8px_rgba(124,58,237,0.4)] anime-pulse">
                                        </div>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($apps->isEmpty())
            <div
                class="premium-card rounded-[2.5rem] p-20 border-dashed border-slate-200 flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 mb-6 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">No Workspaces Found</h3>
                <p class="text-xs font-semibold text-slate-400 mt-3">There are no workspaces registered in the system yet.
                </p>
            </div>
        @endif
    </div>

    <!-- Security Information -->
    <div
        class="mt-12 bg-slate-950 rounded-[2.5rem] p-10 text-white relative overflow-hidden group border border-slate-800 shadow-2xl">
        <div
            class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-[100px] group-hover:bg-primary/20 transition-all duration-700">
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-2xl">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-4">Security Policy</h3>
                <p class="text-[11px] font-medium text-slate-400 leading-relaxed">
                    Changes to user permissions take effect immediately across all workspaces to ensure security.
                </p>
            </div>
            <div
                class="flex items-center gap-4 px-5 py-2.5 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 anime-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Real-time Sync Active</span>
            </div>
        </div>
    </div>
</div>