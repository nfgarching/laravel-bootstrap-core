{{-- x-tags: form.select --}}
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
    'autocomplete' => 'off',
    'options' => [],
])

@php
    // dd($options);
    if ($label == '') {
        $label = ucfirst($field);
    }
    if ($item && !$value) {
        $value = $item->$field;
        $value = $value instanceof \BackedEnum ? $value->value : $value;
    } else if ($value) {
        $slot = $value;
    }
    $class = 'form-control ' . $class;
@endphp 

<div class="form-floating @error($field) is-invalid @enderror">

    <select {{ $attri }}
        class="{{ $class }} @error($field) is-invalid @enderror" 
        @if ($disabled) disabled @endif
        placeholder="{{ $label }}" 
        id="floatingInput-{{ $field }}"
        name="{{ $field }}"
        @if ($readonly) readonly @endif
        {{-- field special --}}
        $autocomplete
    >
        <option value="">---</option>
        @forelse($options as $key => $txt)
            <option {{ $key == $value ? 'selected' : '' }} value="{{ $key }}">
                {{ $txt }}
            </option>
        @empty
            {{ $slot }}
        @endforelse

    </select>

    <label>{{ $label }}</label>

    <div class="invalid-feedback">
        @error($field)
            {{ $message }}
        @enderror
    </div>

</div>
