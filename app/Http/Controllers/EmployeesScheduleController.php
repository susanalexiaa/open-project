<?php

namespace App\Http\Controllers;

use App\Domains\Appointment\Models\Appointment;
use App\Domains\Contractor\Models\Contractor;
use App\Domains\Employees\Action\ReserveAction;
use App\Domains\Employees\Models\EmployeesSchedule;
use App\Domains\Employees\Models\WorkingCell;
use App\Domains\Employees\Models\WorkingDay;
use App\Domains\Employees\Requests\ReserveRequest;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadSource;
use App\Domains\Nomenclature\Models\Nomenclature;
use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeesScheduleController extends Controller
{
    public function index(Request $request)
    {
        $items = EmployeesSchedule::where([
            'team_id' => $request->user()->currentTeam->id
        ])->paginate(10);

        return view('employees.schedule.index', [
            'data' => $items
        ]);
    }

    public function createIndex()
    {
        return view('employees.schedule.create');
    }

    public function updateIndex($schedule_id, Request $request)
    {
        $schedule = EmployeesSchedule::findOrFail($schedule_id);

        if ($request->user()->currentTeam->id != $schedule->team_id) {
            abort(403);
        }

        return view('employees.schedule.create', [
            'schedule' => $schedule
        ]);
    }

    public function apiGetDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "days_ahead" => "required"
        ]);

        if ($validator->fails()) {
            return response(
                $validator->errors(),
                400
            );
        }

        $schedule_id = EmployeesSchedule::getActual()->id;
        $timezone = $request->input('timezone', "Europe/Moscow");
        $date_now = Carbon::now($timezone);
        $days_ahead = $request->days_ahead;

        $query = WorkingDay::query()->whereDate('day', '>=', $date_now)
            ->whereDate('day', '<=', $date_now->add($days_ahead, 'day'))
            ->where('schedule_id', $schedule_id);

        if ($user_id = $request->user_id) {
            $query->where('user_id', $user_id);
        }

        if ($product_id = $request->product_id) {
            $nomenclature = Nomenclature::query()->whereHas('products', function ($query) use ($product_id){
                $query->where('id', $product_id);
            })->first();
            $query->whereHas('user', function ($query) use ($nomenclature) {
                $query->whereHas('nomenclatures', function ($query) use ($nomenclature) {
                    $query->where('nomenclatures.id', $nomenclature->id);
                });
            });
        }

        return $query
            ->select("day")
            ->distinct()
            ->orderBy('day')
            ->get()
            ->transform(function ($item){
                return $item->day->toISOString();
            });
    }

    public function apiGetTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "date" => "required"
        ]);

        if ($validator->fails()) {
            return response(
                $validator->errors(),
                400
            );
        }
        $return_data = [];

        $query = WorkingDay::query()
            ->whereDate('day', Carbon::parse($request->date))
            ->whereDate('day', ">=", Carbon::now()->startOfDay())
            ->with('workingCells', 'user');

        if ($user_id = $request->user_id) {
            $query->where('user_id', $user_id);
        }

        if ($product_id = $request->product_id) {
            $nomenclature = Nomenclature::query()->whereHas('products', function ($query) use ($product_id){
                $query->where('id', $product_id);
            })->first();
            $query->whereHas('user', function ($query) use ($nomenclature) {
                $query->whereHas('nomenclatures', function ($query) use ($nomenclature) {
                    $query->where('nomenclatures.id', $nomenclature->id);
                });
            });
        }

        $days = $query->get();

        foreach ($days as $day) {
            $cells = \DB::select("
                select working_cells.id, working_cells.day_id, working_cells.is_busy, working_cells.start_period,
                COUNT(cells.is_busy) as next_cells_busy
                from working_cells
                inner join working_cells as cells
                on cells.start_period > working_cells.start_period
                and cells.start_period < DATE_ADD(working_cells.start_period, INTERVAL 1 HOUR)
                and cells.is_busy = 0
                where working_cells.is_busy = false
                and working_cells.day_id = " .  $day->id ."
                and working_cells.start_period >= \" ". Carbon::now()->addMinutes(15)->setTimezone('Europe/Moscow')->format('Y-m-d H:i:s') ." \"
                GROUP BY working_cells.id
                HAVING next_cells_busy > 2"
            );
            if (!empty($cells)) {
                $times = [];
                foreach ($cells as $cell) {
                    if ($cell->is_busy) continue;
                    $times[] = [
                        'id' => $cell->id,
                        'time' => Carbon::parse($cell->start_period)->format('H:i'),
                    ];
                }
                if (!empty($times)) {
                    $return_data[$day->user->name] = [
                        "position" => $day->user->position,
                        "times" => $times
                    ];
                }
            }
        }
        return $return_data;
    }

    public function reserve(Request $request)
    {
        $action = new ReserveAction($request);
        return $action->execute();
    }

    public function apiGetService()
    {

    }
}
