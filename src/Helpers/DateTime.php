<?php

namespace Nfgarching\Core\Helpers;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DateTime
{
    /**
     * Kurzdatum für Listen-Buttons (Blade: @btndate): heute „H:i“, im Jahr
     * um heute „d.m“, sonst „m/y“. Carbon-Werte (z. B. UTC-gespeicherte
     * datetime-Casts) werden vorher in die App-Zeitzone umgerechnet.
     */
    public static function btndate(DateTimeInterface|string|null $datetime): string
    {
        if ($datetime === null || $datetime === '') {
            return '';
        }

        if ($datetime instanceof DateTimeInterface) {
            $datetime = Carbon::instance($datetime)
                ->timezone(config('app.timezone'))
                ->format('Y-m-d H:i:s');
        }

        return self::get_btn_date($datetime);
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
