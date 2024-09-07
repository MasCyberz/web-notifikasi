<x-app-layout>
    <x-PageHeader header="Data STNK" classcontainer="" />
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-row-reverse">
                            <a href="{{ route('stnk-tambah') }}" class="btn btn-primary"> <i
                                    class="ti ti-plus fs-2"></i>Tambah Data
                            </a>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-secondary">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="8"
                                            size="3" aria-label="Invoices count" fdprocessedid="7ljtz">
                                    </div>
                                    entries
                                    <div class="mx-2 d-inline-block">
                                        <input type="number" class="form-control form-control-sm"
                                            value="{{ \Carbon\Carbon::now()->format('Y') }}" min="1900"
                                            max="{{ \Carbon\Carbon::now()->year }}" size="3"
                                            aria-label="Invoices count">
                                    </div>
                                    Year
                                </div>
                                <div class="ms-auto text-secondary">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm"
                                            aria-label="Search invoice" fdprocessedid="f6xn2k">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">No.
                                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-sm icon-thick">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M6 15l6 -6l6 6"></path>
                                            </svg>
                                        </th>
                                        <th>Nama Kendaraan</th>
                                        <th>Tipe Kendaraan</th>
                                        <th>Plat Nomor</th>
                                        <th>Tanggal Perpanjangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stnks as $stnk)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stnk->RelasiSTNKtoKendaraan->merk_kendaraan }}</td>
                                            <td>{{ $stnk->RelasiSTNKtoKendaraan->tipe }}</td>
                                            <td>{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->translatedFormat('d F Y') }}</td>

                                            <td class="text-end">
                                                <a href="{{ route('stnk-edit', ['id' => $stnk->id]) }}" class="btn btn-primary btn-icon"><i
                                                        class="ti ti-edit"></i></a>
                                                <a href="{{ route('stnk-delete', ['id' => $stnk->id]) }}" class="btn btn-danger btn-icon"><i
                                                        class="ti ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">Showing <span>1</span> to <span>8</span> of
                                <span>16</span>
                                entries
                            </p>
                            <ul class="pagination m-0 ms-auto">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M15 6l-6 6l6 6"></path>
                                        </svg>
                                        prev
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 6l6 6l-6 6"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
