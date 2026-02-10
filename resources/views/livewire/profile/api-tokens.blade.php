<div class="px-4 py-8">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-3xl tracking-tight text-slate-900 mb-2 leading-tight">
            Signal <span class="text-primary">Tokens</span>
        </h1>
        <p class="text-sm font-semibold text-slate-500">Manage your access keys for programmatic communication with
            Nexus Identity services.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Create Token Form -->
        <div class="lg:col-span-1">
            <div class="premium-card rounded-[2.5rem] p-8">
                <h3
                    class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-8 border-b border-slate-50 pb-4">
                    Generate Link Token</h3>
                <form wire:submit.prevent="createToken" class="space-y-6">
                    <div>
                        <label
                            class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Token
                            Label</label>
                        <input type="text" wire:model="name" placeholder="e.g. Cluster Nexus Port"
                            class="block w-full px-5 py-4 bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all text-sm font-bold placeholder:text-slate-400">
                        @error('name') <span
                            class="text-[10px] font-bold text-rose-500 mt-2 block ml-1 uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center items-center px-6 py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                        Execute Generation
                    </button>
                </form>
            </div>
        </div>

        <!-- Token List -->
        <div class="lg:col-span-2">
            <div class="premium-card rounded-[2.5rem] overflow-hidden">
                <div class="px-10 py-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">Active Signal Channels
                    </h3>
                </div>
                <div class="divide-y divide-slate-50 text-xs">
                    @forelse($tokens as $token)
                        <div
                            class="px-10 py-7 flex items-center justify-between group hover:bg-slate-50/50 transition-colors">
                            <div>
                                <p class="font-bold text-slate-900 uppercase tracking-tight mb-2 flex items-center gap-2">
                                    {{ $token->name }}
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full anime-pulse"></span>
                                </p>
                                <div class="flex items-center gap-4 text-slate-400 font-semibold text-[10px]">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $token->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Standby' }}
                                    </span>
                                </div>
                            </div>
                            <button wire:click="revokeToken({{ $token->id }})"
                                wire:confirm="Are you sure you want to sever this signal token? Programmatic connectivity will be lost."
                                class="px-6 py-2.5 text-[10px] font-black text-rose-500 hover:bg-rose-50 border border-slate-100/50 hover:border-rose-100 rounded-xl uppercase tracking-widest transition-all opacity-0 group-hover:opacity-100 active:scale-95 shadow-sm">
                                Sever Channel
                            </button>
                        </div>
                    @empty
                        <div class="py-24 flex flex-col items-center justify-center text-slate-300">
                            <div
                                class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center mb-6 border border-slate-100">
                                <svg class="w-10 h-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">No Signal Tokens
                                Detected</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Token Success Modal -->
    <div x-data="{ open: @entangle('showTokenModal') }" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md" x-cloak>
        <div
            class="bg-white rounded-[3rem] max-w-lg w-full p-12 shadow-2xl overflow-hidden relative border border-slate-100 anime-in-scale">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-primary/10 rounded-full blur-3xl"></div>

            <h3 class="text-2xl font-black text-slate-900 mb-4 relative uppercase tracking-tight">Channel Key Ready</h3>
            <p class="text-sm font-semibold text-slate-500 mb-10 leading-relaxed">Cryptographic link established. Copy
                your signal token now. For your security, this sequence will be permanently masked after graduation.</p>

            <div
                class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 flex flex-col gap-6 mb-10 group relative">
                <code
                    class="text-primary font-mono text-xs break-all leading-relaxed font-black">{{ $plainTextToken }}</code>
                <button
                    x-on:click="navigator.clipboard.writeText('{{ $plainTextToken }}'); $dispatch('notify', {message: 'Token hashed and copied!'})"
                    class="absolute top-4 right-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-200 text-slate-400 hover:text-primary hover:shadow-lg transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>
            </div>

            <button @click="open = false"
                class="w-full py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                Channel Hashed and Stored
            </button>
        </div>
    </div>
</div>