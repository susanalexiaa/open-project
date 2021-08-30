<?php

namespace App\Domains\Appointment\Contracts;

use App\Domains\Foundation\Contracts\BaseRepositoryInterface;

interface AppointmentRepositoryInterface extends BaseRepositoryInterface
{
    public function all();
}
