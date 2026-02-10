<div class="px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl tracking-tight text-slate-900 mb-2">
                SSO <span class="text-primary">Replay Protection</span>
            </h1>
            <p class="text-sm font-medium text-slate-500">Audit trail of unique token identifiers (JTIs) used for
                single sign-on flows.</p>
        </div>
        <button wire:click="purgeExpired"
            class="px-6 py-3 bg-white text-[10px] font-black uppercase tracking-widest text-primary border border-slate-100 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
            Purge Expired JTIs
        </button>
    </div>

    <!-- Sessions List -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Active Protection Vault</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tracking live token
                usage</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Unique
                            Identifier (JTI)</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Used At
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Protection
                            Expires</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs">
                    @forelse($sessions as $session)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4 font-mono text-primary font-bold uppercase tracking-tight">
                                {{ $session->jti }}
                            </td>
                            <td class="px-8 py-4 font-medium text-gray-500">{{ $session->created_at->format('M d, H:i:s') }}
                            </td>
                            <td class="px-8 py-4 font-medium text-gray-400 italic">
                                {{ $session->expires_at->diffForHumans() }}
                            </td>
                            <td class="px-8 py-4">
                                @if($session->expires_at > now())
                                    <span
                                        class="px-2 py-1 rounded bg-emerald-50 text-emerald-600 font-black text-[8px] uppercase ring-1 ring-emerald-500/20">Active
                                        Protection</span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded bg-gray-50 text-gray-400 font-black text-[8px] uppercase ring-1 ring-gray-200">Expired</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Protection Vault is
                                        Empty</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/50">
            {{ $sessions->links() }}
        </div>
    </div>
</div>