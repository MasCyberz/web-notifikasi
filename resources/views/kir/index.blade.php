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
                        @if (Auth::user()->role_id == 1)
                            <div class="card-header d-flex justify-content-end flex-wrap">
                                <a href="{{ route('kir-tambahPerpanjangan') }}"
                                    class="btn btn-primary ms-2 d-none d-sm-inline"> <i
                                        class="ti ti-plus fs-2"></i>Tambah Data Perpanjangan
                                </a>
                                <a href="{{ route('kir-tambahPerpanjangan') }}"
                                    class="btn btn-primary d-block d-sm-none w-100 my-2"> <i
                                        class="ti ti-plus fs-2"></i>Tambah Data Perpanjangan
                                </a>
                                <a href="{{ route('kir-tambah') }}" class="btn btn-primary ms-2 d-none d-sm-inline"> <i
                                        class="ti ti-plus fs-2"></i>Tambah KIR Baru
                                </a>
                                <a href="{{ route('kir-tambah') }}" class="btn btn-primary d-block d-sm-none w-100"> <i
                                        class="ti ti-plus fs-2"></i>Tambah KIR Baru
                                </a>
                            </div>
                        @endif
                        <div class="card-body border-bottom py-3">
                            <form action="{{ route('kir-index') }}" method="GET" id="filter-form">
                                <div class="row g-2 align-items-center">
                                    <!-- Entries and Year (Left Side) -->
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

                                        <!-- Year Input -->
                                        <div class="me-3">
                                            Tahun
                                            <div class="d-inline-block">
                                                <input type="number" name="year"
                                                    class="form-control form-control-sm" value="{{ request('year') }}"
                                                    min="1900" aria-label="Year" id="year-input"
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
                                        <th>No.</th>
                                        <th>Plat Nomor</th>
                                        <th>Tipe Kendaraan</th>
                                        <th>No. Uji Kendaraan</th>
                                        <th>Tanggal Perpanjangan</th>
                                        <th>Status</th>
                                        <th>Periode</th>
                                        <th>Keterangan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kir as $item)
                                        @php
                                            $isExpired = \Carbon\Carbon::parse($item->tanggal_expired_kir)->isPast();
                                        @endphp
                                        <tr class="{{ $isExpired ? 'bg-dark-subtle' : '' }}">
                                            <td class="{{ $isExpired ? 'text-white' : '' }}">{{ $loop->iteration }}
                                            </td>
                                            <td class="{{ $isExpired ? 'text-white' : '' }}">
                                                {{ $item->kir->kendaraan->nomor_polisi }}</td>
                                            <td class="{{ $isExpired ? 'text-white' : '' }}">
                                                {{ $item->kir->kendaraan->tipe }}</td>
                                            <td class="{{ $isExpired ? 'text-white' : '' }}">
                                                {{ $item->kir->nomor_uji_kendaraan }}</td>
                                            <td class="{{ $isExpired ? 'text-white' : '' }}">
                                                {{ \Carbon\Carbon::parse($item->tanggal_expired_kir)->isoFormat('D MMMM Y') }}
                                            </td>
                                            @if ($item->status)
                                                <td>
                                                    <span
                                                        class="{{ $isExpired ? 'text-white text-capitalize' : '' }} {{ $item->status == 'aktif' ? 'badge text-bg-success' : ($item->status == 'nonaktif' ? 'badge text-bg-danger' : 'badge text-bg-warning') }} text-capitalize ">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>
                                            @else
                                                <!-- Jika tidak ada status, tampilkan string kosong -->
                                                <td>
                                                    <span></span>
                                                </td>
                                            @endif
                                            @if ($item->periode)
                                                <td>
                                                    <span
                                                        class="{{ $isExpired ? 'text-white' : '' }} d-inline-block badge text-bg-info text-capitalize"
                                                        style="max-width: 150px">{{ $item->periode }}</span>
                                                </td>
                                            @else
                                                <!-- Jika tidak ada status, tampilkan string kosong -->
                                                <td>
                                                    <span></span>
                                                </td>
                                            @endif
                                            <td>
                                                <span
                                                    class="{{ $isExpired ? 'text-white' : '' }} d-inline-block text-truncate"
                                                    style="max-width: 150px">{{ $item->alasan_tidak_lulus }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('kir-detail', $item->id) }}"
                                                    class="btn btn-primary btn-icon"><i
                                                        class="ti ti-alert-circle"></i></a>
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('kir-edit', $item->id) }}"
                                                        class="btn btn-success btn-icon {{ $isExpired ? 'd-none' : '' }}"><i
                                                            class="ti ti-edit"></i></a>
                                                    {{-- <a href="{{ route('kir-delete-store', $item->id) }}"
                                                        class="btn btn-danger btn-icon"><i class="ti ti-trash"></i></a> --}}
                                                    <a href="#" class="btn btn-danger btn-icon"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-delete{{ $item->id }}">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    @if ($item->status === 'nonaktif')
                                                        <button type="button" class="btn btn-warning btn-icon p-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateStatusModal{{ $item->id }}">
                                                            Pending
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Modal Ubah Status Pending -->
                                        <div class="modal fade" id="updateStatusModal{{ $item->id }}"
                                            tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('kir-update-status-pending', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="updateStatusModalLabel{{ $item->id }}">Ubah
                                                                Status KIR ke Pending</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-muted">Anda akan menandai kendaraan ini
                                                                sebagai "Pending" karena KIR tidak dapat dilakukan saat
                                                                ini. Mohon berikan alasan mengapa KIR ditunda.</p>
                                                            <div class="form-group">
                                                                <label for="keterangan">Keterangan (Alasan
                                                                    Pending)</label>
                                                                <textarea class="form-control" id="keterangan" name="alasan_tidak_lulus" rows="4" required
                                                                    placeholder="Misalnya, perlu mengganti suku cadang yang hilang..."></textarea>
                                                                <small class="form-text text-muted">Jelaskan alasan
                                                                    penundaan, seperti kebutuhan perbaikan atau
                                                                    penggantian suku cadang.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning">Tandai
                                                                Sebagai Pending</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Modal Delete --}}
                                        <div class="modal modal-blur fade" id="modal-delete{{ $item->id }}"
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
                                                                {{ $item->kir->kendaraan->nomor_polisi }} ?, data yang
                                                                terhapus tidak dapat dikembalikan. </span></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="w-100">
                                                            <div class="row">
                                                                <form
                                                                    action="{{ route('kir-delete-store', $item->id) }}">
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
                                            <a class="page-link" href="{{ $kir->previousPageUrl() }}"
                                                rel="prev">
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

            document.addEventListener('DOMContentLoaded', function() {
                // Kirim form otomatis saat dropdown entries berubah
                document.getElementById('entries-dropdown').addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });

                // Menunggu beberapa waktu setelah input tahun berubah sebelum mengirim form
                let typingTimer;
                let doneTypingInterval = 600; // Waktu tunda (1 detik)

                document.getElementById('year-input').addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(function() {
                        document.getElementById('filter-form').submit();
                    }, doneTypingInterval);
                });

                // Reset timer jika pengguna masih mengetik
                document.getElementById('year-input').addEventListener('keydown', function() {
                    clearTimeout(typingTimer);
                });
            });
        </script>
    @endpush

</x-app-layout>
