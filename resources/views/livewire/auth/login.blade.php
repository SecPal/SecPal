<div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm space-y-10">
        <div>
            <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=blue&shade=600"
                 alt="Your Company">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-gray-200">
                Sign in to your
                account</h2>
        </div>
        <form wire:submit="login" class="space-y-6">
            <div class="relative -space-y-px rounded-md shadow-sm">
                <div
                    class="pointer-events-none absolute inset-0 z-10 rounded-md ring-1 ring-inset ring-gray-300 dark:ring-gray-700"></div>
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input wire:model.blur="username" id="username" name="username" type="text" autocomplete="username"
                           autofocus
                           required
                           class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 ring-1 ring-inset ring-gray-100 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder-gray-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-300 sm:text-sm sm:leading-6"
                           placeholder="Username">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input wire:model.blur="password" id="password" name="password" type="password"
                           autocomplete="current-password" required
                           class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 ring-1 ring-inset ring-gray-100 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder-gray-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-300 sm:text-sm sm:leading-6"
                           placeholder="Password">
                </div>
            </div>

            <div
                x-show="$wire.showErrorIndicator"
                x-transition.out.opacity.duration.2000ms
                x-effect="if($wire.showErrorIndicator) setTimeout(() => $wire.showErrorIndicator = false, 3000)"
                class="flex justify-center"
                style="display: none;"
                aria-live="polite"
            >
                <div class="flex gap-2 items-center text-red-600 text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            </div>

            <div class="flex">
                <button type="submit"
                        class="relative w-full justify-center rounded-md bg-blue-600 dark:bg-blue-800 px-3 py-1.5 text-sm font-semibold leading-6 text-white dark:text-white hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-700 disabled:cursor-not-allowed disabled:opacity-75">
                    Sign in

                    <div wire:loading.flex wire:target="login"
                         class="flex absolute top-0 right-0 bottom-0 items-center pr-4">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener('removeLastAction', function () {
            localStorage.removeItem('lastAction');
        })
    </script>

    <div
        x-data="{ showSpinner: false }"
        x-show="showSpinner"
        x-on:show-spinner.window="showSpinner = true"
        x-on:hide-spinner.window="showSpinner = false"
        style="display: none"
        class="fixed inset-0 flex items-center justify-center z-50 bg-opacity-80 bg-black"
    >
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-white dark:border-gray-400"></div>
    </div>
</div>
