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

    <x-PageHeader header="Data KIR" classcontainer="" />

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Selamat!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-row-reverse">
                            <a href="{{ route('kir-tambah') }}" class="btn btn-primary"> <i
                                    class="ti ti-plus fs-2"></i>Tambah Data
                            </a>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <form action="{{ route('kir-index') }}" method="GET">
                                <div class="row g-2 align-items-center">
                                    <!-- Entries and Year (Left Side) -->
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
                                        <th>No. Uji Kendaraan</th>
                                        <th>Tanggal Perpanjangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kir as $item)
                                        @php
                                            $isExpired = \Carbon\Carbon::parse($item->tanggal_expired_kir)->isPast();
                                        @endphp
                                        <tr class="{{ $isExpired ? 'bg-dark-subtle' : '' }}">
                                            <td><span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $loop->iteration + $kir->firstItem() - 1 }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $item->kendaraan->nomor_polisi }}</span>
                                            </td>
                                            <td>
                                                <span class="{{ $isExpired ? 'text-white' : '' }}">
                                                    {{ $item->kendaraan->tipe }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="{{ $isExpired ? 'text-white' : '' }}">{{ $item->nomor_uji_kendaraan }}</span>
                                            </td>
                                            <td>
                                                <span class="{{ $isExpired ? 'text-white' : '' }}">{{ \Carbon\Carbon::parse($item->tanggal_expired_kir)->isoFormat('D MMMM Y') }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('kir-detail', $item->id) }}"
                                                    class="btn btn-primary btn-icon"><i
                                                        class="ti ti-alert-circle"></i></a>
                                                <a href="{{ route('kir-edit', $item->id) }}"
                                                    class="btn btn-success btn-icon"><i class="ti ti-edit"></i></a>
                                                <a href="{{ route('kir-delete-store', $item->id) }}"
                                                    class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">
                                Showing {{ $kir->firstItem() }} to {{ $kir->lastItem() }} of {{ $kir->total() }}
                                entries
                            </p>
                            @if ($kir->hasPages())
                                <ul class="pagination m-0 ms-auto">
                                    {{-- Previous Page Link --}}
                                    @if ($kir->onFirstPage())
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
                                            <a class="page-link" href="{{ $kir->previousPageUrl() }}" rel="prev">
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
                                    @foreach ($kir->links()->elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <li class="page-item disabled" aria-disabled="true">
                                                <span class="page-link">{{ $element }}</span>
                                            </li>
                                        @endif

                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $kir->currentPage())
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
                                    @if ($kir->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $kir->nextPageUrl() }}" rel="next">
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
