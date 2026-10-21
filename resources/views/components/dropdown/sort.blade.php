{{-- x-tags: dropdown.sort --}}
@props([
    'field',
    'label' => null,
    'wire',
])

@php
    $label ??= ucfirst($field);
@endphp

<li>
    <button type="button" wire:click="sortBy('{{ $field }}')" {{ $attributes->merge(['class' => 'dropdown-item']) }}>
        @if ($wire->sortField == $field)
            <i class="bi bi-sort-{{ $wire->sortAsc ? 'up' : 'down' }}"></i>
        @else
            <i class="bi bi-dash"></i>
        @endif
        {{ $label }}
    </button>
</li>
