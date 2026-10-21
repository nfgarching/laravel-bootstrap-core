<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

trait FormTrait
{
    #[On('field_value')]
    public function field_value($token, $field, $value, $refresh = false)
    {
        // dd( 'field_value', $token, $field, $value, $this->form);
        // if ($value == '0') dd('value==0');
        if ($token == $this->token) {
            // dd($token, $field, $value, $this->form);
            // Log::debug( __CLASS__ . '->' . __FUNCTION__ . '');
            $this->form->$field = $value;
            if ($this->form->NR) {
                $this->updated($field, $value);
                // $this->dispatch('reload-window');
                // Log::debug(__CLASS__ . '->' . __FUNCTION__ .
                //     "->updateForm: $field = $value");
            } else {
                // Log::debug(__CLASS__ . '->' . __FUNCTION__ .
                //     "->setForm: $field = $value");
            }
            // $this->dispatch('reload-form');
            if ($refresh) {
                $this->dispatch('refreshEdit');
            } else {
                $this->skipRender();
            }
            // s. https://laracasts.com/discuss/channels/livewire/livewire-3-refresh-component
        }
    }

    public function update_field($field, $value)
    {
        // dd(get_class() . ' -> ' . __FUNCTION__, $field, $value, $this->form->all());
        if (str_contains($field, '.')) {
            $pieces = explode('.', $field);
            $field = $pieces[1];
        }
        // $this->form->$field = $value;
        if ($this->form->id) {
            $this->form->updateField($field, $value);
        } else {
            // dd('updated', $field, $value, $this->form);
        }
        // $this->dispatch('reload-form', fld: $field, val: $value);
        // $this->dispatch('reload-window');
        // dd('updated_1', $field, $value, $this->form->all());
    }
}
