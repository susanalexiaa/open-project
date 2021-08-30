<?php

namespace App\Domains\Nomenclature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NomenclatureCategory extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];

    use HasFactory, SoftDeletes;

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Domains\Nomenclature\Models\NomenclatureCategory::class, 'parent_id', 'id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NomenclatureCategory::class, 'id', 'parent_id');
    }

    public static function getPathCategories(array $ids): \Illuminate\Support\Collection
    {
        $categories = NomenclatureCategory::find($ids);
        $result = collect();
        foreach ($categories as $category){
            $result = $result->merge($category->getPathCategory());
        }
        return $result->unique();
    }

    public function getPathCategory(): array
    {
        $result = [];
        $category = $this;
        $loop = true;
        while ($loop){
            $result[] = $category->id;
            if (!empty($category->parent_id)){
                $category = $category->load('parent')->parent;
            }else{
                $loop = false;
            }
        }
        return $result;
    }

    public function properties(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(NomenclatureProperty::class, 'nomenclature_category_nomenclature_property', 'category_id', 'property_id')->orderBy('sort');
    }
}
