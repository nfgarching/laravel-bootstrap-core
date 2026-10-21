<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Nfgarching\Core\Models\DatasetEntry;

/**
 * Für Owner-Models (z.B. ChatSession, TextSession): flexibles Dataset aus
 * beliebigen, auch mehrfach angehängten Ziel-Models.
 */
trait HasDataset
{
    public function datasetEntries(): MorphMany
    {
        return $this->morphMany(DatasetEntry::class, 'owner')
            ->orderBy('order')
            ->orderBy('id');
    }

    public function attachDatasetModel(string $type, int|string $id, ?string $label = null): DatasetEntry
    {
        return $this->datasetEntries()->create([
            'model_type' => $type,
            'model_id' => $id,
            'label' => $label,
        ]);
    }

    public function detachDatasetEntry(int $entryId): void
    {
        $this->datasetEntries()->whereKey($entryId)->delete();
    }

    /** Prüft, ob ein bestimmtes Ziel-Model bereits im Dataset hängt. */
    public function hasDatasetModel(string $type, int|string $id): bool
    {
        return $this->datasetEntries()
            ->where('model_type', $type)
            ->where('model_id', $id)
            ->exists();
    }
}
