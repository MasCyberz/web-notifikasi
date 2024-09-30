<x-app-layout>
    <x-PageHeader header="Data STNK" classcontainer="col-lg-8" />
    <div class="page-body">


        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create STNK --}}
            <form action="{{ route('stnk-update', ['id' => $kendaraanTerkait->id]) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <div>
                            <select class="form-select w-25" disabled name="nomor_polisi">
                                {{-- @foreach ($kendaraanTanpaSTNK as $item) --}}
                                    <option value="{{ $kendaraanTerkait->id }}">{{ $kendaraanTerkait->nomor_polisi }}</option>
                                {{-- @endforeach --}}
                            </select>
                        </div>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label">Tipe Perpanjangan</label>
                        <div>
                            <select class="form-select w-25" name="jenis_perpanjangan">
                                    <option value="1 Tahun">Perpanjangan 1 Tahun</option>
                                    <option value="5 Tahun">Perpanjangan 5 Tahun</option>
                            </select>
                        </div>
                    </div> --}}
                    {{-- Nomor STNK --}}
                    <x-Input label="Tgl Perpanjangan 1 Tahun" name="tgl_perpanjangan_1_tahun" value="{{ $perpanjangan_satu_tahun->tanggal_perpanjangan }}" type="date" class="w-25" />
                    <x-Input label="Biaya Perpanjangan 1 Tahun" name="biaya_perpanjangan_1_tahun" type="text" value="{{ $perpanjangan_satu_tahun->biaya }}"
                    class="" />
                    <x-Input label="Tgl Perpanjangan 5 Tahun" name="tgl_perpanjangan_5_tahun" value="{{ $perpanjangan_lima_tahun->tanggal_perpanjangan }}" type="date" class="w-25" />
                    <x-Input label="Biaya Perpanjangan 5 Tahun" name="biaya_perpanjangan_5_tahun" type="text" value="{{ $perpanjangan_lima_tahun->biaya }}"
                    class="" />
                    {{-- Tanggal Perpanjangan --}}
                    {{-- <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" data-mask="** 0000 ***"
                            data-mask-visible="true" placeholder="B 1234 XYZ" autocomplete="off">
                    </div> --}}
                </div>
                <x-cardFooter route="{{ route('stnk-index') }}" />
            </form>
        </div>
    </div>
</x-app-layout>
