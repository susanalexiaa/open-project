<?php

namespace App\Domains\Appointment\DTO;

use Carbon\Carbon;

class UpdateAppointmentRequestData
{
    public $uuid;

    public $user_id;

    public $customer_id;

    public $title;

    public $objective;

    public $date;

    public $hours;

    public $minutes;

    public $new_hours;

    public $new_minutes;

    public $photo;

    public $image_id;

    public $latitude;

    public $longitude;

    public $checkin_time;

    public $team_id;

    public function __construct($data)
    {
        $this->uuid = $data['uuid'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->team_id = $data['team_id'] ?? null;
        $this->customer_id = $data['customer_id'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->objective = $data['objective'] ?? null;
        $this->date = Carbon::parse($data['date'])->format('Y-m-d') ?? null;
        $this->hours = $data['hours'] ?? null;
        $this->minutes = $data['minutes'] ?? null;
        $this->new_hours = $data['new_hours'] ?? null;
        $this->new_minutes = $data['new_minutes'] ?? null;
        $this->photo = $data['photo'] ?? '';
        $this->image_id = $data['image_id'] ?? 0;
        $this->latitude = $data['latitude'] ?? 0;
        $this->longitude = $data['longitude'] ?? 0;
    }
}
