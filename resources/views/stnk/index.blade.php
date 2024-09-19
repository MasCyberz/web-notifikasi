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
                            <form action="{{ route('stnk-index') }}" method="GET">
                                <div class="d-flex">
                                    <!-- Entries Dropdown -->
                                    <div class="text-secondary">
                                        Show
                                        <div class="mx-2 d-inline-block">
                                            <select name="entries" class="form-control form-control-sm">
                                                <option value="" {{ is_null(request('entries')) ? 'selected' : '' }}>Select entries</option>
                                                <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                                                <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                                <option value="15" {{ request('entries') == 15 ? 'selected' : '' }}>15</option>
                                                <option value="20" {{ request('entries') == 20 ? 'selected' : '' }}>20</option>
                                                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                            </select>
                                        </div>
                                        entries
                                    </div>
                        
                                    <!-- Year Input -->
                                    <div class="mx-2 d-inline-block">
                                        <input type="number" name="year" class="form-control form-control-sm"
                                            value="{{ request('year') }}" min="1900"
                                            max="{{ \Carbon\Carbon::now()->year }}" size="3" aria-label="Year" placeholder="{{ \Carbon\Carbon::now()->year }}">
                                    </div>
                                    Year
                                    
                        
                                    <!-- Search Input -->
                                    <div class="ms-auto text-secondary">
                                        Search:
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" name="search" class="form-control form-control-sm"
                                                value="{{ request('search') }}" aria-label="Search">
                                        </div>
                                    </div>
                        
                                    <!-- Submit Button -->
                                    <div class="ms-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                    </div>
                                </div>
                            </form>
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
                                        <th>Biaya Perpanjangan</th>
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
                                            <td>{{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>Rp.{{ $stnk->biaya }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('stnk-edit', ['id' => $stnk->id]) }}"
                                                    class="btn btn-primary btn-icon"><i class="ti ti-edit"></i></a>
                                                <a href="{{ route('stnk-delete', ['id' => $stnk->id]) }}"
                                                    class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">
                                Showing {{ $stnks->firstItem() }} to {{ $stnks->lastItem() }} of
                                {{ $stnks->total() }} entries
                            </p>
                            @if ($stnks->hasPages())
                                <ul class="pagination m-0 ms-auto">
                                    {{-- Previous Page Link --}}
                                    @if ($stnks->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M15 6l-6 6l6 6"></path>
                                                </svg>
                                                prev
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $stnks->previousPageUrl() }}" rel="prev">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M15 6l-6 6l6 6"></path>
                                                </svg>
                                                prev
                                            </a>
                                        </li>
                                    @endif
                        
                                    {{-- Pagination Elements --}}
                                    @foreach ($stnks->links()->elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link">{{ $element }}</span>
                                            </li>
                                        @endif
                        
                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $stnks->currentPage())
                                                    <li class="page-item active"><a
                                                            class="page-link">{{ $page }}</a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                        
                                    {{-- Next Page Link --}}
                                    @if ($stnks->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $stnks->nextPageUrl() }}" rel="next">
                                                next
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M9 6l6 6l-6 6"></path>
                                                </svg>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled" aria-disabled="true">
                                            <a class="page-link" href="#">
                                                next
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M9 6l6 6l-6 6"></path>
                                                </svg>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
