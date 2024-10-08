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
                        @if (Auth::user()->role_id == 1)
                            <div class="card-header d-flex flex-row-reverse gap-2">
                                <a href="{{ route('kendaraan-tambah') }}" class="btn btn-primary"> <i
                                        class="ti ti-plus fs-2"></i>Tambah Data
                                </a>
                                <button type="button" class="btn btn-large btn-success" data-bs-toggle="modal"
                                    data-bs-target="#exportModal">
                                    <i class="ti ti-file-excel pe-2 fs-2"></i>
                                    Export Excel
                                </button>
                            </div>
                        @endif
                        <div class="card-body border-bottom py-3">
                            <form action="{{ route('kendaraan-index') }}" method="GET" id="filter-form">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-6 d-flex flex-wrap">
                                        <!-- Entries Dropdown -->
                                        <div class="me-3 text-secondary">
                                            Show
                                            <div class="d-inline-block">
                                                <select name="entries" class="form-control form-control-sm"
                                                    id="entries-dropdown">
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
                                                    class="form-control form-control-sm" value="{{ request('search') }}"
                                                    aria-label="Search">
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraans as $kendaraan)
                                        <tr>
                                            <td><span
                                                    class="text-secondary">{{ $loop->iteration + $kendaraans->firstItem() - 1 }}</span>
                                            </td>
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
                                                <a href="{{ route('kendaraan-detail', $kendaraan->id) }}"
                                                    class="btn btn-primary btn-icon">
                                                    <i class="ti ti-alert-circle"></i></a>
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('kendaraan-edit', $kendaraan->id) }}"
                                                        class="btn btn-success btn-icon"><i class="ti ti-edit"></i></a>
                                                    {{-- <a href="{{ route('kendaraan-store-delete', $kendaraan->id) }}"
                                                        class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a> --}}
                                                    <a href="#" class="btn btn-danger btn-icon"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-delete{{ $kendaraan->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal Delete -->
                                        <div class="modal modal-blur fade" id="modal-delete{{ $kendaraan->id }}"
                                            tabindex="-1" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                    <div class="modal-status bg-danger"></div>
                                                    <div class="modal-body text-center py-4">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon mb-2 text-danger icon-lg">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M12 9v4"></path>
                                                            <path
                                                                d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                                                            </path>
                                                            <path d="M12 16h.01"></path>
                                                        </svg>
                                                        <h3>Apakah Anda Yakin?</h3>
                                                        <div class="text-secondary"> <span>Apakah anda yakin ingin
                                                                menghapus KIR Kendaraan
                                                                {{ $kendaraan->nomor_polisi }} ?, data yang
                                                                terhapus tidak dapat dikembalikan. </span></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="w-100">
                                                            <div class="row">
                                                                <form
                                                                    action="{{ route('kendaraan-store-delete', $kendaraan->id) }}">
                                                                    @csrf
                                                                    <div class="col"><a href="#"
                                                                            class="btn w-100" data-bs-dismiss="modal">
                                                                            Batal
                                                                        </a></div>
                                                                    <div class="col">
                                                                        <button class="btn btn-danger w-100"
                                                                            data-bs-dismiss="modal">
                                                                            Ya, Hapus
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-secondary">Showing {{ $kendaraans->firstItem() }} to
                                {{ $kendaraans->lastItem() }} of {{ $kendaraans->total() }} entries</p>
                            <ul class="pagination m-0 ms-auto">
                                <!-- Tombol Prev -->
                                @if ($kendaraans->onFirstPage())
                                    <li class="page-item disabled">
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
                                        <a class="page-link" href="{{ $kendaraans->previousPageUrl() }}"
                                            tabindex="-1">
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

                                <!-- Tombol Nomor Halaman dengan Titik-Titik -->
                                @php
                                    $currentPage = $kendaraans->currentPage();
                                    $lastPage = $kendaraans->lastPage();
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($lastPage, $currentPage + 2);
                                @endphp

                                <!-- Tampilkan Halaman Pertama Jika Halaman Saat Ini Lebih Dari 3 -->
                                @if ($currentPage > 3)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $kendaraans->url(1) }}">1</a>
                                    </li>
                                    @if ($currentPage > 4)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                <!-- Tombol Halaman Di Sekitar Halaman Saat Ini -->
                                @for ($i = $startPage; $i <= $endPage; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $kendaraans->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                <!-- Tampilkan Halaman Terakhir Jika Halaman Sekarang Bukan Halaman Terakhir -->
                                @if ($currentPage < $lastPage - 2)
                                    @if ($currentPage < $lastPage - 3)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $kendaraans->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                <!-- Tombol Next -->
                                @if ($kendaraans->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $kendaraans->nextPageUrl() }}">
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
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Export --}}

        <!-- Modal Export -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Export Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="exportForm" action="{{ route('export-data-KendaraanKirStnk') }}" method="GET">
                            <input type="hidden" id="exportType" name="exportType" value="vehicle">

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" id="exportTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="vehicle-tab" data-bs-toggle="tab" href="#vehicle"
                                        role="tab" aria-controls="vehicle" aria-selected="true">
                                        Data Kendaraan
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="kir-stnk-tab" data-bs-toggle="tab" href="#kir-stnk"
                                        role="tab" aria-controls="kir-stnk" aria-selected="false">
                                        KIR & STNK
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content mt-3" id="exportTabsContent">
                                <!-- Tab Data Kendaraan -->
                                <div class="tab-pane fade show active" id="vehicle" role="tabpanel"
                                    aria-labelledby="vehicle-tab">
                                    <!-- Tidak diperlukan input tambahan, langsung eksport semua data kendaraan -->
                                    <p class="text-muted">Semua data kendaraan akan diekspor tanpa filter tambahan.</p>
                                </div>

                                <!-- Tab KIR & STNK -->
                                <div class="tab-pane fade" id="kir-stnk" role="tabpanel"
                                    aria-labelledby="kir-stnk-tab">
                                    <!-- Form untuk ekspor KIR & STNK -->
                                    <div class="mb-3">
                                        <label for="year" class="form-label">Tahun (Opsional):</label>
                                        <input class="form-control" type="number" id="year" name="year"
                                            min="2000" max="3000" step="1"
                                            value="{{ \Carbon\Carbon::now()->year }}" placeholder="Masukkan Tahun">
                                    </div>
                                    <div class="mb-3">
                                        <label for="month" class="form-label">Bulan (Opsional):</label>
                                        <select id="month" name="month" class="form-control">
                                            <option value="">Pilih Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">
                                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3 col">
                                        <label for="platNomor" class="form-label">Plat Nomor Kendaraan:</label>

                                        <!-- Input untuk Pencarian -->
                                        <input type="text" id="searchPlatNomor" class="form-control mb-2"
                                            placeholder="Cari plat nomor...">

                                        <!-- Wrapper untuk Daftar Plat Nomor -->
                                        <div id="platNomorWrapper" class="form-control text-start"
                                            style="height: 250px; overflow-y: auto;">
                                            @foreach ($kendaraans as $kendaraan)
                                                <div class="form-check text-start"
                                                    data-plat-nomor="{{ strtolower($kendaraan->nomor_polisi) }}">
                                                    <input type="checkbox" id="kendaraan{{ $kendaraan->id }}"
                                                        value="{{ $kendaraan->nomor_polisi }}"
                                                        class="form-check-input" name="plat_nomor[]">
                                                    <label class="form-check-label"
                                                        for="kendaraan{{ $kendaraan->id }}">{{ $kendaraan->nomor_polisi }}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Tombol Pilih/Jangan Pilih Semua -->
                                        <button id="toggleSelectAll" type="button"
                                            class="btn btn-outline-primary mt-2">Pilih
                                            Semua</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit" class="btn btn-success mt-3">Export</button>
                        </form>
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

            document.addEventListener('DOMContentLoaded', function() {
                const toggleSelectAllButton = document.getElementById('toggleSelectAll');
                const checkboxes = document.querySelectorAll('#platNomorWrapper input[type="checkbox"]');
                const exportTypeInput = document.getElementById('exportType'); // Ambil input exportType
                let allSelected = false; // Status apakah semua checkbox dipilih

                // Tab navigation event listener
                const vehicleTab = document.getElementById('vehicle-tab');
                const kirStnkTab = document.getElementById('kir-stnk-tab');

                // Set exportType saat tab vehicle diaktifkan
                vehicleTab.addEventListener('shown.bs.tab', function() {
                    exportTypeInput.value = 'vehicle'; // Set value ke vehicle saat tab vehicle aktif
                });

                // Set exportType saat tab kir-stnk diaktifkan
                kirStnkTab.addEventListener('shown.bs.tab', function() {
                    exportTypeInput.value = 'kir_stnk'; // Set value ke kir_stnk saat tab kir-stnk aktif
                });

                // Fungsi untuk Toggle Pilih/Jangan Pilih Semua
                toggleSelectAllButton.addEventListener('click', function() {
                    allSelected = !allSelected;
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = allSelected;
                    });
                    toggleSelectAllButton.textContent = allSelected ? 'Jangan Pilih Semua' : 'Pilih Semua';
                });

                // Realtime Search Functionality
                const searchInput = document.getElementById('searchPlatNomor');
                searchInput.addEventListener('input', function() {
                    const searchQuery = searchInput.value.toLowerCase();
                    const platNomorDivs = document.querySelectorAll('#platNomorWrapper .form-check');

                    platNomorDivs.forEach(function(div) {
                        const platNomor = div.getAttribute('data-plat-nomor');
                        if (platNomor.includes(searchQuery)) {
                            div.style.display = '';
                        } else {
                            div.style.display = 'none';
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Kirim form otomatis saat dropdown entries berubah
                document.getElementById('entries-dropdown').addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });
            });
        </script>
    @endpush
</x-AppLayout>
