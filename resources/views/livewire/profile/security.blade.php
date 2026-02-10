<div class="px-4 py-8">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-3xl tracking-tight text-slate-900 mb-2 leading-tight">
            Security <span class="text-primary">Protocols</span>
        </h1>
        <p class="text-sm font-semibold text-slate-500">Configure your identity protection and access safeguards for the
            Nexus cluster.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- 2FA Module -->
        <div class="premium-card rounded-[2.5rem] p-10 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>

            <div class="flex items-start justify-between mb-8 relative">
                <div
                    class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center border border-primary/10 shadow-sm transition-transform hover:scale-105 duration-300">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                @if($user->two_factor_confirmed_at)
                    <span
                        class="px-3 py-1.5 bg-emerald-50 text-emerald-600 font-black text-[8px] uppercase tracking-widest rounded-lg ring-1 ring-emerald-500/20 shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full anime-pulse"></span>
                        Shield Active
                    </span>
                @else
                    <span
                        class="px-3 py-1.5 bg-slate-50 text-slate-400 font-black text-[8px] uppercase tracking-widest rounded-lg ring-1 ring-slate-200">
                        Vulnerable
                    </span>
                @endif
            </div>

            <h3 class="text-xs font-black tracking-[0.2em] mb-4 uppercase text-slate-900 border-b border-slate-50 pb-4">
                Access Shield (MFA)</h3>
            <p class="text-[11px] font-semibold text-slate-500 leading-relaxed mb-10 mr-4">Add a cryptographic
                verification layer to your identity records by requiring biometric or token-based authorization.</p>

            @if($user->two_factor_confirmed_at)
                <button wire:click="disable2fa"
                    wire:confirm="Are you sure you want to disable the Access Shield? This reduces your account security significantly."
                    class="inline-flex items-center px-8 py-4 bg-rose-50 text-rose-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 transition-all border border-rose-100">
                    Deactivate Shield
                </button>
            @else
                <button wire:click="enable2fa"
                    class="inline-flex items-center px-8 py-4 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                    Activate Protocol
                </button>
            @endif
        </div>

        <!-- Authorized Devices Module -->
        <div class="premium-card rounded-[2.5rem] p-10 relative overflow-hidden group">
            <div class="flex items-start justify-between mb-8">
                <div
                    class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center border border-primary/10 shadow-sm transition-transform hover:scale-105 duration-300">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 003 11c0-5.523 4.477-10 10-10s10 4.477 10 10a9.985 9.985 0 01-2.017 5.992l.053.09M10 11V7a2 2 0 114 0v4a2 2 0 11-4 0z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-xs font-black tracking-[0.2em] mb-4 uppercase text-slate-900 border-b border-slate-50 pb-4">
                Authorized Nodes</h3>
            <p class="text-[11px] font-semibold text-slate-500 leading-relaxed mb-10 mr-4">Review and manage the
                communication endpoints that are currently bridged to your identity across the cluster.</p>

            <div class="space-y-4">
                <div
                    class="flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-slate-100 transition-colors hover:bg-white group-hover:border-primary/10 group-hover:shadow-sm">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-2.5 h-2.5 rounded-full bg-emerald-500 anime-pulse shadow-[0_0_8px_rgba(16,185,129,0.4)]">
                        </div>
                        <span class="text-[10px] font-black text-slate-700 uppercase tracking-tight">Active Connection -
                            Current Terminal</span>
                    </div>
                    <span
                        class="text-[9px] font-bold text-slate-400 uppercase bg-white px-2 py-1 rounded-lg border border-slate-100">IP:
                        {{ request()->ip() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recovery Codes Modal -->
    <div x-data="{ open: @entangle('showRecoveryModal') }" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-md" x-cloak>
        <div
            class="bg-white rounded-[3rem] max-w-lg w-full p-12 shadow-2xl overflow-hidden relative text-center border border-slate-100 anime-in-scale">
            <div
                class="w-24 h-24 bg-emerald-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 ring-8 ring-emerald-50 transition-transform hover:rotate-6 duration-500">
                <svg class="w-12 h-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase tracking-tight">Identity Secured</h3>
            <p class="text-sm font-semibold text-slate-500 mb-10 leading-relaxed px-4">Access Shield is now active for
                your identity cluster. Store these emergency bypass tokens in a secondary safe. They are required if
                your primary MFA node becomes unavailable.</p>

            <div class="grid grid-cols-2 gap-4 mb-10">
                @foreach($recoveryCodes as $code)
                    <div
                        class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-[10px] font-mono font-black text-primary uppercase tracking-tighter">
                        {{ $code }}
                    </div>
                @endforeach
            </div>

            <button @click="open = false"
                class="w-full py-5 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10 active:scale-95">
                Tokens Safely Stored
            </button>
        </div>
    </div>
</div>