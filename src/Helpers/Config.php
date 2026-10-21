<?php

namespace Nfgarching\Core\Helpers;

class Config
{
    public static function getCfgSort($config)
    {
        $collection = collect($config);
        dump($collection);
        $collection->sortBy('field');
        dd($collection);
    }

    public static function getCfgTxt($config = [])
    {
        $arr = [];
        foreach (config($config) as $key => $val) {
            $arr[$key] = $val['txt'];
        }

        return $arr;
    }
}
