{{-- x-tags: button.openModalField --}}
@props([
    'item',
    'field' => 'Memo',
    'attri' => '',
    'label' => '',
    'type' => 'outline-dark',
    'tooltip' => '',
])

<button {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} {{ $attri }} type="button"
    @if ($tooltip) data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" @endif
    x-data=""
    @click="$dispatch('openModalField', {
            modelClass: '{{ addslashes(get_class($item)) }}',
            keyid: '{{ $item->getKey() }}',
            field: '{{ $field }}',
        })">
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @if ($label)
            {{ $label }}
        @else
            {{ $field }}
        @endif
    @endif
</button>
