<x-AppLayout>
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
    <x-PageHeader header="Data Kendaraan" classcontainer="" />
    <!-- Alert Success -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed end-0 my-2 mx-2" style="z-index: 1050;"
            role="alert">
            <strong>Selamat!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="page-body">
        <div class="container-xl">
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <h4 class="alert-title fs-2">Congrats, </h4>
                    <div class="text-secondary">{{ session('success') }}</div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            @endif --}}
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        {{-- {{$kendaraans->model_kendaraan_id}} --}}
                        <div class="card-header d-flex flex-row-reverse">
                            <a href="{{ route('kendaraan-tambah') }}" class="btn btn-primary"> <i
                                    class="ti ti-plus fs-2"></i>Tambah Data
                            </a>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <form action="{{ route('kendaraan-index') }}" method="GET">
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
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable ">
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
                                        <th>No. Polisi</th>
                                        <th>Merk</th>
                                        <th>Tipe Kendaraan</th>
                                        <th>User Kendaraan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraans as $kendaraan)
                                        <tr>
                                            <td><span class="text-secondary">{{ $loop->iteration + $kendaraans->firstItem() - 1 }}</span></td>
                                            <td>
                                                {{ $kendaraan->nomor_polisi }}
                                            </td>
                                            <td>
                                                {{ $kendaraan->merk_kendaraan }}
                                            </td>
                                            <td>
                                                {{ $kendaraan->tipe }}
                                            </td>
                                            <td>
                                                {{ $kendaraan->user_kendaraan }}
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('kendaraan-detail', $kendaraan->id) }}" class="btn btn-primary btn-icon">
                                                    <i class="ti ti-alert-circle"></i></a>
                                                <a href="{{ route('kendaraan-edit', $kendaraan->id) }}"
                                                    class="btn btn-success btn-icon"><i class="ti ti-edit"></i></a>
                                                <a href="{{ route('kendaraan-store-delete', $kendaraan->id) }}"
                                                    class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">Showing {{ $kendaraans->firstItem() }} to {{ $kendaraans->lastItem() }} of {{ $kendaraans->total() }} entries</p>
                            <ul class="pagination m-0 ms-auto">
                                <!-- Tombol Prev -->
                                @if ($kendaraans->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M15 6l-6 6l6 6"></path>
                                            </svg>
                                            prev
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $kendaraans->previousPageUrl() }}" tabindex="-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M15 6l-6 6l6 6"></path>
                                            </svg>
                                            prev
                                        </a>
                                    </li>
                                @endif

                                <!-- Tombol Nomor Halaman -->
                                @for ($i = 1; $i <= $kendaraans->lastPage(); $i++)
                                    <li class="page-item {{ $i == $kendaraans->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $kendaraans->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Tombol Next -->
                                @if ($kendaraans->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $kendaraans->nextPageUrl() }}">
                                            next
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M9 6l6 6l-6 6"></path>
                                            </svg>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                            next
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M9 6l6 6l-6 6"></path>
                                            </svg>
                                        </a>
                                    </li>
                                @endif
                            </ul>

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
            }, 5000); // Menghilang setelah 5 detik
        </script>
    @endpush
</x-AppLayout>
