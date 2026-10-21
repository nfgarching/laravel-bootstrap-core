{{-- x-tags: field.select --}}
@props([
    'field',
    'attri' => '',
    'class' => '',
    'label' => '',
    'token' => rand(),
    'value' => '',
    'form'  => null,
    'item'  => null,
    'type'  => 'text',
    'options' => [],
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
    } else {
        $value = old($field, $value);
    }
    $class = 'form-control ' . $class;
    // echo $field, $value ;

    // dump($field, $attri, $class, $label, $type, $value, $options);
@endphp

<div>

    <select name="{{ $field }}" autocomplete="off" {{ $attri }}
        class="{{ $class }} @error($field) is-invalid @enderror" placeholder="{{ $label }}"
        type="{{ $type }}">
        <option value="">{{ $label }}:</option>

        @forelse($options as $key => $txt)
            {{-- <option value="{{ $key }}" @if ($key == $value) selected="selected" @endif> --}}
            {{-- <option @if ($key == $value) selected="selected" @endif value="{{ $key }}"> --}}
            {{-- <option value="{{ $key }}"> --}}

            <option {{ $key == $value ? 'selected' : '' }} value="{{ $key }}">
                @if (is_array($txt))
                    {{ $txt['name'] ?? ($txt['text'] ?? $txt['txt'] ?? '') }}
                @else
                    {{ $txt }}
                @endif
                {{-- {{ $txt . " - $key - $value" }} --}}
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

</div>
