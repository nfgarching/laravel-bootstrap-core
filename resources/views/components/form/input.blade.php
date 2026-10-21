{{-- x-tags: form.input --}}
@props([
    'field' => '',
    'attri' => '',
    'class' => '',
    'disabled' => false,
    'label' => '',
    'item' => \Illuminate\Support\Facades\View::shared('__bindModel'),
    'readonly' => false,
    'value' => '',
    // field special
    'datalist' => [],
    'type' => 'text',
])

@php
    if ($label == '') {
        $label = ucfirst($field);
    }
    if ($item && !$value) {
        $slot = $item->$field;
    } elseif ($value) {
        $slot = $value;
    }
    $class = 'form-control ' . $class;
@endphp

<div class="form-floating @error($field) is-invalid @enderror">

    <input {{ $attri }} 
        class="{{ $class }} @error($field) is-invalid @enderror"
        @if ($disabled) disabled @endif
        placeholder="{{ $label }}" 
        id="floatingInput-{{ $field }}"
        name="{{ $field }}"
        @if ($readonly) readonly @endif
        {{-- field special --}}
        type="{{ $type }}" 
        value="{{ $slot }}"
        list="datalist-{{ $label }}"
    >

    <label for="floatingInput-{{ $field }}">{{ $label }}</label>

    @if ($datalist)
        <datalist id="datalist-{{ $label }}">
            @forelse($datalist as $txt)
                <option>
                    {{ $txt }}
                </option>
            @empty
            @endforelse
        </datalist>
    @endif

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
