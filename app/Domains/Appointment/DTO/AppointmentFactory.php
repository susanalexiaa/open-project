<?php

namespace App\Domains\Appointment\DTO;

use App\Domains\Appointment\DTO\UpdateAppointmentRequestData;

class AppointmentFactory
{
    public static function fromUpdateRequest(UpdateAppointmentRequest $request): UpdateAppointmentRequestData
    {
        return new UpdateAppointmentRequestData([
            'uuid' => $request->get('uuid'),
            'user_id' => \Auth::id(),
            //TODO: здесь, может стоит переделать получение ID с бека, а не с реквеста? А то проблема с безопасностью, можно запрос с ID отправить
            'team_id' => \Auth::user()->personalTeam()->id,
            'customer_id' => $request->get('customer_id'),
            'title' => $request->get('title'),
            'objective' => $request->get('objective'),
            'date' => $request->get('date'),
            'hours' => $request->get('hours'),
            'minutes' => $request->get('minutes'),
            'new_hours' => $request->get('new_hours'),
            'new_minutes' => $request->get('new_minutes'),
            'photo' => $request->get('photo'),
            'image_id' => $request->get('image_id'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
        ]);
    }

    public static function fromDeleteRequest(DeleteAppointmentRequest $request): DeleteAppointmentRequestData
    {
        return new DeleteAppointmentRequestData([
            'user_id' => $request->get('user_id'),
            'date' => $request->get('date'),
            'hours' => $request->get('hours'),
            'minutes' => $request->get('minutes'),
        ]);
    }

    public static function fromGetRequest(GetAppointmentsRequest $request): GetAppointmentsRequestData
    {
        return new GetAppointmentsRequestData([
            'user_id' => $request->route('id'),
        ]);
    }
}
