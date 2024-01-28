<div>
    @if (session('on_duty'))
        <button type="button"
                wire:click="endShift"
                wire:confirm="{{ __('Do you want to end your shift and log out?') }}"
                class="p-2 -mr-1 transition duration-200 rounded focus:outline-none focus:shadow-outline"
                aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="grey"
                 class="h-6 w-6 dark:stroke-white">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    @else
        <button type="button"
                class="p-2 -mr-1 transition duration-200 rounded focus:outline-none focus:shadow-outline"
                aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="grey"
                 class="w-6 h-6 dark:stroke-white">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </button>
    @endif
</div>
