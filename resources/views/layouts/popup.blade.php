<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if (file_exists(resource_path('views/layouts/parts/head.blade.php')))
    @include('layouts.parts.head')
@else
    @include('layout::parts.head')
@endif

<body>

    {{-- @if (file_exists(resource_path('views/layouts/parts/navbar.blade.php')))
        @include('layouts.parts.navbar')
    @else
        @include('layout::parts.navbar')
    @endif --}}
    {{-- modals + toasts werden bereits vom script-Partial (unten) eingebunden.
         NICHT zusätzlich hier einbinden, sonst rendert jede Modal-Komponente
         (u.a. <livewire:modal.raw />) doppelt -> Formular mehrfach + doppelte
         id="modalRAW". Vgl. main.blade.php, das ebenfalls nur script einbindet. --}}

    @if (Session::has('message'))
        <div x-init="$dispatch('show-message', { message: '{{ Session::get('message') }}' })"></div>
    @endif

    {{-- <div class="container-fluid"> --}}
    {{-- <div class="row"> --}}
    <!-- Nav tabs -->
    {{-- <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    @if (file_exists(resource_path('views/layouts/parts/sidebar.blade.php')))
                        @include('layouts.parts.sidebar')
                    @else
                        @include('layout::parts.sidebar')
                    @endif
                </div>
            </nav> --}}
    <!--Container Main start-->
    {{-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> --}}
        {{-- <br><br> --}}
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
    {{-- </main> --}}
    <!--Container Main end-->
    {{-- </div> --}}
    {{-- </div> --}}

    @if (file_exists(resource_path('views/layouts/parts/script.blade.php')))
        @include('layouts.parts.script')
    @else
        @include('layout::parts.script')
    @endif

</body>

</html>
