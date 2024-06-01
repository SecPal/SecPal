<div>
    <x-dialog wire:model="show">
        @if(isset($journal))
            <x-menu.close>
                <x-dialog.open>
                    <x-menu.item
                    >
                        {{ t('Edit') }}
                    </x-menu.item>
                </x-dialog.open>
            </x-menu.close>
        @else
            <x-dialog.open>
                <button type="button"
                        class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline dark:focus-visible:ring-2 dark:focus-visible:ring-offset-2 dark:focus-visible:ring-indigo-500">{{ t('Add incident') }}</button>
            </x-dialog.open>
        @endif
        <x-dialog.panel>
            @include('livewire.incident-form')
        </x-dialog.panel>
    </x-dialog>
</div>
