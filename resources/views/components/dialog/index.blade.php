<div
    x-data="{ dialogOpen: false }"
    x-modelable="dialogOpen"
    class="dark:text-white"
    {{ $attributes }}
    tabindex="-1"
>
    {{ $slot }}
</div>
