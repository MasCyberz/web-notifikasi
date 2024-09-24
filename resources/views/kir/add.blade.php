<x-app-layout>
    <x-PageHeader header="Data KIR" classcontainer="col-lg-8" />
    <div class="page-body">


        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create KIR --}}
            <form action="{{ route('kir-tambah-store') }}" method="POST" class="card">
                @csrf
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kendaraan</label>
                                <div>
                                    <select class="form-select w-100 w-md-50" name="kendaraan_id">
                                        @foreach ($kendaraans as $item)
                                            <option value="{{ $item->id }}">{{ $item->nomor_polisi }} |
                                                {{ $item->tipe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-Input label="Nomor Uji Kendaraan" name="nomor_uji_kendaraan" type="text"
                                class="" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <x-Input label="Tanggal Perpanjangan KIR" name="tanggal_expired_kir" type="date"
                                class="" />
                        </div>
                    </div>
                </div>
                <x-cardFooter route="{{ route('stnk-index') }}" />
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
                document.querySelector('.tanggal-expired-kir input').setAttribute('min', today);
            });
        </script>
    @endpush
</x-app-layout>
