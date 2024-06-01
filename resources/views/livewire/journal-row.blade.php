<tr>
    <td class="py-4 px-2 text-sm font-medium text-gray-900 dark:text-gray-400">{{ $journal->sqid }}</td>
    <td class="hidden py-4 px-2 text-sm text-gray-900 dark:text-gray-400 lg:table-cell">{{ $journal->incident_time->format('d.m.Y H:i') }}</td>
    <td class="px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
        {{ $journal->category->name }}
        <dl class="font-normal lg:hidden">
            <dt class="sr-only">Date</dt>
            <dd class="mt-2 text-gray-900 dark:text-gray-400">{{ $journal->incident_time->format('d.m.Y H:i') }}</dd>
        </dl>
    </td>
    <td class="flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400">
        {{ limitAndCleanString($journal->description) }}
    </td>
    <td class="hidden flex-auto px-3 py-4 text-sm text-gray-900 dark:text-gray-400 sm:table-cell">
        {{  limitAndCleanString($journal->measures) }}
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
    <td class="whitespace-nowrap p-3 text-sm">
        <div class="flex items-center justify-end">
            <x-menu>
                <x-menu.button class="rounded hover:bg-gray-300 dark:hover:bg-gray-700">
                    <x-icon.ellipsis-horizontal />
                </x-menu.button>

                <x-menu.items>
                    @can('update', $journal)
                        <livewire:incident-modal :$location_data :key="$journal->id" :$journal @added="$refresh" />
                    @endcan

                    @can('delete', $journal)
                        <x-menu.close>
                            <x-menu.item
                                wire:click="$dispatch('deleted')"
                                wire:confirm.prompt="{{ t('Are you sure you want to delete this incident?\n\nType the incident # to confirm:') }}|{{ $journal->sqid }}"
                            >
                                {{ t('Delete') }}
                            </x-menu.item>
                        </x-menu.close>
                    @endcan
                </x-menu.items>
            </x-menu>
        </div>
    </td>
</tr>
