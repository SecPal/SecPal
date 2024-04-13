<div class="dark:bg-gray-800">
    <x-nav/>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200">Journal</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">A (placeholder) list of all incidents</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-600">Add incident</button>
            </div>
        </div>
        <div class="mt-8 -mx-4 sm:-mx-0">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead>
                <tr>
                    <th scope="col" class="py-3.5 px-2 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ __('Incident') }}</th>
                    <th scope="col" class="hidden py-3.5 px-2 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 lg:table-cell">{{ __('Date') }}</th>
                    <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ __('Category') }}</th>
                    <th scope="col" class="flex-auto p-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ __('Description') }}</th>
                    <th scope="col" class="hidden flex-auto p-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 sm:table-cell">{{ __('Action') }}</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200  lg:table-cell">{{ __('reported by') }}</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Edit</span></th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Edit</span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                    <td class="py-4 px-2 text-sm font-medium text-gray-900 dark:text-gray-400">XX1234</td>
                    <td class="hidden py-4 px-2 text-sm text-gray-900 dark:text-gray-400 lg:table-cell">28.03.2024 14:00</td>
                    <td class="px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
                        Shoplifter
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only">Date</dt>
                            <dd class="mt-2 text-gray-900 dark:text-gray-400">28.03.2024 14:00</dd>
                        </dl>
                    </td>
                    <td class="flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut l
                    </td>
                    <td class="hidden flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400 sm:table-cell">
                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut l
                    </td>
                    <td class="hidden px-3 py-4 text-sm text-gray-900 dark:text-gray-400 lg:table-cell">Holger Schmermbeck</td>
                    <td class="py-4 pl-3 pr-0 text-right text-sm font-medium">
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="grey"
                                 class="h-6 w-6 dark:stroke-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </button>
{{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"--}}
{{--                             stroke="grey"--}}
{{--                             class="h-6 w-6 dark:stroke-white">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                  d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />--}}
{{--                        </svg>--}}

                    </td>
                    <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                        <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="grey"
                             class="h-6 w-6 dark:stroke-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                            </svg>
                        </button>
                    </td>
                </tr>
                <!-- More rows... -->
                </tbody>
            </table>
        </div>
    </div>
</div>
