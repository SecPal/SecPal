<div
    class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-900 py-6 shadow-xl">
    <div class="px-4 sm:px-6">
        <div class="flex items-start justify-between">
            <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200"
                id="slide-over-title">{{ __('Change Password') }}</h2>
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
    <div class="flex items-center justify-center min-h-screen">
        <div class="relative mt-6 w-full px-4 sm:px-6">
            <form wire:submit="changePassword" class="space-y-6">
                <div class="relative -space-y-px rounded-md shadow-sm">
                    <div
                        class="pointer-events-none absolute inset-0 z-10 rounded-md ring-1 ring-inset ring-gray-300 dark:ring-gray-700"></div>
                    <div>
                        <label for="current_password" class="sr-only">{{ __('Your actual Password') }}</label>
                        <input wire:model.blur="current_password" id="current_password" name="current_password"
                               type="password" autocomplete="password"
                               autofocus
                               required
                               class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 ring-1 ring-inset ring-gray-100 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder-gray-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-300 sm:text-sm sm:leading-6"
                               placeholder="{{ __('Actual Password') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">{{ __('New Password') }}</label>
                        <input wire:model.blur="password" id="password" name="password" type="password"
                               required
                               class="relative block w-full border-0 py-1.5 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 ring-1 ring-inset ring-gray-100 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder-gray-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-300 sm:text-sm sm:leading-6"
                               placeholder="{{ __('New Password') }}">
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">{{ __('Password Repeat') }}</label>
                        <input wire:model.blur="password_confirmation" id="password_confirmation"
                               name="password_confirmation" type="password"
                               required
                               class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-800 ring-1 ring-inset ring-gray-100 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder-gray-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:focus:ring-blue-300 sm:text-sm sm:leading-6"
                               placeholder="{{ __('Password Repeat') }}">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="flex flex-col items-center justify-center text-red-600 text-sm font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="flex">
                    <button type="submit"
                            class="relative w-full justify-center rounded-md bg-blue-600 dark:bg-blue-800 px-3 py-1.5 text-sm font-semibold leading-6 text-white dark:text-white hover:bg-blue-500 dark:hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-blue-700 disabled:cursor-not-allowed disabled:opacity-75">
                        {{ __('Change Password') }}

                        <div wire:loading.flex wire:target="changePassword"
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
    </div>
</div>
