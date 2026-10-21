<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

trait ListingTrait
{
    use WithPagination;

    protected $paginationTheme = 'simple-bootstrap';

    public $pageLimit = 8;

    public $pageStart = 1;

    public $pageTitle = '';

    public $pageTotal = 0;

    public $search = '';

    public $sortFields = [];

    public $sortField = 'NR';

    public $sortAsc = false;

    public $filter;

    public $scope;

    public $where;

    public $toggleSelect = false;

    public $toggleFilter_ = true;

    // public $check = [], $radio = [];
    public $selectAll = false;

    public function build_listing()
    {
        if (! $this->table) {
            exit('NO Table set.');
        }
        $this->title = $this->table;
        // $this->pageLimit    = 7;
        // $this->sortField    = "id";
        // $this->sortAsc      = false;
        // $this->search       = "";
    }

    public function build_rows($rows)
    {
        $rows = $rows
            ->search($this->search)
            // ->where($this->build_search())
            ->where($this->build_where())
            ->orderBy($this->sortField, $this->sortAsc ? 'ASC' : 'DESC')
            ->paginate($this->pageLimit, ['*'], $this->table);

        return $rows;
    }

    public function build_search()
    {
        // $this->regRows = Register::search($this->regSearch)->limit(5)->orderBy('id', 'desc')->get();
        // if( $this->search )
        // {
        //     $query = DB::table($this->table);
        //     $this->build_search($this->search);
        //     dd($this->search);
        // }
        // $query->where($attribute, 'LIKE', "%{$term}%");
        $where = [];
        if ($this->search) {
            // dump($this->search);
            $attributes = [];
            $cols = DB::select('SHOW FIELDS FROM `'.$this->table.'`');
            foreach ($cols as $col) {
                // Loop over the terms and do a search for each.
                // $query->where('name', 'like', '%' . $term . '%');
                switch ($col->Field) {
                    case 'bezeichnung':
                    case 'suchbegriff':
                        $attributes[] = $col->Field;
                        break;
                }
            }

            $arrsearch = explode(' ', $this->search);
            // dump($arrsearch);

            $where = function (Builder $query) use ($attributes, $arrsearch) {
                foreach ($attributes as $attribute) {
                    foreach ($arrsearch as $searchterm) {
                        // Loop over the terms and do a search for each.
                        // $query->where('name', 'like', '%' . $term . '%');
                        $query->where($attribute, 'LIKE', "%{$searchterm}%");
                    }
                }
                // dump($query);
            };
            // dump($where);

            // dump($this->search);
            // $users = DB::table('users')
            // ->where('votes', '>', 100)
            // ->orWhere(function (Builder $query) {
            //     $query->where('name', 'Abigail')
            //           ->where('votes', '>', 50);
            // })
            // ->get();
            // dd($users);

            // $query = DB::table($this->table);
            // // $this->build_search($this->search);
            // dd($this->search);
        }

        return $where;

        // $output = $query->where('name', 'Abigail')->where('votes', '>', 50);
        // dd($output);
    }

    public function build_where()
    {
        $where = [];
        if (isset($this->bucId)) {
            $where[] = ['booking_id', $this->bucId];
        }
        if (isset($this->docId)) {
            $where[] = ['document_id', $this->docId];
        }
        if (isset($this->namId)) {
            $where[] = ['name_id', $this->namId];
        }
        if (isset($this->regId)) {
            $where[] = ['register_id', $this->regId];
        }

        return $where;
    }

    public function btn_select()
    {
        session()->flash('message', 'Item/s selected.');

        $this->dispatch('hide-modal');
        // $this->dispatch('show-toast', [ message: session()->get('message') ]);
    }

    public function change($keyId, $field, $value)
    {
        // dump('change', $keyId, $field, $value);
        $item = $this->model::find($keyId);
        // dd('change', $this->model, $keyId, $field, $value, $item);
        // $this->validate();
        $ret = $item->update([
            $field => $value,
        ]);
        // dd($item, $ret);
        $this->dispatch('show-message', message: 'Gespeichert');
        $this->skipRender();
    }

    public function delete($keyId)
    {
        // dump('delete', $keyId, $field, $value);
        $item = $this->model::find($keyId); // dump($item);
        $item->delete();
        $this->dispatch('show-message', message: 'Gelöscht');
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <div class="container-fluid my-5">
                <center>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Laden ... ... ...</p>
                </center>
            </div>
        </div>
        HTML;
    }

    public function render_X()
    {
        $this->startTime = microtime(true);
        $rows = $this->model::search($this->search);
        if ($this->filter) {
            $rows = $rows->where($this->filter, $this->where)
                ->orderBy($this->sortField, $this->sortAsc ? 'ASC' : 'DESC')
                ->orderBy('NR', 'ASC');
        } else {
            $rows = $rows->orderBy($this->sortField, $this->sortAsc ? 'ASC' : 'DESC');
        } // dd($rows->get());

        return view($this->pageView, [
            'rows' => $rows->paginate($this->pageLimit, ['*'], $this->table),
        ]);
    }

    public function saveDropdown()
    {
        // dd($this->form);
        $item = $this->form->create();
    }

    // public function delete($id)
    // public function insert($id)
    // public function update($id)
    // public function destroy($id)
    public function create_X()
    {
        $this->item = [];
        $this->modalSwitch = 'insert';
        if (isset($this->docId)) {
            $this->item['document_id'] = $this->docId;
        }
        if (isset($this->namId)) {
            $this->item['name_id'] = $this->namId;
        }
        if (isset($this->regId)) {
            $this->item['register_id'] = $this->regId;
        }
        // dd($this->item);
    }

    public function delete_X($id)
    {
        if ($this->modelName) {
            // dd($this->modelName, $this->table, $id, $this->item);
            $this->modelName::findOrFail($id)->delete();
        } else {
            // dd($this->modelName, $this->table, $id, $this->item);
            DB::table($this->table)->where('id', $id)->delete();
        }
        // $this->dispatch('show-toast', message: 'Item gelöscht.');
        $this->dispatch('hide-modal');
    }

    // public function destroy($id)
    public function getItem_X($id, $switch = 'update')
    {
        // dd($id);
        // dd($this->modelName, $this->table, $id);
        $this->modalSwitch = $switch;
        if ($this->modelName) {
            $this->item = $this->modelName::findOrFail($id)->toArray();
            // dd($this->item);
        } else {
            $item = DB::table($this->table)->find($id);
            $this->item = get_object_vars($item);
            // dd($this->item);
        }
    }

    public function edit_X($id, $switch = 'update')
    {
        $this->item = [];
        $this->modalSwitch = $switch;
        if ($this->modelName) {
            $this->item = $this->modelName::findOrFail($id)->toArray();
        } else {
            $item = DB::table($this->table)->find($id);
            $this->item = get_object_vars($item);
        }
        // dd($this->modelName, $this->table, $id, $this->item);
    }

    public function reset_search()
    {
        $this->search = '';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function submit_X($formData)
    {
        dd('submit', $this->table, $formData);
        if ($this->modalSwitch == 'insert') {
            dd($formData);
            if ($this->modelName) {
                $this->modelName::create($formData);
            } else {
                DB::table($this->table)
                    ->insert($formData);
            }
        } else {
            if ($this->modelName) {
                dd($formData);
                // $this->item->save();
                $this->modelName::findOrFail($formData['id'])->update($formData);
            } else {
                DB::table($this->table)
                    ->where('id', $formData['id'])
                    ->update($formData);
            }
        }
        $this->dispatch('hide-modal');
    }
}
