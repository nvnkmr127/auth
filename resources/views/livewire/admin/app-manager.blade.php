<div class="px-4 py-8">
    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
                        Registered <span class="text-primary">Apps</span>
                    </h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Manage and configure all registered
                        applications in the system.</p>
                </div>
            </div>

            <button wire:click="create"
                class="inline-flex items-center px-6 py-3 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                <svg class="mr-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 4v16m8-8H4" />
                </svg>
                Add New App
            </button>
        </div>
    </div>

    <!-- Apps Table Container -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-6 pl-10 pr-3 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">App
                                Name</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Website
                                URL</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</span>
                        </th>
                        <th scope="col" class="relative py-6 pl-3 pr-10 text-right">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @foreach ($apps as $app)
                        <tr wire:key="app-{{ $app->id }}" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-6 pl-10 pr-3">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center border border-primary/10 text-primary font-black text-xs">
                                        {{ substr($app->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ $app->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold">ID:
                                            {{ strtoupper($app->slug) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6">
                                <span
                                    class="text-xs font-bold text-slate-500 font-mono tracking-tight">{{ $app->domain }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6">
                                @if($app->status === 'active')
                                    <span
                                        class="px-3 py-1.5 rounded-lg bg-emerald-50 text-[9px] font-black text-emerald-600 border border-emerald-100/50 tracking-widest uppercase flex items-center gap-1.5 w-fit">
                                        <span class="w-1 h-1 bg-emerald-500 rounded-full anime-pulse"></span>
                                        Operational
                                    </span>
                                @elseif($app->status === 'maintenance')
                                    <span
                                        class="px-3 py-1.5 rounded-lg bg-amber-50 text-[9px] font-black text-amber-600 border border-amber-100/50 tracking-widest uppercase flex items-center gap-1.5 w-fit">
                                        <span class="w-1 h-1 bg-amber-500 rounded-full"></span>
                                        Maintenance
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 text-[9px] font-black text-slate-400 border border-slate-200 tracking-widest uppercase flex items-center gap-1.5 w-fit">
                                        <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap py-6 pl-3 pr-10 text-right space-x-2">
                                <button wire:click="syncConfig({{ $app->id }})" title="Sync Roles & Permissions"
                                    class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all active:scale-95">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <button wire:click="edit({{ $app->id }})"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary-light rounded-xl transition-all active:scale-95">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $app->id }})"
                                    wire:confirm="Are you sure you want to delete this app? This will stop it from connecting to the system."
                                    class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all active:scale-95">
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

        @if($apps->hasPages())
            <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-50">
                {{ $apps->links() }}
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
                class="inline-block align-bottom bg-white rounded-[2.5rem] p-10 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100 relative z-10">
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 rounded-2xl bg-primary-light flex items-center justify-center text-primary shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight" id="modal-title">
                            {{ $isEditing ? 'Edit App' : 'Add New App' }}
                        </h3>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">App
                                    Name</label>
                                <input wire:model="name" type="text"
                                    class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                                    placeholder="e.g. Sales Portal">
                                @error('name') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">App
                                    ID (Slug)</label>
                                <input wire:model="slug" type="text"
                                    class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                                    placeholder="sales-portal">
                                @error('slug') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Redirect
                                URL (Domain)</label>
                            <input wire:model="domain" type="url"
                                class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                                placeholder="https://app.example.com">
                            @error('domain') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Sync
                                Token (Secret)</label>
                            <input wire:model="sync_token" type="text"
                                class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                                placeholder="Enter a secret token for syncing roles">
                            <p class="text-[9px] text-slate-400 mt-2 ml-1">Must match 'sync_token' in Consumer app
                                config.</p>
                            @error('sync_token') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">App
                                Status</label>
                            <select wire:model="status"
                                class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all cursor-pointer appearance-none">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-5 flex items-center pt-8 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error('status') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button wire:click="save" type="button"
                        class="flex-1 px-6 py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                        Save App
                    </button>
                    <button @click="show = false" type="button"
                        class="px-6 py-5 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>