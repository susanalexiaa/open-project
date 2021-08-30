<div x-data="{ open: false }" class="text-sm">
    <div class="relative bg-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center py-4 md:justify-start md:space-x-10">
                <div class="flex justify-start">
                    {{ __('Admin menu') }}
                </div>

                <div class="md:hidden">
                    <button type="button"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            aria-expanded="false"
                            @click="open = ! open">
                        <span class="sr-only">Open menu</span>
                        <!-- Heroicon name: outline/menu -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <nav class="hidden md:flex space-x-8">
                    <x-jet-nav-link href="{{ route('users') }}" :active="request()->routeIs('users')">
                        {{ __('Users') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('statuses') }}" :active="request()->routeIs('statuses')">
                        {{ __('Statuses') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('integrations') }}" :active="request()->routeIs('integrations')">
                        {{ __('Integrations') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('integrations.telephony') }}" :active="request()->routeIs('integrations.telephony')">
                        {{ __('Telephony') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('logs') }}" :active="request()->routeIs('logs')">
                        {{ __('Logs') }}
                    </x-jet-nav-link>

                    <x-jet-nav-link href="{{ route('admin.teams') }}" :active="request()->routeIs('admin.teams')">
                        {{ __('Teams') }}
                    </x-jet-nav-link>
                </nav>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}"
             class="absolute top-0 inset-x-0 p-2 transition transform origin-top-right md:hidden">
            <div
                class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50 h-screen">
                <div class="pt-5 pb-6 px-5">
                    <div class="flex items-center justify-between border-b-2 border-gray-100">
                        <div class="text-md">
                            {{ __('Admin menu') }}
                        </div>
                        <div class="-mr-2">
                            <button type="button"
                                    @click="open = ! open"
                                    class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                <span class="sr-only">Close menu</span>
                                <!-- Heroicon name: outline/x -->
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mt-6">
                        <nav class="grid gap-y-8">
                            <x-jet-nav-link href="{{ route('users') }}" :active="request()->routeIs('users')">
                                {{ __('Users') }}
                            </x-jet-nav-link>

                            <x-jet-nav-link href="{{ route('statuses') }}" :active="request()->routeIs('statuses')">
                                {{ __('Statuses') }}
                            </x-jet-nav-link>

                            <x-jet-nav-link href="{{ route('integrations') }}"
                                            :active="request()->routeIs('integrations')">
                                {{ __('Integrations') }}
                            </x-jet-nav-link>

                            <x-jet-nav-link href="{{ route('integrations.telephony') }}"
                                            :active="request()->routeIs('integrations.telephony')">
                                {{ __('Telephony') }}
                            </x-jet-nav-link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
