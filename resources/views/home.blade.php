<x-app-layout>

    @push('styles')
        <style>
            /* Custom styles for full-width alert on mobile */
            @media (max-width: 768px) {
                .alert {
                    width: 100%;
                    right: 0;
                    margin: 50px 0;
                    position: absolute;
                    top: 0;
                }
            }
        </style>
    @endpush

    <x-pageHeader header="Dashboard" classcontainer="" />

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Selamat!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12 col-md-6 {{ Auth::user()->role_id == 1 ? 'col-xl-4' : '' }}">
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
                                    <div class="fw-bold o">
                                        {{ $totalStnk }} Total STNK {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary fw-bold">
                                        {{ $totalStnkBulanIni }} perpanjangan pada bulan
                                        {{ \Carbon\Carbon::now()->format('F') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 {{ Auth::user()->role_id == 1 ? 'col-xl-4' : '' }}">
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
                                    <div class="fw-bold o">
                                        {{ $totalKIR }} Total KIR {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary fw-bold">
                                        {{ $totalKIRBulanIni }} perpanjangan pada bulan
                                        {{ \Carbon\Carbon::now()->format('F') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->role_id == 1)
                    <div class="col-12 col-xl-4">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="bg-yellow text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-x -->
                                            <i class="ti ti-users fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold">
                                            {{ $dataUser }} User
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row mt-3">
                <div class="col-md-12 my-3 d-flex justify-content-between">
                    <h3 class="page-title">Pemberitahuan!</h3>
                    <a href="{{ route('pemberitahuan-lainnya') }}" class="text-decoration-none text-secondary">
                        Lihat Selengkapnya...
                    </a>
                    {{-- <a href="{{ route('belum-perpanjang') }}" class="text-decoration-none text-warning">
                        <i class="ti ti-exclamation-triangle"></i> Belum Diperpanjang
                    </a>                     --}}
                </div>
                

                @forelse ($allNotifications as $notifikasi)
                    @php
                        $warna = '';
                        $judul = '';

                        if ($notifikasi->tipe_notifikasi === 'STNK') {
                            if ($notifikasi->kategori_waktu === 'H-45') {
                                $judul = 'Pembuatan PR STNK';
                                $warna = 'primary'; // H-45 hingga H-11
                            } elseif ($notifikasi->kategori_waktu === 'H-10') {
                                $judul = 'Perpanjangan STNK';
                                $warna = 'warning'; // H-10 hingga H-1
                            } else {
                                $judul = 'Perpanjangan STNK';
                                $warna = 'danger'; // Hari H dan jatuh tempo
                            }
                        } elseif ($notifikasi->tipe_notifikasi === 'KIR') {
                            if ($notifikasi->kategori_waktu === 'H-45') {
                                $judul = 'Pembuatan PR KIR';
                                $warna = 'primary'; // H-45 hingga H-11
                            } elseif ($notifikasi->kategori_waktu === 'H-10') {
                                $judul = 'Perpanjangan KIR';
                                $warna = 'warning'; // H-10 hingga H-1
                            } else {
                                $judul = 'Perpanjangan KIR';
                                $warna = 'danger'; // Hari H dan jatuh tempo
                            }
                        }
                    @endphp

                    <div class="col-xl-3">
                        <div class="card text-bg-{{ $notifikasi->warna }} mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $notifikasi->judul }}</h5>
                                <p class="card-text">
                                    Plat Nomor: <span
                                        class="fw-bold">{{ $notifikasi->relasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kir->kendaraan->nomor_polisi }}</span>
                                    <br>
                                    Tenggat Waktu: {{ $notifikasi->message }}
                                    <br>
                                    Tanggal Perpanjangan:
                                    {{ \Carbon\Carbon::parse($notifikasi['tenggat'])->format('d M Y') }}
                                </p>
                                @if ($notifikasi->tipe_notifikasi === 'STNK')
                                    <a href="{{ route('detail-alert', ['id' => $notifikasi->id, 'tipe' => 'STNK']) }}"
                                        class="btn btn-light">Selengkapnya</a>
                                @elseif ($notifikasi->tipe_notifikasi === 'KIR')
                                    <a href="{{ route('detail-alert', ['id' => $notifikasi->id, 'tipe' => 'KIR']) }}"
                                        class="btn btn-light">Selengkapnya</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Script Notifikasi --}}
                    @if ($notifikasi->showNotification)
                        @push('scripts')
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
                                            body: "{{ $notifikasi->message }}",
                                            requireInteraction: true,
                                        };
                                        new Notification("Pengingat {{ $judul }}", options);
                                        setCookie(
                                            "notification-{{ $notifikasi->tipe_notifikasi }}-{{ $notifikasi->kategori_waktu }}-{{ $notifikasi->id }}",
                                            "shown", 1);
                                    }
                                }

                                if (!getCookie(
                                        "notification-{{ $notifikasi->tipe_notifikasi }}-{{ $notifikasi->kategori_waktu }}-{{ $notifikasi->id }}"
                                    )) {
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
                        @endpush
                    @endif
                @empty
                    <p>Tidak ada notifikasi yang tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setTimeout(function() {
                let alert = document.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('hide');
                }
            }, 3000); // Menghilang setelah 5 detik
        </script>
    @endpush
</x-app-layout>
