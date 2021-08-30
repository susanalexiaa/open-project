<?php

namespace App\Http\Controllers;


use App\Domains\Appointment\Models\Appointment;
use Carbon\Carbon;
use App\Domains\Appointment\Actions\RemoveAppointmentImageAction;
use App\Domains\Appointment\Actions\UpdateAppointmentPhotoAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Domains\Appointment\DTO\AppointmentFactory;
use App\Domains\Appointment\Actions\GetAppointmentsAction;
use App\Domains\Appointment\Actions\DeleteAppointmentAction;
use App\Domains\Appointment\Actions\UpdateAppointmentAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Domains\Appointment\Actions\UpdateAppointmentCoordsAction;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::latest()->where('user_id', Auth::id())->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $model = new Appointment();
        $date = Carbon::parse($request->date) ?? Carbon::parse('2021-01-01');
        return view('appointments.edit', compact('model', 'date'));
    }

    public function edit($uuid)
    {
        $model = Appointment::findByUuidOrFail($uuid);
        $date = $model->datetime;
        return view('appointments.edit', compact('model', 'date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([]);

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title') ?? 'title',
            'date' => $request->input('date') ?? '2021-01-01',
            'hours' => (int) $request->input('hours') ?? 9,
            'minutes' => (int) $request->input('minutes') ?? 0,
        ]);

        return redirect(route('appointments.list'));
    }

    public function getAppointment(string $uuid)
    {
        try {
            $response = Appointment::findByUuidOrFail($uuid);
        } catch (ModelNotFoundException $e) {
            return Response::json([], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return Response::json([], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json(['appointment' => $response], JsonResponse::HTTP_OK);
    }

    public function getAppointments(GetAppointmentsRequest $request, GetAppointmentsAction $action)
    {
        $data = AppointmentFactory::fromGetRequest($request);

        try {
            $response = $action->execute($data);
        } catch (ModelNotFoundException $e) {
            return Response::json([], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return Response::json([], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json(['appointments' => $response], JsonResponse::HTTP_OK);
    }

    public function updateAppointment(UpdateAppointmentRequest $request, UpdateAppointmentAction $action)
    {
        $data = AppointmentFactory::fromUpdateRequest($request);

        try {
            $response = $action->execute($data);
        } catch (ModelNotFoundException $e) {
            return Response::json([], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return Response::json([], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json(['appointment' => $response], JsonResponse::HTTP_OK);
    }

    public function updateCoordinates(UpdateAppointmentRequest $request, UpdateAppointmentCoordsAction $action)
    {
        $data = AppointmentFactory::fromUpdateRequest($request);

        return $action->execute($data);
    }

    public function uploadImage(UpdateAppointmentRequest $request, UpdateAppointmentPhotoAction $action)
    {
        $data = AppointmentFactory::fromUpdateRequest($request);

        try {
            $response = $action->execute($data);
        } catch (ModelNotFoundException $e) {
            return Response::json(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json(['uuid' => $response], JsonResponse::HTTP_OK);
    }

    public function removeImage(UpdateAppointmentRequest $request, RemoveAppointmentImageAction $action)
    {
        $data = AppointmentFactory::fromUpdateRequest($request);

        return $action->execute($data);
    }

    public function deleteAppointment(DeleteAppointmentRequest $request, DeleteAppointmentAction $action)
    {
        $data = AppointmentFactory::fromDeleteRequest($request);

        return $action->execute($data);
    }

    public function cancel($uuid){
        $appointment = Appointment::findByUuidOrFail($uuid);
        return view('appointments.cancel', compact('appointment'));
    }

    public function cancelPost($uuid, Request $request){
        $appointment = Appointment::findByUuidOrFail($uuid);
        $appointment->cancel_reason = $request->reason;
        $appointment->status = 0;
        $appointment->save();
        return redirect(route('appointments.list'));
    }
}
