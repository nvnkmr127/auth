<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
        <h1 class="text-2xl font-semibold text-gray-900">User Access Management</h1>
        <p class="mt-1 text-sm text-gray-600">Manage application access and roles for users.</p>
    </div>

    <!-- Search -->
    <div class="mt-4 mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
            class="w-full sm:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 border">
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($users as $user)
                <li>
                    <div wire:click="selectUser({{ $user->id }})"
                        class="block hover:bg-gray-50 cursor-pointer transition duration-150 ease-in-out">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                            <span
                                                class="font-medium leading-none text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-indigo-600 truncate">{{ $user->name }}</div>
                                        <div class="flex text-sm text-gray-500">
                                            <p>{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->appAccesses()->count() }} Apps
                                    </span>
                                </div>
                                <div class="ml-5 flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Slide-over Panel -->
    <div x-data="{ open: @entangle('showSlideOver') }" x-show="open" style="display: none;"
        class="fixed inset-0 overflow-hidden z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">

        <div class="absolute inset-0 overflow-hidden">
            <!-- Background overlay -->
            <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="open = false; $wire.closeSlideOver()"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="open" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">

                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="px-4 py-6 sm:px-6 bg-indigo-700">
                            <div class="flex items-start justify-between">
                                <h2 class="text-lg font-medium text-white" id="slide-over-title">
                                    Manage Access
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" @click="open = false; $wire.closeSlideOver()"
                                        class="rounded-md bg-indigo-700 text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-1">
                                <p class="text-sm text-indigo-300">
                                    {{ $selectedUser?->name }} ({{ $selectedUser?->email }})
                                </p>
                            </div>
                        </div>

                        <div class="relative flex-1 px-4 py-6 sm:px-6">
                            @if($selectedUser)
                                <div class="space-y-6">
                                    @foreach($accessSettings as $appId => $setting)
                                        <div
                                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div class="flex-1">
                                                <h3 class="text-sm font-medium text-gray-900">{{ $setting['app_name'] }}</h3>
                                                <p class="text-xs text-gray-500">{{ $setting['app_slug'] }}</p>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <!-- Role Selector -->
                                                <select wire:change="updateRole({{ $appId }}, $event.target.value)"
                                                    @if(!$setting['has_access']) disabled @endif
                                                    class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs py-1 {{ !$setting['has_access'] ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                    <option value="viewer" @selected($setting['role'] == 'viewer')>Viewer</option>
                                                    <option value="editor" @selected($setting['role'] == 'editor')>Editor</option>
                                                    <option value="admin" @selected($setting['role'] == 'admin')>Admin</option>
                                                </select>

                                                <!-- Toggle Switch -->
                                                <button wire:click="toggleAccess({{ $appId }})" type="button"
                                                    class="{{ $setting['has_access'] ? 'bg-indigo-600' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                    role="switch"
                                                    aria-checked="{{ $setting['has_access'] ? 'true' : 'false' }}">
                                                    <span aria-hidden="true"
                                                        class="{{ $setting['has_access'] ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>