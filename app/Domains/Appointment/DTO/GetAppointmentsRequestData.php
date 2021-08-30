<?php

namespace App\Domains\Appointment\DTO;

class GetAppointmentsRequestData
{
    public $user_id;

    public function __construct($data)
    {
        $this->user_id = $data['user_id'] ?? null;
    }
}
