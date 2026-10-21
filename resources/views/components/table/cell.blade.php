{{-- x-tags: table.cell --}}
@props([
    'cols' => '1',
    'value' => '',
    'width' => '',
])

<td colspan="{{ $cols }}" width="{{ $width }}">
    @if ($value)
        {{ Str::limit($value, 75) }}
    @else
        {{ $slot }}
    @endif
</td>
