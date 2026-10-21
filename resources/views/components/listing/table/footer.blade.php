<!-- livewire.listing.table.footer -->

@php
    if (!isset($colspan)) {
        $colspan = 3;
    }
@endphp


<tr>
    <td colspan="{{ $colspan }}">
        @include('core::listing.pagination')
    </td>
</tr>
