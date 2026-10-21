{{-- x-tags: wire.text --}}
@props([
    'field',
    'attri' => '',
    'class' => '',
    'dirty' => 'bg-info',
    'fname' => '',
    'keyid' => '',
    'label' => '',
    'modifier' => '',
    'token' => rand(),
    'value' => '',
    // text special
    'rows' => '5',
])

@php
    if($fname == '') {
        $fname = $field;
    }
    if ($label === '' || $label === null) {
        $label = ucfirst($field);
    }
    if (isset($this->form) && is_object($this->form) && property_exists($this->form, $field)) {
        $field = 'form.' . $field;
    } elseif (isset($this->form) && is_array($this->form) && isset($this->form[$field])) {
        $field = 'form.' . $field;
    } elseif (isset($this->item) && !is_array($this->item) && isset($this->item->$field)) {
        $field = 'item.' . $field;
    } elseif (isset($this->item) && is_array($this->item) && isset($this->item[$field])) {
        $field = 'item.' . $field;
    }
    if ($slot->isNotEmpty()) {
        $value = $slot;
    }
    $class = 'form-control ' . $class;
@endphp

<div class="@if ($label !== false) form-floating @endif @error($field) is-invalid @enderror">

    <textarea {{ $attri }}
        @switch($modifier)
            @case('blur')
                wire:model.live.blur="{{ $field }}"
                @break
            @case('change')
                wire:model.live.change="{{ $field }}"
                @break
            @case('lazy')
                wire:model.lazy="{{ $field }}"                
                @break
            @case('live')
                wire:model.live="{{ $field }}"                
                @break
            @case('store')
                onkeydown="this.classList.add('bg-info');"
                wire:change="store({{ $keyid }},'{{ $fname }}',$event.target.value)" 
                wire:loading.class="bg-info"
                wire:loading.attr="disabled"
                @break
            @case('disabled')
                wire:model="{{ $field }}"
                disabled
                @break
            @case('readonly')
                wire:model="{{ $field }}"
                readonly
                @break
            @default
                wire:model="{{ $field }}"                
        @endswitch
        @if ($dirty) wire:dirty.class="{{ $dirty }}" @endif 
        class="{{ $class }} @error($field) is-invalid @enderror" 
        id="field-{{ $token }}" 
        name="{{ $fname }}"
        rows="{{ $rows }}" 
        style="height: auto;"
    >{{ $value }}</textarea>

    {{-- <label for="field-{{ $token }}">{{ $label . '-c:wire-' . date('i:s') }}</label> --}}
    {{-- <label for="field-{{ $token }}">{{ $label }} - {{ $field }}</label> --}}
    @if ($label !== false)
        <label for="field-{{ $token }}">{{ $label }}</label>
    @endif

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
