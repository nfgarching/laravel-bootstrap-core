{{-- x-tags: table.row.line2 --}}
@props([
    'acc_id' => 'accordionExample',
    'row_id' => '',
])

<tr></tr>
<tr class="accordion-collapse collapse"
    data-bs-parent="#{{ $acc_id }}"
    id="collapse{{ $acc_id . $row_id }}"
>
    <td></td>
    {{ $slot }}
    <td></td>
</tr>
