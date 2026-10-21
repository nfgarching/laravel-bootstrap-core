<!-- Listing listing.selectHeader -->

@if($toggleSelect !==false)
<x-button.group>
    <x-dropdown>
        <x-dropdown.click click="$toggle('selectAll')">
            @if ( $selectAll )
            Select Nothing
            @else
            Select All
            @endif
        </x-dropdown.click>
    </x-dropdown>
    <x-button click="btn_select" modid="$modalOutput" type='outline-primary'>
        click:btn_select
    </x-button>
</x-button.group>
<hr>
@endif
