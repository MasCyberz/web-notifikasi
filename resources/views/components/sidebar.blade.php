<aside class="navbar navbar-vertical navbar-expand-sm navbar-dark overflow-y-hidden">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand text-center navbar-brand-autodark">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                {{-- <img src="..." width="110" height="32" alt="Tabler" class="navbar-brand-image"> --}}
                <p class="m-0 fs-2">{{ config('app.name') }}</p>
            </a>
        </h1>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <x-navbarItems />
            </ul>
        </div>
    </div>
</aside>
