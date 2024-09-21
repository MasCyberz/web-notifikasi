<x-app-layout>
    <x-PageHeader header="{{ $tipe === 'STNK' ? 'Detail STNK Kendaraan' : 'Detail KIR Kendaraan' }}"
        classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader
                            titleHeader="{{ $tipe === 'STNK' ? 'Detail STNK Kendaraan' : 'Detail KIR Kendaraan' }} {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kendaraan->nomor_polisi }}" />
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
                                            <label class="form-label">No Uji KIR</label>
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

                            @if ($tipe === 'KIR')
                                <div class="mt-4">


                                    <form action="#" method="POST">
                                        @csrf
                                        <div class="d-flex justify-content-between">
                                            <!-- Tombol Terima -->
                                            <button type="submit" class="btn btn-success" name="action"
                                                value="terima">Terima</button>

                                            <!-- Tombol Tolak dengan Alpine.js -->
                                            <div x-data="{ tolak: false }">
                                                <button type="button" class="btn btn-danger"
                                                    @click="tolak = !tolak">Tolak</button>

                                                <!-- Input text muncul ketika tolak di-klik -->
                                                <div x-show="tolak" class="mt-3">
                                                    <label for="alasanTolak" class="form-label">Alasan
                                                        Penolakan:</label>
                                                    <textarea id="alasanTolak" class="form-control" name="alasan_tolak" rows="3"
                                                        placeholder="Jelaskan alasan penolakan"></textarea>
                                                </div>

                                                <!-- Submit button jika tolak -->
                                                <button type="submit" class="btn btn-danger mt-3" name="action"
                                                    value="tolak">Submit Tolak</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <x-cardFooter route="{{ route('dashboard') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
