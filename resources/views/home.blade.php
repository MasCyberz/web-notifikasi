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
                    <a href="{{route('pemberitahuan-lainnya')}}" class="text-decoration-none text-secondary">
                        <p class="page-title fs-4">Lihat Selengkapnya...</p>
                    </a>
                </div>
                <div class="col-xl-3">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pembuatan PR dalam 1.5 Bulan</h5>
                            <p class="card-text">PR untuk kendaraan B 1234 CD perlu dibuat dalam 1.5 bulan.</p>
                            <a href="#" class="btn btn-primary">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
