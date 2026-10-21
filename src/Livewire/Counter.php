<?php

namespace Nfgarching\Core\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Counter extends Component
{
    public $count = 1;

    #[On('testing')]
    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        // return view('core::counter_old.counter');
        return <<<HTML
            <div>
                <h1>{{ $this->count }}</h1>
                <button wire:click="increment">+</button>
                <button wire:click="decrement">-</button>
                <hr>
                <center>{{ date('H:i:s') }}</center>
            </div>
        HTML;
    }
}
