<x-app-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group">
                <x-button.link :href="route('core.props.index')" type="outline-secondary" tooltip="Zurück zur Liste">
                    <i class="bi bi-arrow-left-circle"></i>
                </x-button.link>
                <x-button type="light">Prop #{{ $prop->id }}</x-button>
            </div>
            <div class="btn-group float-end">
                <x-button.link :href="route('core.props.edit', $prop)" type="outline-primary" class="btn-sm">
                    <i class="bi bi-pencil-square"></i>
                </x-button.link>
            </div>
        </div>
        <hr>
    </x-slot>

    <x-card>

        <x-show.item :item="$prop->toArray()" />

    </x-card>

</x-app-layout>
