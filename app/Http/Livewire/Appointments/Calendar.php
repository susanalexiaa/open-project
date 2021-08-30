<?php

namespace App\Http\Livewire\Appointments;

use App\Models\Appointment;
use Carbon\Carbon;
use Livewire\Component;
use App\Domain\Appointment\DTO\AppointmentFactory;
use Domain\Appointment\Actions\GetAppointmentsAction;

class Calendar extends Component
{
    public $uuid;

    public $title;

    public $objective;

    public $datetime;

    public $date;

    public $hours;

    public $minutes;

    public $comments;

    public $period;

    public $customer_id;

    public $individual_id;

    public $date_formatted;

    public $cancel_reason;

    public $calendar_hidden;

    public $openEventModal = false;

    public $isEditMode = false;

    public $search;

    public $times;

    public $events;

    public $event_list;

    public $objectives;

    public $reasons;

    public function getCalendarState()
    {
        $this->calendar_hidden = auth()->user()->calendar_hidden;
    }

    public function getEventList($startDate, $endDate)
    {
        if (!empty($this->period)) {
            $period = explode(' — ', $this->period);
            $startDate = $period[0];
            $endDate = array_key_exists(1, $period) ? $period[1] : $period[0];
        }

        $start = Carbon::createFromFormat('d.m.Y', $startDate)->startOfDay();
        $end = Carbon::createFromFormat('d.m.Y', $endDate)->endOfDay();

        $search = !empty($this->search) ? $this->search : '';

        $this->event_list = Appointment::with('customer', 'individual', 'user')
            ->whereBetween('datetime', [$start, $end])
            ->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('objective', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('individual', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->where('user_id', auth()->id())
            ->orderby('datetime')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->uuid => [
                        'uuid' => $item->uuid,
                        'user' => $item->user ? $item->user['name'] : 'Пользователь',
                        'user_id' => $item->user_id,
                        'customer' => $item->customer ? $item->customer['name'] : 'Организация',
                        'customer_id' => $item->customer_id,
                        'individual' => $item->individual ? $item->individual['name'] : 'Физическое лицо',
                        'individual_id' => $item->individual_id,
                        'title' => $item->title,
                        'objective' => $item->objective,
                        'date' => $item->date,
                        'hours' => $item->hours,
                        'minutes' => $item->minutes,
                        'datetime' => $item->datetime,
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude,
                        'checkin_time' => $item->checkin_time,
                        'cancel_reason' => $item->cancel_reason,
                        'team_id' => $item->team_id,
                        'status' => $item->status,
                        'images' => $item->images,
                        'comments' => $item->comments,
                        'date_formatted' => $item->date_formatted,
                        'time_formatted' => $item->time_formatted,
                    ],
                ];
            })->toArray();

