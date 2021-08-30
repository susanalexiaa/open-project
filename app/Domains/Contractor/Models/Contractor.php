<?php

namespace App\Domains\Contractor\Models;

use App\Domains\Directory\Models\PhoneCall;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Propaganistas\LaravelPhone\PhoneNumber;

class Contractor extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $guarded = [];

    public function phoneCalls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PhoneCall::class);
    }

    public function emails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailFromAccountToContractor::class);
    }

    public static function search($key): \Illuminate\Database\Eloquent\Builder
    {
        return Contractor::query()
            ->where('title', 'like', '%'.$key.'%')
            ->orWhere('phone', 'like', '%'.$key.'%')
            ->orWhere('email', 'like', '%'.$key.'%');
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace(' ', '', $value);
    }

    public function getFormattedPhone()
    {
        try {
            return str_replace('-', ' ', PhoneNumber::make($this->phone, 'RU')->formatInternational());
        } catch (\libphonenumber\NumberParseException $e) {
            return $this->phone;
        }
    }

    public static function findOrCreateByPhone($phone){
        $phone = str_replace('+', '', $phone);
        $contractors = Contractor::where('phone', 'LIKE', '%'. $phone .'%');

        if( $contractors->exists() ){
            $contractor = $contractors->first();
        }else{
            $contractor = Contractor::create([
                'title' => "Неизвестный контрагент",
                'phone' => $phone
            ]);
        }

        return $contractor;
    }

    public static function findByEmail($email)
    {
        return Contractor::where('email', $email);
    }

    public function attachEmail($title, $content, $made_at)
    {
        $data = compact('title', 'content', 'made_at');
        $data['contractor_id'] = $this->id;

        return EmailFromAccountToContractor::create($data);
    }
}
