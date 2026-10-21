<?php

namespace Nfgarching\Core\Helpers;

use Illuminate\Support\Number;

class DateTime
{
    public static function btndate($datetime)
    {
        return 'Hallo';
    }

    public static function get_btn_date($datetime)
    {
        if (date('Y-m-d') == date('Y-m-d', strtotime($datetime))) {
            $txt = date('H:i', strtotime($datetime));
        } elseif ((date('Y') - 1).date('-m-d') < date('Y-m-d', strtotime($datetime)) &&
            (date('Y') + 1).date('-m-d') > date('Y-m-d', strtotime($datetime))
        ) {
            $txt = date('d.m', strtotime($datetime));
        } else {
            $txt = date('m/y', strtotime($datetime));
        }

        return $txt;
    }

    public static function get_min_from_sec($seconds)
    {
        return Number::format($seconds / 60, locale: 'de', maxPrecision: 1);
    }
}
