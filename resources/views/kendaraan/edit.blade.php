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
                                    value="{{ old('nomor_polisi', $kendaraan->nomor_polisi) }}">
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
                                class="" value="{{ old('tipe', $kendaraan->tipe) }}" />
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
                            <div class="mb-3 w-100 w-lg-50" x-data="{ open: false, search: '', selected: {{ $kendaraan->model_kendaraan_id }} }">
                                <label class="form-label">Model Kendaraan</label>
                                <div class="relative">
                                    <button type="button" @click="open = !open" class="form-select w-100">
                                        <span x-text="selectedName"></span>
                                    </button>
                                    <div x-show="open" @click.outside="open = false"
                                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                                        <input type="text" x-model="search"
                                            class="w-full px-3 py-2 border-b border-gray-300 rounded-t-lg focus:outline-none"
                                            placeholder="Cari model...">
                                        <ul class="max-h-60 overflow-y-auto">
                                            <template x-for="model in filteredModels" :key="model.id">
                                                <li @click="selectModel(model)"
                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                                                    x-text="model.name"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            {{-- <x-Input label="Tahun" name="tahun" type="number" class="" /> --}}
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" min="1901"
                                    max="3000" placeholder="2024" value="{{ old('tahun', $kendaraan->tahun) }}">
                            </div>
                            <x-Input label="Warna" name="warna" type="text" class=""
                                value="{{ old('warna', $kendaraan->warna) }}" />
                            <x-Input label="Nomor Rangka" name="nomor_rangka" type="text" class=""
                                value="{{ old('nomor_rangka', $kendaraan->nomor_rangka) }}" />
                            <x-Input label="Nomor Mesin" name="nomor_mesin" type="text" class=""
                                value="{{ old('nomor_mesin', $kendaraan->nomor_mesin) }}" />
                            <x-Input label="Bahan Bakar" name="bahan_bakar" type="text" class=""
                                value="{{ old('bahan_bakar', $kendaraan->bahan_bakar) }}" />
                        </div>
                        <x-cardFooter route="{{ route('kendaraan-index') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdownSearch', () => ({
                    open: false,
                    search: '',
                    selected: @json($kendaraan->model_kendaraan_id),

                    get filteredModels() {
                        return @json($models).filter(model => model.name.toLowerCase()
                            .includes(this.search.toLowerCase()));
                    },

                    get selectedName() {
                        const model = @json($models).find(model => model.id === this
                            .selected);
                        return model ? model.name : 'Select Model';
                    },

                    selectModel(model) {
                        this.selected = model.id;
                        this.open = false;
                    }
                }));
            });
        </script>
    @endpush

</x-app-layout>
