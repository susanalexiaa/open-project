<?php

namespace App\Domains\Directory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emailuid extends Model
{
    use HasFactory;

    public $guarded = [];

    public static function isExist($uid, $email)
    {
        return Emailuid::where('email', $email)->where('emailuid', $uid)->exists();
    }
}
