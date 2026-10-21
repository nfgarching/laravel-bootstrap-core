{{-- x-tags: table.filter.dropdown.enum --}}
@props(['field', 'label', 'enum', 'oldval', 'onchange'])

@php
    $itemFilterField = $field;
    $enumClass = $enum ?? '';

    // Prüfen ob die Enum-Klasse existiert und die options() Methode hat
    if (class_exists($enumClass) && method_exists($enumClass, 'options')) {
        $itemsArrayField = $enumClass::options();
        $disabled = '';
    } else {
        $disabled = 'disabled';
        $itemsArrayField = [];
    }

    $label ??= ucfirst($itemFilterField);
    $oldval ??= '';
    $onchange ??= 'onchange=this.form.submit()';
@endphp

<select class="form-select" id="{{ $itemFilterField }}" name="{{ $itemFilterField }}" {{ $onchange }} {{ $disabled }}>
    <option value="">{{ $label }}:</option>
    @foreach ($itemsArrayField as $key => $val)
        <option value="{{ $key }}" @if ($oldval == $key) selected @endif>
            {{ Str::limit($val, 15) }}
        </option>
    @endforeach
</select>
