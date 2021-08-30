<?php

namespace App\Domains\Directory\Models;

use App\Domains\Feedback\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    public $guarded = [];
    public $timestamps = false;

    public function feedbacks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function getRating()
    {
        $feedbacks = $this->feedbacks;
        if( count($feedbacks) == 0){ return null; }

        $summary = 0;

        foreach($feedbacks as $feedback){
            $summary+=$feedback->rating;
        }

        return $summary/( $feedbacks->count() );
    }
}
