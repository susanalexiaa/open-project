<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;

class UpdateAppointmentAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        $appointment = $this->repository->findByAttributes($data);

        $data->hours = $data->new_hours;
        $data->minutes = $data->new_minutes;

        unset($data->new_hours);
        unset($data->new_minutes);

        return is_null($appointment) ?
            $this->repository->create((array) $data) :
            $this->repository->update($appointment->id, (array) $data);
    }
}
