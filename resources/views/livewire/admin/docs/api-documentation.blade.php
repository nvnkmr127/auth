<div class="min-h-screen">
    {{-- Decorative Background Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-amber-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] bg-orange-500/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="hidden lg:flex flex-col w-72 bg-slate-950 border-r border-slate-900 shrink-0">
            <div class="p-6">
                <div class="flex items-center gap-3 px-2 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-amber-600 flex items-center justify-center shadow-lg shadow-amber-600/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-slate-200">API Documentation</span>
                </div>

                <nav class="space-y-1">
                    @php
                        $navItems = [
                            ['id' => 'introduction', 'label' => 'Introduction', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['id' => 'concepts', 'label' => 'Core Concepts', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                            ['id' => 'integration', 'label' => 'Integration Guide', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                            ['id' => 'middleware', 'label' => 'Middleware Setup', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                            ['id' => 'workflows', 'label' => 'Example Workflows', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            ['id' => 'error-handling', 'label' => 'Error Handling', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['id' => 'security', 'label' => 'Security', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ['id' => 'api-reference', 'label' => 'API Reference', 'icon' => 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                        <button 
                            wire:click="setSection('{{ $item['id'] }}')"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm font-medium transition-all duration-200 group rounded-xl {{ $activeSection === $item['id'] ? 'bg-amber-600/10 text-amber-500 border border-amber-600/20 shadow-[0_0_15px_rgba(217,119,6,0.1)]' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}"
                        >
                            <svg class="w-5 h-5 {{ $activeSection === $item['id'] ? 'text-amber-500' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </button>
                    @endforeach

                    <div class="pt-4 mt-4 border-t border-slate-800/50">
                        <a 
                            href="{{ route('profile.api-tokens') }}"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm font-medium text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 transition-all duration-200 group rounded-xl border border-transparent"
                        >
                            <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Manage API Tokens
                        </a>
                    </div>
                </nav>
            </div>

            <div class="mt-auto p-6 border-t border-slate-800">
                <div class="p-4 rounded-2xl bg-amber-600/5 border border-amber-600/10">
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] mb-2">Need Help?</p>
                    <p class="text-[11px] text-slate-400 font-semibold leading-relaxed">Check our support channels or view audit logs in admin.</p>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-slate-950/20 relative">
            <div class="max-w-4xl mx-auto px-6 py-12 lg:px-12">
                
                {{-- Mobile Top Bar --}}
                <div class="lg:hidden flex items-center justify-between mb-8 pb-6 border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-200">API Docs</span>
                    </div>
                    <select 
                        wire:model.live="activeSection"
                        class="bg-slate-900 border-slate-800 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5"
                    >
                        @foreach($navItems as $item)
                            <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                    @if($activeSection === 'introduction')
                        <section id="introduction">
                            <h1 class="text-4xl font-extrabold text-white mb-6 tracking-tight">Nexus Identity API</h1>
                            <div class="prose prose-invert max-w-none prose-slate">
                                <p class="text-xl text-slate-400 leading-relaxed mb-8">
                                    Welcome to the Nexus Identity API documentation. This platform provides a centralized Single Sign-On (SSO) solution for all satellite applications.
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                                    <div class="premium-card rounded-3xl p-8 bg-slate-900/50 border-slate-800 hover:border-amber-500/50 transition-all">
                                        <h3 class="text-amber-500 font-black text-xs uppercase tracking-widest mb-3">Satellite Ready</h3>
                                        <p class="text-slate-400 text-sm font-semibold">Easily integrate any web application with our JWT-based authentication system.</p>
                                    </div>
                                    <div class="premium-card rounded-3xl p-8 bg-slate-900/50 border-slate-800 hover:border-amber-500/50 transition-all">
                                        <h3 class="text-amber-500 font-black text-xs uppercase tracking-widest mb-3">Secure by Default</h3>
                                        <p class="text-slate-400 text-sm font-semibold">Built-in rate limiting, audit logging, and RSA-256 token signing.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'concepts')
                        <section id="concepts">
                            <h2 class="text-3xl font-bold text-white mb-6">Core Concepts</h2>
                            <div class="prose prose-invert max-w-none">
                                <div class="space-y-8">
                                    <div class="relative pl-8 border-l-2 border-amber-500">
                                        <h4 class="text-lg font-black text-slate-100 uppercase tracking-tight">1. Single Sign-On (SSO)</h4>
                                        <p class="text-slate-400 font-semibold">Users log in once to the central Nexus Identity server and gain access to all authorized satellite applications without re-entering credentials.</p>
                                    </div>
                                    <div class="relative pl-8 border-l-2 border-slate-700">
                                        <h4 class="text-lg font-bold text-slate-100">2. JSON Web Token (JWT)</h4>
                                        <p class="text-slate-400">All authentication data is transmitted via secure, signed JWTs. Satellite applications verify these tokens using a public RSA key.</p>
                                    </div>
                                    <div class="relative pl-8 border-l-2 border-slate-700">
                                        <h4 class="text-lg font-bold text-slate-100">3. RSA-256 Signing</h4>
                                        <p class="text-slate-400">Tokens are signed using a private key kept securely on the Nexus server. Satellite apps only need the public key to verify authenticity.</p>
                                    </div>
                                </div>
                                <div class="mt-12 p-6 rounded-2xl bg-amber-500/5 border border-amber-500/20">
                                    <h4 class="text-amber-500 font-bold flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Security Warning
                                    </h4>
                                    <p class="text-sm text-slate-400">Never share the private key. Only distribute the public key to satellite application administrators.</p>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'integration')
                        <section id="integration">
                            <h2 class="text-3xl font-bold text-white mb-6">Integration Guide</h2>
                            <p class="text-slate-400 mb-8 text-lg">Follow these steps to integrate your satellite application.</p>
                            
                            <div class="space-y-12">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-200 mb-4 flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-sm">1</span>
                                        Update Environment Variables
                                    </h3>
                                    <div class="relative group">
                                        <pre class="bg-slate-900 border border-slate-800 rounded-2xl p-6 overflow-x-auto text-slate-300 font-mono text-sm leading-relaxed shadow-2xl">
AUTH_CORE_URL=https://auth.example.com
AUTH_CORE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----"</pre>
                                        <button class="absolute top-4 right-4 p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-slate-200 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold text-slate-200 mb-4 flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 flex items-center justify-center text-sm">2</span>
                                        Create SSO Controller
                                    </h3>
                                    <div class="relative group">
                                        <pre class="bg-slate-900 border border-slate-800 rounded-2xl p-6 overflow-x-auto text-slate-300 font-mono text-sm leading-relaxed shadow-2xl">
public function callback(Request $request)
{
    $token = $request-&gt;query('token');
    
    // 1. Decode JWT with public key
    $decoded = JWT::decode($token, new Key($key, 'RS256'));

    // 2. Find or create user
    $user = User::firstOrCreate(['email' =&gt; $decoded-&gt;email]);

    // 3. Authenticate
    Auth::login($user);

    return redirect('/dashboard');
}</pre>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'middleware')
                        <section id="middleware">
                            <h2 class="text-3xl font-black text-white mb-6 uppercase tracking-tight">Middleware Setup</h2>
                            <p class="text-slate-400 mb-8 font-semibold">Force all unauthenticated users to the central login.</p>
                            
                            <div class="bg-amber-600/5 border border-amber-600/20 rounded-3xl p-8 shadow-2xl shadow-amber-900/20">
                                <pre class="text-amber-200 font-mono text-sm leading-relaxed whitespace-pre-wrap">
public function handle(Request $request, Closure $next)
{
    if (auth()->check()) {
        return $next($request);
    }

    $redirectUrl = url()->current();
    return redirect(
        env('AUTH_CORE_URL') . '/login?redirect=' . urlencode($redirectUrl)
    );
}</pre>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'workflows')
                        <section id="workflows">
                            <h2 class="text-3xl font-bold text-white mb-8">Example Workflows</h2>
                            
                            <div class="space-y-8">
                                <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800 relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-4 opacity-10">
                                        <svg class="w-24 h-24 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-indigo-400 mb-4">Workflow 1: Login & Access</h4>
                                    <ol class="space-y-4 text-slate-300 text-sm">
                                        <li class="flex gap-3">
                                            <span class="text-slate-500">01</span>
                                            User visits satellite dashboard.
                                        </li>
                                        <li class="flex gap-3">
                                            <span class="text-slate-500">02</span>
                                            Middleware redirects to Nexus Login.
                                        </li>
                                        <li class="flex gap-3">
                                            <span class="text-slate-500">03</span>
                                            User authenticates at Nexus.
                                        </li>
                                        <li class="flex gap-3">
                                            <span class="text-slate-500">04</span>
                                            Nexus generates JWT and redirects back with token.
                                        </li>
                                        <li class="flex gap-3">
                                            <span class="text-slate-500">05</span>
                                            Satellite verifies token and creates session.
                                        </li>
                                    </ol>
                                </div>

                                <div class="p-8 rounded-3xl bg-slate-900/40 border border-slate-800">
                                    <h4 class="text-xl font-bold text-indigo-400 mb-4">Workflow 2: Admin Operations</h4>
                                    <p class="text-slate-400 text-sm mb-4">Administrators can manage users and application access via the central panel.</p>
                                    <ul class="space-y-3 text-slate-300 text-sm">
                                        <li class="flex items-center gap-3">
                                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                            Create users and set initial permissions.
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                            Revoke multi-app access instantly.
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                            Monitor SSO sessions via administrative dashboard.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'error-handling')
                        <section id="error-handling">
                            <h2 class="text-3xl font-bold text-white mb-6">Error Handling</h2>
                            <div class="overflow-hidden rounded-2xl border border-slate-800">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-900/80 text-slate-300 font-bold uppercase tracking-wider">
                                        <tr>
                                            <th class="px-6 py-4">HTTP Status</th>
                                            <th class="px-6 py-4">Scenario</th>
                                            <th class="px-6 py-4">Resolution</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800 bg-slate-950/40 text-slate-400">
                                        <tr class="hover:bg-slate-900/50 transition-colors">
                                            <td class="px-6 py-4 font-mono text-amber-500 font-bold">401</td>
                                            <td class="px-6 py-4 text-slate-200 font-bold">Unauthorized / Invalid JWT</td>
                                            <td class="px-6 py-4 font-medium">Ensure public key matches or token is not expired.</td>
                                        </tr>
                                        <tr class="hover:bg-slate-900/50 transition-colors">
                                            <td class="px-6 py-4 font-mono text-amber-500 font-bold">403</td>
                                            <td class="px-6 py-4 text-slate-200 font-bold">Forbidden / No App Access</td>
                                            <td class="px-6 py-4 font-medium">Contact admin to grant access for this specific application.</td>
                                        </tr>
                                        <tr class="hover:bg-slate-900/50 transition-colors">
                                            <td class="px-6 py-4 font-mono text-amber-500 font-bold">422</td>
                                            <td class="px-6 py-4 text-slate-200 font-bold">Validation Failure</td>
                                            <td class="px-6 py-4 font-medium">Check for missing redirect params or malformed payloads.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'security')
                        <section id="security">
                            <h2 class="text-3xl font-bold text-white mb-6">Security Best Practices</h2>
                            <div class="grid grid-cols-1 gap-4">
                                @php
                                    $tips = [
                                        ['title' => 'HTTPS Transmission', 'desc' => 'Always use TLS for transporting JWTs in production to prevent MITM attacks.'],
                                        ['title' => 'Audience Validation', 'desc' => "Check the 'aud' claim in the JWT to ensure the token was meant for your app."],
                                        ['title' => 'Short Lifespans', 'desc' => 'Our tokens expire in 60 seconds. Handle expiration by requesting new tokens via SSO.'],
                                        ['title' => 'Key Rotation', 'desc' => 'Rotate RSA keys annually or whenever a breach is suspected.'],
                                    ];
                                @endphp
                                @foreach($tips as $tip)
                                    <div class="flex items-start gap-4 p-6 rounded-2xl bg-amber-600/5 border border-amber-600/10">
                                        <div class="w-10 h-10 rounded-xl bg-amber-600/20 flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-slate-200 uppercase tracking-tight">{{ $tip['title'] }}</h4>
                                            <p class="text-sm text-slate-400 font-semibold leading-relaxed">{{ $tip['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($activeSection === 'api-reference')
                        <section id="api-reference">
                            <h2 class="text-3xl font-bold text-white mb-6">API Reference Summary</h2>
                            <div class="space-y-4">
                                @php
                                    $endpoints = [
                                        ['method' => 'GET', 'path' => '/login', 'auth' => 'Public', 'desc' => 'Centralized login entry point.'],
                                        ['method' => 'GET', 'path' => '/logout', 'auth' => 'Auth', 'desc' => 'Terminate all SSO sessions.'],
                                        ['method' => 'GET', 'path' => '/apps/{id}/open', 'auth' => 'Auth', 'desc' => 'Initiate SSO redirect to satellite app.'],
                                        ['method' => 'GET', 'path' => '/admin/audit-logs', 'auth' => 'Admin', 'desc' => 'Retrieve authentication logs.'],
                                    ];
                                @endphp

                                @foreach($endpoints as $ep)
                                    <div class="p-6 rounded-2xl bg-slate-900/50 border border-slate-800 flex flex-col md:flex-row md:items-center gap-6">
                                        <div class="flex items-center gap-4 min-w-[200px]">
                                            <span class="px-3 py-1 rounded text-xs font-bold {{ $ep['method'] === 'GET' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-blue-500/10 text-blue-500' }}">
                                                {{ $ep['method'] }}
                                            </span>
                                            <code class="text-slate-200 text-sm font-mono truncate">{{ $ep['path'] }}</code>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-slate-400">{{ $ep['desc'] }}</p>
                                        </div>
                                        <div class="mt-4 md:mt-0">
                                            <span class="text-[10px] uppercase tracking-widest px-2 py-1 rounded-full border border-slate-700 text-slate-500 font-bold">
                                                {{ $ep['auth'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-12 p-8 rounded-3xl bg-amber-600/5 border border-amber-600/10">
                                <h3 class="text-xl font-black text-white mb-4 flex items-center gap-3 uppercase tracking-tight">
                                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    Token Management
                                </h3>
                                <p class="text-slate-400 mb-6 font-semibold leading-relaxed">
                                    To interact with our APIs or integrate satellite applications, you'll need to generate and manage API tokens. You can create tokens with specific scopes and rotation policies via the user profile.
                                </p>
                                <a href="{{ route('profile.api-tokens') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-600 text-white font-black uppercase tracking-widest text-[10px] hover:bg-amber-500 transition-colors shadow-lg shadow-amber-600/20">
                                    Manage My API Tokens
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </section>
                    @endif
                </div>

                {{-- Footer Pagination --}}
                <div class="mt-24 pt-12 border-t border-slate-800 flex justify-between items-center text-slate-500 text-sm">
                    <p>&copy; {{ date('Y') }} Nexus Identity. Built for scale.</p>
                </div>
            </div>
        </main>
    </div>
</div>
