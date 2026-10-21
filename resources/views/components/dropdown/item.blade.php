{{-- x-tags: dropdown.item, dropdown.link --}}
@props([
    'href' => '',
    'confirm' => '',
])


<li>
    <a href="{{ $href }}" @if ($confirm) wire:confirm="{{ $confirm }}" @endif
        {{ $attributes->merge(['class' => 'dropdown-item']) }}>
        {{ $slot }}
    </a>
</li>



{{-- <button type="button" wire:click="{{ $click }}"
    @if ($confirm) wire:confirm="{{ $confirm }}" @endif
    {{ $attributes->merge(['class' => 'dropdown-item text-truncate']) }}>
    {{ $slot }}
</button> --}}
