<?php

namespace App\Domains\Lead\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class LeadsOpenedByUser extends Model
{
    use HasFactory;

    public $table = 'leadsOpenedByUser';
    public $timestamps = false;
    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function cleanUnused()
    {
        LeadsOpenedByUser::where('until', '<', Carbon::now())->delete();
    }
}
