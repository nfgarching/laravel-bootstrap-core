<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

/**
 * Livewire-Concern für das generische Dataset-Panel.
 *
 * Die konsumierende Komponente liefert den Owner (aktuelle Session) und den
 * Config-Key mit den erlaubten Model-Klassen, z.B. 'textbot.dataset_models'
 * oder 'chatbot.dataset_models' (Form: FQCN => ['label' => ..., 'display' => ...]).
 */
trait ManagesDataset
{
    public ?string $datasetType = null;

    public string $datasetKey = '';

    public string $datasetLabel = '';

    /** Owner-Model des Datasets (z.B. die aktuelle Session). */
    abstract public function datasetOwner(): ?Model;

    /** Config-Key mit der Whitelist der erlaubten Model-Klassen. */
    abstract public function datasetConfigKey(): string;

    /** @return array<class-string, array{label?: string, display?: string}> */
    #[Computed]
    public function datasetModelOptions(): array
    {
        return config($this->datasetConfigKey(), []);
    }

    #[Computed]
    public function datasetEntries(): Collection
    {
        return $this->datasetOwner()?->datasetEntries()->get() ?? collect();
    }

    public function addDatasetEntry(): void
    {
        $owner = $this->datasetOwner();
        if (! $owner) {
            return;
        }

        $type = $this->datasetType;
        $key = trim($this->datasetKey);

        // Whitelist-Prüfung gegen die Config, nie den rohen Request-Wert nutzen.
        if (! $type || ! array_key_exists($type, $this->datasetModelOptions) || ! class_exists($type)) {
            $this->addError('datasetType', 'Bitte ein gültiges Modell wählen.');

            return;
        }

        if ($key === '' || ! $type::query()->whereKey($key)->exists()) {
            $this->addError('datasetKey', 'Kein Datensatz mit dieser ID gefunden.');

            return;
        }

        $owner->attachDatasetModel($type, $key, $this->datasetLabel !== '' ? trim($this->datasetLabel) : null);

        $this->reset('datasetKey', 'datasetLabel');
        $this->resetErrorBag(['datasetType', 'datasetKey']);
        unset($this->datasetEntries);
    }

    public function removeDatasetEntry(int $entryId): void
    {
        $this->datasetOwner()?->detachDatasetEntry($entryId);
        unset($this->datasetEntries);
    }
}
