<?php

namespace App\Domains\Lead\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use HasFactory;
    public $guarded = [];
    public $timestamps = false;
    public $table = "lead_statuses";

    public static function getActive()
    {
        return LeadStatus::where('is_active', 1)->orderBy('order')->get();
    }

    public static function getAll()
    {
        return LeadStatus::orderBy('order')->get();
    }

    public function isActive()
    {
        return $this->is_active;
    }
}
