<x-app-layout>
    <x-PageHeader header="Data KIR" classcontainer="col-lg-8"/>
    <div class="page-body">


        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create KIR --}}
            <form action="" class="card">
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!"/>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kendaraan</label>
                        <div>
                            <select class="form-select w-25">
                                <option>Wuling Cortez | B 1234 XYZ</option>
                                <option>Wuling Cortez | B 1234 XYZ</option>
                                <option>Wuling Cortez | B 1234 XYZ</option>
                            </select>
                        </div>
                    </div>
                    <x-Input label="Nomor Uji Kendaraan" name="nomor_uji_kendaraan" type="text" class="w-100 w-xl-50"/>
                    <x-Input label="Tanggal Perpanjangan KIR" name="tanggal_expired_kir" type="date" class="w-25"/>
                </div>
                <x-cardFooter route="{{ route('stnk-index') }}"/>
            </form>
        </div>
    </div>
</x-app-layout>
