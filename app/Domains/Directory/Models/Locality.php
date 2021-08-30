<?php

namespace App\Domains\Directory\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Locality::class, 'AOGUID', 'PARENTGUID');
    }
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Locality::class, 'PARENTGUID', 'AOGUID');
    }
}
