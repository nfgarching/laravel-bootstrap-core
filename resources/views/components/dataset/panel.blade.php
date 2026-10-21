{{-- Generisches Dataset-Panel für Offcanvas/Seitenbereiche.
     Einbindung: @include('core::dataset.panel', ['configKey' => 'textbot.dataset_models'])
     Erwartet den Livewire-Concern Nfgarching\Core\Traits\ManagesDataset
     in der umgebenden Komponente. --}}

<div class="mb-4">
    <h6 class="text-muted text-uppercase small mb-2">
        <i class="bi bi-database"></i> Datensatz
    </h6>

    <ul class="list-group list-group-flush mb-2">
        @forelse ($this->datasetEntries as $entry)
            <li class="list-group-item d-flex justify-content-between align-items-center px-2 py-1">
                <span class="text-truncate me-2">
                    <span class="badge text-bg-secondary me-1">{{ $entry->typeLabel($configKey) }}</span>
                    <small>{{ $entry->displayText($configKey) }}</small>
                </span>
                <button type="button"
                        class="btn btn-sm btn-outline-danger border-0"
                        wire:click="removeDatasetEntry({{ $entry->id }})"
                        wire:confirm="Eintrag wirklich entfernen?"
                        title="Entfernen">
                    <i class="bi bi-trash3"></i>
                </button>
            </li>
        @empty
            <li class="list-group-item text-muted small px-2 py-1">Keine Einträge</li>
        @endforelse
    </ul>

    <div class="row g-1">
        <div class="col-12">
            <select class="form-select form-select-sm" wire:model="datasetType">
                <option value="">Modell wählen …</option>
                @foreach ($this->datasetModelOptions as $class => $meta)
                    <option value="{{ $class }}">{{ $meta['label'] ?? class_basename($class) }}</option>
                @endforeach
            </select>
            @error('datasetType')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-4">
            <input type="text"
                   class="form-control form-control-sm"
                   placeholder="ID"
                   wire:model="datasetKey"
                   wire:keydown.enter="addDatasetEntry">
        </div>
        <div class="col-8">
            <input type="text"
                   class="form-control form-control-sm"
                   placeholder="Bezeichnung (optional)"
                   wire:model="datasetLabel"
                   wire:keydown.enter="addDatasetEntry">
        </div>
        <div class="col-12">
            @error('datasetKey')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
            <button type="button" class="btn btn-sm btn-outline-primary w-100" wire:click="addDatasetEntry">
                <i class="bi bi-plus-lg"></i> Hinzufügen
            </button>
        </div>
    </div>
</div>
