<div class="dark:bg-gray-800">
    <x-nav/>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200">
                    {{ t('Journal') }} -
                    <div class="inline-block">
                        <select wire:model.live="actual_location" class="w-64 bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 py-2 pl-1 pr-10 text-sm text-gray-900 dark:text-white border-0 ring-0 rounded-md text-left">
                            <option value="">{{ t('Select your location') }}</option>
                            @foreach($locations as $location)
                                @canany(['viewRecentJournal', 'viewFullJournal'], $location)
                                    <option value="{{ $location->id }}">
                                        {{ $location->name }} - {{ $location->location }}
                                    </option>
                                @endcanany
                            @endforeach
                        </select>
                    </div>
                </h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ t('A list of all incidents.') }}</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                @can('create-journal', $location_data)
                    <livewire:add-incident :$location_data :key="$location_data->id" @added="$refresh" />
                @endcan
            </div>
        </div>
        <div class="relative mt-8 -mx-4 sm:-mx-0">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead>
                <tr>
                    <th scope="col" class="py-3.5 px-2 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ t('Incident') }}</th>
                    <th scope="col" class="hidden py-3.5 px-2 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 lg:table-cell">{{ t('Date') }}</th>
                    <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ t('Category') }}</th>
                    <th scope="col" class="flex-auto p-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ t('Description') }}</th>
                    <th scope="col" class="hidden flex-auto p-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200 sm:table-cell">{{ t('Action') }}</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200  lg:table-cell">{{ t('reported by') }}</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Edit</span></th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Edit</span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($journals as $journal)
                    <tr>
                        <td class="py-4 px-2 text-sm font-medium text-gray-900 dark:text-gray-400">{{ $journal->id }}</td>
                        <td class="hidden py-4 px-2 text-sm text-gray-900 dark:text-gray-400 lg:table-cell">{{ $journal->incident_time->format('d.m.Y H:i') }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
                            {{ $journal->category->name }}
                            <dl class="font-normal lg:hidden">
                                <dt class="sr-only">Date</dt>
                                <dd class="mt-2 text-gray-900 dark:text-gray-400">{{ $journal->incident_time->format('d.m.Y H:i') }}</dd>
                            </dl>
                        </td>
                        <td class="flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
                            {{ Str::of($journal->description)->limit(100) }}
                        </td>
                        <td class="hidden flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400 sm:table-cell">
                            {{ Str::of($journal->measures)->limit(100)->replace("\n", ', ') }}
                        </td>
                        <td class="hidden px-3 py-4 text-sm text-gray-900 dark:text-gray-400 lg:table-cell">{{ $journal->reportedBy->lastname }}, {{ $journal->reportedBy->firstname }}</td>
                        <td class="py-4 pl-3 pr-0 text-right text-sm font-medium">
                            @if($journal->reviewed_by)
                                <button type="button" class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="grey"
                                         class="h-6 w-6 dark:stroke-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            @else
                                <button type="button" class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="grey"
                                         class="h-6 w-6 dark:stroke-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                </button>
                            @endif
                        </td>
                        <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                            <button type="button" class="hover:bg-gray-200 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="grey" class="h-6 w-6 dark:stroke-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{-- Table loading spinners... --}}
            <div wire:loading class="absolute inset-0 bg-white opacity-50">
                {{-- --}}
            </div>

            <div wire:loading.flex class="flex justify-center items-center fixed inset-0">
                <x-icon.spinner size="10" class="text-gray-500 dark:text-gray-800" />
            </div>
        </div>
    </div>
</div>
