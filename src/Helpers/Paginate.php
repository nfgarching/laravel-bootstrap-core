<?php

namespace Nfgarching\Core\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Paginate
{
    public static function paginate($items, $perPage = 5, $pageName = 'page', $page = null)
    {

        $page = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);
        // dd($page);
        // $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        // $currentpage = $page;
        // $path = LengthAwarePaginator::resolveCurrentPath();
        $options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ];
        $offset = ($page * $perPage) - $perPage;
        $itemstoshow = array_slice($items, $offset, $perPage);

        return new LengthAwarePaginator($itemstoshow, $total, $perPage, $page, $options);

    }
}
