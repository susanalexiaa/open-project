<?php

namespace App\Domains\Nomenclature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclatureProperty extends Model
{
    use HasFactory;

    protected static $types = [
        0 => [
            'id' => 1,
            'name' => 'Список',
            'sort' => 1
        ]
    ];

    public static function getTypes(): array
    {
        return self::$types;
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NomenclaturePropertyValue::class, 'property_id', 'id')->orderBy('sort');
    }

}
