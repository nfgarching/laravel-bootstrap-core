<!-- Listing listing.headerLeft -->

<x-slot name="headerLeft">

    <div class="input-group" role="group">

        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
            data-bs-toggle="dropdown"></button>
        <ul class="dropdown-menu">

            <x-dropdown.link class="disabled" href="#">
                <i class="bi bi-funnel"></i> Filter
            </x-dropdown.link>

            <li>
                <hr class="dropdown-divider">
            </li>

            <x-dropdown.click click="$toggle('toggleSelect')">
                @if ($toggleSelect)
                    Select deaktivieren
                @else
                    Select aktivieren
                @endif
            </x-dropdown.click>

            @if ($sortFields)
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <h6 class="dropdown-header">Sortieren</h6>
                </li>
                @foreach ($sortFields as $sortKey)
                    <x-dropdown.sort field="{{ $sortKey }}" :wire="$this" />
                @endforeach
            @endif

            <li>
                <hr class="dropdown-divider">
            </li>

            <x-dropdown.link class="disabled" href="#">
                ToDo PDF erstellen
            </x-dropdown.link>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <center>
                    ...
                </center>
            </li>

        </ul>

        <input wire:model.live.blur="search" wire:keydown.enter="$refresh" type="text" class="form-control"
            placeholder="{{ $pageTitle ? $pageTitle : 'Suchen...' }}">

        <button wire:loading.delay class="btn" type="button">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </button>

        <div wire:loading.remove>
            @if ($search)
                <button wire:click="reset_search" class="btn" type="button">
                    <i class="bi bi-x-circle"></i>
                </button>
            @else
                <button wire:click="reset_search" class="btn" type="button" id="button-addon2">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            @endif
        </div>

        {{-- <div wire:loading.delay>
            <div class="spinner-grow" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div> --}}

        {{-- <div wire:loading.remove>
            @if ($search)
                <button wire:click="reset_search" class="btn btn-outline-danger" type="button">
                    <i class="bi bi-x-circle"></i>
                </button>
            @endif
        </div> --}}

    </div>

</x-slot>
