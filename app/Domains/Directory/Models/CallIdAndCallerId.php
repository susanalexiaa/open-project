<?php

namespace App\Domains\Directory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CallIdAndCallerId extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $guarded = [];

    public static function cleanUnused()
    {
        CallIdAndCallerId::query()->where('until', '<', Carbon::now())->delete();
    }
}
