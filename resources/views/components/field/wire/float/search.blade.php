{{-- x-tags: wire.search --}}
{{-- Does not work yet! --}}

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
    'option' => [],
    'type' => 'text',

    // 'field' => 'search',
    // 'label' => 'Name suchen',
    // 'placeholder' => 'Name eingeben...',
    'results' => [],
    'selectedId' => null,
    'resultKey' => 'NR',
    'resultLabel' => 'Suchbegriff',
    'resultSub' => ['NR', 'ID'],
    'selectAction' => 'selectName',
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
    $class = 'form-control ' . $class;
@endphp

<div class="@if ($label !== false) form-floating @endif @error($field) is-invalid @enderror">

    <input {{ $attri }}
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
        placeholder="{{ $label }}"
        type="{{ $type }}"
        @if ($value) value="{{ $value }}" @endif
    >

    <div class="position-absolute w-100 bg-white border rounded-bottom shadow-sm"
        style="z-index: 1000; max-height: 200px; overflow-y: auto; top: 100%;">

        @forelse($option as $key => $txt)
            {{-- <option value="{{ $key }}" @if ($isSelected($key)) selected="selected" @endif> --}}
            {{-- <option value="{{ $key }}"> --}}
            <option {{ $key == $value ? 'selected' : '' }} value="{{ $key }}">
                {{ $txt }}
            </option>
        @empty
            {{ $slot }}
        @endforelse

        {{-- @if (count($results) > 0)
            @foreach ($results as $result)
                <div class="p-2 border-bottom cursor-pointer list-group-item-action" style="cursor: pointer;"
                    wire:click="{{ $selectAction }}({{ $result[$resultKey] }})">
                    {{ $result[$resultLabel] }}
                    <small class="text-muted">
                        ({{ collect($resultSub)->map(fn($key) => $result[$key])->implode('/') }})
                    </small>
                </div>
            @endforeach
        @endif --}}

    </div>

    @if ($label !== false)
        <label for="field-{{ $token }}">{{ $label . '-c:wire-' . date('i:s') }}</label>
    @endif

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
