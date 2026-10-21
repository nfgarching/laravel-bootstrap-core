{{-- x-tags: field.text, field.textarea --}}
@props([
    'field' => '',
    'attri' => '',
    'class' => '',
    'label' => '',
    'token' => rand(),
    'value' => '',
    'form'  => null,
    'item'  => null,
    'rows'  => '10',
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
@endphp

<textarea 
    name="{{ $field }}" 
    class="{{ $class }}@error($field) is-invalid @enderror" 
    placeholder="{{ $label }}"
    rows="{{ $rows }}" 
    {{ $attri }}
>@if ($slot->isNotEmpty()){{ $slot }}@else{{ $value }}@endif

</textarea>

<div class="invalid-feedback">
    @error($field)
        {{ $message }}
    @enderror
</div>
