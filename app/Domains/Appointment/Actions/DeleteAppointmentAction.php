<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;

class DeleteAppointmentAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        $appointment = $this->repository->findByAttributes($data);

        return $this->repository->delete($appointment->id);
    }
}
