<x-app-layout>

    @push('styles')
        <style>
            /* Custom styles for full-width alert on mobile */
            @media (max-width: 768px) {
                .alert {
                    width: 100%;
                    right: 0;
                    margin: 50px 0;
                    position: absolute;
                    top: 0;
                }
            }
        </style>
    @endpush

    <x-PageHeader header="History Data STNK" classcontainer="" />

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Selamat!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        @if (Auth::user()->role_id == 1)
                            <div class="card-header d-flex flex-row-reverse">
                                <a href="{{ route('stnk-tambah') }}" class="btn btn-primary"> <i
                                        class="ti ti-plus fs-2"></i>Tambah Data
                                </a>
                            </div>
                        @endif
                        <div class="card-body border-bottom py-3">
                            <form action="{{ route('stnk-index') }}" method="GET">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-6 d-flex flex-wrap">
                                        <!-- Entries Dropdown -->
                                        <div class="me-3 text-secondary">
                                            Show
                                            <div class="d-inline-block">
                                                <select name="entries" class="form-control form-control-sm">
                                                    <option value=""
                                                        {{ is_null(request('entries')) ? 'selected' : '' }}>Select
                                                        entries</option>
                                                    <option value="5"
                                                        {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                                                    <option value="10"
                                                        {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                                    <option value="15"
                                                        {{ request('entries') == 15 ? 'selected' : '' }}>15</option>
                                                    <option value="20"
                                                        {{ request('entries') == 20 ? 'selected' : '' }}>20</option>
                                                    <option value="25"
                                                        {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                                </select>
                                            </div>
                                            entries
                                        </div>

                                        <!-- Year Input -->
                                        <div class="me-3">
                                            Tahun
                                            <div class="d-inline-block">
                                                <input type="number" name="year"
                                                    class="form-control form-control-sm" value="{{ request('year') }}"
                                                    min="1900" aria-label="Year"
                                                    placeholder="{{ \Carbon\Carbon::now()->year }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Search and Apply (Right Side) -->
                                    <div class="col-12 col-md-6 d-flex justify-content-md-end flex-wrap">
                                        <!-- Search Input -->
                                        <div class="me-3 text-secondary">
                                            Search:
                                            <div class="d-inline-block">
                                                <input type="text" name="search"
                                                    class="form-control form-control-sm"
                                                    value="{{ request('search') }}" aria-label="Search">
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div>
                                            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                        </div>
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
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-sm icon-thick">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M6 15l6 -6l6 6"></path>
                                            </svg> --}}
                                        </th>
                                        <th>Plat Nomor</th>
                                        <th>Tipe Kendaraan</th>
                                        <th>Tanggal Perpanjangan</th>
                                        <th>Biaya Perpanjangan</th>
                                        <th>Tipe Perpanjangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stnks as $stnk)
                                        @php
                                            $isExpired = \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->isPast();
                                        @endphp
                                        <tr class="{{ $isExpired ? 'bg-dark-subtle' : '' }}">
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $loop->iteration + $stnks->firstItem() - 1 }}</span>
                                            </td>
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $stnk->RelasiSTNKtoKendaraan->nomor_polisi }}</span>
                                            </td>
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $stnk->RelasiSTNKtoKendaraan->tipe }}</span>
                                            </td>
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ \Carbon\Carbon::parse($stnk->tanggal_perpanjangan)->isoFormat('D MMMM Y') }}</span>
                                            </td>
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ 'Rp. ' . $stnk->biaya }}</span>
                                            </td>
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $stnk->jenis_perpanjangan }}</span>
                                            </td>
                                            <td class="text-end">
                                                {{-- <a href="{{ route('stnk-detail', $stnk->id) }}"
                                                    class="btn btn-primary btn-icon"><i
                                                        class="ti ti-alert-circle"></i></a> --}}
                                                @if (Auth::user()->role_id == 1)
                                                    {{-- <a href="{{ route('stnk-edit', ['id' => $stnk->id]) }}"
                                                        class="btn btn-success btn-icon"><i class="ti ti-edit"></i></a>
                                                    <a href="{{ route('stnk-delete', ['id' => $stnk->id]) }}"
                                                        class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a> --}}
                                                @endif
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
                                                @elseif (
                                                    $page == 1 ||
                                                        $page == $stnks->lastPage() ||
                                                        ($page >= $stnks->currentPage() - 2 && $page <= $stnks->currentPage() + 2))
                                                    <li class="page-item"><a class="page-link"
                                                            href="{{ $url }}">{{ $page }}</a></li>
                                                @elseif ($page == $stnks->currentPage() - 3 || $page == $stnks->currentPage() + 3)
                                                    <li class="page-item disabled"><span class="page-link">...</span>
                                                    </li>
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

    @push('scripts')
        <script>
            setTimeout(function() {
                let alert = document.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('hide');
                }
            }, 3000); // Menghilang setelah 5 detik
        </script>
    @endpush

</x-app-layout>
