<x-app-layout>
    <x-PageHeader header="Data STNK" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Edit STNK --}}
            <form action="{{ route('stnk-update', ['id' => $kendaraanTerkait->id]) }}" method="POST" class="card"
                id="form-edit">
                @csrf
                @method('PUT')
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <div>
                            <select class="form-select w-25" disabled name="nomor_polisi">
                                <option value="{{ $kendaraanTerkait->id }}">{{ $kendaraanTerkait->nomor_polisi }}
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Tanggal Perpanjangan 1 Tahun --}}
                    <x-Input label="Tgl Perpanjangan 1 Tahun" name="tgl_perpanjangan_1_tahun"
                        value="{{ old('tgl_perpanjangan_1_tahun', optional($perpanjangan_satu_tahun)->tanggal_perpanjangan ? optional($perpanjangan_satu_tahun)->tanggal_perpanjangan->format('Y-m-d') : '') }}"
                        type="date" class="w-25 mb-3" />

                    {{-- Biaya Perpanjangan 1 Tahun --}}
                    <x-Input label="Biaya Perpanjangan 1 Tahun" name="biaya_perpanjangan_1_tahun" type="text"
                        value="{{ old('biaya_perpanjangan_1_tahun', optional($perpanjangan_satu_tahun)->biaya) }}"
                        class="mb-3 biaya-input" />

                    {{-- Tanggal Perpanjangan 5 Tahun --}}
                    <x-Input label="Tgl Perpanjangan 5 Tahun" name="tgl_perpanjangan_5_tahun"
                        value="{{ old('tgl_perpanjangan_5_tahun', optional($perpanjangan_lima_tahun)->tanggal_perpanjangan ? optional($perpanjangan_lima_tahun)->tanggal_perpanjangan->format('Y-m-d') : '') }}"
                        type="date" class="w-25 mb-3" />

                    {{-- Biaya Perpanjangan 5 Tahun --}}
                    <x-Input label="Biaya Perpanjangan 5 Tahun" name="biaya_perpanjangan_5_tahun" type="text"
                        value="{{ old('biaya_perpanjangan_5_tahun', optional($perpanjangan_lima_tahun)->biaya ?? 'Tambahkan Data Perpanjangan Terlebih Dahulu') }}"
                        class="biaya-input" />
                </div>
                <x-cardFooter route="{{ route('stnk-index') }}" />
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fungsi untuk memformat angka dengan titik ribuan
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Fungsi untuk menghapus format titik pada angka
            function removeFormatNumber(number) {
                return number.replace(/\./g, "");
            }

            // Menambahkan event listener pada input dengan class "biaya-input"
            document.querySelectorAll('.biaya-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    // Menghapus format lama dan memasukkan format baru
                    let value = removeFormatNumber(this.value);
                    if (!isNaN(value) && value.length > 0) {
                        this.value = formatNumber(value);
                    }
                });
            });

            // Saat form disubmit, kita hapus titik agar format angka bersih
            document.getElementById('form-edit').addEventListener('submit', function() {
                document.querySelectorAll('.biaya-input').forEach(function(input) {
                    input.value = removeFormatNumber(input.value); // Menghapus semua titik
                });
            });
        </script>
    @endpush
</x-app-layout>
