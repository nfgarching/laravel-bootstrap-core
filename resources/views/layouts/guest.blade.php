<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if (file_exists(resource_path('views/layouts/parts/head.blade.php')))
    @include('layouts.parts.head')
@else
    @include('layout::parts.head')
@endif

<body>

    @if (Session::has('message'))
        <div x-init="$dispatch('show-message', { message: '{{ Session::get('message') }}' })"></div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <!--Container Main start-->
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
