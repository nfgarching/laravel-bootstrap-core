{{-- x-tags: button, button.click, button.link, link --}}
@props([
    'attri' => '',
    'click' => '',
    'href' => '',
    'label' => '',
    'type'  => 'primary',
    'tooltip' => '',
    'onclick' => '',
    'opacity' => false,
])

@php
    $opacityStyle = '';
    if ($opacity !== false) {
        $alpha = ($opacity === true || $opacity === 'true') ? 0.5 : (float) $opacity;
        $rgb = [
            'primary'   => '13,110,253',
            'secondary' => '108,117,125',
            'success'   => '25,135,84',
            'danger'    => '220,53,69',
            'warning'   => '255,193,7',
            'info'      => '13,202,240',
            'light'     => '248,249,250',
            'dark'      => '33,37,41',
        ][str_replace('outline-', '', $type)] ?? '248,249,250';
        $opacityStyle = "--bs-btn-bg: rgba({$rgb},{$alpha}); --bs-btn-border-color: rgba({$rgb},{$alpha});";
    }
@endphp

@if ($href)
    <a {{ $attributes->merge(['class' => 'btn btn-' . $type, 'style' => $opacityStyle]) }} {{ $attri }}
        href="{{ $href }}" role="button"
        @if ($click)
            wire:click="{{ $click }}"
        @endif
        @if ($tooltip)
            data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" title="{{ $tooltip }}"
        @endif
        @if ($onclick)
            onclick="{{ $onclick }}"
        @endif
    >
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @if ($label)
                {{ $label }}
            @else
                Speichern
            @endif
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => 'btn btn-' . $type, 'style' => $opacityStyle]) }} {{ $attri }}
        @if ($click)
            wire:loading.attr="disabled"
            wire:click="{{ $click }}"
        @endif
        type="button"
        @if ($tooltip)
            data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" title="{{ $tooltip }}"
        @endif
        @if ($onclick)
            onclick="{{ $onclick }}"
        @endif
    >
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @if ($label)
                {{ $label }}
            @else
                Speichern
            @endif
        @endif
    </button>
@endif
