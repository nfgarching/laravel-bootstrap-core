{{-- x-tags: wire.select-enum --}}
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
    // select special
    'options' => [],
])

@php
    if ($fname == '') {
        $fname = $field;
    }
    if ($label === '' || $label === null) {
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
    $class = 'form-select ' . $class;

    // dump(\App\Enums\NameStatus::class);

    // dump($option);

    // $option = \App\Enums\NameStatus::class::cases();

    // foreach ($options as $key => $option) {
    //     # code...
    //     dump($key);
    //     dump($value);
    // }

    // dd('stop');

    // $option = [];

@endphp

<div class="@if ($label !== false) form-floating @endif @error($field) is-invalid @enderror">

    <select autocomplete="off" {{ $attri }}
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
            @case('store')
                onkeydown="this.classList.add('bg-info');"
                wire:change="store({{ $keyid }},'{{ $field }}',$event.target.value)" 
                wire:loading.class="bg-info"
                wire:loading.attr="disabled"
                @break
            @case('disabled')
            @case('readonly')
                wire:model="{{ $field }}"
                disabled
                @break
            @default
                wire:model="{{ $field }}"                
        @endswitch
        @if ($dirty) wire:dirty.class="{{ $dirty }}" @endif
        class="{{ $class }} @error($field) is-invalid @enderror" id="field-{{ $token }}"
        id="field-{{ $token }}" name="{{ $fname }}" value="{{ $value }}">

        <option>---</option>

        @forelse($options as $key => $option)
            {{-- <option value="{{ $key }}" @if ($isSelected($key)) selected="selected" @endif> --}}
            {{-- <option value="{{ $key }}"> --}}
            <option {{ $option->value == $value ? 'selected' : '' }} value="{{ $option->value }}">
                {{ $option->label() }}
            </option>
        @empty
            {{ $slot }}
        @endforelse

    </select>

    {{-- <label for="field-{{ $token }}">{{ $label . '-c:wire-' . date('i:s') }}</label> --}}
    @if ($label !== false)
        <label for="field-{{ $token }}">{{ $label }}</label>
    @endif


    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
