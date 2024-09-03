<x-app-layout>
    <x-PageHeader header="Form Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('kendaraan-store-add') }}" method="POST" class="card">
                        @csrf
                        <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" name="nomor_polisi" class="form-control" placeholder="B 1234 XYZ"
                                    value="{{ old('nomor_polisi') }}">
                            </div>
                            <div class="mb-3 w-100 w-lg-50 ">
                                <label class="form-label">Merk Kendaraan</label>
                                <select class="form-select" name="merk_kendaraan">
                                    <option value="toyota">Toyota</option>
                                    <option value="wuling">Wuling</option>
                                    <option value="mitsubishi">Mitsubishi</option>
                                    <option value="hyundai">Hyundai</option>
                                    <option value="kia">Kia</option>
                                    <option value="honda">Honda</option>
                                    <option value="yamaha">Yamaha</option>
                                    <option value="byd">BYD</option>
                                </select>
                            </div>
                            <x-Input label="Tipe Kendaraan" name="tipe" type="text" placeholder="Avanza 1.4 MT"
                                class="" value="{{ old('tipe') }}" />
                            <div class="mb-3 w-100 w-xl-50">
                                <label class="form-label">Jenis Kendaraan</label>
                                <select class="form-select" name="jenis_kendaraan">
                                    <option value="mobilpenumpang">MOBIL PENUMPANG</option>
                                    <option value="mobilbarang">MOBIL BARANG</option>
                                    <option value="sepedamotor">SEPEDA MOTOR</option>
                                    <option value="bus">BUS</option>
                                    <option value="kendaraankhusus">KENDARAAN KHUSUS</option>
                                </select>
                            </div>

                            <!-- Dropdown with Search Feature using Alpine.js -->
                            <div class="mb-3 w-100 w-lg-50" x-data="{ search: '' }">
                                <label class="form-label">Model Kendaraan</label>
                                <input type="text" x-model="search" class="form-control mb-2"
                                    placeholder="Cari model...">
                                <select class="form-select" name="model_kendaraan_id">
                                    <template
                                        x-for="model in {{ $models }}.filter(model => model.name.toLowerCase().includes(search.toLowerCase()))"
                                        :key="model.id">
                                        <option :value="model.id" x-text="model.name"></option>
                                    </template>
                                </select>
                            </div>


                            {{-- <x-Input label="Tahun" name="tahun" type="number" class="" /> --}}
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" min="1901"
                                    max="3000" placeholder="2024" value="{{ old('tahun') }}">
                            </div>
                            <x-Input label="Warna" name="warna" type="text" class=""
                                value="{{ old('warna') }}" />
                            <x-Input label="Nomor Rangka" name="nomor_rangka" type="text" class=""
                                value="{{ old('nomor_rangka') }}" />
                            <x-Input label="Nomor Mesin" name="nomor_mesin" type="text" class=""
                                value="{{ old('nomor_mesin') }}" />
                            <x-Input label="Bahan Bakar" name="bahan_bakar" type="text" class=""
                                value="{{ old('bahan_bakar') }}" />
                        </div>
                        <x-cardFooter route="{{ route('kendaraan-index') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush

</x-app-layout>
