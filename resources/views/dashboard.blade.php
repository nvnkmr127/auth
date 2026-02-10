<x-layouts.app>
    <div class="mb-10">
        <div class="flex items-center gap-6 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 uppercase">
                    System <span class="text-indigo-600">Settings</span>
                </h1>
                <p class="text-sm font-medium text-gray-500 mt-1">Manage your workspace configuration and tools.</p>
            </div>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- WhatsApp API -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 border border-emerald-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">WhatsApp API</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Connect your Meta Business account, manage
                    phone numbers, and profile.</p>
            </div>
        </a>

        <!-- AI Brain -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 border border-blue-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0012 18.75c-1.03 0-1.9-.4-2.593-.903l-.547-.547z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">AI Brain</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Train your AI, manage knowledge base
                    documents, and configure auto-replies.</p>
            </div>
        </a>

        <!-- Chat Routing -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center mb-6 border border-purple-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Chat Routing</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Configure how chats are assigned to agents
                    and teams.</p>
            </div>
        </a>

        <!-- Quick Replies -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center mb-6 border border-orange-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Quick Replies</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Manage canned responses for frequently
                    asked questions.</p>
            </div>
        </a>

        <!-- Taxonomy -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center mb-6 border border-rose-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Taxonomy</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Manage contact categories, chat tags, and
                    conversation labels.</p>
            </div>
        </a>

        <!-- Environment -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center mb-6 border border-gray-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Environment</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Global system settings, email
                    configuration, and core preferences.</p>
            </div>
        </a>

        <!-- Security -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-6 border border-amber-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Security</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Compliance registry, audit logs, and data
                    security policies.</p>
            </div>
        </a>

        <!-- Backups -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center mb-6 border border-cyan-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Backups</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">Manage system snapshots, automated backups,
                    and data restoration.</p>
            </div>
        </a>

        <!-- Dev Tools -->
        <a href="#"
            class="group relative bg-white p-8 rounded-[2.5rem] border border-gray-100/60 shadow-sm shadow-gray-200/40 hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-100 transition-all duration-300">
            <div class="flex flex-col h-full">
                <div
                    class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-6 border border-slate-100/50 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-3">Dev Tools</h3>
                <p class="text-xs font-medium text-gray-400 leading-relaxed">API Tokens, Webhook management, and
                    technical documentation.</p>
            </div>
        </a>
    </div>
</x-layouts.app>