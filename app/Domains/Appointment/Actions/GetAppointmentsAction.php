<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;

class GetAppointmentsAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        //TODO: здесь, юзер айди уже не нужен
        //return $this->repository->findForCalendar();
        return $this->repository->findByUserId($data);
    }
}
