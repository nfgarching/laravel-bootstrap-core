<div>
    <!-- livewire.listing.index-->

    @include('livewire.modal.switch')

    <x-card>

        @include('livewire.listing.card.headerLeft')
        @include('livewire.listing.card.headerRight') 

        @include('livewire.listing.select.header')

        <x-table class="table" id="accordion-{{ $token }}">
            @forelse ($rows as $row)
                @if ($loop->first)
                    <x-slot:head>
                        @include('livewire.listing.table.header')
                    </x-slot:head>
                @endif
                @include('livewire.listing.table.row1')
                @include('livewire.listing.table.row2')
                @if ($loop->last && $rows->lastpage() > 1)
                    @include('livewire.listing.table.footer')
                @endif
            @empty
                <x-table.row>
                    <x-table.cell width="10%" />
                    <x-table.cell width="90%" value="--- No Data ---" />
                </x-table.row>
            @endforelse
        </x-table>

    </x-card>

    <x-counter startTime="{{ $startTime }}" />

</div>
