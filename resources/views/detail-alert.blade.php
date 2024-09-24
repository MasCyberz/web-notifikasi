<x-app-layout>
    <x-PageHeader header="{{ $tipe === 'STNK' ? 'Detail STNK Kendaraan' : 'Detail KIR Kendaraan' }}"
        classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <x-cardHeader
                            titleHeader="{{ $tipe === 'STNK' ? 'Detail STNK Kendaraan' : 'Detail KIR Kendaraan' }} {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kendaraan->nomor_polisi }}" />
                        <div class="card-body">
                            <div class="row">
                                @if ($KIRHistory->status != null)
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div
                                                class="form-control text-center fw-bold text-uppercase fs-2 {{ $KIRHistory->status === 'lulus' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                                {{ $KIRHistory->status }}</div>
                                        </div>
                                    </div>
                                @endif
                                @if ($tipe === 'STNK')
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Perpanjangan</label>
                                            <div class="form-control">{{ $notifikasi->tanggal_perpanjangan }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Biaya</label>
                                            <div class="form-control">{{ $notifikasi->biaya }}</div>
                                        </div>
                                    </div>
                                @elseif ($tipe === 'KIR')
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Perpanjangan</label>
                                            <div class="form-control">
                                                {{ \Carbon\Carbon::parse($KIRHistory->tanggal_expired_kir)->format('d-m-Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">No Uji KIR</label>
                                            <div class="form-control">{{ $notifikasi->nomor_uji_kendaraan }}</div>
                                        </div>
                                    </div>
                                @endif
                                @if ($KIRHistory->alasan_tidak_lulus != null)
                                    <div class="col-12">
                                        <label class="form-label">Keterangan</label>
                                        <div class="mb-3">
                                            <div class="form-control">
                                                {{ $KIRHistory->alasan_tidak_lulus }}</div>
                                        </div>
                                    </div>
                                @endif


                                {{-- Informasi Kendaraan --}}


                                <div class="col-12 col-md-6 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Polisi</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_polisi ?? $notifikasi->kendaraan->nomor_polisi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor BPKB</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_bpkb ?? ($notifikasi->kendaraan->nomor_bpkb ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Merk Kendaraan</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->merk_kendaraan ?? $notifikasi->kendaraan->merk_kendaraan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipe</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tipe ?? $notifikasi->kendaraan->tipe }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->jenis_kendaraan ?? $notifikasi->kendaraan->jenis_kendaraan }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Model</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->modelKendaraan->name ?? $notifikasi->kendaraan->modelKendaraan->name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tahun ?? $notifikasi->kendaraan->tahun }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tahun Registrasi</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->tahun_registrasi ?? ($notifikasi->kendaraan->tahun_registrasi ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Rangka</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_rangka ?? $notifikasi->kendaraan->nomor_rangka }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Mesin</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->nomor_mesin ?? $notifikasi->kendaraan->nomor_mesin }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->warna ?? $notifikasi->kendaraan->warna }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bahan Bakar</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->bahan_bakar ?? $notifikasi->kendaraan->bahan_bakar }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Ident</label>
                                        <div class="form-control">
                                            {{ $notifikasi->RelasiSTNKtoKendaraan->ident ?? ($notifikasi->kendaraan->ident ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($tipe === 'KIR' && \Carbon\Carbon::parse($KIRHistory->tanggal_expired_kir)->isPast())
                                @if (empty($KIRHistory->status) && empty($KIRHistory->alasan_tidak_lulus))
                                    <div class="my-4">
                                        <div class="row">
                                            <div class="col-md-6 mt-2 mt-lg-0">
                                                <!-- Form untuk Tidak Lulus -->
                                                <form id="tidakLulusForm"
                                                    action="{{ route('kir-update-status-kir', $KIRHistory->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="tidak lulus">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        data-bs-toggle="modal" data-bs-target="#tolakModal">Tidak
                                                        Lulus</button>

                                                    <!-- Modal Konfirmasi Tidak Lulus -->
                                                    <div class="modal modal-blur fade" id="tolakModal" tabindex="-1"
                                                        aria-labelledby="tolakModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="tolakModalLabel">
                                                                        Alasan
                                                                        Tidak Lulus</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Silakan jelaskan alasan tidak lulus KIR ini. Dengan
                                                                    mengisi form, Anda akan menyatakan proses KIR
                                                                    sebagai
                                                                    tidak
                                                                    lulus.
                                                                    <div class="mb-3 mt-3">
                                                                        <label for="alasan_tidak_lulus"
                                                                            class="form-label">Alasan
                                                                            tidak lulus:</label>
                                                                        <textarea id="alasan_tidak_lulus" class="form-control" name="alasan_tidak_lulus" rows="3"
                                                                            placeholder="Jelaskan alasan penolakan"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Kirim</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="col-md-6">
                                                <!-- Form untuk Lulus -->
                                                <form id="lulusForm"
                                                    action="{{ route('kir-update-status-kir', $KIRHistory->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="lulus">
                                                    <button type="button" class="btn btn-success w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#terimaModal">Lulus</button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal Konfirmasi Lulus -->
                                        <div class="modal modal-blur fade" id="terimaModal" tabindex="-1"
                                            aria-labelledby="terimaModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="terimaModalLabel">Konfirmasi Lulus
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menyatakan KIR ini sebagai lulus?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success"
                                                            form="lulusForm">Ya, Lulus</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <x-cardFooter route="{{ route('dashboard') }}" :showSubmitButton="false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

</x-app-layout>
