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
                                        132 Total STNK {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary">
                                        12 pada bulan {{ \Carbon\Carbon::now()->format('F') }}
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
                                        132 Total KIR {{ \Carbon\Carbon::now()->format('Y') }}
                                    </div>
                                    <div class="text-secondary">
                                        32 pada bulan {{ \Carbon\Carbon::now()->format('F') }}
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

                {{-- Notifikasi PR (1,5 Bulan) --}}
                @foreach ($kirPR->slice(0, 4) as $kir)
                    <div class="col-xl-3">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pembuatan PR Untuk KIR</h5>
                                <p class="card-text">Segera buat PR untuk kendaraan {{ $kir->kendaraan->nomor_polisi }}
                                    untuk perpanjangan KIR pada <br>
                                    <span>{{ \Carbon\Carbon::parse($kir->tanggal_expired_kir)->format('d-M-Y') }}</span>.
                                </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @foreach ($stnkPR as $stnk)
                    <div class="col-xl-3">
                        <div class="card text-bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Pembuatan PR Untuk STNK</h5>
                                <p class="card-text">Segera buat PR untuk kendaraan
                                    {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} untuk perpanjangan STNK pada <br>
                                    <span>{{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->format('d-M-Y') }}</span>.
                                </p>
                                <a href="#" class="btn btn-light">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Notifikasi 10 Hari --}}

                @foreach ($stnkPRTenDays as $stnk)
                    <div class="col-xl-3">
                        <div class="card text-bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Perpanjangan STNK H-10</h5>
                                <p class="card-text">Batas waktu perpanjangan STNK untuk kendaraan
                                    {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }} tinggal 10 hari lagi. Segera
                                    lakukan tindakan yang diperlukan.</p>
                                <a href="#" class="btn btn-primary">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach ($kirPRTenDays as $kir)
                    <div class="col-xl-3">
                        <div class="card text-bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Perpanjangan KIR H-10</h5>
                                <p class="card-text">Batas waktu perpanjangan KIR untuk kendaraan
                                    {{ $kir->kendaraan->nomor_polisi }} tinggal 10 hari lagi. Segera
                                    lakukan tindakan yang diperlukan.</p>
                                <a href="#" class="btn btn-primary">Selengkapnya</a>
                            </div>
                        </div>
                    </div>
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
