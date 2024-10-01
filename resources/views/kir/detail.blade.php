<x-app-layout>
    <x-PageHeader header="Detail KIR Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader titleHeader="KIR Kendaraan {{ $kir->kir->kendaraan->nomor_polisi }}" />
                        <div class="card-body">
                            <div class="row">
                                @if ($kir->status != null)
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div
                                                class="form-control text-center fw-bold text-uppercase fs-2 {{ $kir->status === 'lulus' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                {{ $kir->status }}</div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No. Uji Kendaraan</label>
                                        <div class="form-control">{{ $kir->kir->nomor_uji_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Uji Kendaraan</label>
                                        <div class="form-control">
                                            {{ \Carbon\Carbon::parse($kir->tanggal_expired_kir)->format('d F Y') }}
                                        </div>
                                    </div>
                                </div>
                                @if ($kir->alasan_tidak_lulus != null)
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <div class="form-control">{{ $kir->alasan_tidak_lulus }}</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Data Kendaraan --}}
                                <div class="col-12 col-md-6 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Polisi</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->nomor_polisi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor BPKB</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->nomor_bpkb }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Merk Kendaraan</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->merk_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->tipe }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->jenis_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->modelKendaraan->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->tahun }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Registrasi</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->tahun_registrasi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rangka</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->nomor_rangka }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Mesin</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->nomor_mesin }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->warna }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->bahan_bakar }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ident</label>
                                        <div class="form-control">{{ $kir->kir->kendaraan->ident }}</div>
                                    </div>
                                </div>
                            </div>
                            <x-cardFooter route="{{ route('kir-index') }}" :showSubmitButton="false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
