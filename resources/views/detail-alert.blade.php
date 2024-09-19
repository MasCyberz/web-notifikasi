<x-app-layout>
    <x-PageHeader header="Detail KIR Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader
                            titleHeader="KIR Kendaraan {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kendaraan->nomor_polisi }}" />
                        <div class="card-body">
                            <div class="row">
                                @if ($tipe === 'STNK')
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Perpanjangan</label>
                                            <div class="form-control">{{ $notifikasi->tanggal_perpanjangan }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Biaya</label>
                                            <div class="form-control">{{ $notifikasi->biaya }}</div>
                                        </div>
                                    </div>
                                @elseif ($tipe === 'KIR')
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Perpanjangan</label>
                                            <div class="form-control">{{ $notifikasi->tanggal_expired_kir }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Perpanjangan</label>
                                            <div class="form-control">{{ $notifikasi->nomor_uji_kendaraan }}</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Informasi Kendaraan --}}


                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Polisi</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kendaraan->nomor_polisi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Merk Kendaraan</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->merk_kendaraan ?? $notifikasi->kendaraan->merk_kendaraan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tipe ?? $notifikasi->kendaraan->tipe }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->jenis_kendaraan ?? $notifikasi->kendaraan->jenis_kendaraan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->modelKendaraan->name ?? $notifikasi->kendaraan->modelKendaraan->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tahun ?? $notifikasi->kendaraan->tahun }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->warna ?? $notifikasi->kendaraan->warna }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rangka</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_rangka ?? $notifikasi->kendaraan->nomor_rangka }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Mesin</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_mesin ?? $notifikasi->kendaraan->nomor_mesin }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->bahan_bakar ?? $notifikasi->kendaraan->bahan_bakar }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor BPKB</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_bpkb ?? ($notifikasi->kendaraan->nomor_bpkb ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Registrasi</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tahun_registrasi ?? ($notifikasi->kendaraan->tahun_registrasi ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Ident</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->ident ?? ($notifikasi->kendaraan->ident ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-cardFooter route="{{ route('dashboard') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
