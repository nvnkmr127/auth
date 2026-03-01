<div class="px-4 py-8">
    <div class="mb-12">
        <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
            Trusted <span class="text-primary">Devices</span>
        </h1>
        <p class="text-sm font-semibold text-slate-500 mt-2">Manage devices that have accessed your account. Unrecognized devices should be removed immediately.</p>
    </div>

    <div class="premium-card rounded-[2.5rem] overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Active History</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tracking {{ $devices->count() }} devices</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Device / Browser</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">IP Address</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Last Active</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs">
                    @forelse($devices as $device)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                                        @if(Str::contains(strtolower($device->user_agent), ['iphone', 'android', 'mobile']))
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-col max-w-[200px]">
                                        <span class="font-bold text-slate-900 truncate">{{ $device->user_agent }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 font-mono text-slate-500 tracking-tight">
                                {{ $device->ip_address }}
                            </td>
                            <td class="px-8 py-4 font-medium text-slate-400">
                                {{ $device->last_active_at->diffForHumans() }}
                            </td>
                            <td class="px-8 py-4">
                                @if($device->is_trusted)
                                    <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-600 font-black text-[8px] uppercase ring-1 ring-emerald-500/20">Trusted</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-amber-50 text-amber-600 font-black text-[8px] uppercase ring-1 ring-amber-500/20">Recognized</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-right">
                                <div class="flex items-center gap-2">
                                    @if(!$device->is_trusted)
                                        <button wire:click="trustDevice({{ $device->id }})" class="p-2 text-slate-300 hover:text-emerald-500 transition-colors" title="Trust this device">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    @endif
                                    <button wire:click="removeDevice({{ $device->id }})" wire:confirm="Remove this device? You will be logged out on that device." class="p-2 text-slate-300 hover:text-red-500 transition-colors" title="Remove device">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-300 uppercase font-black text-[10px] tracking-widest">No devices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
