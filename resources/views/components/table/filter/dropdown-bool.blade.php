{{-- x-tags: table.filter.dropdown.bool --}}
@props(['field', 'label', 'bool', 'oldval', 'onchange'])

@php
    $itemFilterField = $field;
    $enumClass = $enum ?? '';
    $disabled = '';

    // Prüfen ob die Enum-Klasse existiert und die options() Methode hat
    if (class_exists($enumClass) && method_exists($enumClass, 'options')) {
        $itemsArrayField = $enumClass::options();
        $disabled = '';
    } else {
        // $disabled = 'disabled';
        $itemsArrayField = [];
    }

    $label ??= ucfirst($itemFilterField);
    $oldval ??= '';
    $onchange ??= 'onchange=this.form.submit()';
@endphp

<select class="form-select" id="{{ $itemFilterField }}" name="{{ $itemFilterField }}" {{ $onchange }}
    {{ $disabled }}>

    <option value="">{{ $label }}:</option>
    {{-- <option value="">Alle:</option> --}}
    <option value="1" {{ $oldval === '1' ? 'selected' : '' }}>True</option>
    <option value="0" {{ $oldval === '0' ? 'selected' : '' }}>False</option>

</select>

{{-- <select class="form-select" id="is_active" name="is_active" onchange="this.form.submit()">
    <option value="">Alle</option>
    <option value="1" {{ $request->is_active === '1' ? 'selected' : '' }}>Aktiv</option>
    <option value="0" {{ $request->is_active === '0' ? 'selected' : '' }}>Inaktiv</option>
</select> --}}
