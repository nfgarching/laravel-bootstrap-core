{{-- x-tags: table.row.line1 --}}
@props([
    'acc_id' => 'accordionExample',
    'row_id' => '',
    'toggleCollapse' => true, // Standard: true (toggleCollapse eingeblendet)
    'toggleChecks' => false, // Standard: false (Checkboxes ausgeblendet)
    'toggleRadios' => false, // Standard: false (Radioboxes ausgeblendet)
    'selectAll' => false,
])

<tr {{ $attributes->merge(['class' => '']) }}>

    {{-- select check- and radiobox --}}
    <td align="center" nowrap>
        @if ($toggleChecks)
            <input class="form-check-input" name="checks[]" type="checkbox" value="{{ $row_id }}"
                @if ($selectAll !== false) checked @endif>
        @endif
        @if ($toggleRadios)
            <input class="form-check-input" name="select" type="radio" value="{{ $row_id }}">
        @endif
    </td>

    {{ $slot }}

    @if ($toggleCollapse)
        <td align="center" width="5%">
            <a class="btn-toggle nav-item" data-bs-toggle="collapse" data-bs-target="#collapse{{ $acc_id . $row_id }}"
                href="">
                <i class="bi bi-arrows-collapse"></i>
            </a>
        </td>
    @endif

</tr>
