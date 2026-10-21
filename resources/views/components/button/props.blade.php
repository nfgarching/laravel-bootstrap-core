{{-- x-tags: button.props --}}
@props(['model', 'modid', 'type' => 'primary'])

@php

    $keyid = null;

    $props = DB::table('props')->where('model_type', $model)->where('model_id', $modid)->get()->toArray();

    // dd($model, $modid, $props);

    $count = count($props);
    if ($props) {
        $keyid = $props[0]->id;
        $model = $props[0]->model_type;
        $modid = $props[0]->model_id;
        // dump($dbmodel);
    }

@endphp

@if ($keyid)
    @if (count($props) == 1)
        <x-button.link class="position-relative" :href="route('props.show', $keyid)" target="_blank">
            Prop
        </x-button.link>
    @else
        <x-button.link class="position-relative" :href="route('props.index', [])">
            Props
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ count($props) }}
                <span class="visually-hidden">unread messages</span>
            </span>
        </x-button.link>
    @endif
    @if (count($props) == 100)
        <x-button.model :model="$model" :modid="$modid" />
    @endif
@endif
</td>
