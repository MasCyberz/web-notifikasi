<li class="nav-item {{ Request::route()->named('dashboard', 'pemberitahuan-lainnya', 'detail-alert') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-home fs-2"></i>
        </span>
        <span class="nav-link-title fw-semibold">
            Dashboard
        </span>
    </a>
</li>
<li class="nav-item {{ Request::route()->named('stnk-index', 'stnk-tambah', 'stnk-detail', 'stnk-edit') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('stnk-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-car fs-2"></i>
        </span>
        <span class="nav-link-title fw-semibold">
            Data STNK
        </span>
    </a>
</li>
<li class="nav-item {{ Request::route()->named('kir-index', 'kir-tambah', 'kir-detail', 'kir-edit') ? 'active' : '' }}">
    <a class="nav-link" href="{{ Route('kir-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-truck fs-2"></i>
        </span>
        <span class="nav-link-title fw-semibold">
            Data KIR
        </span>
    </a>
</li>
<li
    class="nav-item {{ Request::route()->named('kendaraan-index', 'kendaraan-tambah', 'kendaraan-detail', 'kendaraan-edit') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('kendaraan-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <i class="ti ti-car fs-2"></i>
        </span>
        <span class="nav-link-title fw-semibold">
            Data Kendaraan
        </span>
    </a>
</li>
@if (Auth::user()->role_id == 1)
    <li
        class="nav-item {{ Request::route()->named('management-user-index', 'management-user-tambah', 'management-user-edit', ) ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('management-user-index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="ti ti-users fs-2"></i>
            </span>
            <span class="nav-link-title fw-semibold">
                Management User
            </span>
        </a>
    </li>
@endif


<li class="display-block d-lg-none">
    <form action="{{ route('logout') }}" method="POST" class="nav-item">
        @csrf
        {{-- <a class="nav-link" href="{{ route('kendaraan-index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="ti ti-car fs-2"></i>
            </span>
            <span class="nav-link-title fw-semibold">
                Logout
            </span>
        </a> --}}
        <button class="nav-link">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <i class="ti ti-logout"></i>
            </span>
            <span class="nav-link-title fw-semibold">Logout</span>
        </button>
    </form>
</li>
