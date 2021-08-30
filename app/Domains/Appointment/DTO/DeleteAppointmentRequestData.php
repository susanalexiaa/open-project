<?php

namespace App\Domains\Appointment\DTO;

use Carbon\Carbon;

class DeleteAppointmentRequestData
{
    public $user_id;

    public $date;

    public $hours;

    public $minutes;

    public function __construct($data)
    {
        $this->user_id = $data['user_id'] ?? null;
        $this->date = Carbon::parse($data['date'])->format('Y-m-d') ?? null;
        $this->hours = $data['hours'] ?? null;
        $this->minutes = $data['minutes'] ?? null;
    }
}
