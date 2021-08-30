<?php

namespace App\Domains\Lead\Models;

use App\Domains\Billing\Models\Billing;
use App\Domains\Comment\Models\Comment;
use App\Domains\Contractor\Models\Contractor;
use App\Domains\Contractor\Models\ContractorLead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Lead extends Model
{
    use HasFactory;
    use HasTrixRichText;
    use SoftDeletes;

    public $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeadStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeadSource::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Billing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contractors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Contractor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function readings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserLeadRead::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsible(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HistoryLead::class);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadProduct::class);
    }


    public function attachComment($trix)
    {
        $data = array_merge([
            'lead_id' => $this->id,
            'user_id' => Auth::id()
        ], $trix);

        Comment::query()->create($data);
    }
    //TODO: переделать

    public function attachContractor($id)
    {
        ContractorLead::query()->create([
            'lead_id' => $this->id,
            'contractor_id' => $id
        ]);
    }
    //TODO: переделать

    public function attachContractors($ids)
    {
        foreach($ids as $id){
            $this->attachContractor($id);
        }
    }
    //TODO: переделать

    public function setDescription($content)
    {
        DB::table('trix_rich_texts')->insert([
            'field' => 'description',
            'model_type' => Lead::class,
            'model_id' => $this->id,
            'content' => nl2br($content),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
    //TODO: переделать

    public static function search($key)
    {
        $content_ids = DB::table('trix_rich_texts')->where('model_type', Lead::class)
                                    ->where('content', 'like', '%'.$key.'%')->get()->pluck('model_id')->toArray();

        $query = Lead::where(function ($q) use ($key, $content_ids) {
            $q->whereHas('contractors', function (Builder $query) use ($key){
                $query->where('title', 'like', '%'.$key.'%')
                        ->orWhere('phone', 'like', '%'.$key.'%');
            })->orWhereIn('id', $content_ids)->orWhere('id', $key);
        })->where('team_id', Auth::user()->currentTeam->id);

        return $query;
    }

    //TODO: переделать

    public function getTextDescription( $length = 256 )
    {
        $content = DB::table('trix_rich_texts')
                    ->where('model_type', Lead::class)
                    ->where('model_id', $this->id)->first();

        $content = is_null( $content ) ? '' : $content->content;

        $content = strip_tags(html_entity_decode($content));

        return Str::limit($content, $length, '...');
    }

    public function deleteContractor($id)
    {
        $this->contractors()->detach($id);
    }


    public function isReadByCurrentUser(): bool
    {
        return $this->readings()->where('user_id', Auth::id())->exists();
    }

    public function read()
    {
        UserLeadRead::query()->firstOrCreate(['user_id' => Auth::id(), 'lead_id' => $this->id]);
    }

    public function startWorkingWith()
    {
        $open = LeadsOpenedByUser::firstOrNew([
            'user_id' => Auth::id(),
            'lead_id' => $this->id,
        ]);

        $open->until = Carbon::now()->addMinutes(15);
        $open->save();
    }

    public function endWorkingWith()
    {
        LeadsOpenedByUser::query()->where([
            'user_id'=> Auth::id(),
            'lead_id' => $this->id
        ])->delete();
    }

    public function isAvailableToWorkingWith(): bool
    {
        LeadsOpenedByUser::cleanUnused();

        $record = LeadsOpenedByUser::query()->where([
            'lead_id' => $this->id,
            ['until', '>', Carbon::now() ]
        ])->first();

        return is_null( $record ) || $record->user_id == Auth::id();
    }

    public function getUserOpenedTheLead()
    {
        $record = LeadsOpenedByUser::where([
            'lead_id' => $this->id
        ])->first();

        return $record->user;
    }

    public function getPeriodOfCurrentStatus()
    {
        return $this->history->first()->created_at->diffForHumans(null, true);
    }

    public function getRelatedContractorsLeads()
    {
        $contractor_id = $this->contractor_id;

        if( is_null($contractor_id) ){
            $first = $this->contractors->first();

            if( is_null($first) ){
                return collect();
            }else{
                $contractor_id = $first->id;
            }
        }

        $team_id = Auth::user()->currentTeam->id;

        return Lead::whereHas('contractors', function ( Builder $query) use ($contractor_id) {
            $query->where('contractors.id', $contractor_id);
        })->where('team_id', $team_id)->orderByDesc('id')->get();
    }
}
