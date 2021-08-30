<?php

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelephonyController;
use App\Http\Controllers\EmployeesScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/nomenclatures/categories/getList', function (Request $request, \App\Domains\Nomenclature\Repositories\NomenclatureCategoryRepository $repository) {
    return response()->json([
        'results' => $repository->findForSelect($request['q'] ?? ''),
    ], JsonResponse::HTTP_OK);
});

Route::get('/nomenclatures/getList', function (Request $request, \App\Domains\Nomenclature\Repositories\NomenclatureRepository $repository) {
    return response()->json([
        'results' => $repository->findForSelect($request['q'] ?? ''),
    ], JsonResponse::HTTP_OK);
});

Route::get('/nomenclatures/products/getList', function (Request $request, \App\Domains\Nomenclature\Repositories\NomenclatureProductRepository $repository) {
    return response()->json([
        'results' => $repository->findForSelect($request['q'] ?? ''),
    ], JsonResponse::HTTP_OK);
});
Route::get('/nomenclatures/products/export', function (Request $request, \App\Domains\Nomenclature\Repositories\NomenclatureProductRepository $repository) {
    return response()->json([
        'results' => $repository->export(),
    ], JsonResponse::HTTP_OK);
});

Route::match(['get', 'post'], 'integrations/telephony/{id}', [TelephonyController::class, 'acceptRequest'])->name('integrations.telephony.accept');


Route::get('/nomenclature/properties/values', function (Request $request, \App\Domains\Nomenclature\Repositories\NomenclaturePropertyValueRepository $repository) {
    return response()->json([
        'results' => $repository->findForSelect($request['q'] ?? '', $request->propertyId),
    ], JsonResponse::HTTP_OK);
});

Route::get('/localities', function (Request $request, \App\Domains\Directory\Repositories\LocalityRepository $repository) {
    return response()->json([
        'results' => $repository->findByName($request['q'] ?? ''),
    ], JsonResponse::HTTP_OK);
});


Route::get('/users/export', function (Request $request, \App\Domains\User\Repositories\UserRepository $repository) {
    return response()->json([
        'results' => $repository->export(),
    ], JsonResponse::HTTP_OK);
});


// DesktopApp
Route::post('/token', [\App\Http\Controllers\APIAuthController::class, 'requestToken']);

// MobileAPP
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/mobile/reserve', [EmployeesScheduleController::class, 'reserve']);
    Route::get('/mobile/getDate', [EmployeesScheduleController::class, 'apiGetDate']);
    Route::get('/mobile/getTime', [EmployeesScheduleController::class, 'apiGetTime']);
    Route::get('/mobile/getService', [EmployeesScheduleController::class, 'apiGetService']);
});
