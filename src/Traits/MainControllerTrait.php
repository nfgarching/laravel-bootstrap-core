<?php

namespace Nfgarching\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait MainControllerTrait
{
    // public $bucId, $docId, $keyId, $namId, $regId; //, $regRows = [], $regSearch; // public $perPage;
    // public $item = [];
    public $table;

    public $title;

    public $token;

    public $model;

    public $startTime;

    public function x_build_main()
    {
        $this->token = rand();
        $this->startTime = microtime(true);
    }

    public function x_build_model(string $modelName = '')
    {
        $this->build_main();
        if (! $modelName && isset($this->modelName)) {
            $modelName = $this->modelName;
        } elseif (! $modelName) {
            dd('No Model');
        }
        $this->model = new $modelName;
        $this->table = $this->model->getTable();
        $this->title = $this->table;
    }

    public function get_rows($rows, $request, $where = [])
    {
        // If $rows is a class name string, instantiate it
        if (is_string($rows)) {
            $rows = new $rows;
        }

        if ($request->has('search') && $request->search !== null) {
            $rows = $rows->search($request->search);
        }

        if ($request->hasAny(['categorie', 'categorie_id', 'state', 'type'])) {
            if ($request->referat) {
                $where[] = ['categorie', $request->categorie];
            }
            if ($request->categorie_id) {
                $where[] = ['categorie_id', $request->categorie_id];
            }
            if ($request->state) {
                $where[] = ['state', $request->state];
            }
            if ($request->type) {
                $where[] = ['type',   $request->type];
            }
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'DESC');
        $perPage = $request->get('per_page', 10);

        return $rows->where($where)
            ->orderBy($sort, $direction)
            ->paginate($perPage);
    }

    /**
     * Get navigation data (first, previous, next, last) for a model instance
     *
     * @param  Model  $currentModel  The current model instance
     * @param  Request  $request  The request object with filters and sorting
     * @param  string  $modelClass  The model class name
     * @return array Navigation data with first, previous, next, and last models
     */
    public function getNavigationData($currentModel, $request, string $modelClass): array
    {
        $query = $modelClass::query();

        if ($request->has('search') && $request->search !== null) {
            $query = $query->search($request->search);
        }

        $where = [];
        if ($request->hasAny(['categorie_id', 'state', 'type'])) {
            if ($request->categorie_id) {
                $where[] = ['categorie_id', $request->categorie_id];
            }
            if ($request->state) {
                $where[] = ['state', $request->state];
            }
            if ($request->type) {
                $where[] = ['type', $request->type];
            }
        }

        if (! empty($where)) {
            $query = $query->where($where);
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'DESC');

        $allIds = $query->orderBy($sort, $direction)->pluck('id')->toArray();

        $currentIndex = array_search($currentModel->id, $allIds);

        $navigation = [
            'first' => null,
            'previous' => null,
            'next' => null,
            'last' => null,
            'currentPosition' => 0,
            'totalCount' => 0,
        ];

        if ($currentIndex !== false && count($allIds) > 0) {
            $navigation['currentPosition'] = $currentIndex + 1; // 1-based index
            $navigation['totalCount'] = count($allIds);

            if ($currentIndex > 0) {
                $navigation['first'] = $modelClass::find($allIds[0]);
                $navigation['previous'] = $modelClass::find($allIds[$currentIndex - 1]);
            }
            if ($currentIndex < count($allIds) - 1) {
                $navigation['next'] = $modelClass::find($allIds[$currentIndex + 1]);
                $navigation['last'] = $modelClass::find($allIds[count($allIds) - 1]);
            }
        }

        return $navigation;
    }
}
