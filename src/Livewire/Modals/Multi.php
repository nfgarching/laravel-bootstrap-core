<?php

namespace Nfgarching\Core\Livewire\Modals;

use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;

class Multi extends Component
{
    public string $modalName = 'modalMulti';

    public string $modalTitle = 'Multi anzeigen';

    public string $value = '';

    #[On('openModalMulti')]
    public function open(string $modelName = '', int|string $NR = 0): void
    {
        $this->reset(['value', 'modalTitle']);

        $item = null;
        if ($modelName && class_exists($modelName) && is_subclass_of($modelName, Model::class)) {
            $item = $modelName::find($NR);
        }

        $this->value = $item?->get_multi() ?? '';
        $this->modalTitle = 'Multi anzeigen';

        $this->dispatch('show-modal', modalName: $this->modalName);
    }

    public function saveModal(): void
    {
        $this->dispatch('hide-modal', modalName: $this->modalName);
    }

    public function render(): string
    {
        return <<<'blade'
        <div>
            <x-modal class="modal-lg" title="{{ $modalTitle }}" modid="{{ $modalName }}">

                <textarea class="form-control" rows="15" readonly>{{ $value }}</textarea>

            </x-modal>
        </div>
        blade;
    }
}
