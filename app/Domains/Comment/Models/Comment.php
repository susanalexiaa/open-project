<?php

namespace App\Domains\Comment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Comment extends Model
{
    use HasTrixRichText;
    use HasFactory;

    public $guarded = [];

    public function getContentModel(): \Illuminate\Database\Query\Builder
    {
        return DB::table('trix_rich_texts')
            ->where('model_type', Comment::class)
            ->where('model_id', $this->id);
    }

    public function getContent()
    {
        $content = $this->getContentModel()->first();

        if( !is_null($content) ){
            return $content->content;
        }
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAuthor(): bool
    {
        return $this->user_id == Auth::id();
    }

    public function deleteComment()
    {
        $this->getContentModel()->delete();
        $this->delete();
    }
}
