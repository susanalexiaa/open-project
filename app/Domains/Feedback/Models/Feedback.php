<?php

namespace App\Domains\Feedback\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    public $table = "feedbacks";
    public $guarded = [];

    public function photos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FeedbackPhoto::class);
    }

    public function attachPhoto($url)
    {
        FeedbackPhoto::query()->create([
            'feedback_id' => $this->id,
            'photo' => $url
        ]);
    }

    public function getPhotos()
    {
        $photos = $this->photos;

        if( count($photos) == 0 ){
            return [];
        }

        $result = [];
        foreach($photos as $photo){
            $result[] = asset( 'storage/'.$photo->photo );
        }

        return $result;
    }
}
