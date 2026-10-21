{{-- x-tags: table.filter.search --}}
@props([
    'field'  => '',
    'table'  => 'Suchen',
    'oldval' => '',
])

<input class="form-control" style="width: 200px;" 
    name="{{ $field }}" 
    placeholder="{{ ucfirst($table) }}..."
    type="text" 
    value="{{ $oldval ? $oldval : '' }}"
>
