<nav class="navbar navbar-expand-lg bg-body-secondary sticky-top">
    {{-- Für Backgrounds s. https://getbootstrap.com/docs/5.3/utilities/background/ --}}
    {{-- <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-1 shadow" data-bs-theme="dark"> --}}
    {{-- <nav class="navbar bg-body-tertiary sticky-top"> --}}

    <div class="container-fluid">

        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Sidebar ein-/ausklappen --}}
        <button class="btn btn-link nav-link px-2" type="button" @click="sidebarOpen = !sidebarOpen"
            aria-label="Sidebar umschalten">
            <i class="bi fs-6" :class="sidebarOpen ? 'bi-layout-sidebar' : 'bi-layout-sidebar-inset'"></i>
        </button>

        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/">{{ config('app.name') }}</a>

        <div class="collapse navbar-collapse" id="navbarNav">

            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            &nbsp;

            {{-- Linke Navigation --}}
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Link #1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link #2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled">Link #3</a>
                </li>
                <span class="navbar-text">Text #4</span>
            </ul>

            {{-- Rechte Navigation --}}
            <ul class="navbar-nav ms-auto">
                {{-- Navbar-Buttons, die jedes Feature-Package selbst über die
                 NavbarNavigation-Registry beiträgt (Reihenfolge via order). --}}
                @foreach (app(\Nfgarching\Core\Navigation\NavbarNavigation::class)->visibleItems() as $item)
                        @includeIf($item)
                @endforeach
            </ul>

        </div>

        {{-- Rechte Navigation (am rechten Rand) – außerhalb der Collapse,
             damit sie beim Toggle nicht eingeklappt wird. --}}
        <ul class="navbar-nav flex-row ms-auto">
            <li class="nav-item">
                <a class="nav-link" role="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarDark">
                    <span class="bi bi-three-dots-vertical"></span>
                </a>
            </li>
        </ul>

</nav>

@if (file_exists(resource_path('views/layouts/parts/offcanvas.blade.php')))
    @include('layouts.parts.offcanvas')
@else
    @include('layout::parts.offcanvas')
@endif
