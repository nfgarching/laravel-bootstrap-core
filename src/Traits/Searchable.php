<?php

// vgl. https://www.linkedin.com/pulse/searchable-trait-search-multiple-columns-laravel-ech-chebaby/

namespace Nfgarching\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait Searchable
{
    /**
     * Scope a query to search for a term in the attributes
     *
     * @param  Builder  $query
     * @return Builder
     */
    protected function scopeSearch($query)
    {
        [$searchTerm, $attributes] = $this->parseArguments(func_get_args());
        if (! $searchTerm || ! $attributes) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($attributes, $searchTerm) {
            foreach (Arr::wrap($attributes) as $attribute) {
                $query->when(
                    str_contains($attribute, '.'),
                    function (Builder $query) use ($attribute, $searchTerm) {
                        [$relationName, $relationAttribute] = explode('.', $attribute);
                        $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                            foreach (explode(' ', $searchTerm) as $term) {
                                $query->where($relationAttribute, 'LIKE', "%{$term}%");
                            }
                        });
                    },
                    function (Builder $query) use ($attribute, $searchTerm) {
                        $query->orWhere(function ($query) use ($attribute, $searchTerm) {
                            $arrsearch = explode(' ', $searchTerm);
                            // dump($arrsearch);
                            foreach ($arrsearch as $searchtxt) {
                                // Loop over the terms and do a search for each.
                                // $query->where('name', 'like', '%' . $term . '%');
                                // $query->where($attribute, 'LIKE', "%{$term}%");
                                $query->where($attribute, 'LIKE', "%{$searchtxt}%");
                            }
                        });
                        // $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        // dump($query);
                    }
                );
            }
        });
    }

    /**
     * Parse search scope arguments
     *
     * @return array
     */
    private function parseArguments(array $arguments)
    {
        $args_count = count($arguments);
        switch ($args_count) {
            case 1:
                return [request(config('searchable.key')), $this->searchableAttributes()];
                break;
            case 2:
                return is_string($arguments[1])
                    ? [$arguments[1], $this->searchableAttributes()]
                    : [request(config('searchable.key')), $arguments[1]];
                break;
            case 3:
                return is_string($arguments[1])
                    ? [$arguments[1], $arguments[2]]
                    : [$arguments[2], $arguments[1]];
                break;
            default:
                return [null, []];
                break;
        }
    }

    /**
     * Get searchable columns
     *
     * @return array
     */
    public function searchableAttributes()
    {
        if (method_exists($this, 'searchable')) {
            return $this->searchable();
        }

        return property_exists($this, 'searchable') ? $this->searchable : [];
    }
}
