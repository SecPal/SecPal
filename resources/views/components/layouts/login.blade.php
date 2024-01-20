<!doctype html>
<html class="h-full bg-white dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SecPal Login' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full text-gray-900 dark:text-gray-200">
{{ $slot }}
@livewireScripts
<livewire:auth.logout/>
</body>
</html>
