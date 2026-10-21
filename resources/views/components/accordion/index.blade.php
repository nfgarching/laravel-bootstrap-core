{{-- x-tags: accordion --}}
{{--
    WICHTIG: acc_id und row_id (in x-accordion.item) müssen stabile Werte sein!
    rand() im Blade-View wird bei jedem Livewire-Re-Render neu ausgeführt →
    IDs ändern sich → wire:ignore.self funktioniert nicht mehr (Button-Target
    stimmt nicht mehr mit Collapse-Div-ID überein → Bootstrap-Accordion klappt zu).
    Lösung: stabile ID übergeben, z.B. 'memowv-' . $item->NR
--}}
@props([
    'acc_id' => rand(),
    'flush' => false,
])

<div {{ $attributes->merge(['class' => 'accordion' . ($flush ? ' accordion-flush' : '')]) }} id="acc_{{ $acc_id }}">

    {{ $slot }}

</div>
