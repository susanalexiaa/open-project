<?php

namespace App\Domains\Nomenclature\Models;

use App\Domains\Price\Models\Price;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclatureProduct extends Model
{
    use HasFactory;

    public function nomenclature(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Nomenclature::class);
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(NomenclaturePropertyValue::class, 'nomenclature_product_nomenclature_property_values')->orderBy('sort');
    }

    public function properties(){
//        return $this->hasManyThrough('')
    }

    /**
     * @param $priceTypeId
     * @param false $returnModel
     * @return mixed|null
     */
    public function getPrice($priceTypeId, $returnModel = false){
        $model = $this->prices()
            ->where('price_type_id', $priceTypeId)
            ->where('start_date', '<=', Carbon::now())
            ->orderBy('start_date', 'desc')
            ->first();
        if ($returnModel){
            return $model;
        }
        return $model->pivot->price;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function prices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Price::class, 'nomenclature_prices')->withPivot('price');
    }
}
