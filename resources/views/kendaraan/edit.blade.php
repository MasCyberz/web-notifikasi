<x-app-layout>
    <x-PageHeader header="Form Kendaraan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('kendaraan-store-edit', $kendaraan->id) }}" method="POST" class="card">
                        @csrf
                        @method('PUT')
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
                                    <option value="Toyota" {{$kendaraan->merk_kendaraan == 'Toyota' ? 'selected' : ''}}>Toyota</option>
                                    <option value="Wuling" {{$kendaraan->merk_kendaraan == 'Wuling' ? 'selected' : ''}}>Wuling</option>
                                    <option value="Mitsubishi" {{$kendaraan->merk_kendaraan == 'Mitsubishi' ? 'selected' : ''}}>Mitsubishi</option>
                                    <option value="DSFK" {{$kendaraan->merk_kendaraan == 'DSFK' ? 'selected' : ''}}>DSFK</option>
                                    <option value="Hyundai" {{$kendaraan->merk_kendaraan == 'Hyundai' ? 'selected' : ''}}>Hyundai</option>
                                    <option value="Kia" {{$kendaraan->merk_kendaraan == 'Kia' ? 'selected' : ''}}>Kia</option>
                                    <option value="Suzuki" {{$kendaraan->merk_kendaraan == 'Suzuki' ? 'selected' : ''}}>Suzuki</option>
                                    <option value="BYD" {{$kendaraan->merk_kendaraan == 'BYD' ? 'selected' : ''}}>BYD</option>
                                    <option value="Honda" {{$kendaraan->merk_kendaraan == 'Honda' ? 'selected' : ''}}>Honda</option>
                                    <option value="Yamaha" {{$kendaraan->merk_kendaraan == 'Yamaha' ? 'selected' : ''}}>Yamaha</option>
                                    <option value="ISUZU" {{$kendaraan->merk_kendaraan == 'ISUZU' ? 'selected' : ''}}>ISUZU</option>
                                </select>
                            </div>
                            <x-Input label="Tipe Kendaraan" name="tipe" type="text" placeholder="Avanza 1.4 MT"
                                class="" value="{{ old('tipe', $kendaraan->tipe) }}" />
                            <div class="mb-3 w-100 w-xl-50">
                                <label class="form-label">Jenis Kendaraan</label>
                                <select class="form-select" name="jenis_kendaraan">
                                    <option value="Mobil Penumpang" {{ $kendaraan->jenis_kendaraan == 'Mobil Penumpang' ? 'selected' : ''}}>MOBIL PENUMPANG</option>
                                    <option value="MOBIL BARANG" {{ $kendaraan->jenis_kendaraan == 'MOBIL BARANG' ? 'selected' : ''}}>MOBIL BARANG</option>
                                    <option value="SEPEDA MOTOR" {{ $kendaraan->jenis_kendaraan == 'SEPEDA MOTOR' ? 'selected' : ''}}>SEPEDA MOTOR</option>
                                    <option value="BUS" {{ $kendaraan->jenis_kendaraan == 'BUS' ? 'selected' : ''}}>BUS</option>
                                    <option value="KENDARAAN KHUSUS" {{ $kendaraan->jenis_kendaraan == 'KENDARAAN KHUSUS' ? 'selected' : ''}}>KENDARAAN KHUSUS</option>
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
                                        <option :value="model.id" x-text="model.name"
                                            :selected="model.id == {{ $kendaraan->model_kendaraan_id }}"></option>
                                    </template>
                                </select>
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
