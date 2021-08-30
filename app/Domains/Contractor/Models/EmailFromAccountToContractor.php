<?php

namespace App\Domains\Contractor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailFromAccountToContractor extends Model
{
    use HasFactory;
    public $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'made_at'];
}
