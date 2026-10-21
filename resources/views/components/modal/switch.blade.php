<!-- Modal Switch -->

<x-modal class="{{ $modalSize }}" title="{{ $modalTitle }}" modid="{{ $modalName }}">

    @switch($modalSwitch)

        @case('editField')
            {{-- <x-field.text       field="{{ $modalField }}" value="x-field" /> --}}
            <x-wire.text modifier="change" field="{{ $modalField }}" rows="25" />
            {{-- <x-wire.text field="{{ $modalField }}" /> --}}
            {{-- <x-wire.text  field="{{ $modalField }}" /> --}}

            <div class="form-text">
                ...
            </div>
        @break

        @case('insert')
        @case('update')
            @include('livewire.modal.form')
        @break

        @case('search')
            @include('livewire.modal.search')
        @break

        @case('select')
            @include('livewire.modal.select')
        @break

        @default
            modalSwitch: {{ $modalSwitch }}?

    @endswitch

</x-modal>
