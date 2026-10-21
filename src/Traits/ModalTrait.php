<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ModalTrait
{
    use MainTrait;

    public string $modalName;

    public string $modalField = '';

    public string $modalSize = 'modal-lg';

    public string $modalSwitch = 'default';

    public string $modalTitle = 'modalTitle';

    public string $modalValue = '';

    // public $modalFilter, $modalForm, $modalOutput, $modalRaw;
    // public array $modalCols = [];
    // public array $modalForm = [];
    // public $modalItem;

    public function build_modal()
    {
        if (! isset($this->modalName)) {
            $this->modalName = 'modalName'.rand();
        }
        // if (isset($this->modelName)) {
        //     $this->build_model($this->modelName);
        // } else {
        //     $this->build_main();
        // }
        if (isset($this->title)) {
            $this->modalTitle = $this->title;
        }
    }

    public function open_modal($data = [])
    {
        // dump(get_class() . ' -> ' . __FUNCTION__, $data);
        // dump($this->modalName, $this->modalSize, $this->modalSwitch, $this->modalTitle);
        $this->parse_data($data);
        switch ($this->modalSwitch) {
            case 'default':
                // dump('open_modal', $data, $this->modalSwitch);
                if (isset($this->model) && $data['id']) {
                    $this->modalItem = $this->model::findOrFail($data['id'])->toArray();
                }
                break;
            case 'deletee':
                dd('open_modal', $data, $this->modalSwitch);
                if ($this->model) {
                    $this->model->findOrFail($id)->delete();
                } else {
                    DB::table($this->table)->where('id', $id)->delete();
                }
                // $this->dispatch('show-toast', message: 'modalItem gelöscht.');
                $this->dispatch('hide-modal');
                break;
            case 'insert':
                // $this->form->resetForm();
                foreach ($data as $key => $val) {
                    $this->form->$key = $val;
                }
                break;
            case 'select':
            case 'update':
                if (isset($data['NR'])) {
                    $this->form->resetForm();
                    $item = $this->model->findOrFail($data['NR']);
                    $this->form->setForm($item);
                } else {
                    // dd('open_modal->update', $data, $this->modalSwitch);
                }
                break;
            default:
                dump(get_class().' -> '.__FUNCTION__, $data);
                dd($this->modalName, $this->modalSize, $this->modalSwitch, $this->modalTitle);
        }
        $this->dispatch('show-modal', modalName: $this->modalName);
    }

    public function parse_data($data)
    {
        // dump(get_class() . ' -> ' . __FUNCTION__, $data, $this->form->all());
        if (isset($data['size'])) {
            $this->modalSize = $data['size'];
        }
        if (isset($data['switchTo'])) {
            $this->modalSwitch = $data['switchTo'];
        }
        if (isset($data['title'])) {
            $this->modalTitle = $data['title'];
        }
    }

    public function save_modal($data = [])
    {
        Log::debug(get_class().' -> '.__FUNCTION__, $data);
        info($this->modalName, $this->modalSize, $this->modalSwitch, $this->modalTitle);
        dd('save_modal', $data, $this->form, $this->modalSwitch);
        switch ($this->modalSwitch) {
            case 'insert':
                dd('save_modal->insert', $data, $this->form, $this->modalSwitch);
                if ($this->form) {
                    return $this->form->create();
                } else {
                    dd('save_modal->insert', $data, $this->form, $this->modalSwitch);
                }
                break;

            case 'update':
                dd('save_modal->update', $data, $this->form, $this->modalSwitch);

                if ($this->form) {
                    // code...
                    $this->form->update_all();
                } else {
                    // code...
                    dd('save_modal', $this->form, $this->model, $this->modalItem);
                }

                session()->flash('message', 'Document successfully updated.');
                // $this->dispatch('show-toast', message: session()->get('message'));
                // $this->dispatch(
                //     'show-toast',
                //     title: 'Hallo',
                //     subtitle: 'Welt',
                //     message: session()->get('message'),
                // );
                break;

            default:
                dd('save', $this->form, $this->form->all());
                break;
        }

        dd('save_modal', $this->form, $this->model, $this->modalItem);

        if ($this->modelName) {
            // dd('save', $this->modelName, $this->modalItem);
            $model = $this->modelName::findOrFail($this->modalItem['id']);
            $model->update($this->modalItem);
        } else {
            // dd('save', $this->table, $this->modalItem);
            if (! $this->table) {
                exit('Error by saveing: NO Table set.');
            }
            if (isset($this->modalItem['id'])) {
                // echo $this->table; dump('Update'); dump($this->modalItem->name_id); dd($this->modalItem);
                DB::table($this->table)
                    ->where('id', $this->modalItem['id'])
                    ->update($this->modalItem);
                session()->flash('message', 'modalItem successfully updated.');
            } else {
                // echo $this->table; dd($this->modalItem);
                DB::table($this->table)->insert($this->modalItem);
                session()->flash('message', 'modalItem successfully created.');
            }
        }
        // $this->dispatch('hide-modal', modalName: $this->modalName);
        // $this->dispatch('show-toast', message: session()->get('message'));
    }
}
