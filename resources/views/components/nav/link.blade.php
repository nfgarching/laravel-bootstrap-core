{{-- x-tags: nav.link --}}
@props([
    'data' => [],
    'href' => '',
    'class' => 'nav-link',
    'type' => '', // ggf. nav-link active
    'popup' => '', // 'popup' od. 'left=100,top=100,width=320,height=320',
    'tooltip' => 'Nav Link öffnen',
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
