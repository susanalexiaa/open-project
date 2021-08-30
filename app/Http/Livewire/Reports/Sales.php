<?php

namespace App\Http\Livewire\Reports;

use App\Domains\Lead\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Helpers\Sales as SalesStatistic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Sales extends Component
{
    use WithPagination;

    public $date_start;
    public $date_end;

    public $summary;
    public $count;

    public function mount()
    {
        $this->date_start = Carbon::now(Auth::user()->timezone)->startOfMonth();
        $this->date_end = Carbon::now(Auth::user()->timezone)->endOfMonth();
    }

    public function render()
    {
        $data = Lead::where([
                [ 'created_at', '>=', (clone $this->date_start)->setTimezone('UTC') ],
                [ 'created_at', '<=', (clone $this->date_end)->setTimezone('UTC') ],
                ['team_id', Auth::user()->currentTeam->id]
            ])
            ->select( DB::raw('DATE(CONVERT_TZ(created_at, "+00:00", "'.$this->date_start->format('P').'")) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')->orderByDesc('id');

        $all_items = $data->get();

        $this->summary = SalesStatistic::getSummaryByPeriod( clone $this->date_start, clone $this->date_end);
        $this->count = $all_items->sum('count');


        return view('livewire.reports.sales', [
            'data' => $data->paginate(50)
        ]);
    }

    public function searchByDateStart($date)
    {
        $this->date_start = Carbon::parse($date, Auth::user()->timezone);
        $this->goToPage(1);
    }

    public function searchByDateEnd($date)
    {
        $this->date_end = Carbon::parse($date, Auth::user()->timezone)->endOfDay();
        $this->goToPage(1);
    }
}
