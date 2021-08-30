<?php

namespace App\Models;

use App\Domains\Contractor\Models\Contractor;
use App\Domains\Directory\Models\Company;
use App\Domains\Integration\Models\ConnectedEmailAccount;
use App\Domains\Lead\Models\Lead;
use App\Domains\Nomenclature\Models\Nomenclature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cookie;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'current_team_id', 'position', 'recording_allowed',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // relations
    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function isAdmin()
    {
        return Team::query()->find(1)->hasUser($this);
    }

    public function getMostUsableContractors()
    {
        if( $cookie = Cookie::get('most_usable_contractors') ){
            return Contractor::query()->whereIn('id', json_decode($cookie))->get();
        }else{
            return $this->loadMostUsableContractors();
        }
    }

    public function loadMostUsableContractors()
    {
        $contractors_ids = [];

        $leads = Lead::query()->where('responsible_id', $this->id)->get();

        foreach($leads as $lead){
            foreach( $lead->contractors as $contractor ){
                $id = $contractor->id;
                if( isset($contractors_ids[$id]) ){
                    $contractors_ids[$id]++;
                }else{
                    $contractors_ids[$id] = 1;
                }
            }
        }

        arsort($contractors_ids);

        $top = array_keys( array_slice( $contractors_ids, 0, 5 ) );

        Cookie::queue('most_usable_contractors', json_encode($top), 60*24);

        return Contractor::query()->whereIn('id', $top)->get();
    }

    public function emailAccounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany( ConnectedEmailAccount::class );
    }

    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_users');
    }
}
