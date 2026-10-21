<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if (file_exists(resource_path('views/layouts/parts/head.blade.php')))
    @include('layouts.parts.head')
@else
    @include('layout::parts.head')
@endif

<body x-data="{ sidebarOpen: window.innerWidth >= 992 }">

    @if (file_exists(resource_path('views/layouts/parts/navbar.blade.php')))
        @include('layouts.parts.navbar')
    @else
        @include('layout::parts.navbar')
    @endif

    @if (Session::has('message'))
        <div x-init="$dispatch('show-message', { message: '{{ Session::get('message') }}' })"></div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 bg-light sidebar" 
                x-show="sidebarOpen" x-cloak>
                <!-- Nav tabs -->
                <div class="position-sticky pt-3 sidebar-sticky">
                    @if (file_exists(resource_path('views/layouts/parts/sidebar.blade.php')))
                        @include('layouts.parts.sidebar')
                    @else
                        @include('layout::parts.sidebar')
                    @endif
                </div>
            </nav>
            <!--Container Main start-->
            <main class="ms-sm-auto px-md-4" :class="sidebarOpen ? 'col-md-9 col-lg-10' : 'col-12'">
                <br>
                <!-- Main Header -->
                @if (isset($header))
                    {{ $header }}
                @endif
                <!-- Main Content -->
                {{ $slot }}
                <!-- Main Footer -->
                @if (isset($footer))
                    {{ $footer }}
                @endif
            </main>
            <!--Container Main end-->
        </div>
    </div>

    @if (file_exists(resource_path('views/layouts/parts/script.blade.php')))
        @include('layouts.parts.script')
    @else
        @include('layout::parts.script')
    @endif

</body>

</html>
