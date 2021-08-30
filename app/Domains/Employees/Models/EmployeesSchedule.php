<?php

namespace App\Domains\Employees\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeesSchedule extends Model
{
    use HasFactory;
    public $guarded = [];

    public static function getActual()
    {
        return EmployeesSchedule::where('year', Carbon::now('Europe/Moscow')->year)->first();
    }
}
