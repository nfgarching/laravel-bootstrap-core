<?php

namespace Nfgarching\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Generischer Dataset-Eintrag: verknüpft einen Owner (z.B. ChatSession,
 * TextSession) polymorph mit einem beliebigen Ziel-Model (z.B. Akte,
 * Dokument, Name, Register). Dasselbe Ziel darf mehrfach angehängt werden.
 */
class DatasetEntry extends Model
{
    protected $table = 'dataset_entries';

    protected $guarded = ['created_at', 'updated_at'];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Kurz-Label des Model-Typs aus der Consumer-Config
     * (z.B. config('textbot.dataset_models')).
     */
    public function typeLabel(string $configKey): string
    {
        $meta = config($configKey, [])[$this->model_type] ?? [];

        return $meta['label'] ?? class_basename($this->model_type);
    }

    /**
     * Anzeige-Text des Eintrags: Display-Attribut des Ziel-Models laut Config,
     * Fallback auf das eigene Label bzw. "Klasse #ID". Unauflösbare Ziele
     * (z.B. gelöschte akv-Zeilen) dürfen nicht crashen.
     */
    public function displayText(string $configKey): string
    {
        $meta = config($configKey, [])[$this->model_type] ?? [];

        try {
            $model = $this->model;
        } catch (\Throwable) {
            $model = null;
        }

        $attribute = $meta['display'] ?? null;
        if ($model && $attribute && filled($model->{$attribute} ?? null)) {
            return (string) $model->{$attribute};
        }

        if (filled($this->label)) {
            return $this->label;
        }

        return class_basename($this->model_type).' #'.$this->model_id;
    }
}
