<?php

namespace App\Domains\Integration\Models;

use App\Domains\Directory\Models\Operator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConnectedEmailAccount extends Model
{
    use HasFactory;

    public $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'last_integration'];

    public function setIntegrationTime($time)
    {
        if( !is_null($time) ){
            $date = Carbon::createFromFormat('Y-m-d H:i', $time, Auth::user()->timezone)->setTimezone('UTC');
        }else{
            $date = Carbon::now();
        }

        $this->update([
            'last_integration' => $date
        ]);
    }

    public function getIntegrationLocalTime()
    {
        $time = $this->last_integration;
        return $time ? $time->setTimezone( Auth::user()->timezone )->format("Y-m-d H:i") : null;
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
