{{-- x-tags: table.row --}}
@props([
    'acc_id' => 'accordionExample',
    'row_id' => rand(100, 999),
    'check_value' => null, // Falls null, wird row_id als Checkbox-Wert verwendet
    'toggleCollapse' => true,
    'toggleChecks' => false, // Standard: false (Checkboxes ausgeblendet)
    'toggleRadios' => false, // Standard: false (Radioboxes ausgeblendet)
    'toggleRows' => false, // Standard: false (Anklicken der Zeile für Collapse)
    'selectAll' => false,
])

<tr {{ $attributes->merge(['class' => '']) }}
    @if ($toggleRows == true) data-bs-toggle="collapse" data-bs-target="#collapse{{ $acc_id . $row_id }}" @endif>

    {{-- select check- and radiobox --}}
    <td align="center" nowrap>
        @if ($toggleChecks == true)
            <input class="form-check-input" name="checks[]" type="checkbox" value="{{ $check_value ?? $row_id }}"
                @if ($selectAll !== false) checked @endif>
        @endif
        @if ($toggleRadios == true)
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

@if (isset($row2))
    <tr></tr>
    <tr class="accordion-collapse collapse" data-bs-parent="#{{ $acc_id }}" id="collapse{{ $acc_id . $row_id }}"
        wire:ignore.self>
        <td></td>
        {{ $row2 }}
        <td></td>
    </tr>
@endif
