<x-app-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group">
                <x-button.link :href="route('core.props.index')" type="outline-secondary" tooltip="Zurück zur Liste">
                    <i class="bi bi-arrow-left-circle"></i>
                </x-button.link>
                <x-button type="light">Prop erstellen</x-button>
            </div>
        </div>
        <hr>
    </x-slot>

    <x-form :action="route('core.props.store')" method="POST">

        <x-card>

            <x-slot name="headerLeft">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Speichern
                </button>
            </x-slot>

            @include('page::props._form', ['prop' => null])

        </x-card>

    </x-form>

</x-app-layout>
