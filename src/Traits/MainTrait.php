<?php

namespace Nfgarching\Core\Traits;

trait MainTrait
{
    public $table;

    public $title;

    public $token;

    public $model;

    public $modelName;

    public $startTime;

    public function build_main()
    {
        $this->token = rand();
        $this->startTime = microtime(true);
    }

    public function build_model(string $modelName = '')
    {
        $this->build_main();
        if (! $modelName && isset($this->modelName)) {
            $modelName = $this->modelName;
        } elseif ($modelName) {
            $this->modelName = $modelName;
        } else {
            dd('No Model');
        }
        $this->model = new $modelName;
        $this->table = $this->model->getTable();
        $this->title = $this->table;
    }
}
