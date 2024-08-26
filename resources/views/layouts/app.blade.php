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

    <x-jsCDN />
    <!-- Include Inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7/inputmask.min.js"></script>
    @stack('scripts')

</body>

</html>
