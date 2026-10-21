{{-- x-tags: slim.text --}}
@props([
    'field',
    'attri' => '',
    'class' => '',
    'dirty' => 'bg-info',
    'fname' => '',
    'label' => '',
    'modifier' => '',
    'token' => rand(),
    'value' => '',
    // input special
    'rows' => '5',
])


@php
    if ($label == '') {
        $label = ucfirst($field);
    }
    if (isset($this->form->$field)) {
        $field = 'form.' . $field;
    } elseif (isset($this->item[$field])) {
        $field = 'item.' . $field;
    }
    if ($slot->isNotEmpty()) {
        $value = $slot;
    }
    $class = 'form-control ' . $class;
@endphp

<textarea {{ $attri }}
    @switch($modifier)
        @case('blur')
            wire:model.blur="{{ $field }}"                
            @break
        @case('change')
            wire:model.change="{{ $field }}"                
            @break
        @case('lazy')
            wire:model.lazy="{{ $field }}"                
            @break
        @case('live')
            wire:model.live="{{ $field }}"                
            @break
        @default
            wire:model="{{ $field }}"                
    @endswitch
    @if ($dirty) wire:dirty.class="{{ $dirty }}" @endif wire:model="{{ $field }}"
    class="{{ $class }} @error($field) is-invalid @enderror" id="field-{{ $token }}"
    placeholder="{{ $label }}" rows="{{ $rows }}" style="height: auto;">{{ $value }}</textarea>

<div class="invalid-feedback">
    @error($field)
        {{ $message }}
    @enderror
</div>
