<div class="dark:bg-gray-800">
    <x-nav/>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200">
                    {{ t('Journal') }}
                </h1>
                <div class="inline-block">
                    <x-select-search
                        :items="$selectSearchLocation"
                        placeholderMessage="{{  t('Select location') }}"
                        noMatchMessage="{{ t('Sorry, no location was found for your request.') }}"
                        itemIdKey="actual_location"
                    ></x-select-search>
                </div>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                @can('create-journal', $location_data)
                    <livewire:incident-modal :$location_data :key="$location_data->id" @added="$refresh" />
                @endcan
            </div>
        </div>
        <div class="relative mt-8 -mx-4 sm:-mx-0">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead>
                <tr>
                    <th scope="col" class="py-3.5 px-2 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">{{ t('AddIncident') }}</th>
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
                    <livewire:journal-row :key="$journal->id" :$journal :$location_data @deleted="delete({{ $journal->id }})" />
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
