<div class="px-4 py-8">
    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl tracking-tight leading-tight">
                        User <span class="text-primary">Management</span>
                    </h1>
                    <p class="text-sm font-medium text-slate-500 mt-1">Manage and view all registered users and their
                        permissions.</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <!-- Search & Controls -->
                <div class="relative group min-w-[300px]">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 group-focus-within:text-primary transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="block w-full pl-12 pr-4 py-3 text-sm bg-white border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary/20 transition-all placeholder:text-slate-400 shadow-sm"
                        placeholder="Search users...">
                </div>

                <button wire:click="create"
                    class="inline-flex items-center justify-center px-6 py-3 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                    <svg class="mr-2 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Identities Table Container -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-6 pl-10 pr-3 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">User
                                Name</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Email
                                Address</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">App
                                Access</span>
                        </th>
                        <th scope="col" class="relative py-6 pl-3 pr-10 text-right">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @foreach($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-6 pl-10 pr-3">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-primary-light flex items-center justify-center border border-primary/10 text-primary font-black text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ $user->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">ID:
                                            {{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6">
                                <span class="text-xs font-semibold text-slate-500">{{ $user->email }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-6">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-3 py-1 rounded-lg bg-primary-light text-[10px] font-black text-primary border border-primary/10 tracking-widest uppercase">
                                        {{ $user->app_accesses_count }} APPS
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-6 pl-3 pr-10 text-right space-x-2">
                                <a href="{{ route('admin.user.apps', $user) }}"
                                    class="inline-flex items-center px-4 py-2 bg-slate-50 text-slate-900 border border-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white hover:shadow-lg transition-all">
                                    Manage Access
                                </a>
                                <button wire:click="edit({{ $user->id }})"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary-light rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002-2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $user->id }})"
                                    wire:confirm="Are you sure you want to delete this user? All their data and permissions will be removed."
                                    class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
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

        <!-- Pagination Section -->
        @if($users->hasPages())
            <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-50">
                {{ $users->links() }}
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
                class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>

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
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight text-slate-900" id="modal-title">
                            {{ $isEditing ? 'Edit User' : 'Add New User' }}
                        </h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Full
                                Name</label>
                            <input wire:model="name" type="text"
                                class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                                placeholder="e.g. John Doe">
                            @error('name') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email
                                Address</label>
                            <input wire:model="email" type="email"
                                class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                                placeholder="john@example.com">
                            @error('email') <span
                                class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                                <input wire:model="password" type="password"
                                    class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                                    placeholder="••••••••">
                                @error('password') <span
                                    class="text-rose-500 text-[10px] font-bold mt-2 block ml-1 uppercase">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Confirm
                                    Password</label>
                                <input wire:model="password_confirmation" type="password"
                                    class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                                    placeholder="••••••••">
                            </div>
                        </div>
                        @if($isEditing)
                            <p class="text-[10px] text-slate-400 font-medium ml-1 mt-2"> Leave password fields empty to keep
                                the current password.</p>
                        @endif
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button wire:click="save" type="button"
                        class="flex-1 px-6 py-4 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                        {{ $isEditing ? 'Save Changes' : 'Add User' }}
                    </button>
                    <button @click="show = false" type="button"
                        class="px-6 py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Insights Info -->
    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="premium-card rounded-[2rem] p-8 flex items-center gap-6">
            <div class="w-12 h-12 rounded-2xl bg-primary-light flex items-center justify-center text-primary shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Users</p>
                <p class="text-xl font-black text-slate-900">Total Users: {{ $users->total() }}</p>
            </div>
        </div>

        <div class="premium-card rounded-[2rem] p-8 flex items-center gap-6 md:col-span-2">
            <div
                class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <p class="text-xs font-semibold text-slate-500 leading-relaxed">
                All account changes are tracked in the <a href="{{ route('admin.audit-logs') }}"
                    class="text-primary font-black hover:underline uppercase tracking-tight">Audit Logs</a>.
            </p>
        </div>
    </div>
</div>