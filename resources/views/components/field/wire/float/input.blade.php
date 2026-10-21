{{-- x-tags: wire, wire.input --}}
@props([
    'field' => '',
    'attri' => '',
    'class' => '',
    'dirty' => 'bg-info',
    'fname' => '',
    'keyid' => '',
    'label' => '',
    'modifier' => '',
    'token' => rand(),
    'value' => '',
    // input special
    'datalist' => [],
    'type' => 'text',
])

@php
    if ($fname == '') {
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
        // } elseif (isset($this->form->$field)) {
        //     $value = $this->form->$field;
        // } elseif (isset($this->item[$field])) {
        //     $value = $this->item[$field];
    } else {
        // $value = old($fname, $value); dump($fname . ' old=> ' . $value);
    }
    $class = 'form-control' . $class;

    // if($datalist ) {
    //     dump($datalist);
    // }

@endphp

<div class="@if ($label !== false) form-floating @endif @error($field) is-invalid @enderror">

    <input {{ $attri }}
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
        {{-- placeholder="{{ $label }}"  --}}
        type="{{ $type }}"
        @if ($value) value="{{ $value }}" @endif
        @if ($datalist) list="datalist_{{ $token }}" @endif>

    @if ($datalist)
        <datalist id="datalist_{{ $token }}">
            @forelse($datalist as $txt)
                <option>
                    {{ $txt }}
                </option>
            @empty
            @endforelse
        </datalist>
    @endif

    {{-- <label for="field-{{ $token }}">{{ $label . '-c:wire-' . $field . ' - ' . date('i:s') }}</label> --}}
    @if ($label !== false)
        <label for="field-{{ $token }}">{{ $label }}</label>
    @endif

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
