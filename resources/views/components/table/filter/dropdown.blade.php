{{-- x-tags: table.filter.dropdown --}}
@props(['field', 'label', 'options', 'table', 'oldval', 'onchange'])

@php
    $disabled = '';
    $field ??= '';

    if (isset($options)) {
        $itemFilterField = $field ?? '---';
        $itemsArrayField = $options;
    } elseif (isset($table)) {
        $itemFilterField = $field ?? '---';
        $itemsArrayField = config($table . '.' . $itemFilterField); //dd($itemsArrayField);
        if (!$itemsArrayField) {
            $disabled = 'disabled';
            $itemsArrayField = [];
        }
    } else {
        $disabled = 'disabled';
        $itemsArrayField = ['key' => 'Not found: ' . $field];
    }

    $label ??= ucfirst($itemFilterField);
    $oldval ??= '';
    $onchange ??= 'onchange=this.form.submit()';
@endphp

<select class="form-select" id ="{{ $itemFilterField }}" name ="{{ $itemFilterField }}" {{ $onchange }}
    {{ $disabled }}>
    <option value="">{{ $label }}:</option>
    @foreach ($itemsArrayField as $key => $val)
        @php
            if (is_array($val)) {
                if (isset($val['txt'])) {
                    $val = $val['txt'];
                } else {
                    continue;
                }
            }
        @endphp
        <option value="{{ $key }}" @if ($oldval == $key) selected @endif>
            {{ Str::limit($val, 15) }}
        </option>
    @endforeach
</select>
