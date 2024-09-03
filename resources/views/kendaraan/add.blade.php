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
                                <input type="text" name="nomor_polisi" class="form-control" data-mask="B 0000 ***"
                                    data-mask-visible="true" placeholder="B 1234 XYZ" autocomplete="off">
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
                                class="" />
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
                            {{-- <div x-data="modelSelect()" class="mb-3">
                                <label for="model-dropdown">Pilih Model Kendaraan</label>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="model-dropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span x-text="selectedModelName || 'Pilih Model'"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="model-dropdown">
                                        <template x-for="model in models" :key="model.id">
                                            <li>
                                                <a class="dropdown-item d-flex justify-content-between" href="#" @click="selectModel(model)">
                                                    <span x-text="model.name"></span>
                                                    <button type="button" @click.stop="confirmDelete(model.id)"
                                                        class="btn btn-danger btn-sm float-end">Hapus</button>
                                                </a>
                                            </li>
                                        </template>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <div class="dropdown-item">
                                                <input type="text" x-model="newModelName"
                                                    placeholder="Tambahkan model baru" class="form-control"
                                                    @keydown.enter.prevent="confirmAddNewModel()">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div> --}}

                            <div x-data="modelSelect()" class="mb-3">
                                <label for="model-dropdown">Pilih Model Kendaraan</label>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="model-dropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span x-text="selectedModelName || 'Pilih Model'"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="model-dropdown">
                                        <template x-for="model in models" :key="model.id">
                                            <li>
                                                <a class="dropdown-item" href="#" @click="selectModel(model)">
                                                    <span x-text="model.name"></span>
                                                </a>
                                            </li>
                                        </template>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <div class="dropdown-item d-flex gap-2">
                                                <input type="text" x-model="newModelName"
                                                    placeholder="Tambahkan model baru" class="form-control"
                                                    @keydown.enter.prevent="confirmAddNewModel()">
                                                <button type="button" class="btn btn-primary d-xl-none"
                                                    @click="confirmAddNewModel()">Tambah Model</button>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="model_kendaraan_id" :value="selectedModel">
                            </div>
                            {{-- <x-Input label="Tahun" name="tahun" type="number" class="" /> --}}
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" min="1901"
                                    max="2155" placeholder="2024">
                            </div>
                            <x-Input label="Warna" name="warna" type="text" class="" />
                            <x-Input label="Nomor Rangka" name="nomor_rangka" type="text" class="" />
                            <x-Input label="Nomor Mesin" name="nomor_mesin" type="text" class="" />
                            <x-Input label="Bahan Bakar" name="bahan_bakar" type="text" class="" />
                        </div>
                        <x-cardFooter route="{{ route('kendaraan-index') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // function modelSelect() {
            //     return {
            //         models: @json($models),
            //         selectedModel: '',
            //         selectedModelName: '',
            //         newModelName: '',
            //         confirmDelete(modelId) {
            //             if (confirm('Apakah Anda yakin ingin menghapus model ini?')) {
            //                 this.deleteModel(modelId);
            //             }
            //         },
            //         deleteModel(modelId) {
            //             axios.post('/data-kendaraan/hapus-models', {
            //                     id: modelId
            //                 })
            //                 .then(response => {
            //                     this.models = this.models.filter(model => model.id !== modelId);
            //                     if (this.selectedModel === modelId) {
            //                         this.selectedModel = '';
            //                         this.selectedModelName = '';
            //                     }
            //                 })
            //                 .catch(error => {
            //                     console.error(error);
            //                 });
            //         },
            //         confirmAddNewModel() {
            //             if (this.newModelName.trim() !== '' && confirm('Apakah Anda yakin ingin menambahkan model ini?')) {
            //                 this.addNewModel();
            //             }
            //         },
            //         selectModel(model) {
            //             this.selectedModel = model.id;
            //             this.selectedModelName = model.name;
            //         },
            //         addNewModel() {
            //             if (this.newModelName.trim() !== '') {
            //                 axios.post('/data-kendaraan/tambah-models', {
            //                         name: this.newModelName
            //                     })
            //                     .then(response => {
            //                         this.models.push(response.data);
            //                         this.selectedModel = response.data.id;
            //                         this.selectedModelName = response.data.name;
            //                         this.newModelName = '';
            //                     })
            //                     .catch(error => {
            //                         console.error(error);
            //                     });
            //             }
            //         }
            //     };
            // }
            function modelSelect() {
                return {
                    models: @json($models), // Ensure $models contains your model_kendaraan data
                    selectedModel: '',
                    selectedModelName: '',
                    newModelName: '',
                    confirmAddNewModel() {
                        if (this.newModelName.trim() !== '' && confirm('Apakah Anda yakin ingin menambahkan model ini?')) {
                            this.addNewModel();
                        }
                    },
                    addNewModel() {
                        if (this.newModelName.trim() !== '') {
                            axios.post('/data-kendaraan/tambah-models', {
                                    name: this.newModelName
                                })
                                .then(response => {
                                    this.models.push(response.data);
                                    this.selectedModel = response.data.id;
                                    this.selectedModelName = response.data.name;
                                    this.newModelName = '';
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    },
                    selectModel(model) {
                        this.selectedModel = model.id;
                        this.selectedModelName = model.name;
                    }
                };
            }

            // Handle adding new model with Enter key on desktop
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const newModelName = document.querySelector('input[placeholder="Tambahkan model baru"]').value;
                    if (newModelName.trim() !== '') {
                        if (confirm('Apakah Anda yakin ingin menambahkan model ini?')) {
                            axios.post('/data-kendaraan/tambah-models', {
                                    name: newModelName
                                })
                                .then(response => {
                                    const modelDropdown = document.querySelector('#model-dropdown');
                                    const option = document.createElement('option');
                                    option.value = response.data.id;
                                    option.textContent = response.data.name;
                                    modelDropdown.appendChild(option);
                                    modelDropdown.value = response.data.id;
                                    document.querySelector('input[placeholder="Tambahkan model baru"]').value = '';
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
