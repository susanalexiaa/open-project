<?php

namespace App\Domains\Employees\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WorkingDay extends Model
{
    use HasFactory;
    public $guarded = [];
    public $timestamps = false;

    protected $dates = ["day"];



    public function workingCells()
    {
        return $this->hasMany(WorkingCell::class, 'day_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
