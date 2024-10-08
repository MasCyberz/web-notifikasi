<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <x-links />
    @vite('resources/js/app.js')
</head>

<body class=" border-top-wide border-primary d-flex flex-column overflow-y-hidden">
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">403</div>
                <p class="empty-title">Dilarang</p>
                <p class="empty-subtitle text-secondary">
                    Kami minta maaf, akses tidak diizinkan <br> Hubungi administrator untuk informasi lebih lanjut
                </p>
                <div class="empty-action">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l6 6"></path>
                            <path d="M5 12l6 -6"></path>
                        </svg>
                        Kembali ke halaman utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-jsCDN />
</body>

</html>
