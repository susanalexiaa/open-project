<?php

namespace App\Domains\Employees\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingCell extends Model
{
    use HasFactory;
    public $guarded = [];
    public $timestamps = false;

    protected $dates = ['created_at', 'updated_at', 'start_period'];

    public function day(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WorkingDay::class, 'id', 'day_id');
    }
}
