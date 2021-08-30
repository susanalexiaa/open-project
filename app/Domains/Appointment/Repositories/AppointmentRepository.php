<?php

namespace App\Domains\Appointment\Repositories;

use Carbon\Carbon;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Foundation\Repositories\BaseRepository;
use App\Domains\Appointment\Contracts\AppointmentRepositoryInterface;

class AppointmentRepository extends BaseRepository implements AppointmentRepositoryInterface
{
    protected $modelClass = Appointment::class;

    public function all()
    {
        return $this->model->all();
    }

    public function findByUuid($data)
    {
        return $this->model->findbyUuidOrFail($data->uuid);
    }

    public function findByUserId($data)
    {
        return $this->model
            ->where('user_id', $data->user_id)
            ->with('customer', 'individual', 'user')
            ->get()
            ->sortBy([
            function ($a, $b) {
                return Carbon::createFromDate($a['date']) <=> Carbon::createFromDate($b['date']);
            },
            ['hours', 'asc'],
            ['minutes', 'asc'],
        ]);
    }

    public function findByAttributes($data)
    {
        return $this->model->where([
            'user_id' => $data->user_id,
            'date' => $data->date,
            'hours' => $data->hours,
            'minutes' => $data->minutes,
        ])->first();
    }

    public function findForCalendar()
    {
        return $this->model
            ->with('customer', 'media')
            ->get()
            ->sortBy([
                function ($a, $b) {
                    return Carbon::createFromDate($a['date']) <=> Carbon::createFromDate($b['date']);
                },
                ['hours', 'asc'],
                ['minutes', 'asc'],
            ]);
    }
}
