{{-- x-tags: desc.group --}}
@props([
    'size' => null, // null, 'sm', 'lg', 'xl' oder 1–6 (Bootstrap fs-*), vererbt sich auf alle x-desc
])

@php
    $sizeClass = match ((string) $size) {
        'sm' => 'small',
        'lg' => 'fs-5',
        'xl' => 'fs-4',
        '1', '2', '3', '4', '5', '6' => 'fs-'.$size,
        default => '',
    };
@endphp

<dl {{ $attributes->merge(['class' => trim('row '.$sizeClass)]) }}>

    {{ $slot }}

</dl>
