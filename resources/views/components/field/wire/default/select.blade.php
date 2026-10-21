{{-- x-tags: slim.select --}}
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
    // select special
    'option' => [],
])

@php
    if ($label == '') {
        $label = ucfirst($field);
    }
    if ($value === '') {
        $resolved = null;
        if (isset($this->form) && is_object($this->form) && property_exists($this->form, $field)) {
            $resolved = $this->form->{$field};
        } elseif (isset($this->item) && !is_array($this->item) && isset($this->item->{$field})) {
            $resolved = $this->item->{$field};
        }
        if ($resolved instanceof \BackedEnum) {
            $value = $resolved->value;
        } elseif ($resolved !== null && !is_array($resolved) && !is_object($resolved)) {
            $value = (string) $resolved;
        }
    }
    if (isset($this->form->$field)) {
        $field = 'form.' . $field;
    } elseif (isset($this->item[$field])) {
        $field = 'item.' . $field;
    }
    $class = 'form-select ' . $class;
@endphp

<select autocomplete="off" {{ $attri }}
    @switch($modifier)
        @case('blur')
            wire:model.blur="{{ $field }}"                
            @break
        @case('change')
            wire:model.change.live="{{ $field }}"
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
    @if ($dirty) wire:dirty.class="{{ $dirty }}" @endif
    class="{{ $class }} @error($field) is-invalid @enderror" id="field-{{ $token }}"
    placeholder="{{ $label }}" value="{{ $value }}">

    <option>---</option>

    @forelse($option as $key => $txt)
        {{-- <option value="{{ $key }}" @if ($isSelected($key)) selected="selected" @endif> --}}
        {{-- <option value="{{ $key }}"> --}}
        <option {{ $key == $value ? 'selected' : '' }} value="{{ $key }}">
            {{ $txt }}
        </option>
    @empty
        {{ $slot }}
    @endforelse

</select>

<div class="invalid-feedback">
    @error($field)
        {{ $message }}
    @enderror
</div>
