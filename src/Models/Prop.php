<?php

namespace Nfgarching\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kyslik\ColumnSortable\Sortable;
use Nfgarching\Core\Database\Factories\PropFactory;
use Nfgarching\Core\Traits\Searchable;

class Prop extends Model
{
    use HasFactory, Searchable, Sortable;

    protected static function newFactory(): Factory
    {
        return PropFactory::new();
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'props';

    /**
     * Allowing Mass Assignment
     */
    protected $guarded = [
        'updated_at',
    ];

    public $searchable = [
        'key',
        'label',
        'value',
        'memo',
    ];

    public $sortable = [
        'id',
        'state',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function getDataAttribute(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
