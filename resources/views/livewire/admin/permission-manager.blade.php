<div class="px-4 py-8">
    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase leading-none">
                        Capability <span class="text-primary">Matrix</span>
                    </h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Defining granular entitlement keys and
                        security scopes for the global access mesh.</p>
                </div>
            </div>

            <button wire:click="create"
                class="inline-flex items-center justify-center px-6 py-3 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                <svg class="mr-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Define Capability
            </button>
        </div>
    </div>

    <!-- Permissions Table Container -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-6 pl-10 pr-3 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Security Key
                                (Key)</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Cluster
                                Designation</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Capability
                                Description</span>
                        </th>
                        <th scope="col" class="relative py-6 pl-3 pr-10 text-right">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operations</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @foreach($permissions as $permission)
                        <tr wire:key="permission-{{ $permission->id }}"
                            class="group hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-6 pl-10 pr-3">
                                <span
                                    class="px-3 py-1.5 rounded-lg bg-slate-50 text-[10px] font-mono font-black text-slate-500 border border-slate-100 uppercase tracking-tight">
                                    {{ $permission->key }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6">
                                <span
                                    class="px-3 py-1.5 rounded-lg bg-primary-light text-[9px] font-black text-primary border border-primary/10 tracking-widest uppercase">
                                    {{ $permission->group }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6 font-semibold text-xs text-slate-500">
                                {{ $permission->name }}
                            </td>
                            <td class="whitespace-nowrap py-6 pl-3 pr-10 text-right space-x-2">
                                <button wire:click="edit({{ $permission->id }})"
                                    class="p-2 text-slate-300 hover:text-primary hover:bg-primary-light rounded-xl transition-all active:scale-95">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $permission->id }})"
                                    wire:confirm="Purging this capability will disrupt associated protocol entitlements. Proceed?"
                                    class="p-2 text-slate-200 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all active:scale-95">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($permissions->hasPages())
            <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-50">
                {{ $permissions->links() }}
            </div>
        @endif
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
                class="inline-block align-bottom bg-white rounded-[2.5rem] p-12 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100 relative z-10">
                <div>
                    <div class="flex items-center gap-5 mb-10">
                        <div
                            class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center text-primary shadow-sm">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight leading-none"
                            id="modal-title">
                            {{ $isEditing ? 'Sync Capability' : 'Map Authority' }}
                        </h3>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 ml-1">Capability
                                Alias</label>
                            <input wire:model="name" type="text"
                                class="block w-full px-5 py-4 text-sm font-black bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all shadow-sm"
                                placeholder="e.g. Purge Security Logs">
                            @error('name') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase letter-spacing-wider">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 ml-1">System
                                Handle (Slug)</label>
                            <input wire:model="key" type="text"
                                class="block w-full px-5 py-4 text-sm font-mono font-black bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all shadow-sm"
                                placeholder="e.g. security.logs.purge">
                            @error('key') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase letter-spacing-wider">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 ml-1">Cluster
                                Repository</label>
                            <div class="relative">
                                <input wire:model="group" type="text" list="groups"
                                    class="block w-full px-5 py-4 text-sm font-black bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all shadow-sm"
                                    placeholder="e.g. Identity Governance">
                                <datalist id="groups">
                                    <option value="Identity Management">
                                    <option value="Protocol & Matrix Regulation">
                                    <option value="Satellite Access">
                                    <option value="System Hub">
                                    <option value="Security Ledger">
                                </datalist>
                                <div
                                    class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('group') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase letter-spacing-wider">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex gap-4">
                    <button wire:click="save" type="button"
                        class="flex-1 px-6 py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                        {{ $isEditing ? 'Sync Matrix Node' : 'Initialize Capability' }}
                    </button>
                    <button @click="show = false" type="button"
                        class="px-6 py-5 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all active:scale-95">
                        Discard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>