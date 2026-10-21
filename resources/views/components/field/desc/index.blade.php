{{-- x-tags: desc --}}
@props([
    'label' => '',
    'value' => '',
    'size' => null, // null, 'sm', 'lg', 'xl' oder 1–6 (Bootstrap fs-*)
])

@php
    $sizeClass = match ((string) $size) {
        'sm' => 'small',
        'lg' => 'fs-5',
        'xl' => 'fs-4',
        '1', '2', '3', '4', '5', '6' => 'fs-'.$size,
        default => '',
    };

    // Convert objects (like Enums) to strings
    if (is_object($value)) {
        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        } elseif (method_exists($value, '__toString')) {
            $value = (string) $value;
        } else {
            $value = '';
        }
    } elseif (is_array($value)) {
        $value = implode(', ', $value);
    } elseif (is_bool($value)) {
        $value = $value ? 'Yes' : 'No';
    } elseif (is_null($value)) {
        $value = '';
    } elseif (is_numeric($value)) {
        $value = number_format($value);
    } elseif ($value instanceof \Illuminate\Support\Carbon) {
        $value = $value->format('Y-m-d H:i:s');
    }
    if($slot->isNotEmpty()) {
        $value = '';
    }
@endphp

<dt class="col-3 text-truncate {{ $sizeClass }}" style="text-align:right">{{ $label }}</dt>
<dd class="col-9 text-truncate {{ $sizeClass }}">
    @if ($value)
        {{ Str::limit($value, 50) }}
    @else
        {{ $slot }}
    @endif
</dd>
