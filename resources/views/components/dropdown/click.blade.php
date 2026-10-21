{{-- x-tags: dropdown.click --}}
@props([
    'click' => 'click',
    'confirm' => '',
])

<button type="button" wire:click="{{ $click }}"
    @if ($confirm) wire:confirm="{{ $confirm }}" @endif
    {{ $attributes->merge(['class' => 'dropdown-item text-truncate']) }}>
    {{ $slot }}
</button>
