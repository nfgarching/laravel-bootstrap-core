{{-- x-tags: nav.link --}}
@props([
    // 'attributes' => [],
    // 'data' => [],
    'href' => '',
    'class' => 'nav-link',
    'type' => '', // ggf. nav-link active
    'popup' => '', // 'popup' od. 'left=100,top=100,width=320,height=320',
    'tooltip' => '', // 'Nav Link öffnen'
])

<a class="{{ $class }}" href="{{ $href }}"
    @if ($popup)
        data-bs-toggle="modal" data-bs-target="#popupModal"
        data-popup="{{ $popup }}"
    @endif
    @if ($tooltip)
        data-bs-toggle="tooltip" data-bs-title="{{ $tooltip }}" title="{{ $tooltip }}"
    @endif
    {{-- {{ $attributes }} --}}
>
    {{ $slot }}
</a>

{{-- <x-button
    :data="$data"
    :href="$href"
    :class="$class"
    :type="$type"
    :popup="$popup"
    :tooltip="$tooltip"
    {{ $attributes }}
>
    {{ $slot }}
</x-button> --}}
