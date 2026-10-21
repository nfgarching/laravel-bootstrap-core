{{-- x-tags: button.popup --}}
{{-- Dünner Wrapper um <x-button.dispatch> ohne modalName:
     Standard-Klick öffnet das Popup, Ctrl-Klick öffnet einen neuen Tab. --}}
@props([
    'data' => [],
    'href' => '',
    'class' => 'btn btn-',
    'type' => 'primary',
    'popup' => 'width=800,height=800', // 'popup' od. 'left=100,top=100,width=320,height=320',
    'tooltip' => 'Popup öffnen',
])

<x-button.dispatch
    :data="$data"
    :href="$href"
    :class="$class"
    :type="$type"
    :popup="$popup"
    :tooltip="$tooltip"
    {{ $attributes }}
>
    {{ $slot }}
</x-button.dispatch>
