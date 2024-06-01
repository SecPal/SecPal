<button
    x-menu:item
    x-bind:class="{
        'bg-slate-100 dark:bg-slate-900 text-gray-900 dark:text-gray-100': $menuItem.isActive,
        'text-gray-600 dark:text-gray-300': ! $menuItem.isActive,
        'opacity-50 cursor-not-allowed': $menuItem.isDisabled,
    }"
    class="flex items-center gap-2 w-full px-3 py-1.5 text-left text-sm hover:bg-slate-50 dark:hover:bg-gray-600 disabled:text-gray-500 transition-colors"
    {{ $attributes->merge([
        'type' => 'button',
    ]) }}
>
    {{ $slot }}
</button>
