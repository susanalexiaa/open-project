<?php

namespace App\Domains\Directory\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $guarded = [];
    public $timestamps = false;


    //helpers
    public function createNewEntity($name)
    {
        Entity::query()->create([
            'company_id' => $this->id,
            'name' => $name
        ]);
    }

    // relations

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Entity::class);
    }
}
