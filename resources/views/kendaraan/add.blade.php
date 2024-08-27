<x-app-layout>
    <x-PageHeader header="Form Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create STNK --}}
            <form action="" class="card">
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" data-mask="B 0000 ***"
                            data-mask-visible="true" placeholder="B 1234 XYZ" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merk Kendaraan</label>
                        <div>
                            <select class="form-select w-25">
                                <option>Wuling</option>
                                <option>Mitsubishi</option>
                                <option>Hyundai</option>
                                <option>Kia</option>
                                <option>Honda</option>
                                <option>Yamaha</option>
                                <option>Yamaha</option>
                            </select>
                        </div>
                    </div>
                    <x-Input label="Tipe Kendaraan" name="nama_kendaraan" type="text" placeholder="Cortez"
                        class="w-100 w-xl-50 " />
                    <div class="mb-3">
                        <label class="form-label">Jenis Kendaraan</label>
                        <div>
                            <select class="form-select w-25">
                                <option>Mobil Penumpang</option>
                                <option>Mobil Barang</option>
                                <option>Sepeda Motor</option>
                                <option>Bus</option>
                                <option>Kendaraan Khusus</option>
                            </select>
                        </div>
                    </div>
                    <x-Input label="Model Kendaraan" name="model" type="text" placeholder="Minibus"
                        class="" />
                    <x-Input label="Tahun" name="tahun" type="number" class="" />
                    <x-Input label="Warna" name="warna" type="text" class="" />
                    <x-Input label="Nomor Mesin" name="nomor_mesin" type="text" class="" />
                    <x-Input label="Bahan Bakar" name="bahan_bakar" type="text" class="" />
                </div>
                <x-cardFooter route="{{ route('kendaraan-index') }}" />
            </form>
        </div>
    </div>
</x-app-layout>
