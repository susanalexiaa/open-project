<?php

namespace App\Domains\Lead\Observers;


use App\Domains\Lead\Models\HistoryLead;
use App\Domains\Lead\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     *
     * @param Lead $lead
     * @return void
     */
    public function created(Lead $lead)
    {
        $this->makeRowHistory($lead);
    }

    protected function makeRowHistory($lead)
    {
        HistoryLead::query()->create([
            'lead_id'=> $lead->id,
            'user_id' => Auth::id() ?? 1,
            'status_id' => $lead->status_id
        ]);
    }

    /**
     * Handle the Lead "updated" event.
     *
     * @param Lead $lead
     * @return void
     */
    public function updated(Lead $lead)
    {
        if( $lead->isDirty('status_id') ){
            $this->makeRowHistory($lead);
        }
    }

    /**
     * Handle the Lead "deleted" event.
     *
     * @param Lead $lead
     * @return void
     */
    public function deleted(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     *
     * @param Lead $lead
     * @return void
     */
    public function restored(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     *
     * @param Lead $lead
     * @return void
     */
    public function forceDeleted(Lead $lead)
    {
        //
    }
}