        //if ($list->count() === 0) {
        //    $this->event_list = [1 => 1, 2 => 2, 3 => 3];
        //} else {
        //    $this->event_list = $list->toArray();
        //}
    }

    public function mount(GetAppointmentsRequest $request, GetAppointmentsAction $action)
    {
        $this->resetEvent();

        $this->getCalendarState();
        $this->loadTimes();
        $this->loadEvents($request, $action);
        $this->loadObjectives();
        $this->loadReasons();
    }

    public function render()
    {
        return view('livewire.appointments.calendar');
    }

    public function resetEvent()
    {
        $this->uuid = 0;
        $this->title = '';
        $this->hours = '09';
        $this->minutes = '00';
        $this->customer_id = 0;
        $this->individual_id = 0;
        $this->objective = '';
        $this->comments = '';
        $this->cancel_reason = '';
    }

    public function showEvent($uuid)
    {
        $this->openEventModal = true;
        $this->isEditMode = true;

        $event = $this->event_list[$uuid];

        $this->uuid = $event['uuid'];
        $this->title = $event['title'];
        $this->objective = $event['objective'];
        $this->date = $event['date'];
        $this->hours = $event['hours'];
        $this->minutes = $event['minutes'];
        $this->comments = $event['comments'];
        $this->customer_id = $event['customer_id'];
        $this->individual_id = $event['individual_id'];
        $this->date_formatted = $event['date_formatted'];
    }

    public function createEvent($year, $month, $day)
    {
        $this->resetEvent();

        $this->datetime = Carbon::create($year, $month, $day);
        $this->date = $this->datetime->format('Y-m-d');
        $this->date_formatted = $this->datetime->format('d.m.y');
    }

    public function validateEvent($day)
    {
        if ($this->title == '') {
            return [
                'status' => false,
                'error' => 'Не заполено резюме визита',
            ];
        }

        if ($this->objective == '') {
            return [
                'status' => false,
                'error' => 'Не выбрана цель визита',
            ];
        }

        $dt = Carbon::createFromFormat('Y-m-d H:i', $day . ' ' . $this->hours . ':' . $this->minutes);

        if ($this->uuid !== 0) {
            $event = Appointment::findByUuidOrFail($this->uuid);

            if ($dt->equalTo($event->datetime)) {
                return [
                    'status' => true,
                    'error' => '',
                ];
            }
        }

        $event = Appointment::where(['datetime' => $dt, 'status' => 1])->get()->count();

        if ($event) {
            return [
                'status' => false,
                'error' => 'На указанное время визит уже запланирован',
            ];
        }

        return [
            'status' => true,
            'error' => '',
        ];
    }

    public function saveEvent($day)
    {
        if ($this->uuid !== 0) {
            $day = $this->date;
        }

        $validated = $this->validateEvent($day);

        if ($validated['status'] == false) {
            return $validated;
        }

        if ($this->uuid !== 0) { //Edit existing event
            $event = Appointment::findByUuidOrFail($this->uuid);

            $event->title = $this->title;
            $event->objective = $this->objective;
            $event->datetime = $this->date . ' ' . $this->hours . ':' . $this->minutes;
            $event->customer_id = $this->customer_id;
            $event->individual_id = $this->individual_id;
            $event->comments = $this->comments;

            $event->save();
        } else { // Create new event
            $event = [
                'user_id' => auth()->id(),
                'title' => $this->title,
                'objective' => $this->objective,
                'datetime' => $day . ' ' . $this->hours . ':' . $this->minutes,
                'customer_id' => $this->customer_id,
                'individual_id' => $this->individual_id,
                'team_id' => auth()->user()->currentTeam->id,
            ];

            Appointment::create($event);

            return $validated;
        }
    }

    public function removeEvent()
    {
        unset($this->events[$this->uuid]);

        $event = Appointment::findByUuidOrFail($this->uuid);

        $event->delete();
    }

    public function cancelEvent()
    {
        if ($this->cancel_reason == '') {
            return [
                'status' => false,
                'error' => 'Не выбрана причина отмены',
            ];
        }

        $event = Appointment::findByUuidOrFail($this->uuid);

        $event->cancel_reason = $this->cancel_reason;
        $event->status = 0;

        $event->save();

        return [
            'status' => true,
            'error' => '',
        ];
    }

    public function saveLocation($latitude, $longitude)
    {
        $event = Appointment::findByUuidOrFail($this->uuid);

        $event->latitude = $latitude;
        $event->longitude = $longitude;
        $event->checkin_time = Carbon::now();

        $event->save();
    }

    public function loadEvents($request, $action)
    {
        $data = AppointmentFactory::fromGetRequest($request);

        $data->user_id = auth()->id();

        //$this->event_list = $action->execute($data)->toArray();

        $this->events = $action->execute($data)->mapWithKeys(function ($item) {
            return [
                $item->uuid => [
                    'uuid' => $item->uuid,
                    'user' => $item->user ? $item->user['name'] : 'Пользователь',
                    'user_id' => $item->user_id,
                    'customer' => $item->customer ? $item->customer['name'] : 'Организация',
                    'customer_id' => $item->customer_id,
                    'individual' => $item->individual ? $item->individual['name'] : 'Физическое лицо',
                    'individual_id' => $item->individual_id,
                    'title' => $item->title,
                    'objective' => $item->objective,
                    'date' => $item->date,
                    'hours' => $item->hours,
                    'minutes' => $item->minutes,
                    'datetime' => $item->datetime,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'checkin_time' => $item->checkin_time,
                    'cancel_reason' => $item->cancel_reason,
                    'comments' => $item->comments,
                    'team_id' => $item->team_id,
                    'status' => $item->status,
                    'images' => $item->images,
                    'date_formatted' => $item->date_formatted,
                    'time_formatted' => $item->time_formatted,
                ],
            ];
        })->toArray();

        $this->event_list = $this->events;
    }

    public function toggleCalendar($state)
    {
        $user = auth()->user();

        $user->calendar_hidden = $state;
        $user->save();
    }

    public function loadReasons()
    {
        $this->reasons = [
            [
                'value' => 'Болезнь',
                'label' => 'Болезнь',
            ],
            [
                'value' => 'Опоздание',
                'label' => 'Опоздание',
            ],
            [
                'value' => 'Другое',
                'label' => 'Другое',
            ],
        ];
    }

    public function loadObjectives()
    {
        $this->objectives = [
            [
                'value' => 'Обмен',
                'label' => 'Обмен',
            ],
            [
                'value' => 'Первичный визит',
                'label' => 'Первичный визит',
            ],
            [
                'value' => 'Вторичный визит',
                'label' => 'Вторичный визит',
            ],
            [
                'value' => 'Двойной визит',
                'label' => 'Двойной визит',
            ],
            [
                'value' => 'Другое',
                'label' => 'Другое',
            ],
        ];
    }

    public function loadTimes()
    {
        $this->times = [
            'hours' => [
                [
                    'value' => '8',
                    'label' => '08',
                ],
                [
                    'value' => '9',
                    'label' => '09',
                ],
                [
                    'value' => '10',
                    'label' => '10',
                ],
                [
                    'value' => '11',
                    'label' => '11',
                ],
                [
                    'value' => '12',
                    'label' => '12',
                ],
                [
                    'value' => '13',
                    'label' => '13',
                ],
                [
                    'value' => '14',
                    'label' => '14',
                ],
                [
                    'value' => '15',
                    'label' => '15',
                ],
                [
                    'value' => '16',
                    'label' => '16',
                ],
                [
                    'value' => '17',
                    'label' => '17',
                ],
                [
                    'value' => '18',
                    'label' => '18',
                ],
                [
                    'value' => '19',
                    'label' => '19',
                ],
                [
                    'value' => '20',
                    'label' => '20',
                ],
                [
                    'value' => '21',
                    'label' => '21',
                ],
                [
                    'value' => '22',
                    'label' => '22',
                ],
                [
                    'value' => '23',
                    'label' => '23',
                ],
            ],
            'minutes' => [
                [
                    'value' => '00',
                    'label' => '00',
                ],
                [
                    'value' => '05',
                    'label' => '05',
                ],
                [
                    'value' => '10',
                    'label' => '10',
                ],
                [
                    'value' => '15',
                    'label' => '15',
                ],
                [
                    'value' => '20',
                    'label' => '20',
                ],
                [
                    'value' => '25',
                    'label' => '25',
                ],
                [
                    'value' => '30',
                    'label' => '30',
                ],
                [
                    'value' => '35',
                    'label' => '35',
                ],
                [
                    'value' => '40',
                    'label' => '40',
                ],
                [
                    'value' => '45',
                    'label' => '45',
                ],
                [
                    'value' => '50',
                    'label' => '50',
                ],
                [
                    'value' => '55',
                    'label' => '55',
                ],
            ],
        ];
    }
}
