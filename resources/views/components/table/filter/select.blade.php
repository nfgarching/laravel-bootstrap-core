{{-- x-tags: table.filter.select --}}
@props([
    'field' => '',
    'table' => '',
    'oldval' => '',
    'options' => [],
    'disabled' => '',
])

@php
    $options = collect($options);

    if ($options->isEmpty()) {
        if ($table) {
            $config = config("$table.$field");
            $options = $config ? collect($config) : collect();
        }
        if ($options->isEmpty()) {
            $disabled = 'disabled';
        }
    }
@endphp

<select class="form-select" name="{{ $field }}" {{ $disabled }}>
    <option value="">{{ ucfirst($field) }}:</option>
    @unless ($disabled)
        @foreach ($options as $key => $val)
            <option value="{{ $key }}" @selected($oldval == $key)>
                {{ $val }}
            </option>
        @endforeach
    @endunless
</select>
