<?php

namespace App\Domains\Appointment\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use JamesMills\Uuid\HasUuidTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Appointment extends Model implements HasMedia
{
    use HasFactory, HasUuidTrait, InteractsWithMedia, SoftDeletes, HasTrixRichText;

    protected $fillable = [
        'user_id',
        'customer_id',
        'individual_id',
        'title',
        'objective',
        'datetime',
        'latitude',
        'longitude',
        'checkin_time',
        'comments',
        'lead_id',
        'team_id',
    ];

//    protected $appends = ['date', 'hours', 'minutes','status', 'images', 'date_formatted', 'time_formatted'];

    protected $dates = ['datetime'];

    protected static function booted()
    {
        if (! Auth::user()->isAdmin()) {
            $teamId = Auth::user()->currentTeam->id;
            $currentRole = Auth::user()->teamRole(Auth::user()->currentTeam)->key;
            $userId = Auth::id();
            static::addGlobalScope('for-user', function (Builder $builder) use ($teamId, $currentRole, $userId) {
                $builder->where('team_id', $teamId);
                if ($currentRole !== 'owner' && $currentRole !== 'admin') {
                    $builder->where('user_id', $userId);
                }
            });
        }
    }

//    public function customer(): HasOne
//    {
//        return $this->hasOne(Customer::class, 'id', 'customer_id');
//    }
//
//    public function individual(): HasOne
//    {
//        return $this->hasOne(Individual::class, 'id', 'individual_id');
//    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getImagesAttribute()
    {
        if (!array_key_exists('media', $this->relations)) {
            $this->load('media');
        }

        $related = collect($this->getRelation('media'));

        $images = [];

        $images = $related->transform(function ($value) {
            return [
                'imgModalSrc' => $value->getUrl(),
                'imgThumbSrc' => $value->getUrl('thumb'),
                'imgId' => $value->id,
                'imgModalDesc' => $this->title,
            ];
        });

        return $images;
    }

    public function getStatusAttribute($value)
    {
        if ($value === 0) {
            return 'Отменен';
        }

        if ($this->latitude != 0) {
            return 'Состоялся';
        } elseif ($this->datetime < Carbon::now()) {
            return 'Просрочен';
        }

        return 'Запланирован';
    }

    public function getDateAttribute()
    {
        return $this->datetime->format('Y-m-d');
    }

    public function getHoursAttribute()
    {
        return $this->datetime->format('H');
    }

    public function getMinutesAttribute()
    {
        return $this->datetime->format('i');
    }

    public function getDateFormattedAttribute()
    {
        return $this->datetime->format('d.m.y');
    }

    public function getTimeFormattedAttribute()
    {
        return $this->datetime->format('H:i');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(120)
            ->height(120)
            ->sharpen(10);
    }
}
