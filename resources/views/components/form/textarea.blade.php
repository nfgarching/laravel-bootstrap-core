{{-- x-tags: form.text, form.textarea --}}
@props([
    'field' => '',
    'attri'    => '',
    'class'    => '',
    'disabled' => false,
    'label'    => '',
    'item' => \Illuminate\Support\Facades\View::shared('__bindModel'),
    'readonly' => false,
    'value'    => '',
    // field special: Höhe per CSS, rows wirkt in .form-floating nicht
    'height' => '150px',
])

@php
    if ($label == '') {
        $label = ucfirst($field);
    }
    if ($item && !$value) {
        $slot = $item->$field;
    } else if ($value) {
        $slot = $value;
    }
    $class = 'form-control ' . $class;
@endphp

<div class="form-floating @error($field) is-invalid @enderror">
    
    <textarea  {{ $attri }}
        class="{{ $class }} @error($field) is-invalid @enderror"
        @if ($disabled) disabled @endif
        placeholder="{{ $label }}"
        id="floatingInput-{{ $field }}"
        name="{{ $field }}"
        @if ($readonly) readonly @endif
        style="height: {{ $height }}"
    >{{ $slot }}</textarea>
    
    <label for="floatingInput-{{ $field }}">{{ $label }}</label>

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
