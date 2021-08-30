<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;

class RemoveAppointmentImageAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        $appointment = $this->repository->findByUuid($data);

        $appointment->deleteMedia($data->image_id);

        return true;
    }
}
