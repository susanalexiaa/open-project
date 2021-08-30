<?php

namespace App\Domains\Price\Models;

use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Domains\Price\Models\PriceType;
use App\Domains\Nomenclature\Models\Nomenclature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'start_date',
        'price_type_id',
    ];

    public function priceType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(NomenclatureProduct::class, 'nomenclature_prices')
            ->as('price')
            ->withPivot('price');
    }
}
