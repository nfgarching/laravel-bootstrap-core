<?php

namespace Nfgarching\Core\Livewire\Modals;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

class Field extends Component
{
    public ?Model $item = null;

    public string $field = 'Memo';

    public string $value = '';

    public string $modalName = 'modalField';

    public string $modalTitle = 'Feld bearbeiten';

    #[On('openModalField')]
    public function open(string $modelClass = '', int|string $keyid = 0, string $field = 'memo'): void
    {
        $this->reset(['item', 'field', 'value', 'modalTitle']);

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

    public function render(): string
    {
        return <<<'blade'
        <div>
            <x-modal.save class="modal-lg" title="{{ $modalTitle }}" modid="{{ $modalName }}">

                <textarea class="form-control" wire:model="value" rows="15"></textarea>

            </x-modal.save>
        </div>
        blade;
    }
}
