<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminAppNoti;
use App\Models\AdminUpdateNoti;
use App\Models\User;

class AdminNotificationController extends Controller
{
    public function AppointmentNotificationRead($id) {
        $notification = AdminAppNoti::findOrFail($id);
        $notification->update([
            'is_read' => '1'
        ]);
        $appointments = $notification->appointments;

        return view('staff.appointment.appointment_new', compact('appointments'));
    }

    public function UpdateNotificationRead($userID, $notiID) {
        $staff = User::findOrFail($userID);
        $notification = AdminUpdateNoti::findOrFail($notiID);
        $notification->update([
            'is_read' => '1'
        ]);
        return view('admin.staff.staff_view', compact('staff'));
    }

    public function UpdateNotificationReadAll() {
        $notifications = AdminUpdateNoti::all();
        foreach($notifications as $notification) {
            $notification->update([
                'is_read' => '1'
            ]);
        }
        return redirect()->back();
    }
}
