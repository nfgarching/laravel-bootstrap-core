{{-- x-tags: table.row.line2 --}}
@props([
    'acc_id' => 'accordionExample',
    'row_id' => '',
])

<tr></tr>
{{-- Zusätzliche Attribute (z. B. class="show" für serverseitig aufgeklappte Zeilen, wire:key) landen am <tr> --}}
<tr {{ $attributes->merge(['class' => 'accordion-collapse collapse']) }}
    data-bs-parent="#{{ $acc_id }}"
    id="collapse{{ $acc_id . $row_id }}"
>
    <td></td>
    {{ $slot }}
    <td></td>
</tr>
