<div class="px-4 py-8">
    <div class="mb-12">
        <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
            Portal <span class="text-primary">Settings</span>
        </h1>
        <p class="text-sm font-semibold text-slate-500 mt-2">Configure system-wide integrations and visual experience.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Integration Form -->
        <div class="space-y-8">
            <div class="premium-card rounded-[2.5rem] p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold tracking-tight text-slate-900">Watxio Gateway</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status: <span
                                class="text-emerald-500">Connected</span></p>
                    </div>
                </div>

                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dispatch
                            Endpoint</label>
                        <input wire:model="webhookUrl" type="url"
                            class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                            placeholder="https://flow.watxio.com/api/webhook/otp">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">X-API-KEY</label>
                        <input wire:model="apiKey" type="password"
                            class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all font-mono"
                            placeholder="••••••••••••••••">
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full px-6 py-4 bg-slate-950 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all shadow-xl shadow-primary/10">
                            Update Integration
                        </button>
                    </div>
                </form>
            </div>

            <!-- Frontend Experience -->
            <div class="premium-card rounded-[2.5rem] p-10">
                <div class="flex items-center gap-4 mb-8">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold tracking-tight text-slate-900">Frontend Branding</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Logo & Experience
                            Settings</p>
                    </div>
                </div>

                <form wire:submit="saveBranding" class="space-y-6">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Application
                            Name</label>
                        <input wire:model="appName" type="text"
                            class="block w-full px-5 py-4 text-sm font-bold bg-slate-50 border-slate-100 rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-white focus:border-primary/20 transition-all"
                            placeholder="e.g. OneStudio">
                    </div>

                    <div
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-amber-100 transition-all">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900">Interactive Mascot</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Enable
                                StudioBot on Login</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="mascotEnabled"
                                class="w-5 h-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full px-6 py-4 bg-slate-50 text-slate-900 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white hover:shadow-lg transition-all">
                            Apply UI Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Documentation Card -->
        <div class="space-y-8">
            <div class="premium-card rounded-[2.5rem] p-10 bg-slate-900 border-slate-800 text-slate-300">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Watxio Integration Guide
                </h3>

                <div class="space-y-4 text-sm leading-relaxed">
                    <p>Connect your **OneStudio** identity node to the **Watxio Flow** system using the provided
                        endpoint. This service handles all secure communications for login authorizations.</p>

                    <div
                        class="bg-black/30 rounded-2xl p-6 font-mono text-xs text-secondary-light border border-white/5 overflow-x-auto">
                        <span class="text-primary italic">// API Dispatch Schema</span><br>
                        POST /api/webhook/otp HTTP/1.1<br>
                        Host: flow.watxio.com<br>
                        Content-Type: application/json<br>
                        X-API-KEY: ************<br><br>
                        {<br>
                        &nbsp;&nbsp;"phone": "91XXXXXXXXXX",<br>
                        &nbsp;&nbsp;"message": "Authorized Code: *123456*",<br>
                        }
                    </div>

                    <div class="flex items-start gap-4 mt-8">
                        <div
                            class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-primary shrink-0 font-black text-xs">
                            1</div>
                        <p class="text-xs">Update your **VITE_APP_NAME** to reflect the branding changes globally.</p>
                    </div>
                </div>
            </div>

            <!-- Webhook Tester -->
            <div class="premium-card rounded-[2.5rem] p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Webhook Tester</h4>
                        <p class="text-[10px] font-bold text-slate-400">Validate your Watxio connection</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <input wire:model="testPhone" type="text"
                        class="block w-full px-4 py-3 text-xs font-bold bg-slate-50 border-slate-100 rounded-xl focus:ring-4 focus:ring-primary/10 focus:border-primary/20 transition-all"
                        placeholder="Recipient Phone (e.g. 91...)">

                    <button wire:click="testWebhook" wire:loading.attr="disabled"
                        class="w-full px-5 py-3 bg-slate-950 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary transition-all shadow-lg shadow-primary/10 flex items-center justify-center gap-2">
                        <span wire:loading.remove>Disptach Test Message</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>