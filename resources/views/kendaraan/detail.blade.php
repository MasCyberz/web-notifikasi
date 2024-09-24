<x-app-layout>
    <x-PageHeader header="Detail Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader titleHeader="Kendaraan {{ $kendaraan->nomor_polisi }}" />
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Polisi</label>
                                        <div class="form-control">{{ $kendaraan->nomor_polisi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor BPKB</label>
                                        <div class="form-control">{{ $kendaraan->nomor_bpkb }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Merk Kendaraan</label>
                                        <div class="form-control">{{ $kendaraan->merk_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <div class="form-control">{{ $kendaraan->tipe }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis</label>
                                        <div class="form-control">{{ $kendaraan->jenis_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <div class="form-control">{{ $kendaraan->modelKendaraan->name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control">{{ $kendaraan->tahun }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Registrasi</label>
                                        <div class="form-control">{{ $kendaraan->tahun_registrasi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rangka</label>
                                        <div class="form-control">{{ $kendaraan->nomor_rangka }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Mesin</label>
                                        <div class="form-control">{{ $kendaraan->nomor_mesin }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <div class="form-control">{{ $kendaraan->warna }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar</label>
                                        <div class="form-control">{{ $kendaraan->bahan_bakar }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">User Kendaraan</label>
                                        <div class="form-control">{{ $kendaraan->user_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ident</label>
                                        <div class="form-control">{{ $kendaraan->ident }}</div>
                                    </div>
                                </div>
                                
                            </div>
                            <x-cardFooter route="{{ route('kendaraan-index') }}" :showSubmitButton="false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
