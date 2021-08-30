<?php

namespace App\Http\Livewire\Appointments;

use App\Domains\Appointment\Models\Appointment;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentIndex extends Component
{
    public $searchDates;

    public $search;

    public $eventsState;

    public $activeEvent;

    public $cancel_reason;


    public function render()
    {
        return view('livewire.appointments.appointment-index', [
            'events' => $this->getEvents()
        ]);
    }

    public function getStartEvents($startDate, $endDate){
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        return Appointment::query()
            ->where('datetime', '>=', $startDate)
            ->where('datetime', '<=', $endDate)
            ->pluck('datetime')
            ->toJson();
    }

    public function getEvents(){
        $dates = [];
        if (empty($this->searchDates)){
            $dates[0] = Carbon::now()->startOfDay();
            $dates[1] = Carbon::now()->endOfDay();
        }else{
            $input = explode(' to ', $this->searchDates);
            $dates[0] = Carbon::parse($input[0])->startOfDay();
            if (!empty($input[1])){
                $dates[1] = Carbon::parse($input[1])->endOfDay();
            }else{
                $dates[1] = Carbon::parse($input[0])->endOfDay();
            }
        }
        $search = $this->search;
        $events = Appointment::query()
            ->where('datetime', '>=', $dates[0])
            ->where('datetime', '<=', $dates[1])
            ->with( 'user')
            ->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('objective', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('datetime');
        $events = $events->get()->append('images');
        $this->eventsState = $events->toArray();
        return $events;
    }

    public function saveLocation($uuid, $latitude, $longitude)
    {
        $event = Appointment::findByUuidOrFail($uuid);

        $event->latitude = $latitude;
        $event->longitude = $longitude;
        $event->checkin_time = Carbon::now();

        $event->save();
    }

    public function cancelEvent($uuid)
    {
        if ($this->cancel_reason == '') {
            return [
                'status' => false,
                'error' => 'Не выбрана причина отмены',
            ];
        }
        $event = Appointment::findByUuidOrFail($uuid);
        $event->cancel_reason = $this->cancel_reason;
        $event->status = 0;
        $event->save();
        return [
            'status' => true,
            'error' => '',
        ];
    }

    public function removeEvent($uuid)
    {

        $event = Appointment::findByUuidOrFail($uuid);

        $event->delete();
    }


}
