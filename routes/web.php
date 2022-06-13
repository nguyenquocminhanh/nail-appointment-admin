<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BusinessController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\StaffProfileController;
use App\Http\Controllers\Backend\AppointmentController;
use App\Http\Controllers\Backend\UserNotificationController;
use App\Http\Controllers\Backend\AdminNotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Laravel Breeze, must be auth -> access dashboard
Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


// group middleware ALL USER
Route::middleware('auth')->group(function () {
    // User All Route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'profile')->name('admin.profile');
        Route::get('/edit/profile', 'editProfile')->name('edit.admin.profile');
        Route::post('/store/profile', 'storeProfile')->name('store.admin.profile');

        Route::get('/change/password', 'changePassword')->name('change.password');
        Route::post('/update/password', 'updatePassword')->name('update.password');
    });

    Route::controller(AppointmentController::class)->group(function () {
        Route::get('/appointment/all', 'AppointmentAll')->name('appointment.all');
        Route::get('/appointment/new', 'AppointmentNew')->name('appointment.new');
        Route::get('/appointment/visited', 'AppointmentVisited')->name('appointment.visited');
        Route::get('/appointment/past', 'AppointmentPast')->name('appointment.past');
        Route::get('/appointment/read/all', 'AppointmentReadAll')->name('appointment.read.all');

        Route::get('/appointment/view/{id}', 'AppointmentView')->name('appointment.view');
        Route::get('/appointment/check/{id}', 'AppointmentCheck')->name('appointment.check');
        Route::get('/appointment/delete/{id}', 'AppointmentDelete')->name('appointment.delete');
    });
}); // end group middleware



// group middleware ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    // Business All Route
    Route::controller(BusinessController::class)->group(function () {
        Route::get('/business', 'BusinessAll')->name('business');
        Route::get('/business/add', 'BusinessAdd')->name('business.add');
        Route::post('/business/store', 'BusinessStore')->name('business.store');
        Route::get('/business/edit', 'BusinessEdit')->name('business.edit');
        Route::post('/business/update', 'BusinessUpdate')->name('business.update');
    });

    // Staff All Route
    Route::controller(StaffController::class)->group(function () {
        Route::get('/staff/all', 'StaffAll')->name('staff.all');
        Route::get('/staff/add', 'StaffAdd')->name('staff.add');
        Route::post('/staff/store', 'StaffStore')->name('staff.store');
        Route::get('/staff/edit/{id}', 'StaffEdit')->name('staff.edit');
        Route::post('/staff/update', 'StaffUpdate')->name('staff.update');
        Route::get('/staff/delete/{id}', 'StaffDelete')->name('staff.delete');

        Route::get('/staff/view/{id}', 'StaffView')->name('staff.view');
    });

    // Service All Route
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/service/all', 'ServiceAll')->name('service.all');
        Route::get('/service/add', 'ServiceAdd')->name('service.add');
        Route::post('/service/store', 'ServiceStore')->name('service.store');
        Route::get('/service/edit/{id}', 'ServiceEdit')->name('service.edit');
        Route::post('/service/update', 'ServiceUpdate')->name('service.update');
        Route::get('/service/delete/{id}', 'ServiceDelete')->name('service.delete');
    });

    // Admin Notification All Route
    Route::controller(AdminNotificationController::class)->group(function () {
        Route::get('/admin/notification/read/{id}', 'AppointmentNotificationRead')->name('admin.notification.read');
        Route::get('/update/notification/read/{userID}/{notiID}', 'UpdateNotificationRead')->name('update.notification.read');
        Route::get('/update/read/all', 'UpdateNotificationReadAll')->name('update.read.all');
    });
}); // end group middleware




// group middleware STAFF
Route::middleware(['auth', 'staff'])->group(function () {
    // Staff Profile All Route
    Route::controller(StaffProfileController::class)->group(function () {
        Route::get('/my-profile', 'ViewMyProfile')->name('my.profile');
        Route::get('/my-profile/edit', 'EditMyProfile')->name('edit.profile');

        Route::post('/my-profile/update', 'UpdateMyProfile')->name('update.profile');
    });
 
    // User Notification All Route
    Route::controller(UserNotificationController::class)->group(function () {
        Route::get('/user/notification/read/{id}', 'NotificationRead')->name('user.notification.read');
    });
}); // end group middleware
