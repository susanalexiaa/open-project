<?php

namespace App\Domains\Nomenclature\Models;

use App\Domains\Price\Models\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use App\Models\User;

class Nomenclature extends Model implements HasMedia
{
    use HasFactory, HasTrixRichText, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'nomenclature-trixFields', 'code', 'vat_rate_id', 'measure_unit_id', 'name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(NomenclatureCategory::class, 'nomenclature_categories_relation');
    }


    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NomenclatureProduct::class)->orderBy('sort');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'nomenclature_users');
    }
}
