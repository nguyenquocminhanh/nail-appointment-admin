<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BusinessController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\AppointmentController;

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

Route::get('/business/info', [BusinessController::class, 'BusinessInfo']);

Route::get('/service/all', [ServiceController::class, 'AllService']);

Route::get('/staff/all', [StaffController::class, 'GetStaffAll']);

Route::get('/staff-by-service/{serviceID}', [StaffController::class, 'StaffByService']);

Route::get('/service-by-staff/{staffID}', [ServiceController::class, 'ServiceByStaff']);

Route::get('/timeslots-by-staff/{dayOfWeek}/{staffID}', [StaffController::class, 'TimeslotsByStaff']);

Route::post('/appointment/store', [AppointmentController::class, 'AppointmentStore']);

Route::get('/appointment/cancel/{id}', [AppointmentController::class, 'appointmentCancel']);

Route::get('/slotime/check/{date}', [AppointmentController::class, 'SlottimeCheck']);