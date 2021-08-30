<?php

namespace App\Domains\Billing\Models;

use App\Domains\Lead\Models\Lead;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Billing extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'made_at'];

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
