<?php

namespace Nfgarching\Core\Livewire\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Nfgarching\Core\Traits\TranscribesDictation;
use Nfgarching\Textbot\Exceptions\TextEnhancementException;
use Nfgarching\Textbot\Models\TextUsage;
use Nfgarching\Textbot\Services\AiSettings;

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

    /** KI-Einstellungen für <x-ai-button> (textbot); bleiben über mehrere Aufrufe des Modals erhalten */
    public string $mode = '';

    public string $provider = '';

    public string $aimodel = '';

    /** Letzter KI-Lauf in diesem Feld (textbot_usages) für die Verbrauchsanzeige */
    #[Locked]
    public ?int $aiUsageId = null;

    public function mount(): void
    {
        $this->initAiSettings();
    }

    #[On('openModalField')]
    public function open(string $modelClass = '', int|string $keyid = 0, string $field = 'memo'): void
    {
        $this->reset(['item', 'field', 'value', 'modalTitle', 'preview', 'errorMessage', 'aiUsageId']);

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

    /** <x-ai-button> und der KI-Lauf kommen aus dem textbot-Package */
    #[Computed]
    public function textbotAvailable(): bool
    {
        return class_exists(AiSettings::class) && view()->exists('core::ai-button');
    }

    /** Modi des Textbots für <x-ai-button> */
    #[Computed]
    public function aiModes(): array
    {
        return $this->textbotAvailable ? app(AiSettings::class)->modes() : [];
    }

    /** Provider und Modelle des Textbots für <x-ai-button> */
    #[Computed]
    public function aiProviders(): array
    {
        return $this->textbotAvailable ? app(AiSettings::class)->providers() : [];
    }

    /** Verbrauch des letzten KI-Laufs für <x-ai-button> */
    #[Computed]
    public function aiUsage(): array
    {
        if (! $this->aiUsageId || ! $this->textbotAvailable) {
            return [];
        }

        $usage = TextUsage::find($this->aiUsageId);

        return $usage ? ['last' => TextUsage::summary([$usage])] : [];
    }

    /** Startwerte: Standardmodus des Textbots mit dessen Provider/Modell */
    protected function initAiSettings(): void
    {
        if (! $this->textbotAvailable || $this->mode !== '') {
            return;
        }

        $this->mode = app(AiSettings::class)->defaultMode();
        $this->updatedMode($this->mode);
    }

    /** Ein Moduswechsel zieht Provider und Modell des Modus nach (wie im Textbot) */
    public function updatedMode(string $value): void
    {
        if (! $this->textbotAvailable || ! array_key_exists($value, $this->aiModes)) {
            return;
        }

        ['provider' => $this->provider, 'aimodel' => $this->aimodel] = app(AiSettings::class)->defaultsFor($value);
    }

    public function updatedProvider(string $value): void
    {
        if ($this->textbotAvailable) {
            $this->aimodel = app(AiSettings::class)->defaultModelFor($value);
        }
    }

    /** Feldinhalt per KI bearbeiten – mit Modus, Provider und Modell aus <x-ai-button> */
    public function enhance(): void
    {
        $this->errorMessage = null;

        if (! $this->textbotAvailable) {
            return;
        }

        if (trim($this->value) === '') {
            $this->errorMessage = 'Bitte zuerst einen Text eingeben.';

            return;
        }

        $settings = app(AiSettings::class);
        $ai = $settings->resolve($this->provider, $this->aimodel, $this->mode);

        try {
            ['text' => $this->value, 'usage' => $usage] = $settings->run($this->value, $ai, 'feld');
            $this->aiUsageId = $usage->id;
            unset($this->aiUsage, $this->previewHtml);
        } catch (TextEnhancementException $e) {
            $this->errorMessage = $e->getMessage();
        }
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
                    @if ($this->textbotAvailable)
                        {{-- Split-Button des textbot: KI ausführen, Modus/Provider/Modell im Panel --}}
                        <x-dynamic-component component="ai-button" variant="outline-primary" :modes="$this->aiModes"
                            :providers="$this->aiProviders" :mode="$mode" :provider="$provider" :aimodel="$aimodel"
                            :usage="$this->aiUsage" />
                    @endif
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
