<?php

use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\TelephonyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\EmailController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\EmployeesScheduleController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified', 'localization'])->group(function () {

    Route::get('/dashboard', [LeadsController::class, 'leads'])->name('dashboard');
    Route::get('/test', [LeadsController::class, 'test']);

    Route::prefix('leads')->group(function () {
        Route::get('/create', [LeadsController::class, 'create'])->name('lead.create');
        Route::post('/create', [LeadsController::class, 'save'])->name('lead.save');
    });

    Route::prefix('contractors')->group(function () {
        Route::get('/', [ContractorController::class, 'index'])->name('contractors.index');
        Route::get('/calls', [ContractorController::class, 'calls'])->name('contractors.calls');
    });

    Route::get('/directories', function () {
        return view('pages.directories.index');
    })->name('directories');

    Route::prefix('nomenclatures')->group(function () {
        Route::get('/', [\App\Http\Controllers\NomenclatureController::class, 'index'])->name('nomenclatures.index');
        Route::get('/create', [\App\Http\Controllers\NomenclatureController::class, 'create'])->name('nomenclatures.create');
        Route::get('/{nomenclature}', [\App\Http\Controllers\NomenclatureController::class, 'edit'])->name('nomenclatures.edit');
        Route::delete('/{nomenclature}', [\App\Http\Controllers\NomenclatureController::class, 'destroy'])->name('nomenclatures.destroy');
        Route::get('/categories/create', [\App\Http\Controllers\NomenclatureController::class, 'createCategory'])->name('nomenclatures.category.create');
        Route::get('/categories/{category}', [\App\Http\Controllers\NomenclatureController::class, 'editCategory'])->name('nomenclatures.category.edit');
        Route::delete('/categories/{category}', [\App\Http\Controllers\NomenclatureController::class, 'destroyCategory'])->name('nomenclatures.category.destroy');
    });
    Route::prefix('prices')->group(function () {
        $controller = PriceController::class;
        Route::get('/', [$controller, 'index'])->name('prices.index');
        Route::get('/create', [$controller, 'create'])->name('prices.create');
        Route::get('{priceType}/edit', [$controller, 'edit'])->name('prices.edit');
        Route::delete('{priceType}/destroy', [$controller, 'destroy'])->name('prices.destroy');
        Route::get('/prices/{price}/edit', [$controller, 'editPrice'])->name('prices.editPrice');
        Route::delete('/prices/{price}/destroy', [$controller, 'destroyPrice'])->name('prices.destroyPrice');
    });


    Route::get('/feedback', [FeedbackController::class, 'feedbacks'])->name('feedbacks');
    Route::get('/language/{slug}', [LanguageController::class, 'changeLanguage'])->name('changeLanguage');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Локалити
    Route::get('/localities/list', [\App\Http\Controllers\DirectoryController::class, 'localities']);

    Route::get('/connected_emails', function () {
        return view('email_connector');
    })->name('connected_emails');

    Route::get('/email/{id}', [EmailController::class, 'email'])->name('email');

    Route::get('/entities', function () {
        return view('entities');
    })->name('entities');

    Route::group(['prefix' => 'appointments', 'as'=> 'appointments.'], function () {
        $controller = AppointmentController::class;
        Route::post('/images', [$controller, 'uploadImage'])->name('image.upload');
        Route::delete('/images', [$controller, 'removeImage'])->name('image.remove');
        Route::get('/', [$controller, 'index'])->name('list');
        Route::get('/create', [$controller, 'create'])->name('create');
        Route::get('/{uuid}', [$controller, 'edit'])->name('edit');
        Route::get('/{uuid}/cancel', [$controller, 'cancel']);
        Route::post('/{uuid}/cancel', [$controller, 'cancelPost'])->name('cancel');
    });

    Route::group(['middleware' => [
        'admin'
    ]], function () {
        Route::prefix('admin')->group(function () {
            Route::get('/users', function () {
                return view('admin.users');
            })->name('users');
            Route::get('/teams', [TeamController::class, 'index'])->name('admin.teams');
            Route::name('admin.teams.')
                ->prefix('teams')
                ->group(function () {
                    Route::get('/create', [TeamController::class, 'create'])->name('create');
                    Route::get('{team}/edit', [TeamController::class, 'edit'])->name('edit');
                    Route::get('{team}/destroy', [TeamController::class, 'destroy'])->name('destroy');
                });

            Route::get('/statuses', function () {
                return view('admin.statuses');
            })->name('statuses');


            Route::prefix('integrations')->group( function () {

                Route::get('/', function () {
                    return view('admin.integrations');
                })->name('integrations');

                Route::prefix('telephony')->group(function () {
                    Route::get('/', function () {
                        return view('admin.telephony_integrations');
                    })->name('integrations.telephony');
                });
            });

        });

        Route::get('logs', [ LogViewerController::class, 'index' ])->name('logs');
    });


    Route::get('/reports/sales', [ ReportsController::class, 'sales' ] )->name('report.sales');
});

Route::prefix('entities')->group(function () {
    Route::get('{id}', [ FeedbackController::class, 'feedback' ])->name('feedback');
    Route::post('{id}', [ FeedbackController::class, 'sendFeedback' ]);
    Route::get('{id}/showQRCode', [ FeedbackController::class, 'showQRCode' ])->name('showQRCode');
});

Route::prefix('employees/schedule')->group(function(){
    Route::get('/', [EmployeesScheduleController::class, 'index'])->name('employees.schedule');
    Route::get('/create', [EmployeesScheduleController::class, 'createIndex'])->name('employees.schedule.create');
    Route::get('{schedule_id}/update', [EmployeesScheduleController::class, 'updateIndex'])->name('employees.schedule.update');
});

Route::get('thankyou', [ FeedbackController::class, 'thankyou' ])->name('thankyou');
