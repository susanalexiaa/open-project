<?php

namespace App\Helpers;

use App\Domains\Billing\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Sales
{
    public static function getSummaryByDay($day)
    {
        $date = Carbon::parse($day, Auth::user()->timezone );
        $team_id = Auth::user()->currentTeam->id;

        $total = Billing::where([
            ['made_at', '<=', (clone $date)->endOfDay()->setTimezone('UTC')],
            ['made_at', '>=', $date->setTimezone('UTC')]
        ])->whereHas('lead', function (Builder $query) use ($team_id) {
            $query->where('team_id', $team_id);
        })->get()->sum('total');

        return $total;
    }

    public static function getSummaryByPeriod($from, $to)
    {
        $team_id = Auth::user()->currentTeam->id;

        $total = Billing::query()->where([
            ['made_at', '>=', $from->setTimezone('UTC')],
            ['made_at', '<=', $to->setTimezone('UTC')],
        ])->whereHas('lead', function (Builder $query) use ($team_id) {
            $query->where('team_id', $team_id);
        })->get()->sum('total');

        return $total;
    }
}
