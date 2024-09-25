<x-app-layout>
    <x-PageHeader header="Data STNK" classcontainer="col-lg-8" />
    <div class="page-body">


        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create STNK --}}
            <form action="{{ route('stnk-store') }}" method="POST" class="card">
                @csrf
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="mb-3 col-12">
                        <label class="form-label">Plat Nomor</label>
                        <select class="form-select tomselected" id="select-nomor-polisi" name="nomor_polisi"
                            placeholder="Pilih Plat Nomor...">
                            <option value="" disabled selected>Pilih Plat Nomor...</option> <!-- Placeholder -->
                            @foreach ($kendaraan as $item)
                                <option value="{{ $item->id }}">{{ $item->nomor_polisi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Perpanjangan</label>
                        <div>
                            <select class="form-select w-25" name="jenis_perpanjangan">
                                    <option value="1 Tahun">Perpanjangan 1 Tahun</option>
                                    <option value="5 Tahun">Perpanjangan 5 Tahun</option>
                            </select>
                        </div>
                    </div>
                    {{-- Nomor STNK --}}
                    <x-Input label="Biaya Perpanjangan Terakhir" name="biaya" type="text" placeholder="3.000.000"
                        class="" />
                    {{-- Tanggal Perpanjangan --}}
                    <x-Input label="Tgl. Perpanjangan STNK" name="tgl_perpanjangan" type="date" class="w-25" />
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect('#select-nomor-polisi', {
                    searchField: 'text',
                    create: false,
                    placeholder: 'Pilih Plat Nomor...',
                    onItemAdd(value) {
                        console.log('Item added:', value);
                    },
                    onItemRemove(value) {
                        console.log('Item removed:', value);
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
