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
                            {{-- <div class="mb-3">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" name="nomor_polisi" class="form-control" placeholder="B 1234 XYZ"
                                    value="{{ old('nomor_polisi') }}">
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">Nomor Polisi</label>
                                <input type="text" name="nomor_polisi"
                                    class="form-control @error('nomor_polisi') is-invalid @enderror"
                                    value="{{ old('nomor_polisi') }}" placeholder="B 1234 XYZ" autocomplete="off">
                                @error('nomor_polisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 w-100 w-lg-50">
                                <label class="form-label">Merk Kendaraan</label>
                                <select class="form-select @error('merk_kendaraan') is-invalid @enderror"
                                    name="merk_kendaraan">
                                    <option value="" selected>Pilih Merk Kendaraan</option>
                                    <option value="Toyota" {{ old('merk_kendaraan') == 'Toyota' ? 'selected' : '' }}>
                                        Toyota</option>
                                    <option value="Wuling" {{ old('merk_kendaraan') == 'Wuling' ? 'selected' : '' }}>
                                        Wuling</option>
                                    <option value="Mitsubishi"
                                        {{ old('merk_kendaraan') == 'Mitsubishi' ? 'selected' : '' }}>Mitsubishi
                                    </option>
                                    <option value="DSFK" {{ old('merk_kendaraan') == 'DSFK' ? 'selected' : '' }}>DSFK
                                    </option>
                                    <option value="Hyundai" {{ old('merk_kendaraan') == 'Hyundai' ? 'selected' : '' }}>
                                        Hyundai</option>
                                    <option value="Kia" {{ old('merk_kendaraan') == 'Kia' ? 'selected' : '' }}>Kia
                                    </option>
                                    <option value="Suzuki" {{ old('merk_kendaraan') == 'Suzuki' ? 'selected' : '' }}>
                                        Suzuki</option>
                                    <option value="BYD" {{ old('merk_kendaraan') == 'BYD' ? 'selected' : '' }}>BYD
                                    </option>
                                    <option value="Honda" {{ old('merk_kendaraan') == 'Honda' ? 'selected' : '' }}>
                                        Honda</option>
                                    <option value="Yamaha" {{ old('merk_kendaraan') == 'Yamaha' ? 'selected' : '' }}>
                                        Yamaha</option>
                                    <option value="ISUZU" {{ old('merk_kendaraan') == 'ISUZU' ? 'selected' : '' }}>
                                        ISUZU</option>
                                </select>
                                @error('merk_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <x-Input label="Tipe Kendaraan" name="tipe" type="text" placeholder="Avanza 1.4 MT"
                                class="" value="{{ old('tipe') }}" />
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mb-3 w-100 w-xl-50">
                                <label class="form-label">Jenis Kendaraan</label>
                                <select class="form-select @error('jenis_kendaraan') is-invalid @enderror"
                                    name="jenis_kendaraan">
                                    <option value="mobilpenumpang"
                                        {{ old('jenis_kendaraan') == 'mobilpenumpang' ? 'selected' : '' }}>MOBIL
                                        PENUMPANG</option>
                                    <option value="mobilbarang"
                                        {{ old('jenis_kendaraan') == 'mobilbarang' ? 'selected' : '' }}>MOBIL BARANG
                                    </option>
                                    <option value="sepedamotor"
                                        {{ old('jenis_kendaraan') == 'sepedamotor' ? 'selected' : '' }}>SEPEDA MOTOR
                                    </option>
                                    <option value="bus" {{ old('jenis_kendaraan') == 'bus' ? 'selected' : '' }}>BUS
                                    </option>
                                    <option value="kendaraankhusus"
                                        {{ old('jenis_kendaraan') == 'kendaraankhusus' ? 'selected' : '' }}>KENDARAAN
                                        KHUSUS</option>
                                </select>
                                @error('jenis_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dropdown with Search Feature using Alpine.js -->
                            <div class="mb-3 w-100 w-lg-50" x-data="{ search: '' }">
                                <label class="form-label">Model Kendaraan</label>
                                <input type="text" x-model="search" class="form-control mb-2"
                                    placeholder="Cari model...">
                                <select class="form-select @error('model_kendaraan_id') is-invalid @enderror"
                                    name="model_kendaraan_id">
                                    <template
                                        x-for="model in {{ json_encode($models) }}.filter(model => model.name.toLowerCase().includes(search.toLowerCase()))"
                                        :key="model.id">
                                        <option :value="model.id" x-text="model.name"></option>
                                    </template>
                                </select>
                                @error('model_kendaraan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun Pembuatan</label>
                                <input type="number" name="tahun" id="tahun"
                                    class="form-control @error('tahun') is-invalid @enderror" min="1901"
                                    placeholder="2024" value="{{ old('tahun') }}">
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <x-Input label="Warna" name="warna" type="text"
                                class="" value="{{ old('warna') }}" />
                            @error('warna')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor Rangka" name="nomor_rangka" type="text"
                                class=""
                                value="{{ old('nomor_rangka') }}" />
                            @error('nomor_rangka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor Mesin" name="nomor_mesin" type="text"
                                class="" value="{{ old('nomor_mesin') }}" />
                            @error('nomor_mesin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Bahan Bakar" name="bahan_bakar" type="text"
                                class="" value="{{ old('bahan_bakar') }}" />
                            @error('bahan_bakar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor BPKB" name="nomor_bpkb" type="text"
                                class="" value="{{ old('nomor_bpkb') }}" />
                            @error('nomor_bpkb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Tahun Registrasi" name="tahun_registrasi" type="text"
                                class=""
                                value="{{ old('tahun_registrasi') }}" />
                            @error('tahun_registrasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Ident" name="ident" type="text"
                                class="" value="{{ old('ident') }}" />
                            @error('ident')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

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
