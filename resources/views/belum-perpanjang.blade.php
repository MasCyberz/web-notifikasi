<x-app-layout>
    @push('styles')
        <style>
            .back-to-top {
                position: fixed;
                bottom: 56px;
                right: 56px;
                border-radius: 100%;
                z-index: 1000;
            }
        </style>
    @endpush

    <x-pageHeader header="Data Yang Belum Perpanjangan" classcontainer="" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row mt-3">
                {{-- Notifikasi yang belum diperpanjang --}}
                @if ($notifikasiAktif->isNotEmpty())
                    @foreach ($notifikasiAktif as $item)
                        <div class="col-xl-3">
                            <div class="card text-bg-{{ $item->warna }} mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    <p class="card-text">
                                        Plat Nomor :
                                        <span
                                            class="fw-bold">{{ $item->toKendaraan->nomor_polisi ?? 'N/A' }}</span><br>

                                        @if ($item->tipe_notifikasi === 'KIR')
                                            Tanggal Expired KIR : {{ $item->tanggal_expired_kir->format('d M Y') }}<br>
                                        @elseif ($item->tipe_notifikasi === 'STNK')
                                            Tanggal Perpanjangan STNK :
                                            {{ $item->tanggal_perpanjangan->format('d M Y') }}<br>
                                            Jenis Perpanjangan :
                                            @if ($item->jenis_perpanjangan === '1 Tahun')
                                                1 Tahun
                                            @elseif ($item->jenis_perpanjangan === '5 Tahun')
                                                5 Tahun
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        @endif

                                        {{-- Tampilkan message hanya sekali --}}
                                        {{ $item->message }}

                                    </p>

                                    {{-- Tautan ke halaman detail berdasarkan tipe notifikasi --}}
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
                            Tidak ada kendaraan yang belum diperpanjang.
                        </div>
                    </div>
                @endif

                {{-- Notifikasi yang sudah tidak diperpanjang lebih dari 365 hari --}}
                <div class="col-md-12 my-3">
                    <h3 class="page-title"><span>Perpanjangan Tertunda (Lebih dari 1 Tahun)</span></h3>
                </div>
                @if ($notifikasiLebih365Hari->isNotEmpty())
                    @foreach ($notifikasiLebih365Hari as $item)
                        <div class="col-xl-3">
                            <div class="card text-bg-{{ $item->warna }} mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->judul }}</h5>
                                    <p class="card-text">
                                        Plat Nomor :
                                        <span
                                            class="fw-bold">{{ $item->toKendaraan->nomor_polisi ?? 'N/A' }}</span><br>
                                        {{-- Tentukan apakah notifikasi KIR atau STNK --}}
                                        @if ($item->tipe_notifikasi === 'KIR')
                                            Tanggal Expired KIR : {{ $item->tanggal_expired_kir->format('d M Y') }}<br>
                                        @elseif ($item->tipe_notifikasi === 'STNK')
                                            Tanggal Perpanjangan STNK :
                                            {{ $item->tanggal_perpanjangan->format('d M Y') }}<br>
                                            Jenis Perpanjangan :
                                            @if ($item->jenis_perpanjangan === '1 Tahun')
                                                1 Tahun
                                            @elseif ($item->jenis_perpanjangan === '5 Tahun')
                                                5 Tahun
                                            @else
                                                Tidak Diketahui
                                            @endif
                                        @endif

                                        {{ $item->message }}
                                    </p>

                                    {{-- Tautan ke halaman detail berdasarkan tipe notifikasi --}}
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
                            Tidak ada kendaraan yang sudah lebih dari 365 hari tidak diperpanjang.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <a href="" class="btn btn-primary btn-sm back-to-top" style="display: none;">
        <i class="ti ti-arrow-up fs-2"></i>
    </a>

    @push('scripts')
        <script>
            // Script untuk menampilkan atau menyembunyikan tombol ketika scroll
            window.onscroll = function() {
                const backToTopButton = document.querySelector('.back-to-top');
                if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            };

            // Scroll ke atas saat tombol diklik
            document.querySelector('.back-to-top').onclick = function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            };
        </script>
    @endpush
</x-app-layout>
