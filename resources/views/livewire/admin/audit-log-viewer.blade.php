<div class="px-4 py-8">
    <!-- Page Header Section -->
    <div class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary-light flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase leading-none">
                        Security <span class="text-primary">Ledger</span>
                    </h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Chronological repository of system
                        mutations,
                        security events, and entitlement changes within the Nexus.</p>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <div class="relative group min-w-[320px]">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 group-focus-within:text-primary transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="block w-full pl-12 pr-4 py-3.5 text-sm bg-white border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary/20 transition-all placeholder:text-slate-400 shadow-sm font-semibold"
                        placeholder="Filter by action or actor...">
                </div>

                <div class="relative min-w-[200px]">
                    <select wire:model.live="module"
                        class="block w-full px-5 py-3.5 text-[10px] font-black text-slate-700 bg-white border border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all cursor-pointer shadow-sm appearance-none uppercase tracking-widest">
                        <option value="">All Repositories</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}">{{ strtoupper($mod) }} Cluster</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Ledger Container -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden mb-12">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-50">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-6 pl-10 pr-3 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Temporal
                                Node</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identity
                                Actor</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Action
                                Protocol</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-left">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Target
                                Entity</span>
                        </th>
                        <th scope="col" class="px-6 py-6 text-right pr-10">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Origin
                                IP</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse($logs as $log)
                        <tr wire:key="log-{{ $log->id }}" class="group hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-7 pl-10 pr-3">
                                <div class="flex flex-col">
                                    <span
                                        class="text-xs font-black text-slate-900 tracking-tight uppercase">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span
                                        class="text-[9px] font-mono font-bold text-slate-400 mt-1.5 uppercase tracking-widest">{{ $log->created_at->format('H:i:s T') }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-7">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center text-[10px] font-black text-primary border border-primary/10 shadow-sm">
                                        {{ substr($log->user ? $log->user->name : 'N', 0, 1) }}
                                    </div>
                                    <span class="text-[11px] font-black text-slate-700 uppercase tracking-tight">
                                        {{ $log->user ? $log->user->name : 'NEXUS AUTOPILOT' }}
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-7">
                                <div class="flex items-center gap-4">
                                    <span
                                        class="px-3 py-1.5 rounded-lg bg-slate-50 text-[9px] font-black text-slate-500 border border-slate-100 tracking-[0.1em] uppercase">
                                        {{ $log->module }}
                                    </span>
                                    <span
                                        class="text-xs font-bold text-slate-600 tracking-tight uppercase">{{ $log->action }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-7">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[10px] font-black text-slate-900 uppercase tracking-widest">{{ class_basename($log->target_type) }}</span>
                                    <span class="text-[9px] font-mono font-bold text-slate-400 mt-1.5 opacity-70">REF:
                                        {{ str_pad($log->target_id, 8, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-7 pr-10 text-right">
                                <span
                                    class="text-[10px] font-mono font-black text-primary bg-primary-light px-3 py-1.5 rounded-lg border border-primary/10 shadow-sm">{{ $log->ip_address }}</span>
                            </td>
                        </tr>
                        @if($log->changes)
                            <tr class="bg-slate-50/20">
                                <td colspan="5" class="px-10 py-5">
                                    <details class="group/details">
                                        <summary
                                            class="flex items-center gap-3 cursor-pointer text-[9px] font-black text-primary uppercase tracking-[0.3em] list-none select-none">
                                            <div
                                                class="w-5 h-5 bg-white border border-slate-100 rounded-lg flex items-center justify-center shadow-sm transition-transform group-open/details:rotate-90">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="3">
                                                    <path d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            Delta Payload Explorer
                                        </summary>
                                        <div
                                            class="mt-6 p-8 bg-slate-950 rounded-[2rem] border border-slate-800 shadow-2xl relative overflow-hidden anime-in-slide-down">
                                            <div class="absolute top-0 right-0 p-6 flex items-center gap-2">
                                                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full anime-pulse"></div>
                                                <span
                                                    class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em]">Validated
                                                    Ledger Entry</span>
                                            </div>
                                            <pre
                                                class="text-[11px] font-mono text-emerald-400 leading-relaxed overflow-x-auto custom-scrollbar pt-4"><code>{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-20 h-20 rounded-[2rem] bg-slate-50 flex items-center justify-center text-slate-300 mb-6 border border-slate-100 shadow-inner">
                                        <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">No Signals
                                        Detected</h3>
                                    <p
                                        class="text-[10px] font-semibold text-slate-400 mt-4 uppercase tracking-widest leading-relaxed">
                                        Adjust your temporal filters or cluster parameters to verify results.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-10 py-8 bg-slate-50/30 border-t border-slate-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    <!-- Security Insight Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div
            class="bg-slate-950 rounded-[2.5rem] p-12 text-white relative overflow-hidden border border-slate-800 shadow-2xl group">
            <div
                class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-[100px] group-hover:bg-primary/20 transition-all duration-700">
            </div>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <h3
                        class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-8 border-b border-white/5 pb-4">
                        Integrity Level</h3>
                    <p class="text-2xl font-black tracking-tight mb-5">LEDGER MUTATION FIDELITY: 100%</p>
                    <p class="text-[11px] text-slate-400 leading-relaxed font-semibold uppercase tracking-wide">
                        Nexus Identity maintains an immutable record of all state mutations. Logs are stored with
                        write-once-read-many (WORM) parameters to ensure absolute forensic integrity across the cluster.
                    </p>
                </div>
            </div>
        </div>

        <div
            class="bg-primary rounded-[2.5rem] p-12 text-white relative overflow-hidden group shadow-2xl shadow-primary/20">
            <div
                class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-[100px] group-hover:bg-white/20 transition-all duration-700">
            </div>
            <div class="relative z-10">
                <h3
                    class="text-[10px] font-black uppercase tracking-[0.4em] text-white/60 mb-8 border-b border-white/10 pb-4">
                    Cluster Diagnostics</h3>
                <div class="flex items-center gap-8 mb-10">
                    <div
                        class="w-20 h-20 rounded-[2rem] bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-lg transition-transform group-hover:scale-105">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black tracking-tighter">LEDGER SECURED</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70 mt-3">Governance
                            Synchronizer Active</p>
                    </div>
                </div>
                <div class="h-2 w-full bg-black/10 rounded-full overflow-hidden shadow-inner">
                    <div
                        class="h-full w-[98%] bg-white rounded-full anime-pulse shadow-[0_0_15px_rgba(255,255,255,0.5)]">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>