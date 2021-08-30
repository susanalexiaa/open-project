<?php

namespace App\Domains\Lead\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryLead extends Model
{
    use HasFactory;

    public $table = "history_lead";
    public $guarded = false;
}
