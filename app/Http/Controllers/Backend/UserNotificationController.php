<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAppNoti;

class UserNotificationController extends Controller
{
    public function NotificationRead($id) {
        $notification = UserAppNoti::findOrFail($id);
        $notification->update([
            'is_read' => '1'
        ]);
        $appointments = $notification->appointments;

        return view('staff.appointment.appointment_new', compact('appointments'));
    }
}
