<div class="px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-3xl tracking-tight text-slate-900 mb-2">
                Active <span class="text-primary">Sessions</span>
            </h1>
            <p class="text-sm font-medium text-slate-500">Real-time tracking of authenticated users across the Identity
                Hub.</p>
        </div>
        <div class="flex gap-4">
            <button wire:click="purgeGuestSessions"
                class="px-6 py-3 bg-white text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-100 rounded-xl hover:text-red-500 hover:border-red-100 transition-all">
                Prue Guests
            </button>
        </div>
    </div>

    <!-- Sessions List -->
    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Real-time Status</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tracking
                {{ $sessions->total() }} active users</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">User</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">IP Address
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Browser
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Last
                            Activity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs">
                    @forelse($sessions as $session)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-[10px]">
                                        {{ substr($session->user?->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-900">{{ $session->user?->name ?? 'Unknown' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $session->user?->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 font-mono text-slate-500 tracking-tight">
                                {{ $session->ip_address }}
                            </td>
                            <td class="px-8 py-4 text-slate-400 max-w-[200px] truncate" title="{{ $session->user_agent }}">
                                {{ Str::limit($session->user_agent, 40) }}
                            </td>
                            <td class="px-8 py-4 font-medium text-slate-400">
                                {{ $session->last_activity->diffForHumans() }}
                            </td>
                            <td class="px-8 py-4 text-right">
                                <button wire:click="terminateSession('{{ $session->id }}')"
                                    wire:confirm="Are you sure you want to terminate this session? The user will be logged out."
                                    class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">No Active User Sessions
                                        Found</span>
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