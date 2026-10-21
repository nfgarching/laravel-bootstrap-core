{{-- x-tags: button.raw --}}
@props([
    'item',
    'attri' => '',
    'label' => '',
    'type' => 'outline-danger',
    'tooltip' => '',
    'popup' => 'width=800,height=800',
])

@php
    $href = route('raw', ['model' => get_class($item), 'keyid' => $item->getKey()]);
    if (! $tooltip) {
        $tooltip = '<small>Press => <b>Open Modal</b><br>SHIFT => <b>Open Popup</b><br>CTRL &nbsp;=> <b>Open Tab</b></small>';
    }
    // dump($href,$tooltip);
@endphp

<button {{ $attributes->merge(['class' => 'btn btn-' . $type]) }} {{ $attri }} type="button"
    @if ($tooltip) data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $tooltip }}" @endif
    x-data="{
        openCentered(url, opts) {
            const p = Object.fromEntries(opts.split(',').map(e => e.split('=')));
            const w = parseInt(p.width) || 800;
            const h = parseInt(p.height) || 800;
            const left = Math.round((screen.width - w) / 2);
            const top  = Math.round((screen.height - h) / 2);
            window.open(url, '_blank', opts + ',left=' + left + ',top=' + top);
        }
    }"
    @click="if ($event.shiftKey) {
            openCentered('{{ $href }}', '{{ $popup }}');
        } else if ($event.ctrlKey) {
            window.open('{{ $href }}', '_blank');
        } else {
            $dispatch('openModalRaw', {
                model: '{{ addslashes(get_class($item)) }}',
                keyid: '{{ $item->getKey() }}',
            });
        }">
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($label)
        {{ $label }}
    @else
        <i class="bi bi-pencil-square"></i>
    @endif
</button>
