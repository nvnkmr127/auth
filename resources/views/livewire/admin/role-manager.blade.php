<div class="px-4 py-8">
    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase leading-none">
                        Access <span class="text-primary">Protocols</span>
                    </h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Defining the hierarchical structure of
                        identity entitlements and satellite access levels.</p>
                </div>
            </div>

            <button wire:click="create"
                class="inline-flex items-center px-6 py-3 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                <svg class="mr-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Establish Protocol
            </button>
        </div>
    </div>

    <!-- Role Grid -->
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($roles as $role)
            <div wire:key="role-{{ $role->id }}" class="premium-card rounded-[2.5rem] flex flex-col h-full group">
                <div class="p-8 flex-grow">
                    <div class="flex items-center justify-between mb-8">
                        <div
                            class="w-12 h-12 rounded-2xl {{ $role->is_global ? 'bg-primary-light text-primary' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center border border-current/10 shadow-sm transition-transform group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span
                            class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase tracking-[0.2em] {{ $role->is_global ? 'bg-primary-light text-primary border border-primary/20 flex items-center gap-1.5' : 'bg-slate-50 text-slate-400 border border-slate-200' }}">
                            @if($role->is_global) <span class="w-1 h-1 bg-primary rounded-full anime-pulse"></span> @endif
                            {{ $role->is_global ? 'Universal' : 'Satellite' }}
                        </span>
                    </div>

                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest break-words"
                        title="{{ $role->name }}">{{ $role->name }}</h3>
                    <p class="text-[10px] font-mono font-black text-primary mt-2">{{ strtoupper($role->key) }}</p>
                    <p class="mt-5 text-[11px] font-semibold text-slate-400 leading-relaxed line-clamp-3">
                        "{{ $role->description }}"</p>
                </div>

                <div class="px-8 py-7 bg-slate-50/50 border-t border-slate-50 rounded-b-[2.5rem]">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-slate-900 leading-none">{{ $role->users_count }}</span>
                            <span
                                class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Identities</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span
                                class="text-2xl font-black text-primary leading-none">{{ $role->permissions_count }}</span>
                            <span
                                class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Capabilities</span>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="edit({{ $role->id }})"
                            class="flex-1 py-3.5 px-4 bg-white border border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-600 rounded-xl hover:bg-primary-light hover:text-primary hover:border-primary/20 transition-all shadow-sm active:scale-95">
                            Reconfigure
                        </button>
                        @if(!in_array($role->key, ['super_admin']))
                            <button wire:click="delete({{ $role->id }})"
                                wire:confirm="Protocol decommissioning will purge all linked entitlements. Proceed?"
                                class="p-3.5 bg-white border border-slate-100 text-slate-300 rounded-xl hover:bg-rose-50 hover:text-rose-500 hover:border-rose-100 transition-all active:scale-95 shadow-sm">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal System -->
    <div x-data="{ show: @entangle('showModal') }" x-show="show" x-on:keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="inline-block align-bottom bg-white rounded-[2.5rem] p-12 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-100 relative z-10">
                <div>
                    <div class="flex items-center gap-5 mb-10">
                        <div
                            class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center text-primary shadow-sm">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3c1.282 0 2.47.24 3.558.679m-4.507 10.248a4 4 0 01-.854-5.648L12 4m-4 10l.496-2.c.229-.922.868-1.698 1.718-2.141A5.053 5.053 0 0010 7V4m6 4v10a3 3 0 01-3 3h-3a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight leading-none"
                                id="modal-title">
                                {{ $isEditing ? 'Sync Access Protocol' : 'Define Authority' }}
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Governance
                                Schema Orchestration</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Left Column: Settings -->
                        <div class="space-y-8">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Protocol
                                    Designation</label>
                                <input wire:model="name" type="text"
                                    class="block w-full px-5 py-4 text-sm font-black bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all uppercase tracking-tight"
                                    placeholder="e.g. Audit Commissioner">
                                @error('name') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Cryptographic
                                    Key</label>
                                <input wire:model="key" type="text"
                                    class="block w-full px-5 py-4 text-sm font-black bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                                    placeholder="audit_commissioner">
                                @error('key') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Orchestration
                                    Mandate</label>
                                <textarea wire:model="description" rows="4"
                                    class="block w-full px-5 py-4 text-sm font-semibold text-slate-600 bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all leading-relaxed placeholder:italic"
                                    placeholder="Brief detailing of the internal protocol mandates..."></textarea>
                                @error('description') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 shadow-sm">
                                <h4
                                    class="text-[10px] font-black text-slate-900 uppercase tracking-[0.3em] mb-6 border-b border-slate-200 pb-4 flex items-center justify-between">
                                    Transmission Scope
                                    <span class="w-2 h-2 bg-primary rounded-full anime-pulse"></span>
                                </h4>
                                <div class="space-y-6">
                                    <button type="button" wire:click="$toggle('is_global')"
                                        class="flex items-center group cursor-pointer focus:outline-none w-full">
                                        <div class="relative">
                                            <div
                                                class="block w-12 h-7 {{ $is_global ? 'bg-primary' : 'bg-slate-200' }} rounded-full transition-colors border-2 border-transparent">
                                            </div>
                                            <div
                                                class="absolute left-1.5 top-1.5 bg-white w-4 h-4 rounded-full shadow-md transition-transform {{ $is_global ? 'translate-x-5' : '' }}">
                                            </div>
                                        </div>
                                        <span
                                            class="ml-4 text-[10px] font-black text-slate-700 uppercase tracking-widest">Universal
                                            Scope (Nexus)</span>
                                    </button>

                                    @if(!$is_global)
                                        <div class="animate-in fade-in slide-in-from-top-2 relative">
                                            <select wire:model="app_id"
                                                class="block w-full px-5 py-4 text-[10px] font-black uppercase text-slate-800 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all appearance-none cursor-pointer">
                                                <option value="">Link Target Satellite</option>
                                                @foreach($apps as $app)
                                                    <option wire:key="app-opt-{{ $app->id }}" value="{{ $app->id }}">
                                                        {{ strtoupper($app->name) }} Node
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Permission Matrix -->
                        <div class="flex flex-col">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 ml-1">Capability
                                Matrix</label>
                            <div
                                class="flex-grow h-[500px] overflow-y-auto border border-slate-100 rounded-[2.5rem] p-8 bg-slate-50/50 custom-scrollbar shadow-inner">
                                @foreach($permissionGroups as $group => $permissions)
                                    <div wire:key="group-{{ $group }}" class="mb-10 last:mb-0">
                                        <div class="flex items-center gap-4 mb-6">
                                            <div class="h-[1px] flex-grow bg-slate-200"></div>
                                            <h5
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-[0.4em] whitespace-nowrap">
                                                {{ $group ?? 'System' }} Repository
                                            </h5>
                                            <div class="h-[1px] flex-grow bg-slate-200"></div>
                                        </div>
                                        <div class="grid grid-cols-1 gap-4">
                                            @foreach($permissions as $permission)
                                                <label wire:key="perm-{{ $permission->id }}"
                                                    class="relative flex items-center p-5 bg-white border border-slate-100 rounded-2xl hover:border-primary/20 hover:shadow-lg transition-all cursor-pointer group">
                                                    <div class="relative flex h-6 items-center">
                                                        <input wire:model="selectedPermissions" value="{{ $permission->id }}"
                                                            type="checkbox"
                                                            class="h-6 w-6 rounded-lg border-slate-200 text-primary focus:ring-4 focus:ring-primary/10 transition-all cursor-pointer">
                                                    </div>
                                                    <div class="ml-4 flex flex-col">
                                                        <span
                                                            class="text-[10px] font-black text-slate-900 uppercase tracking-tight group-hover:text-primary transition-colors">{{ $permission->name }}</span>
                                                        <span
                                                            class="text-[8px] font-mono font-bold text-slate-400 mt-1 uppercase">{{ $permission->key }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex gap-4">
                    <button wire:click="save" type="button"
                        class="flex-1 px-6 py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                        Establish Protocol Link
                    </button>
                    <button @click="show = false" type="button"
                        class="px-6 py-5 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95">
                        Cancel Orchestration
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>