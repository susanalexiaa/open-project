<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Repositories\AppointmentRepository;
use Illuminate\Support\Str;

class UpdateAppointmentPhotoAction
{
    protected $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($data)
    {
        $appointment = $this->repository->findByUuid($data);

        $appointment
            ->addMediaFromBase64($data->photo)
            ->toMediaCollection();

        return $appointment->uuid;
    }

    private function getMimeType(string $image)
    {
        $f = finfo_open();

        return explode('/', finfo_buffer($f, $image, FILEINFO_MIME_TYPE))[1];
    }
}
