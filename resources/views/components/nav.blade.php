<header
    {{ $attributes->class(['bg-white dark:bg-gray-900']) }}
    x-data="{ mobileMenu: false,  settingsFlyout: false, slideOver: false }"
    @keydown.window.escape="slideOver = false"
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
            <a href="#" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200">Product</a>
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
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
            <button type="button" wire:click="$dispatchTo('auth.logout', 'logout')"
                    class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200">{{ __('Logout') }} <span
                    aria-hidden="true">&rarr;</span></button>
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
                        <a href="#"
                           class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Product</a>
                        <a href="#"
                           class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Features</a>
                        <a href="#"
                           class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">Marketplace</a>
                        <div class="relative">
                            <button @click="settingsFlyout = !settingsFlyout" type="button"
                                    class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                    </div>
                    <div class="py-6">
                        <button type="button" wire:click="$dispatchTo('auth.logout', 'logout')"
                                class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            {{ __('Logout') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide-out panel markup starts... -->
    <div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-show="slideOver"
         x-cloak @click.away="slideOver = false">
        <!-- Your slide-out panel content goes here -->
        <div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <!--
              Background backdrop, show/hide based on slide-over state.

              Entering: "ease-in-out duration-500"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in-out duration-500"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div
                class="fixed inset-0 bg-gray-500 dark:bg-gray-700 bg-opacity-75 dark:bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div @click.away="slideOver = false"
                         class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <!--
                          Slide-over panel, show/hide based on slide-over state.

                          Entering: "transform transition ease-in-out duration-500 sm:duration-700"
                            From: "translate-x-full"
                            To: "translate-x-0"
                          Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
                            From: "translate-x-0"
                            To: "translate-x-full"
                        -->
                        <div class="pointer-events-auto w-screen max-w-md">
                            <div
                                class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-900 py-6 shadow-xl">
                                <div class="px-4 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200"
                                            id="slide-over-title">Panel title</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button"
                                                    x-on:click="slideOver = false"
                                                    class="relative rounded-md bg-white dark:bg-gray-900 text-gray-400 hover:text-gray-500 dark:hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                <span class="absolute -inset-2.5"></span>
                                                <span class="sr-only">Close panel</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                     stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                    <!-- Your content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
