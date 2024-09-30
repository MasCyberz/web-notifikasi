<x-app-layout>
    <x-pageHeader header="Pemberitahuan Lainnya" classcontainer="" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row mt-3">

                @php
                    $hariIni = $notifikasi->filter(function ($item) {
                        return $item->tanggal_perpanjangan->isSameDay(\Carbon\Carbon::today());
                    });
                @endphp

                @if ($hariIni->isNotEmpty())
                    <div class="col-md-12 mb-3">
                        <h3 class="page-title"><span>Hari ini</span></h3>
                    </div>
                    @foreach ($hariIni as $item)
                        <div class="col-xl-3">
                            <div class="card text-bg-danger mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    <p class="card-text">
                                        Plat Nomor: <span
                                            class="fw-bold">{{ $item->relasiSTNKtoKendaraan->nomor_polisi ?? 'N/A' }}</span>
                                        <br>
                                        Tenggat Waktu: {{ $item->message }}
                                        <br>
                                        Tanggal Perpanjangan: {{ $item->tanggal_perpanjangan->format('d-M-Y') }}
                                        <!-- Tampilkan jenis perpanjangan -->
                                        Jenis Perpanjangan:
                                        @if ($item->jenis_perpanjangan === '1 Tahun')
                                            1 Tahun
                                        @elseif ($item->jenis_perpanjangan === '5 Tahun')
                                            5 Tahun
                                        @else
                                            Tidak Diketahui
                                        @endif
                                    </p>
                                    {{-- <a href="{{ route('detail-alert', $item->id) }}"
                                        class="btn btn-light">Selengkapnya</a> --}}
                                    @if ($item->tipe_notifikasi === 'STNK')
                                        <a href="{{ route('detail-alert', ['id' => $item->id, 'tipe' => 'STNK']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @elseif ($item->tipe_notifikasi === 'KIR')
                                        <a href="{{ route('detail-alert', ['id' => $item->id, 'tipe' => 'KIR']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            Tidak ada Perpanjangan untuk hari ini.
                        </div>
                    </div>
                @endif


                {{-- H-10 --}}
                @php
                    $hMinus10 = $notifikasi->filter(function ($item) {
                        $daysToExpire = $item->tanggal_perpanjangan->diffInDays(\Carbon\Carbon::today());
                        return $daysToExpire > 0 && $daysToExpire <= 10;
                    });
                @endphp

                @if ($hMinus10->isNotEmpty())
                    <div class="col-md-12 mb-3">
                        <h3 class="page-title"><span>H-10 Perpanjangan</span></h3>
                    </div>
                    @foreach ($hMinus10 as $item)
                        <div class="col-xl-3">
                            <div class="card text-bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    <p class="card-text">
                                        Plat Nomor: <span
                                            class="fw-bold">{{ $item->relasiSTNKtoKendaraan->nomor_polisi ?? 'N/A' }}</span><br>
                                        Tenggat Waktu: {{ $item->message }}<br>
                                        Tanggal Perpanjangan: {{ $item->tanggal_perpanjangan->format('d-M-Y') }}<br>
                                        <!-- Tampilkan jenis perpanjangan jika STNK -->
                                        @if ($item->tipe_notifikasi === 'STNK')
                                            Jenis Perpanjangan:
                                            @if (isset($item->jenis_perpanjangan))
                                                @if ($item->jenis_perpanjangan === '1 Tahun')
                                                    1 Tahun
                                                @elseif ($item->jenis_perpanjangan === '5 Tahun')
                                                    5 Tahun
                                                @else
                                                    Tidak Diketahui
                                                @endif
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        @endif
                                    </p>
                                    @if ($item->tipe_notifikasi === 'STNK')
                                        <a href="{{ route('detail-alert', ['id' => $item->id, 'tipe' => 'STNK']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @elseif ($item->tipe_notifikasi === 'KIR')
                                        <a href="{{ route('detail-alert', ['id' => $item->id, 'tipe' => 'KIR']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            Tidak ada Perpanjangan dalam H-10.
                        </div>
                    </div>
                @endif


                {{-- H-45 --}}
                @php
                    $hMinus45 = $notifikasi->filter(function ($item) {
                        $daysToExpire = $item->tanggal_perpanjangan->diffInDays(\Carbon\Carbon::today());
                        return $daysToExpire > 10 && $daysToExpire <= 45;
                    });
                @endphp

                @if ($hMinus45->isNotEmpty())
                    <div class="col-md-12 mb-3">
                        <h3 class="page-title"><span>1,5 bulan</span></h3>
                    </div>
                    @foreach ($hMinus45 as $item)
                        <div class="col-xl-3">
                            <div class="card text-bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    <p class="card-text">
                                        Plat Nomor: <span
                                            class="fw-bold">{{ $item->relasiSTNKtoKendaraan->nomor_polisi ?? 'N/A' }}</span>
                                        <br>
                                        Tenggat Waktu: {{ $item->message }}
                                        <br>
                                        Tanggal Perpanjangan: {{ $item->tanggal_perpanjangan->format('d-M-Y') }}
                                        <!-- Tampilkan jenis perpanjangan jika STNK -->
                                        @if ($item->tipe_notifikasi === 'STNK')
                                            Jenis Perpanjangan:
                                            @if (isset($item->jenis_perpanjangan))
                                                @if ($item->jenis_perpanjangan === '1 Tahun')
                                                    1 Tahun
                                                @elseif ($item->jenis_perpanjangan === '5 Tahun')
                                                    5 Tahun
                                                @else
                                                    Tidak Diketahui
                                                @endif
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        @endif
                                    
                                    </p>
                                    @if ($item->tipe_notifikasi === 'STNK')
                                        <a href="{{ route('detail-alert', ['id' => $item->id, 'tipe' => 'STNK']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @elseif ($item->tipe_notifikasi === 'KIR')
                                        <a href="{{ route('detail-alert', ['id' => $item->kirHistories->pluck('id')->first(), 'tipe' => 'KIR']) }}"
                                            class="btn btn-light">Selengkapnya</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            Tidak ada Perpanjangan dalam 1,5 bulan ke depan.
                        </div>
                    </div>
                @endif


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
