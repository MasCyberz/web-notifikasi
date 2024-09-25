<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <x-links />

    @vite('resources/js/app.js')

</head>

<body>
    @stack('styles')
    <div class="page">
        <x-sidebar />
        <x-navbar />
        <div class="page-wrapper">
            {{ $slot }}
            <x-footer />
        </div>
    </div>


    <x-jsCDN />
    @stack('scripts')

</body>

</html>
