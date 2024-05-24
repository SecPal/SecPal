<div>
    @props(['items', 'placeholderMessage', 'noMatchMessage', 'itemIdKey'])

    <div x-data="
    {
        query: '',
        items: {{ json_encode($items) }},
        placeholderMessage: '{{ $placeholderMessage ?? t('Select Item...') }}',
        noMatchMessage: '{{ $noMatchMessage ?? t('No items match your query.') }}',
        get filteredItems() {
            return this.query === ''
                ? this.items
                : this.items.filter((item) => item.label.toLowerCase().includes(this.query.toLowerCase()));
        },
        selected: null,
        defaultSelected: null,
        selectItem() {
            if (this.selected !== null) {
                this.$wire.{{ $itemIdKey }} = this.selected.id;
            }
        },
        reset() {
            this.selected = this.defaultSelected;
            this.query = '';
        },
    }"
         x-init="selected = items.find(item => item.id === $wire.{{ $itemIdKey }}) || {id: null, label: ''}; defaultSelected = selected; $watch('selected', () => { if (selected !== null) selectItem(); })"
         @reset-select-search.window="reset()"
    >
        <div x-combobox x-model="selected">
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="relative w-full">
                    <input
                        wire:ignore
                        x-combobox:input
                        :display-value="item=>item.label"
                        @change="query = $event.target.value"
                        @blur="query = ''"
                        @keydown.escape="query = ''"
                        class="block w-full rounded-md border-0 pr-10 py-1.5 dark:bg-gray-700 text-gray-900 dark:text-gray-50 placeholder:text-gray-400 dark:placeholder:text-gray-600 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm"
                        :placeholder="placeholderMessage"
                        wire:blur="$refresh"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <button x-combobox:button class="block h-10 w-10 text-gray-600 dark:text-gray-300 hover:text-gray-400 dark:hover:text-white focus:outline-none transition ease-in-out duration-200">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
                <div x-combobox:options x-cloak class="absolute left-0 w-full mt-1 rounded-md border-0 bg-white dark:bg-gray-700 rounded-b-md border-t-0 dark:border-gray-700 focus:outline-none z-10" x-transition.out.opacity>
                    <ul class="divide-y divide-transparent dark:divide-gray-700">
                        <template x-for="(item, idx) in filteredItems" :key="item.id">
                            <li
                                x-combobox:option
                                :value="item"
                                :disabled="item.disabled"
                                @click="selectItem()"
                                :class="{ 'rounded-t-md': idx === 0, 'rounded-b-md': idx === (filteredItems.length - 1) }"
                                class="w-full py-1 text-sm hover:bg-gray-200 dark:bg-gray-700 select-none dark:hover:bg-gray-600">
                                <span x-text="item.label" class="text-sm text-gray-900 dark:text-gray-50 pl-2"></span>
                            </li>
                        </template>
                    </ul>
                </div>
                <p x-show="filteredItems.length === 0 && query.length > 0" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 pl-2">
                    {{ $noMatchMessage }}
                </p>
            </div>
        </div>
    </div>
</div>
