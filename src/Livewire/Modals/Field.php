<?php

namespace Nfgarching\Core\Livewire\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Nfgarching\Core\Traits\TranscribesDictation;

class Field extends Component
{
    use TranscribesDictation;
    use WithFileUploads;

    public ?Model $item = null;

    public string $field = 'Memo';

    public string $value = '';

    public string $modalName = 'modalField';

    public string $modalTitle = 'Feld bearbeiten';

    /** Vorschau statt Textfeld anzeigen (<x-markdown-textarea>) */
    public bool $preview = false;

    /** Meldung des Diktats (TranscribesDictation) */
    public ?string $errorMessage = null;

    #[On('openModalField')]
    public function open(string $modelClass = '', int|string $keyid = 0, string $field = 'memo'): void
    {
        $this->reset(['item', 'field', 'value', 'modalTitle', 'preview', 'errorMessage']);

        if ($modelClass && class_exists($modelClass) && is_subclass_of($modelClass, Model::class)) {
            $this->item = $modelClass::find($keyid);
        }

        $this->field = $field;
        $this->value = $this->item?->getAttribute($field) ?? '';
        $this->modalTitle = $field;

        $this->dispatch('show-modal', modalName: $this->modalName);
    }

    public function saveModal(): void
    {
        $this->item?->update([$this->field => $this->value]);

        $this->dispatch('hide-modal', modalName: $this->modalName);
        $this->dispatch('reload');
    }

    /**
     * Feldinhalt als gerendertes Markdown. Der Inhalt stammt aus der Datenbank,
     * daher wird enthaltenes HTML maskiert statt ausgegeben.
     */
    #[Computed]
    public function previewHtml(): string
    {
        return Str::markdown($this->value, ['html_input' => 'escape', 'allow_unsafe_links' => false]);
    }

    /** <x-markdown-textarea> kommt aus dem textbot-Package; ohne es ein schlichtes Textfeld */
    #[Computed]
    public function markdownAvailable(): bool
    {
        return view()->exists('core::markdown-textarea');
    }

    public function render(): string
    {
        return <<<'blade'
        <div>
            <x-modal.save class="modal-lg" title="{{ $modalTitle }}" modid="{{ $modalName }}">

                @if ($this->markdownAvailable)
                    <x-dynamic-component component="markdown-textarea" id="modal-field-value" wire:model="value"
                        rows="15" :preview="$preview" :preview-html="$preview ? $this->previewHtml : ''"
                        preview-class="textbot-preview" />
                @else
                    <textarea id="modal-field-value" class="form-control" wire:model="value" rows="15"></textarea>
                @endif

                <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                    <x-dictation target="#modal-field-value" transcribe />
                    @if ($errorMessage)
                        <span class="small text-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $errorMessage }}
                        </span>
                    @endif
                </div>

            </x-modal.save>
        </div>
        blade;
    }
}
