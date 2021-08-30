<?php


namespace App\Domains\Employees\Action;


use App\Domains\Appointment\Models\Appointment;
use App\Domains\Contractor\Models\Contractor;
use App\Domains\Employees\Models\WorkingCell;
use App\Domains\Lead\Actions\CreateLeadProductByNomenclatureProduct;
use App\Domains\Lead\Models\Lead;
use App\Domains\Lead\Models\LeadProduct;
use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveAction
{

    protected static $teamId = 5;
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(){
        $validator = $this->validate();

        if ($validator->fails()) {
            return response(
                $validator->errors(),
                400
            );
        }
        $cell = WorkingCell::query()->find($this->request->time_id);
        if (empty($cell) || $cell->is_busy){
            return response()->json([
                'cell' => 'Данное время недоступно для записи'
            ], 400);
        }

        $contractor = Contractor::query()->firstOrCreate([
            'phone' => $this->request->phone
        ],[
            'title' => $this->request->name,
        ]);
        $text = "<div>
            <div>Дата записи: " . $cell->start_period . "</div>";
        $text .= "<div>Исполнитель: " . $cell->day->user->name . " </div>";
        if (!empty($this->request->service_id)){
            $product = NomenclatureProduct::query()->find($this->request->service_id);
            $text .= "<div>Услуга: " . $product->name . "</div>";
        }
        $text .= "</div>";
        $user = User::query()->first();
        $lead = Lead::query()->create([
            'user_id' => $user->id,
            'source_id' => 2,
            'status_id' => 1,
            'responsible_id' => 1,
            'team_id' => self::$teamId,
            'lead-trixFields' => [
                'content' => $text
            ],
        ]);
        if (!empty($this->request->service_id)){
            $action = new CreateLeadProductByNomenclatureProduct($lead, $product);
            $action->execute();
        }
        $lead->contractors()->attach([$contractor->id]);

        Appointment::query()->create([
            'user_id' => $user->id,
            'team_id' => self::$teamId,
            'title' => 'Запись №' . $lead->id,
            'lead_id' => $lead->id,
            'datetime' => $cell->start_period,
        ]);
        $cell->is_busy = true;
        $cell->save();
        $this->reserveFollowingCells($cell);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    protected function validate(){
        return Validator::make($this->request->all(), [
            'time_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
        ]);
    }

    protected function reserveFollowingCells(WorkingCell $cell){
        WorkingCell::query()->where('day_id', $cell->day_id)
            ->where('start_period', '<', $cell->start_period->addHour())
            ->update(['is_busy' => true]);
    }

}
