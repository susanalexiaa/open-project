<?php

namespace App\Domains\Contractor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorLead extends Model
{
    use HasFactory;

    public $guarded = [];
    public $table = "contractor_lead";
}
