<?php

namespace App\Domains\Nomenclature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclaturePropertyValue extends Model
{
    use HasFactory;


    public function property(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NomenclatureProperty::class, 'id', 'property_id');
    }


}
