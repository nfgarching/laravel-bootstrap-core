{{-- x-tags: form.label --}}
@props(['label' => ''])

@if($label)
    <label {!! $attributes !!}>{{ $label }}</label>
@endif
 