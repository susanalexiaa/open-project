<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;
use Carbon\Carbon;

class UpdateAppointmentCoordsAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        $appointment = $this->repository->findByAttributes($data);

        $data->checkin_time = Carbon::now();

        $this->repository->update($appointment->id, (array) $data);

        return true;
    }
}
