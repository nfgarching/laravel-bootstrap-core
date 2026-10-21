{{-- x-tags: form.group --}}
@props([
    'addon' => '',
    // 'addon' => '<i class="bi bi-card-text"></i>',
])

<div {!! $attributes->merge(['class' => 'input-group mb-0']) !!}>

    {!! $slot !!}

</div>
