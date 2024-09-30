<x-app-layout>
    <x-PageHeader header="Detail STNK Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader titleHeader="STNK Kendaraan {{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}" />
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Polisi</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor BPKB</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->nomor_bpkb }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pajak 1 Tahun</label>
                                        <div class="form-control">{{ \Carbon\Carbon::parse($perpanjangan_satu_tahun->tanggal_perpanjangan)->isoFormat('D MMMM Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Biaya Pajak 1 Tahun</label>
                                        <div class="form-control">{{ $perpanjangan_satu_tahun->biaya }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Pajak 5 Tahun</label>
                                        <div class="form-control">{{ \Carbon\Carbon::parse($perpanjangan_lima_tahun->tanggal_perpanjangan)->isoFormat('D MMMM Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"> Biaya Pajak 5 Tahun</label>
                                        <div class="form-control">{{ $perpanjangan_lima_tahun->biaya }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Merk Kendaraan</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->merk_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->tipe }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->jenis_kendaraan }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->modelKendaraan->name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->tahun }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Registrasi</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->tahun_registrasi }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rangka</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->nomor_rangka }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Mesin</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->nomor_mesin }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->warna }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->bahan_bakar }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ident</label>
                                        <div class="form-control">{{ $stnk->RelasiSTNKtoKendaraan->ident }}</div>
                                    </div>
                                </div>
                            </div>
                            <x-cardFooter route="{{ route('stnk-index') }}" :showSubmitButton="false"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
