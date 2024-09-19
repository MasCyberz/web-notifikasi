<x-app-layout>
    <x-pageHeader header="Dashboard" classcontainer="" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-sm-6 col-xl-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span
                                        class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                        <i class="ti ti-car fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $totalStnk }} Total STNK {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary">
                                        {{ $totalStnkBulanIni }} pada bulan {{ \Carbon\Carbon::now()->format('F') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span
                                        class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                        <i class="ti ti-truck fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $totalKIR }} Total KIR {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary">
                                        {{ $totalKIRBulanIni }} pada bulan {{ \Carbon\Carbon::now()->format('F') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span
                                        class="bg-gray text-dark avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-x -->
                                        <i class="ti ti-users fs-2"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">
                                        {{ $dataUser }} User
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 my-3 d-flex justify-content-between">
                    <h3 class="page-title">Pemberitahuan!</h3>
                    <a href="{{ route('pemberitahuan-lainnya') }}" class="text-decoration-none text-secondary">
                        <p class="page-title fs-4">Lihat Selengkapnya...</p>
                    </a>
                </div>

                {{-- Notifikasi Hari H --}}

                @foreach ($stnkToday as $stnk)
                    @php
                        $deadline = \Carbon\Carbon::parse($stnk->tanggal_perpanjangan);
                        $message =
                            'STNK untuk kendaraan ' .
                            $stnk->RelasiSTNKtoKendaraan->nomor_polisi .
                            ' segera diperpanjang hari ini.';
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Waktunya Perpanjangan STNK!</h5>
                                <p class="card-text">Perpanjangan STNK untuk kendaraan
                                    <span class="fw-bold">{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</span> hari
                                    ini.
                                </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    <script>
                        function setCookie(name, value, days) {
                            var expires = "";
                            if (days) {
                                var date = new Date();
                                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                                expires = "; expires=" + date.toUTCString();
                            }
                            document.cookie = name + "=" + (value || "") + expires + "; path=/";
                        }

                        function getCookie(name) {
                            var nameEQ = name + "=";
                            var ca = document.cookie.split(';');
                            for (var i = 0; i < ca.length; i++) {
                                var c = ca[i];
                                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                            }
                            return null;
                        }

                        function showNotificationSTNK() {
                            if (Notification.permission === "granted") {
                                var options = {
                                    body: "{{ $message }}",
                                    requireInteraction: true,
                                };
                                new Notification("Pengingat STNK", options);
                                setCookie("notification-stnk-hari-ini-{{ $stnk->id }}", "shown", 1);
                            }
                        }

                        if (!getCookie("notification-stnk-hari-ini-{{ $stnk->id }}")) {
                            if (Notification.permission === "default") {
                                Notification.requestPermission().then(permission => {
                                    if (permission === "granted") {
                                        showNotificationSTNK();
                                    }
                                });
                            } else if (Notification.permission === "granted") {
                                showNotificationSTNK();
                            }
                        }
                    </script>
                @endforeach
                @foreach ($kirToday as $kir)
                    @php
                        $deadline = \Carbon\Carbon::parse($kir->tanggal_perpanjangan);
                        $message =
                            'STNK untuk kendaraan ' . $kir->kendaraan->nomor_polisi . ' segera diperpanjang hari ini.';
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Waktunya Perpanjangan STNK!</h5>
                                <p class="card-text">Perpanjangan STNK untuk kendaraan
                                    <span class="fw-bold">{{ $kir->kendaraan->nomor_polisi }}</span> hari ini.
                                </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    <script>
                        function setCookie(name, value, days) {
                            var expires = "";
                            if (days) {
                                var date = new Date();
                                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                                expires = "; expires=" + date.toUTCString();
                            }
                            document.cookie = name + "=" + (value || "") + expires + "; path=/";
                        }

                        function getCookie(name) {
                            var nameEQ = name + "=";
                            var ca = document.cookie.split(';');
                            for (var i = 0; i < ca.length; i++) {
                                var c = ca[i];
                                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                            }
                            return null;
                        }

                        function showNotificationSTNK() {
                            if (Notification.permission === "granted") {
                                var options = {
                                    body: "{{ $message }}",
                                    requireInteraction: true,
                                };
                                new Notification("Pengingat STNK", options);
                                setCookie("notification-kir-hari-ini-{{ $kir->id }}", "shown", 1);
                            }
                        }

                        if (!getCookie("notification-kir-hari-ini-{{ $kir->id }}")) {
                            if (Notification.permission === "default") {
                                Notification.requestPermission().then(permission => {
                                    if (permission === "granted") {
                                        showNotificationSTNK();
                                    }
                                });
                            } else if (Notification.permission === "granted") {
                                showNotificationSTNK();
                            }
                        }
                    </script>
                @endforeach

                {{-- Notifikasi 10 Hari --}}

                @foreach ($stnkTenDays as $stnk)
                    @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($stnk->tanggal_perpanjangan);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $diffInHours = $now->diffInHours($deadline, false); // Menghitung sisa jam
                        $diffInMinutes = $now->diffInMinutes($deadline, false); // Menghitung sisa menit
                        $message = '';
                        $showNotificationSTNKHminus10 = $diffInDays <= 10;

                        if ($diffInDays > 0) {
                            $message = "akan jatuh tempo dalam $diffInDays hari.";
                        } elseif ($diffInDays === 0 && $diffInHours > 0) {
                            $message = "akan jatuh tempo dalam $diffInHours jam.";
                        } elseif ($diffInHours === 0 && $diffInMinutes > 0) {
                            $message = "akan jatuh tempo dalam $diffInMinutes menit.";
                        }
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Perpanjangan STNK</h5>
                                <p class="card-text">STNK : <span class="fw-bold">{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</span> {{ $message }}</p>
                                <p class="card-text">Tanggal : {{ $stnk->tanggal_perpanjangan }} </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @if ($showNotificationSTNKHminus10)
                        <script>
                            function setCookie(name, value, days) {
                                var expires = "";
                                if (days) {
                                    var date = new Date();
                                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiry date
                                    expires = "; expires=" + date.toUTCString();
                                }
                                document.cookie = name + "=" + (value || "") + expires + "; path=/";
                            }

                            function getCookie(name) {
                                var nameEQ = name + "=";
                                var ca = document.cookie.split(';');
                                for (var i = 0; i < ca.length; i++) {
                                    var c = ca[i];
                                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                                }
                                return null;
                            }

                            function showNotificationSTNK() {
                                if (Notification.permission === "granted") {
                                    var options = {
                                        body: "STNK untuk kendaraan {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} {{ $message }}.",
                                        requireInteraction: true,
                                    };
                                    new Notification("Pengingat STNK", options);
                                    setCookie("notification-stnk-h10-{{ $stnk->id }}", "shown",
                                        1); // Set cookie for 1 day
                                }
                            }

                            if (!getCookie("notification-stnk-h10-{{ $stnk->id }}")) {
                                if (Notification.permission === "default") {
                                    Notification.requestPermission().then(permission => {
                                        if (permission === "granted") {
                                            showNotificationSTNK();
                                        }
                                    });
                                } else if (Notification.permission === "granted") {
                                    showNotificationSTNK();
                                }
                            }
                        </script>
                    @endif
                @endforeach
                @foreach ($kirTenDays as $kir)
                    @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($kir->tanggal_expired_kir);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $diffInHours = $now->diffInHours($deadline, false); // Menghitung sisa jam
                        $diffInMinutes = $now->diffInMinutes($deadline, false); // Menghitung sisa menit
                        $message = '';
                        $showNotificationKIRhMinus10 = $diffInDays <= 10;

                        if ($diffInDays > 0) {
                            $message = "akan jatuh tempo dalam $diffInDays hari.";
                        } elseif ($diffInDays === 0 && $diffInHours > 0) {
                            $message = "akan jatuh tempo dalam $diffInHours jam.";
                        } elseif ($diffInHours === 0 && $diffInMinutes > 0) {
                            $message = "akan jatuh tempo dalam $diffInMinutes menit.";
                        }
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Perpanjangan KIR</h5>
                                <p class="card-text">KIR : <span class="fw-bold">{{ $kir->kendaraan->nomor_polisi }}</span>
                                    {{ $message }}
                                </p>
                                <p>Tanggal : {{ $kir->tanggal_expired_kir }}</p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @if ($showNotificationKIRhMinus10)
                        <script>
                            function setCookie(name, value, days) {
                                var expires = "";
                                if (days) {
                                    var date = new Date();
                                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiry date
                                    expires = "; expires=" + date.toUTCString();
                                }
                                document.cookie = name + "=" + (value || "") + expires + "; path=/";
                            }

                            function getCookie(name) {
                                var nameEQ = name + "=";
                                var ca = document.cookie.split(';');
                                for (var i = 0; i < ca.length; i++) {
                                    var c = ca[i];
                                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                                }
                                return null;
                            }

                            function showNotificationKIR() {
                                if (Notification.permission === "granted") {
                                    var options = {
                                        body: "KIR untuk kendaraan {{ $kir->kendaraan->nomor_polisi }} {{ $message }}.",
                                        requireInteraction: true,
                                    };
                                    new Notification("Pengingat KIR", options);
                                    setCookie("notification-kir-h10-{{ $kir->id }}", "shown",
                                        1); // Set cookie for 1 day
                                }
                            }

                            if (!getCookie("notification-kir-h10-{{ $kir->id }}")) {
                                if (Notification.permission === "default") {
                                    Notification.requestPermission().then(permission => {
                                        if (permission === "granted") {
                                            showNotificationKIR();
                                        }
                                    });
                                } else if (Notification.permission === "granted") {
                                    showNotificationKIR();
                                }
                            }
                        </script>
                    @endif
                @endforeach

                {{-- Notifikasi PR (1,5 Bulan) --}}
                @foreach ($kirPR->slice(0, 4) as $kir)
                    @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($kir->tanggal_expired_kir);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $showNotification = $diffInDays <= 45;
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pembuatan PR Untuk KIR</h5>
                                <p class="card-text">Buat PR untuk kendaraan <span class="fw-bold">{{ $kir->kendaraan->nomor_polisi }}</span>
                                    untuk perpanjangan KIR pada </p>
                                    <p>Jatuh Tempo : {{ \Carbon\Carbon::parse($kir->tanggal_expired_kir)->format('d-M-Y') }}</p>
                                
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @if ($showNotification)
                        <script>
                            function setCookie(name, value, days) {
                                var expires = "";
                                if (days) {
                                    var date = new Date();
                                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiry date
                                    expires = "; expires=" + date.toUTCString();
                                }
                                document.cookie = name + "=" + (value || "") + expires + "; path=/";
                            }

                            function getCookie(name) {
                                var nameEQ = name + "=";
                                var ca = document.cookie.split(';');
                                for (var i = 0; i < ca.length; i++) {
                                    var c = ca[i];
                                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                                }
                                return null;
                            }

                            function showNotification() {
                                if (Notification.permission === "granted") {
                                    var options = {
                                        body: "KIR untuk kendaraan {{ $kir->kendaraan->nomor_polisi }} akan jatuh tempo pada {{ $kir->tanggal_expired_kir->format('d-m-Y') }}",
                                        requireInteraction: true,
                                    }

                                    var notification = new Notification("Persiapan PR KIR {{ $kir->kendaraan->nomor_polisi }}", options);
                                    setCookie("notification-kir-{{ $kir->id }}", "shown",
                                        1); // Notifikasi akan di-set ulang setiap hari
                                }
                            }

                            if (!getCookie("notification-kir-{{ $kir->id }}")) {
                                if (Notification.permission === "default") {
                                    Notification.requestPermission().then(permission => {
                                        if (permission === "granted") {
                                            showNotification();
                                        }
                                    });
                                } else if (Notification.permission === "granted") {
                                    showNotification();
                                }
                            }
                        </script>
                    @endif
                @endforeach
                @foreach ($stnkPR->slice(0, 4) as $stnk)
                    @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($stnk->tanggal_perpanjangan);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $showNotification = $diffInDays <= 45;
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">PR Perpanjangan STNK</h5>
                                <p class="card-text">Buat PR untuk kendaraan : <span class="fw-bold">{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</span></p>
                                <p>Jatuh Tempo : {{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->format('d-M-Y') }}</p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    @if ($showNotification)
                        <script>
                            function setCookie(name, value, days) {
                                var expires = "";
                                if (days) {
                                    var date = new Date();
                                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiry date
                                    expires = "; expires=" + date.toUTCString();
                                }
                                document.cookie = name + "=" + (value || "") + expires + "; path=/";
                            }

                            function getCookie(name) {
                                var nameEQ = name + "=";
                                var ca = document.cookie.split(';');
                                for (var i = 0; i < ca.length; i++) {
                                    var c = ca[i];
                                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                                }
                                return null;
                            }

                            function showNotification() {
                                if (Notification.permission === "granted") {
                                    var options = {
                                        body: "STNK untuk kendaraan {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} akan jatuh tempo pada {{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->format('d-M-Y') }}",
                                        requireInteraction: true,
                                    }

                                    var notification = new Notification("Persiapan PR STNK {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}",
                                        options);
                                    setCookie("notification-stnk-{{ $stnk->id }}", "shown", 1);;
                                }
                            }

                            if (!getCookie("notification-stnk-{{ $stnk->id }}")) {
                                if (Notification.permission === "default") {
                                    Notification.requestPermission().then(permission => {
                                        if (permission === "granted") {
                                            showNotification();
                                        }
                                    });
                                } else if (Notification.permission === "granted") {
                                    showNotification();
                                }
                            }
                        </script>
                    @endif
                @endforeach

                {{-- <div class="col-xl-3">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pembuatan PR dalam 1.5 Bulan</h5>
                            <p class="card-text">PR untuk kendaraan B 1234 CD perlu dibuat dalam 1.5 bulan.</p>
                            <a href="#" class="btn btn-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">PR Jatuh Tempo H-10</h5>
                            <p class="card-text">PR untuk kendaraan B 2345 EF jatuh tempo dalam 10 hari.</p>
                            <a href="#" class="btn btn-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
