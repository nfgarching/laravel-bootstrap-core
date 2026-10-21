{{-- x-tags: icon --}}
@props([
    'name' => 'question',
    'size' => null,
])

@php
    $sizeClass = match ($size) {
        'sm' => 'fs-6',
        'lg' => 'fs-3',
        'xl' => 'fs-1',
        default => '',
    };
@endphp

<i {{ $attributes->merge(['class' => trim('bi bi-'.$name.' '.$sizeClass)]) }}></i>
