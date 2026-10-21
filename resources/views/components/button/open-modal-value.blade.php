{{-- x-tags: button.openModalValue --}}
@props([
    'value',
    'attri' => '',
    'label' => '',
    'type' => 'outline-dark',
    'tooltip' => '',
])

<button {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} {{ $attri }} type="button"
    @if ($tooltip) data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" @endif
    x-data=""
    @click="$dispatch('openModalValue', {
            value: '{{ $value }}',
        })">
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @if ($label)
            {{ $label }}
        @else
            {{ Str::limit($value, 30) }}
        @endif
    @endif
</button>
