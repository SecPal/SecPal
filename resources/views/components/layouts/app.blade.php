<!doctype html>
<html class="h-full bg-white dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SecPal' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        window.addEventListener('popstate', function () {
            location.reload();
        });
    </script>
</head>
<body class="h-full">
{{ $slot }}
@livewireScripts
<livewire:shift/>
<livewire:auth.logout/>
</body>
</html>
