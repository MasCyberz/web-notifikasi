<x-app-layout>
    <x-pageHeader header="Pemberitahuan Lainnya" classcontainer="" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row mt-3">

                {{-- Hari Ini --}}
                <div class="col-md-12 mb-3">
                    <h3 class="page-title"><span>Hari ini</span></h3>
                </div>
                @if (count($hariIniSTNK) == 0 && count($hariIniKIR) == 0)
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            Tidak ada Perpanjangan untuk hari ini.
                        </div>
                    </div>
                @endif
                @foreach ($hariIniSTNK as $stnk)
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
                                <h5 class="card-title">Perpanjangan STNK</h5>
                                <p class="card-text">{{ $message }}</p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    {{-- <script>
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
                                setCookie("notification-stnk-hari-ini-{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}", "shown", 1);
                            }
                        }

                        if (!getCookie("notification-stnk-hari-ini-{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}")) {
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
                    </script> --}}
                @endforeach
                @foreach ($hariIniKIR as $kir)
                    @php
                        $deadline = \Carbon\Carbon::parse($kir->tanggal_expired_kir);
                        $message =
                            'KIR untuk kendaraan ' . $kir->kendaraan->nomor_polisi . ' segera diperpanjang hari ini.';
                    @endphp
                    <div class="col-xl-3">
                        <div class="card text-bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Perpanjangan KIR</h5>
                                <p class="card-text">{{ $message }}</p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    {{-- <script>
                        function showNotificationKIR() {
                            if (Notification.permission === "granted") {
                                var options = {
                                    body: "{{ $message }}",
                                    requireInteraction: true,
                                };
                                new Notification("Pengingat KIR", options);
                                setCookie("notification-kir-hari-ini-{{ $kir->kendaraan->nomor_polisi }}", "shown", 1);
                            }
                        }

                        if (!getCookie("notification-kir-hari-ini-{{ $kir->kendaraan->nomor_polisi }}")) {
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
                    </script> --}}
                @endforeach


                {{-- H-10 --}}
                <div class="col-md-12 mb-3">
                    <h3 class="page-title"><span>H-10 Perpanjangan</span></h3>
                </div>
                @if (count($hMinus10STNK) == 0 && count($hMinus10KIR) == 0)
                    <div class="col-md-12"></div>
                        <div class="alert alert-info" role="alert">
                            Tidak ada Perpanjangan dalam H-10.
                        </div>
                    </div>
                @endif
                @foreach ($hMinus10STNK as $stnk)
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
                                <h5 class="card-title">Persiapan STNK {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</h5>
                                <p class="card-text">STNK : {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} {{ $message }} </p>
                                <p class="card-text">Tanggal : {{ $stnk->tanggal_perpanjangan->format('d-m-Y') }}</p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    {{-- @if ($showNotificationSTNKHminus10)
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
                                    setCookie("notification-stnk-{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}", "shown",
                                        1); // Set cookie for 1 day
                                }
                            }

                            if (!getCookie("notification-stnk-{{ $stnk->tanggal_perpanjangan }}")) {
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
                    @endif --}}
                @endforeach
                @foreach ($hMinus10KIR as $kir)
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
                    @if ($diffInDays >= 0 || $diffInHours >= 0 || $diffInMinutes >= 0)
                        <div class="col-xl-3">
                            <div class="card text-bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Persiapan KIR {{ $kir->kendaraan->nomor_polisi }}</h5>
                                    <p class="card-text">KIR : {{ $kir->kendaraan->nomor_polisi }} {{ $message }}</p>
                                    <p class="card-text">Tanggal : {{ $kir->tanggal_expired_kir->format('d-m-Y') }}</p>
                                    <a href="#" class="btn btn-light">Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- @if ($showNotificationKIRhMinus10)
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
                                    setCookie("notification-kir-{{ $kir->kendaraan->nomor_polisi }}", "shown",
                                        1); // Set cookie for 1 day
                                }
                            }

                            if (!getCookie("notification-kir-{{ $kir->tanggal_expired_kir }}")) {
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
                    @endif --}}
                @endforeach


                {{-- 45 Hari Sebelumnya --}}
                <div class="col-md-12 mb-3">
                    <h3 class="page-title"><span>1,5 bulan</span></h3>
                </div>
                @foreach ($prDateSTNK as $stnk)
                    {{-- @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($stnk->tanggal_perpanjangan);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $showNotification = $diffInDays <= 45;
                    @endphp --}}
                    <div class="col-xl-3 col-sm-6">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Persiapan PR Perpanjangan STNK</h5>
                                <p class="card-text">STNK : {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} </p>
                                <p class="card-text">Jatuh Tempo : {{ $stnk->tanggal_perpanjangan->format('d-M-Y') }}
                                </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    {{-- @if ($showNotification)
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
                                        body: "STNK untuk kendaraan {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} akan jatuh tempo pada {{ $stnk->tanggal_perpanjangan->format('d-m-Y') }}",
                                        requireInteraction: true,
                                    }

                                    var notification = new Notification("Persiapan PR STNK {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}",
                                        options);
                                    setCookie("notification-stnk-{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}", "shown", 1);;
                                }
                            }

                            if (!getCookie("notification-stnk-{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}")) {
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
                    @endif --}}
                @endforeach
                @foreach ($prDateKIR as $kir)
                    {{-- @php
                        $now = \Carbon\Carbon::now();
                        $deadline = \Carbon\Carbon::parse($kir->tanggal_expired_kir);
                        $diffInDays = $now->diffInDays($deadline, false); // Menghitung sisa hari
                        $showNotification = $diffInDays <= 45;
                    @endphp --}}
                    <div class="col-xl-3 col-sm-6">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Persiapan PR Perpanjangan KIR</h5>
                                <p class="card-text">KIR : {{ $kir->kendaraan->nomor_polisi }} </p>
                                <p class="card-text">Jatuh Tempo : {{ $kir->tanggal_expired_kir->format('d-m-Y') }} </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                    {{-- @if ($showNotification)
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
                                    setCookie("notification-kir-{{ $kir->kendaraan->nomor_polisi }}", "shown",
                                        1); // Notifikasi akan di-set ulang setiap hari
                                }
                            }

                            if (!getCookie("notification-kir-{{ $kir->kendaraan->nomor_polisi }}")) {
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
                    @endif --}}
                @endforeach


                {{-- <div class="col-md-12 mb-3">
                    <h3 class="page-title"><span>November</span></h3>
                </div>
                <div class="col-xl-3">
                    <div class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Perpanjangan STNK dalam</h5>
                            <p class="card-text">STNK untuk kendaraan B 2345 EF akan diperpanjang dalam 10 hari.</p>
                            <a href="#" class="btn btn-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <h3 class="page-title"><span>December</span></h3>
                </div>
                <div class="col-xl-3">
                    <div class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Perpanjangan STNK dalam</h5>
                            <p class="card-text">STNK untuk kendaraan B 2345 EF akan diperpanjang dalam 10 hari.</p>
                            <a href="#" class="btn btn-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
