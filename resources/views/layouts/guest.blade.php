<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Центр Ресниц') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-['Rubik'] text-[#1e1f22] antialiased bg-gray-100">

<div class="min-h-screen flex flex-col items-center pt-20 sm:pt-32 px-4">

    <div class="w-full sm:max-w-md p-8 bg-white border border-[#f1f1f5] rounded-3xl shadow-sm overflow-hidden h-[620px] flex flex-col justify-between">
        <div class="flex flex-col h-full">
            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>
