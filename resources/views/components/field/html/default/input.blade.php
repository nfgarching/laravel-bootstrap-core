{{-- x-tags: field, field.input, input --}}
@props([
    'field' => '',
    'attri' => '',
    'class' => '',
    'label' => '',
    'token' => rand(),
    'value' => '',
    'form'  => null,
    'item'  => null,
    'type'  => 'text',
    'datalist' => [],
])

@php
    if ($label == '') {
        $label = ucfirst($field);
    }
    if (isset($form[$field])) {
        // $field = 'form.' . $field;
        $value = $form[$field] ?? '';
        // echo $field, ' 1=> ', $value;
    } elseif (isset($form->$field)) {
        // $field = 'form.' . $field;
        $value = $form->$field ?? '';
        // echo $field, ' 2=> ', $value;
    } elseif (isset($item[$field])) {
        // $field = 'item.' . $field;
        $value = $item[$field] ?? '';
        // echo $field, ' 3=> ', $value;
    } elseif (isset($item->$field)) {
        // $field = 'item.' . $field;
        $value = $item->$field ?? '';
        // echo $field, ' 4=> ', $value;
    }
    $class = 'form-control ' . $class;
    $value = old($field, $value);
    // echo $field;
@endphp

<div class="mb-3">

    <label for="{{ $field }}" class="form-label">{{ $label }}</label>

    <input {{ $attri }} class="{{ $class }} 
            @error($field) is-invalid @enderror
            @if (old($field)) is-valid @endif"
        @if ($datalist) list="datalist_{{ $label }}" @endif
        id="{{ $field }}"
        name="{{ $field }}" 
        placeholder="{{ $label }}"
        type="{{ $type }}" 
        value="{{ $value }}"
    >

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