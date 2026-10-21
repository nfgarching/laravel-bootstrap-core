{{-- x-tags: dropdown --}}
@props([
    'close'  => 'false',
    'divid'  => null,
    'label'  => '<span class="bi bi-plus"></span>',
    'toggle' => false,
    'type'   => 'outline-primary',
    'width'  => '400px',
])

@php
    $class = 'btn btn-' . $type;
    if ($toggle) $class .= ' dropdown-toggle';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'dropdown']) }} @if ($divid) id="{{ $divid }}" @endif>

    <button
        {{ $attributes->except('class')->merge(['class' => $class]) }} 
        type="button"
        data-bs-toggle="dropdown"
        data-bs-auto-close="{{ $close }}"
        aria-expanded="false"
    >
        {!! $label !!}
    </button>

    <div class="dropdown-menu dropdown-menu-dark" style="width: {{ $width }};">

        {{ $slot }}

    </div>

</div>
