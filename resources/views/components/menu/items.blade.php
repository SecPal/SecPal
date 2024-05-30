<div
    x-menu:items
    x-anchor.bottom-end.offset.3="document.getElementById($id('alpine-menu-button'))"
    class="min-w-[10rem] z-10 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 divide-y divide-gray-100 dark:divide-gray-500 rounded-md shadow-lg py-1 outline-none"
    x-cloak
>
    {{ $slot }}
</div>
