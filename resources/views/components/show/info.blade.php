{{-- x-tags: show.info --}}
<!-- Info -->
<x-card>

    <x-slot name="headerLeft">INFO</x-slot>
    <x-slot name="headerRight"></x-slot>

    @php
        $token = rand();
        $toggleRows = true;
    @endphp
    <x-table :acc_id="$token" class="table-sm table-striped">

        <x-table.row :acc_id="$token" :$toggleRows>{{-- Fields (All) --}}
            <td>Fields (All)</td>
            <x-slot:row2>
                <td><x-desc.list :item="$item->attributesToArray()" /></td>
            </x-slot:row2>
        </x-table.row>

        @if ($item->relationLoaded('akten'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Akten</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->akten?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('addresses'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Addresses</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->addresses?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('contacts'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Contacts</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->contacts?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('documents'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Documents</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->documents?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('dokumente'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Dokumente</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->dokumente?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('files'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Files</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->files?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('model'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Model</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->model?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('namen'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Namen</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->namen?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('names'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Names</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->names?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('props'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Props</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->props?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        @if ($item->relationLoaded('registers'))
            <x-table.row :acc_id="$token" :$toggleRows>
                <td>Registers</td>
                <x-slot:row2>
                    <td><x-desc.list :item="$item->registers?->toArray()" /></td>
                </x-slot:row2>
            </x-table.row>
        @endif

        <x-table.row :acc_id="$token" :$toggleRows>{{-- [DUMP] Item --}}
            <td>[DUMP] Item</td>
            <x-slot:row2>
                <td>@php dump($item->attributesToArray()); @endphp</td>
            </x-slot:row2>
        </x-table.row>

        <x-table.row :acc_id="$token" :$toggleRows>{{-- EMPTY --}}
            <td>[EMPTY]</td>
            <x-slot:row2>
                <td>...</td>
            </x-slot:row2>
        </x-table.row>

    </x-table>

</x-card>
