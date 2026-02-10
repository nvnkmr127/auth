<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-slate-900 bg-[#f8fafc]">
    <div x-data="{ sidebarOpen: false }" class="min-h-full">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"></div>

        <!-- Sidebar for Mobile & Desktop -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 z-50 flex w-72 flex-col transition-transform duration-300 ease-in-out md:translate-x-0 md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
            <!-- Sidebar Component -->
            <div class="flex min-h-0 flex-1 flex-col bg-slate-950 border-r border-slate-900 shadow-2xl">
                <div class="flex flex-1 flex-col overflow-y-auto pt-8 pb-4">
                    <div class="flex flex-shrink-0 items-center px-6 mb-10">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-lg shadow-primary/20">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-white tracking-widest uppercase truncate">
                                {{ Auth::user()->currentTeam?->name ?? 'Control Center' }}
                            </span>
                        </div>
                    </div>

                    <nav class="flex-1 space-y-8 px-4">
                        <!-- IDENTITY SECTION -->
                        <div>
                            <h3 class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">
                                Account
                            </h3>
                            <div class="space-y-1">
                                <a href="{{ route('dashboard') }}"
                                    class="group flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>

                                <a href="{{ route('apps.index') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('apps.index') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('apps.index') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Applications
                                </a>

                                <a href="{{ route('profile.security') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('profile.security') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('profile.security') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Security Settings
                                </a>

                                <a href="{{ route('profile.api-tokens') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('profile.api-tokens') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('profile.api-tokens') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    API Tokens
                                </a>
                            </div>
                        </div>

                        <!-- ADMINISTRATION SECTION -->
                        @auth
                            <div>
                                <h3 class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">
                                    Administration
                                </h3>
                                <div class="space-y-1">
                                    <a href="{{ route('admin.users') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        User Management
                                    </a>

                                    <a href="{{ route('admin.apps') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.apps*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.apps*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Registered Apps
                                    </a>

                                    <a href="{{ route('admin.roles') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.roles*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.roles*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        User Roles
                                    </a>

                                    <a href="{{ route('admin.permissions') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.permissions*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.permissions*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Permissions
                                    </a>

                                    <a href="{{ route('admin.audit-logs') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.audit-logs*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.audit-logs*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 0 002-2M9 5a2 2 0 012-2h2a2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        Audit Logs
                                    </a>

                                    <a href="{{ route('admin.sso-sessions') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.sso-sessions*') ? 'bg-primary/10 text-primary shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                        <svg class="mr-3 h-5 w-5 {{ request()->routeIs('admin.sso-sessions*') ? 'text-primary' : 'text-slate-500 group-hover:text-slate-300' }}"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path
                                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 003 11c0-5.523 4.477-10 10-10s10 4.477 10 10a9.985 9.985 0 01-2.017 5.992l.053.09M10 11V7a2 2 0 114 0v4a2 2 0 11-4 0z" />
                                        </svg>
                                        Active Sessions
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </nav>
                </div>

                <!-- User Profile at Bottom -->
                <div class="px-4 py-8">
                    <div
                        class="bg-white/5 backdrop-blur-sm rounded-3xl p-4 flex items-center border border-white/10 shadow-lg">
                        <div class="relative">
                            <div
                                class="w-11 h-11 rounded-2xl bg-primary flex items-center justify-center text-white font-black text-xs shadow-lg shadow-primary/20">
                                {{ substr(Auth::user()->name ?? 'SA', 0, 2) }}
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-slate-950 rounded-full shadow-sm">
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-white truncate uppercase tracking-tight">
                                    {{ Auth::user()->name }}
                                </p>
                            </div>
                            <span
                                class="inline-block mt-1 px-2 py-0.5 rounded-lg text-[7px] font-black bg-primary/20 text-primary border border-primary/20 tracking-widest">SYSTEM
                                ADMIN</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="flex">
                            @csrf
                            <button type="submit"
                                class="p-2 text-slate-500 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200"
                                title="Sign Out">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="flex flex-1 flex-col md:pl-64 min-h-screen">
            <!-- Desktop Top Header -->
            <header
                class="hidden md:flex h-20 items-center justify-between px-8 bg-white border-b border-gray-100 sticky top-0 z-40">
                <div class="flex items-center gap-8 flex-1">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-black text-slate-900 tracking-widest uppercase">Auth <span
                                class="text-primary">Portal</span></span>
                        <div class="h-4 w-[1px] bg-slate-200"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Control
                            Center</span>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative w-full max-w-md group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400 group-focus-within:text-primary transition-colors"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                            class="block w-full pl-11 pr-12 py-3 text-sm bg-slate-50 border-transparent rounded-2xl focus:ring-4 focus:ring-primary/10 focus:border-primary/20 focus:bg-white transition-all placeholder:text-slate-400"
                            placeholder="Search...">
                        <div class="absolute inset-y-0 right-4 flex items-center">
                            <span
                                class="text-[10px] font-bold text-slate-400 bg-white border border-slate-200 px-2 py-1 rounded-lg">/</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Global SSO Status -->
                    <div
                        class="flex items-center gap-3 px-5 py-2.5 bg-primary-light rounded-2xl border border-primary/10 shadow-sm">
                        <div class="flex flex-col items-end">
                            <span
                                class="text-[8px] font-black text-primary/60 uppercase tracking-widest leading-none">SSO</span>
                            <span class="text-[10px] font-bold text-primary uppercase leading-none mt-1">Active</span>
                        </div>
                        <div
                            class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] anime-pulse">
                        </div>
                    </div>

                    <div class="h-6 w-[1px] bg-gray-100"></div>

                    <!-- Utilities -->
                    <div class="flex items-center gap-4">
                        <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Toggle Theme">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        <div class="relative">
                            <button class="text-gray-400 hover:text-gray-600 transition-colors" title="Notifications">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            <span
                                class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-primary border-2 border-white rounded-full"></span>
                        </div>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-100"></div>

                    <!-- User Information -->
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                            <span
                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ Auth::user()->roles->first()?->name ?? 'Super Admin' }}</span>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-sm overflow-hidden ring-2 ring-transparent hover:ring-primary/20 transition-all cursor-pointer">
                            <span
                                class="text-xs font-black text-primary uppercase">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="flex">
                            @csrf
                            <button type="submit"
                                class="ml-2 p-2.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200 group"
                                title="Sign Out">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Mobile Header -->
            <div class="sticky top-0 z-40 flex h-16 flex-shrink-0 bg-white shadow-sm md:hidden">
                <button @click="sidebarOpen = true" type="button" class="px-4 text-gray-400 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex flex-1 items-center px-4">
                    <span class="text-xs font-black text-slate-900 tracking-[0.2em] uppercase leading-none">Auth <span
                            class="text-primary">Portal</span></span>
                </div>
            </div>

            <!-- Main Page Content -->
            <main class="flex-1 bg-gray-50/30">
                <div class="max-w-7xl mx-auto py-10 px-6 sm:px-8 lg:px-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Notifications -->
    <div x-data="{ 
            messages: [],
            remove(id) {
                this.messages = this.messages.filter(m => m.id !== id)
            },
            add(message, type = 'success') {
                const id = Date.now()
                this.messages.push({ id, text: message, type })
                setTimeout(() => this.remove(id), 5000)
            }
        }" @notify.window="add($event.detail.message, $event.detail.type)"
        class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-[100]">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <template x-for="msg in messages" :key="msg.id">
                <div x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <template x-if="msg.type === 'success'">
                                    <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                                <template x-if="msg.type === 'error'">
                                    <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900" x-text="msg.text"></p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button @click="remove(msg.id)"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</body>

</html>