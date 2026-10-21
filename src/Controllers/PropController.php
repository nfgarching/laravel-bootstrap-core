<?php

namespace Nfgarching\Core\Controllers;

use Illuminate\Http\Request;
use Nfgarching\Core\Models\Prop;

class PropController
{
    public function index(Request $request)
    {
        $where = [];
        foreach (['state', 'type', 'key', 'label'] as $field) {
            if ($request->$field) {
                $where[] = [$field, $request->$field];
            }
        }

        $props = Prop::search($request->search)
            ->where($where)
            ->sortable(['id' => 'desc'])
            ->paginate($request->integer('per_page', 8))
            ->withQueryString();

        return view('page::props.index', [
            'props' => $props,
            'request' => $request,
            'startTime' => microtime(true),
        ]);
    }

    public function action(Request $request, $id)
    {
        switch ($request->switchTo) {
            default:
                dd($request->all(), $id);
                break;
        }

        return back()->withInput();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page::props.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->switchTo) {
            $prop = Prop::create($request->only(['model_type', 'model_id', 'key', 'state', 'type', 'label', 'value', 'memo']));

            return redirect()->route('core.props.show', $prop);
        }

        switch ($request->switchTo) {
            case 'change':
                foreach ($request->collect('checks') as $keyid) {
                    if ($request->state) {
                        Prop::find($keyid)->update(['state' => $request->state]);
                    }
                    if ($request->type) {
                        Prop::find($keyid)->update(['type' => $request->type]);
                    }
                }

                return back()->withInput();

            case 'delete':
                break;

            default:
                throw new \Exception("switchTo nicht programmiert: {$request->switchTo}");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prop $prop)
    {
        return view('page::props.edit', ['prop' => $prop]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prop $prop)
    {
        $prop->update($request->only(['model_type', 'model_id', 'key', 'state', 'type', 'label', 'value', 'memo']));

        return redirect()->route('core.props.show', $prop);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prop $prop, Request $request)
    {
        return view('page::props.show', [
            'prop' => $prop,
            'request' => $request,
            'startTime' => microtime(true),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prop $prop)
    {
        dd($prop->toArray());
    }
}
