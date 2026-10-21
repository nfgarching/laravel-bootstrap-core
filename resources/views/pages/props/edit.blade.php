<x-app-layout>

    <x-slot name="header">
        <div class="container">
            <div class="btn-group">
                <x-button.link :href="route('core.props.show', $prop)" type="outline-secondary" tooltip="Zurück">
                    <i class="bi bi-arrow-left-circle"></i>
                </x-button.link>
                <x-button type="light">Prop #{{ $prop->id }} bearbeiten</x-button>
            </div>
        </div>
        <hr>
    </x-slot>

    <x-form :action="route('core.props.update', $prop)" method="PUT">

        <x-card>

            <x-slot name="headerLeft">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Speichern
                </button>
            </x-slot>

            @include('page::props._form', ['prop' => $prop])

        </x-card>

    </x-form>

</x-app-layout>
