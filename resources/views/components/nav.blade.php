<header
    {{ $attributes->class(['bg-white dark:bg-gray-900']) }}
    x-data="{ mobileMenu: false,  settingsFlyout: false, slideOver: false }"
    @keydown.window.escape="slideOver = false"
    x-on:password-changed.window="slideOver = false"
>
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5">
                <span class="sr-only">Your Company</span>
                <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                     alt="">
            </a>
        </div>
        <div class="flex lg:hidden">
            <livewire:shift @shift-changed="$refresh" identifier="mobile"/>
            <!-- Hamburger Menu Button -->
            <button type="button" x-on:click="mobileMenu = !mobileMenu; settingsFlyout = false"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                <span class="sr-only">Open main menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
        </div>
        <div class="hidden lg:flex lg:gap-x-12">
            @if(auth()->user()->isOnDuty()) {{-- don't show menu if not on duty --}}
                <a href="{{ route('journal') }}" wire:navigate class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200">{{ __('Journal') }}</a>
                <a href="#" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200">Features</a>
                <a href="#" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200">Marketplace</a>
                <div class="relative">
                    <button @click="settingsFlyout = !settingsFlyout" type="button"
                            class="inline-flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200"
                            aria-expanded="false">
                        <span>{{ __('Settings') }}</span>
                    </button>
                    <div x-show="settingsFlyout"
                         @click.away="settingsFlyout = false"
                         class="absolute left-1/2 z-10 mt-5 flex w-screen max-w-min -translate-x-1/2 px-4"
                         style="display: none;"
                    >
                        <div
                            class="w-56 shrink rounded-xl bg-white dark:bg-gray-900 p-4 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-200/5">
                            <a href="#" class="block p-2 text-gray-900 dark:text-gray-200">Analytics</a>
                            <a href="#" class="block p-2 text-gray-900 dark:text-gray-200">Engagement</a>
                            <a href="#" class="block p-2 text-gray-900 dark:text-gray-200">Security</a>
                            <a href="#" class="block p-2 text-gray-900 dark:text-gray-200">Integrations</a>
                            <a href="#" class="block p-2 text-gray-900 dark:text-gray-200">Automations</a>
                            <a href="#" x-on:click.prevent="slideOver = true, mobileMenu = false, settingsFlyout = false"
                               class="block p-2 text-gray-900 dark:text-gray-200">{{ __('Change Password') }}</a>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->locations()->count())
                <button type="button" @click="$dispatch('start-shift')" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200 uppercase">{{ __('Please start your shift or log out!') }}</button>
            @endif
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <livewire:shift @shift-changed="$refresh" identifier="desktop"/>

            <!--User menu-->
            <div class="relative ml-3" x-data="{ userMenu: false }" @click.away="userMenu = false">
                <button @click="userMenu = !userMenu" type="button"
                        class="relative flex max-w-xs items-center rounded-full bg-white dark:bg-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full"
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                         alt="">
                </button>

                <div
                    x-show="userMenu"
                    class=" absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                    style="display: none;">
                    <a href="#"
                       x-on:click.prevent="slideOver = true, mobileMenu = false, settingsFlyout = false, userMenu = false"
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                       role="menuitem" tabindex="-1" id="user-menu-item-0">{{ __('Change Password') }}</a>
                    <a href="#"
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600"
                       role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                    <div role="menuitem" tabindex="-1" id="user-menu-item-2">
                        <button type="button" wire:click="$dispatchTo('auth.logout', 'logout')"
                                class="w-full text-left px-4 py-2 text-sm font-semibold leading-6 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                            {{ __('Logout') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile menu, show/hide based on menu open state. -->
    <div class="lg:hidden" x-show="mobileMenu" role="dialog" style="display: none;" aria-modal="true">
        <div class="fixed inset-0 z-10"></div>
        <div
            class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white dark:bg-gray-900 px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
            <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                         alt="">
                </a>
                <button type="button" x-on:click="mobileMenu = false; settingsFlyout = false"
                        class="-m-2.5 rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Close menu</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                        @if(auth()->user()->isOnDuty()) {{-- don't show menu if not on duty --}}
                            <a href="{{ route('journal') }}"
                               wire:navigate
                               class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Journal') }}</a>
                            <a href="#"
                               class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Features</a>
                            <a href="#"
                               class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Marketplace</a>
                            <div class="relative w-full">
                                <button @click="settingsFlyout = !settingsFlyout" type="button"
                                        class="-mx-3 w-full block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 text-left">
                                    <span>{{ __('Settings') }}</span>
                                </button>
                                <div x-show="settingsFlyout">
                                    <a href="#"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Analytics</a>
                                    <a href="#"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Engagement</a>
                                    <a href="#"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Security</a>
                                    <a href="#"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Integrations</a>
                                    <a href="#"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Automations</a>
                                    <a href="#"
                                       x-on:click.prevent="slideOver = true, mobileMenu = false, settingsFlyout = false"
                                       class="ml-6 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Change Password') }}</a>
                                </div>
                            </div>
                        @elseif(auth()->user()->locations()->count())
                            <button type="button"
                               @click="$dispatch('start-shift')"
                               class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Please start your shift or log out!') }}
                            </button>
                        @endif
                    </div>
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full"
                                     src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                     alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-gray-200">Tom Cook</div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">tom@example.com</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="#"
                               x-on:click.prevent="slideOver = true, mobileMenu = false, settingsFlyout = false, userMenu = false"
                               class="block px-4 py-2 text-base font-medium text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Change Password') }}</a>
                            <a href="#"
                               class="block px-4 py-2 text-base font-medium text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Settings</a>
                            <button wire:click="$dispatchTo('auth.logout', 'logout')"
                                    class="block w-full text-left px-4 py-2 text-base font-medium text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('Logout') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide-out panel markup starts... -->
    <div class="fixed inset-0 overflow-hidden z-50" style="z-index: 50; display: none;" x-show="slideOver"
         x-transition:enter="transition ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Background overlay with transition. -->
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>

        <!-- Slide-in panel with transition -->
        <div class="absolute inset-0 overflow-hidden">
            <section
                class="absolute inset-y-0 right-0 pl-10 max-w-full flex"
                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-700"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-700"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
            >
                <div @click.away="slideOver = false" class="pointer-events-auto w-screen max-w-md">
                    <livewire:change-password/>
                </div>
            </section>
        </div>
    </div>

    <!-- Success Alert TODO: refactor to a reusable component -->
    <div class="rounded-md p-4 fixed right-0 top-0 m-6"
         x-data="{ showSuccess: false, message: 'Success!' }"
         x-show="showSuccess"
         x-init="
         window.addEventListener('password-changed', event => {
             showSuccess = true;
             message = event.detail.message || message;
             setTimeout(() => { showSuccess = false }, 3000);
         });
     "
         :class="{ 'bg-green-50 dark:bg-green-900': showSuccess, 'hidden': !showSuccess }"
         style="display: none;"
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400 dark:text-green-200" viewBox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium" x-text="message"
                   :class="{ 'text-green-800 dark:text-green-200': showSuccess }"></p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button"
                            class="inline-flex rounded-md bg-green-50 dark:bg-green-900 p-1.5 text-green-500 dark:text-green-200 hover:bg-green-100 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50"
                            @click="showSuccess = false">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
