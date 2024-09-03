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
    <div class="page">
        <x-sidebar />
        <x-navbar />
        <div class="page-wrapper">
            {{ $slot }}
            <x-footer />
        </div>
    </div>

    {{-- Tabler.IO --}}
    <script src="https://unpkg.com/@tabler/core@latest/dist/js/tabler.min.js"></script>
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <x-jsCDN />
    @stack('scripts')

</body>

</html>
