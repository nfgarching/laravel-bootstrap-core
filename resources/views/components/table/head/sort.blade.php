{{-- x-tags: table.head.sort --}}
@props([
    'field' => '',
    'route' => '',
    'class' => 'btn btn-block',
    'label' => 'field',
    'width' => '10%',
])

@php
    if ($label == 'field') {
        $label = ucfirst($field);
    }
    if ($route == '') {
        $route = url()->current();
    } else {
        $route = route($route);
    }
@endphp

{{-- 
    There is a blade directive in kyslik/column-sortable
    for you to create sortable links in your views.
--}}

<th nowrap width="{{ $width }}">
    @sortablelink($field, $label, '', [
        'class' => $class,
        'rel' => 'nofollow',
        'href' => $route,
    ])
</th>
