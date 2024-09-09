<x-app-layout>
    <x-PageHeader header="Data KIR" classcontainer="col-lg-8" />
    <div class="page-body">


        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create KIR --}}
            <form action="{{ route('kir-edit-store', $kir->id) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kendaraan</label>
                                <div>
                                    <select class="form-select w-100 w-md-50" name="kendaraan_id_disabled" disabled>
                                        @foreach ($kendaraans as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $kir->kendaraan_id ? 'selected' : '' }}>
                                                {{ $item->nomor_polisi }} | {{ $item->tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- Input hidden untuk menyimpan nilai yang akan dikirim ke server -->
                                    <input type="hidden" name="kendaraan_id" value="{{ $kir->kendaraan_id }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-Input label="Nomor Uji Kendaraan" name="nomor_uji_kendaraan" type="text"
                                class="" :value="old('nomor_uji_kendaraan', $kir->nomor_uji_kendaraan)"/>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <x-Input label="Tanggal Perpanjangan KIR" name="tanggal_expired_kir" type="date"
                                class="" :value="old('tanggal_expired_kir', $kir->tanggal_expired_kir)" />
                        </div>
                    </div>
                </div>
                <x-cardFooter route="{{ route('stnk-index') }}" />
            </form>
        </div>
    </div>
</x-app-layout>
