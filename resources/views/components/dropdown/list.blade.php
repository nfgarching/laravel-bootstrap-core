{{-- x-tags: dropdown.list --}}
@props([
    'close'  => 'true',
    'divid'  => null,
    'label'  => '',
    'option' => [],
    'toggle' => true,
    'type'   => 'outline-dark',
    'width'  => 'auto',
])

@php
    $class = 'btn btn-' . $type;
    if ($toggle) $class .= ' dropdown-toggle';
@endphp

<div class="btn-group dropdown" role="group" @if ($divid) id="{{ $divid }}" @endif>

    <button
        {{ $attributes->merge(['class' => $class]) }} type="button"
        data-bs-toggle="dropdown"
        data-bs-auto-close="{{ $close }}"
        aria-expanded="false"
    >
        {{ $label }}
    </button>

    {{-- div statt ul: die Einträge (dropdown.click, Trenner, Überschriften) sind keine <li> --}}
    <div class="dropdown-menu" style="width: {{ $width }};">

        @forelse ($option as $href => $txt)
            <a class="dropdown-item" href="{{ $href }}">
                {{ $txt }}
            </a>
        @empty
            {{ $slot }}
        @endforelse

    </div>

</div>
