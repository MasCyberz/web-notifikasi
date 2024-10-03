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
                                    name="merk_kendaraan" id="merk_kendaraan">
                                    <option value="" selected>Pilih Merk Kendaraan</option>
                                    <option value="Toyota" {{ old('merk_kendaraan') == 'Toyota' ? 'selected' : '' }}>
                                        Toyota</option>
                                    <option value="Wuling" {{ old('merk_kendaraan') == 'Wuling' ? 'selected' : '' }}>
                                        Wuling</option>
                                    <option value="Mitsubishi"
                                        {{ old('merk_kendaraan') == 'Mitsubishi' ? 'selected' : '' }}>Mitsubishi
                                    </option>
                                    <option value="DFSK" {{ old('merk_kendaraan') == 'DFSK' ? 'selected' : '' }}>DFSK
                                    </option>
                                    <option value="Daihatsu"
                                        {{ old('merk_kendaraan') == 'Daihatsu' ? 'selected' : '' }}>Daihatsu</option>
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
                                    <option value="Volkswagen"
                                        {{ old('merk_kendaraan') == 'Volkswagen' ? 'selected' : '' }}>Volkswagen
                                    </option>
                                    <option value="Nissan" {{ old('merk_kendaraan') == 'Nissan' ? 'selected' : '' }}>
                                        Nissan</option>
                                    <option value="MG" {{ old('merk_kendaraan') == 'MG' ? 'selected' : '' }}>MG
                                    </option>
                                </select>
                                <div class="mt-2">
                                    <input type="text" class="form-control" id="merk_baru"
                                        placeholder="Masukkan merk baru jika tidak ada di daftar">
                                </div>
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
                                    <option value="Mobil Penumpang"
                                        {{ old('jenis_kendaraan') == 'Mobbil Penumpang' ? 'selected' : '' }}>MOBIL
                                        PENUMPANG</option>
                                    <option value="Mobil Barang"
                                        {{ old('jenis_kendaraan') == 'Mobil Barang' ? 'selected' : '' }}>MOBIL BARANG
                                    </option>
                                    <option value="Sepeda Motor"
                                        {{ old('jenis_kendaraan') == 'Sepeda Motor' ? 'selected' : '' }}>SEPEDA MOTOR
                                    </option>
                                    <option value="Bus" {{ old('jenis_kendaraan') == 'Bus' ? 'selected' : '' }}>BUS
                                    </option>
                                    <option value="Kendaraan Khusus"
                                        {{ old('jenis_kendaraan') == 'Kendaraan Khusus' ? 'selected' : '' }}>KENDARAAN
                                        KHUSUS</option>
                                </select>
                                @error('jenis_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dropdown with Search Feature using Alpine.js -->
                            <div class="mb-3 w-100 w-lg-50" x-data="{
                                search: '',
                                models: {{ json_encode($models) }},
                                addNewModel() {
                                    // Cek apakah input untuk nama model baru tidak kosong
                                    if (this.search.trim() === '') {
                                        alert('Silakan masukkan nama model yang ingin ditambahkan.');
                                        return;
                                    }
                            
                                    // Cek apakah model sudah ada
                                    const existingModel = this.models.find(m => m.name.toLowerCase() === this.search.toLowerCase());
                            
                                    if (existingModel) {
                                        alert('Model sudah ada.');
                                        return;
                                    }
                            
                                    // Tampilkan pesan konfirmasi untuk menambahkan model baru
                                    if (confirm('Model tidak ditemukan. Apakah Anda ingin menambahkannya?')) {
                                        fetch('/data-kendaraan/tambah-models', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ name: this.search })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    this.models.push(data.model); // Tambahkan model baru ke dalam daftar
                                                    this.search = ''; // Reset input pencarian
                                                } else {
                                                    alert('Gagal menambahkan model.');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                            });
                                    }
                                }
                            }">
                                <label class="form-label">Model Kendaraan</label>

                                <div class="input-group mb-2">
                                    <input type="text" x-model="search" class="form-control"
                                        placeholder="Cari model...">
                                    <button type="button" class="btn btn-primary" @click="addNewModel()"
                                        x-show="search && models.filter(m => m.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                        Tambah Model
                                    </button>
                                </div>

                                <select class="form-select @error('model_kendaraan_id') is-invalid @enderror"
                                    name="model_kendaraan_id">
                                    <template
                                        x-if="models.filter(m => m.name.toLowerCase().includes(search.toLowerCase())).length > 0">
                                        <template
                                            x-for="model in models.filter(m => m.name.toLowerCase().includes(search.toLowerCase()))"
                                            :key="model.id">
                                            <option :value="model.id" x-text="model.name"></option>
                                        </template>
                                    </template>
                                    <template
                                        x-if="search && models.filter(m => m.name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                        <option disabled>No results found</option>
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

                            <x-Input label="Warna" name="warna" type="text" class=""
                                value="{{ old('warna') }}" />
                            @error('warna')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor Rangka" name="nomor_rangka" type="text" class=""
                                value="{{ old('nomor_rangka') }}" />
                            @error('nomor_rangka')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor Mesin" name="nomor_mesin" type="text" class=""
                                value="{{ old('nomor_mesin') }}" />
                            @error('nomor_mesin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Bahan Bakar" name="bahan_bakar" type="text" class=""
                                value="{{ old('bahan_bakar') }}" />
                            @error('bahan_bakar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Nomor BPKB" name="nomor_bpkb" type="text" class=""
                                value="{{ old('nomor_bpkb') }}" />
                            @error('nomor_bpkb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Tahun Registrasi" name="tahun_registrasi" type="text" class=""
                                value="{{ old('tahun_registrasi') }}" />
                            @error('tahun_registrasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="Ident" name="ident" type="text" class=""
                                value="{{ old('ident') }}" />
                            @error('ident')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <x-Input label="User" name="user_kendaraan" type="text" class=""
                                value="{{ old('user') }}" />
                            @error('user')
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
    <script>
        document.getElementById('merk_baru').addEventListener('change', function() {
            var newMerk = this.value;
            if (newMerk) {
                var select = document.getElementById('merk_kendaraan');
                var optionExists = Array.from(select.options).some(option => option.value === newMerk);
    
                // Jika opsi belum ada, tambahkan opsi baru
                if (!optionExists) {
                    var newOption = document.createElement('option');
                    newOption.value = newMerk;
                    newOption.text = newMerk;
                    select.appendChild(newOption);
                    
                    // Pilih merk baru yang ditambahkan
                    select.value = newMerk;
                }
            }
        });
    </script>
    
    @endpush

</x-app-layout>
