{{-- x-tags: desc.list --}}
@props(['item', 'count' => null])

@php
    // dump($item);
    if (is_object($item)) {
        if (method_exists($item, 'toArray')) {
            $item = $item->toArray();
        } else {
            $item = (array) $item;
        }
    }

    // Ensure we have an array at this point
    if (!is_array($item)) {
        $item = [];
    }
@endphp


<br>

<x-desc.group class="small">

    @forelse (($count !== null ? array_slice($item, 0, $count) : $item) as $key => $val)
        @php
            // dump($key, $val);
        @endphp

        @if (is_array($val) || is_object($val))
            keyID: {{ $key }}
            <x-desc.list :item="$val" />
        @else
            <x-desc :label="$key" :value="$val" />
        @endif
    @empty
        {{ $slot }}
    @endforelse

    @if ($count !== null && count($item) > $count)
        <div class="text-muted small">... {{ count($item) - $count }} weitere</div>
    @endif

</x-desc.group>
