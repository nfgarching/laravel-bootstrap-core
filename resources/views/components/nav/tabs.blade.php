{{-- x-tags: nav.tabs --}}
@props([
    'tabs' => ['<i class="bi bi-bullseye"></i>'],
    'show' => '0',
    'token' => null,
])

<ul class="nav nav-tabs" role="tablist">
    @foreach ($tabs as $key => $val)
        @php
            // dump($key, $show, $val);
        @endphp
        @if ($val)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if ($show == $key) active @endif" data-bs-toggle="tab"
                    data-bs-target="#tab{{ $token . $key }}" type="button" role="tab">{!! $val !!}</button>
            </li>
        @endif
    @endforeach
</ul>

<div class="tab-content">

    {{ $slot }}

</div>
