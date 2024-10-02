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

<body>
    <div class="page page-center ">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <p class="navbar-brand navbar-brand-autodark">
                    <span class="fs-2 fw-bold text-uppercase">{{ config('app.name') }}</span>
                </p>
            </div>
            @if (session('loginError'))
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-title fs-3">Opss, Ada kesalahan</h4>
                    <div class="text-secondary">{{ session('loginError') }}</div>
                </div>
            @endif
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Masuk ke akun Anda</h2>
                    <form action="{{ route('authenticating') }}" method="POST" autocomplete="off" novalidate="">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="email" name="username" class="form-control" placeholder="Your Username Here"
                                autocomplete="off">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                Password
                            </label>
                            <div class="input-group input-group-flat">
                                <input id="password" type="password" name="password" class="form-control"
                                    placeholder="Your password Here" autocomplete="off">
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" data-bs-toggle="tooltip"
                                        aria-label="Show password"
                                        data-bs-original-title="Show password"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                        <svg id="eyeopen" class="fs-3" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                        <svg class="visually-hidden" id="eyeclosed" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye-closed">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M21 9c-2.4 2.667 -5.4 4 -9 4c-3.6 0 -6.6 -1.333 -9 -4" />
                                            <path d="M3 15l2.5 -3.8" />
                                            <path d="M21 14.976l-2.492 -3.776" />
                                            <path d="M9 17l.5 -4" />
                                            <path d="M15 17l-.5 -4" />
                                        </svg>
                                </span>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                            {{-- <a href="{{ route('dashboard') }}" type="submit" class="btn btn-primary w-100">Masuk</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const eyeclosed = document.getElementById('eyeclosed')
        const eyeopen = document.getElementById('eyeopen')
        const password = document.getElementById('password')

        eyeopen.addEventListener('click', function() {
            password.setAttribute('type', 'text')
            eyeopen.classList.add('visually-hidden')
            eyeclosed.classList.remove('visually-hidden')
        })
        eyeclosed.addEventListener('click', function() {
            password.setAttribute('type', 'password')
            eyeclosed.classList.add('visually-hidden')
            eyeopen.classList.remove('visually-hidden')
        })
    </script>
    <x-jsCDN />
</body>

</html>
