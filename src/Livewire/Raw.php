<?php

namespace Nfgarching\Core\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layout::popup')]
class Raw extends Component
{
    /** @var array<int, object> */
    public array $cols = [];

    /** @var array<string, mixed> */
    public array $form = [];

    public ?Model $item = null;

    public string $modelType = '';

    public int|string|null $modelId = null;

    public string $modalName = 'modalRAW';

    public string $modalTitle = 'RAW';

    public bool $popupMode = false;

    public function mount(): void
    {
        if (request()->hasAny(['model', 'keyid'])) {
            $this->popupMode = true;
            $this->loadItem(
                request()->string('model')->toString(),
                request()->string('keyid')->toString(),
            );
        }
    }

    /**
     * Fill the form from a dispatched payload and open the in-page modal.
     *
     * @param  class-string<Model>  $model
     */
    #[On('openModalRaw')]
    public function open(string $model = '', int|string $keyid = ''): void
    {
        $this->reset(['cols', 'form', 'item', 'modelType', 'modelId']);
        $this->resetValidation();

        $this->loadItem($model, $keyid);

        $this->dispatch('show-modal', modalName: $this->modalName);
    }

    /**
     * Load the model instance and its column metadata into the form.
     *
     * @param  class-string<Model>  $model
     */
    private function loadItem(string $model, int|string $keyid): void
    {
        if ($model === '' || ! class_exists($model) || ! is_subclass_of($model, Model::class)) {
            return;
        }

        $this->item = $model::find($keyid);

        if (! $this->item) {
            return;
        }

        $this->modelType = $model;
        $this->modelId = $keyid;
        $this->form = $this->item->toArray();
        $this->modalTitle = "RAW: {$this->item->getTable()}";

        $connection = DB::connection($this->item->getConnectionName());

        $this->cols = $connection->getDriverName() === 'sqlite'
            ? array_map(
                static fn (object $col): object => (object) ['Field' => $col->name, 'Type' => $col->type],
                $connection->select('PRAGMA table_info('.$this->item->getTable().')'),
            )
            : $connection->select('SHOW FIELDS FROM `'.$this->item->getTable().'`');
    }

    public function saveModal(): void
    {
        $this->item->update(
            $this->restoreLineEndings($this->form)
        );

        session()->flash('message', __('Datensatz wurde gespeichert.'));

        if ($this->popupMode) {
            $this->js('window.opener && window.opener.location.reload(); window.close();');
        } else {
            $this->dispatch('reload-window');
        }
    }

    /**
     * Browser normalisieren Zeilenumbrüche in <textarea> auf LF. Legacy-Texte (z. B. Textbausteine)
     * sind mit CRLF gespeichert und werden von LibreOffice nur so als Absatz erkannt – daher
     * CRLF wiederherstellen, wenn der ursprüngliche Wert CRLF enthielt oder die Tabelle zur
     * Legacy-Verbindung gehört.
     *
     * @param  array<string, mixed>  $form
     * @return array<string, mixed>
     */
    private function restoreLineEndings(array $form): array
    {
        $legacy = $this->item->getConnectionName() === 'akv';

        foreach ($form as $key => $value) {
            if (! is_string($value) || ! str_contains($value, "\n")) {
                continue;
            }

            $original = $this->item->getRawOriginal($key);

            if ($legacy || (is_string($original) && str_contains($original, "\r\n"))) {
                $form[$key] = preg_replace('/\r\n|\r|\n/', "\r\n", $value);
            }
        }

        return $form;
    }

    public function deleteModal(): void
    {
        $this->item->delete();

        session()->flash('message', __('Datensatz wurde gelöscht.'));

        if ($this->popupMode) {
            $this->js('window.opener && window.opener.location.reload(); window.close();');
        } else {
            $this->dispatch('reload-window');
        }
    }

    public function render(): string
    {
        return <<<'blade'
        <div>
            @if ($popupMode)
                {{-- Eigenständiges Popup-Fenster: kein Modal, normale Seite --}}
                <div class="container py-3">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h1 class="card-title fs-5 mb-0">
                                <i class="bi bi-pencil-square me-2"></i>{{ $modalTitle }}
                            </h1>
                        </div>
                        <div class="card-body">
                            @include('core-livewire::partials.raw-fields')
                        </div>
                        <div class="card-footer d-flex justify-content-between gap-2">
                            <button wire:loading.attr="disabled" wire:target="deleteModal" wire:click="deleteModal"
                                wire:confirm="Datensatz wirklich löschen? Dies kann nicht rückgängig gemacht werden."
                                type="button" class="btn btn-outline-danger">Löschen</button>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="window.close()"
                                    wire:loading.attr="disabled" wire:target="saveModal">Abbrechen</button>
                                <button wire:loading.attr="disabled" wire:target="saveModal" wire:click="saveModal"
                                    type="submit" class="btn btn-danger">Speichern</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- In-Page Bootstrap-Modal --}}
                <x-modal.save class="modal-lg" title="{{ $modalTitle }}" modid="{{ $modalName }}">

                    @include('core-livewire::partials.raw-fields')

                    <x-slot:footer>
                        <div class="container d-flex justify-content-between">
                            <button wire:loading.attr="disabled" wire:target="deleteModal" wire:click="deleteModal"
                                wire:confirm="Datensatz wirklich löschen? Dies kann nicht rückgängig gemacht werden."
                                type="button" class="btn btn-outline-danger">Löschen</button>
                            <div class="btn-group">
                                <button wire:loading.attr="disabled" type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Abbrechen</button>
                                <button wire:loading.attr="disabled" wire:target="saveModal" wire:click="saveModal"
                                    type="submit" class="btn btn-danger">Speichern</button>
                            </div>
                        </div>
                    </x-slot:footer>

                </x-modal.save>
            @endif
        </div>
        blade;
    }
}
