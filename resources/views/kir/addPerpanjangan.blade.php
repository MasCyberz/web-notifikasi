<x-app-layout>
    <x-PageHeader header="Tambah Data Perpanjangan" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="col-12 col-lg-8 container-xl">
            <form action="{{ route('kir-storePerpanjangan') }}" method="POST" class="card">
                @csrf
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row row-cards">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">KIR Kendaraan</label>
                                <div>
                                    <select class="form-select w-100 w-md-50" name="kirs_id">
                                        @foreach ($KIRkendaraan as $kir)
                                            <option value="{{ $kir->id }}">
                                                {{ $kir->kendaraan->nomor_polisi }} | {{ $kir->kendaraan->tipe }} |
                                                {{ $kir->nomor_uji_kendaraan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <x-Input label="Tanggal Perpanjangan KIR" name="tanggal_expired_kir" type="date"
                                class="" :value="old('tanggal_expired_kir')" />
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Periode</label>
                                <div class="form-selectgroup">
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="periode" value="periode 1"
                                            class="form-selectgroup-input">
                                        <span class="form-selectgroup-label">Periode 1</span>
                                    </label>
                                    <label class="form-selectgroup-item">
                                        <input type="radio" name="periode" value="periode 2"
                                            class="form-selectgroup-input">
                                        <span class="form-selectgroup-label">Periode 2</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-cardFooter route="{{ route('kir-index') }}" />
            </form>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
