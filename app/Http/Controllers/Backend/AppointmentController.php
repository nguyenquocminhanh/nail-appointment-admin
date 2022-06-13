<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\UserAppNoti;
use App\Models\AdminAppNoti;
use Auth;
use Carbon\Carbon;
use Mail;
use App\Mail\AppointmentMail;
use App\Mail\CancelAppointmentMail;


class AppointmentController extends Controller
{
    public function AppointmentAll() {
        date_default_timezone_set('America/New_York');
        if(Auth::user()->role->name == 'admin') {
            $appointments = Appointment::orderBy('date', 'ASC')->orderBy('time', 'ASC')->where('date', '>=', Carbon::today())->get();
        } else {
            $appointments = Appointment::where('user_id', Auth::user()->id)->where('date', '>=', Carbon::today())
            ->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();
        }
        return view('staff.appointment.appointment_all', compact('appointments'));
    }

    public function AppointmentNew() {
        date_default_timezone_set('America/New_York');
        if(Auth::user()->role->name == 'admin') {
            $appointments = Appointment::orderBy('date', 'ASC')->orderBy('time', 'ASC')->where('is_admin_read', '0')->where('date', '>=', Carbon::today())->get();
        } else {
            $appointments = Appointment::where('user_id', Auth::user()->id)->orderBy('date', 'ASC')->where('is_user_read', '0')->where('date', '>=', Carbon::today())->orderBy('time', 'ASC')->get();
        }
        return view('staff.appointment.appointment_new', compact('appointments'));
    }

    public function AppointmentVisited() {
        if(Auth::user()->role->name == 'admin') {
            $appointments = Appointment::orderBy('date', 'ASC')->orderBy('time', 'ASC')->where('status', '1')->get();
        } else {
            $appointments = Appointment::where('user_id', Auth::user()->id)->orderBy('date', 'ASC')->where('status', '1')->orderBy('time', 'ASC')->get();
        }
        return view('staff.appointment.appointment_visited', compact('appointments'));
    }

    public function AppointmentPast() {
        if(Auth::user()->role->name == 'admin') {
            $appointments = Appointment::orderBy('date', 'ASC')->orderBy('time', 'ASC')->where('date', '<', Carbon::today())->get();
        } else {
            $appointments = Appointment::where('user_id', Auth::user()->id)->where('date', '<', Carbon::today())
            ->orderBy('date', 'ASC')->orderBy('time', 'ASC')->get();
        }
        return view('staff.appointment.appointment_past', compact('appointments'));
    }

    public function AppointmentView($id) {
        $appointment = Appointment::findOrfail($id);
        if (Auth::user()->role->name == 'staff') {
            $appointment->update([
                'is_user_read' => '1'
            ]);
        } else {
            $appointment->update([
                'is_admin_read' => '1'
            ]);
        }
        return view('staff.appointment.appointment_view', compact('appointment'));
    }

    public function AppointmentDelete($id) {
        $appointment = Appointment::findOrFail($id);
        $user_noti_id = $appointment->user_noti_id;
        $admin_noti_id = $appointment->admin_noti_id;

        $appointment->delete();

        // update noti
        if(UserAppNoti::findOrFail($user_noti_id)->appointments()->count() == 0) {
            UserAppNoti::findOrFail($user_noti_id)->delete();
        }

        if(AdminAppNoti::findOrFail($admin_noti_id)->appointments()->count() == 0) {
            AdminAppNoti::findOrFail($admin_noti_id)->delete();
        }

        $notification = array(
            'message' => 'Appointment Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('appointment.all')->with($notification);
    }

    public function AppointmentCheck($id) {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = '1';
        $appointment->save();
        $notification = array(
            'message' => 'Appointment Checked Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AppointmentReadAll() {
        // staff
        if(Auth::user()->role->name == 'staff') {
            $appointments = Appointment::where('user_id', Auth::user()->id)->get();
            foreach($appointments as $appointment) {
                $appointment->update([
                    'is_user_read' => '1'
                ]);
            };
        } else {    // admin
            $appointments = Appointment::all();
            foreach($appointments as $appointment) {
                $appointment->update([
                    'is_admin_read' => '1'
                ]);
            };
        }

        return redirect()->back();
    }

    // API
    public function AppointmentStore(Request $request) {
        date_default_timezone_set('America/New_York');

        $name = $request->input('name');
        $phone_number = $request->input('phone_number');
        $email = $request->input('email');
        $notes = $request->input('notes');

        $user_id = $request->input('user_id');         
        $date = $request->input('date'); 
        $time = $request->input('time'); 
        $services = $request->input('services'); 

        // create new User Notification if Noti already read, if not add to
        $noti = UserAppNoti::where('user_id', $user_id)->where('is_read', '0')->first();
        if ($noti == null) {
            $noti = UserAppNoti::create([
                'user_id' => $user_id,
                'created_at' => Carbon::now()
            ]);
            $noti_id = $noti->id;
        } else {
            $noti_id = $noti->id;
            $noti->update([
                'updated_at' => Carbon::now()
            ]);
        }

        // create new Admin Notification if Noti already read, if not add to
        $admin_noti = AdminAppNoti::where('is_read', '0')->first();
        if ($admin_noti == null) {
            $admin_noti = AdminAppNoti::create([
                'created_at' => Carbon::now()
            ]);
            $admin_noti_id = $admin_noti->id;
        } else {
            $admin_noti_id = $admin_noti->id;
            $admin_noti->update([
                'updated_at' => Carbon::now()
            ]);
        }

        try {
            $appointment = Appointment::create([
                'name' => $name,
                'phone_number' => $phone_number,
                'email' => $email,
                'notes' => $notes,

                'user_id' => $user_id,
                'date' => $date,
                'time' => $time,
                'services' => $services,
                'user_noti_id' => $noti_id,
                'admin_noti_id' => $admin_noti_id
            ]);

            // send mail to client if they gave email
            if ($appointment && $email) {
                $emailData = [
                    'name' => $name,
                    'business_name' => Business::find(1)->name,
                    'time' => date('g:i A', strtotime($time)),
                    'date' => date('D, M d, Y', strtotime($date)),
                    'staff' => $appointment->user->name,
                    'services' => $services,
                    'business_address' => Business::find(1)->address,
                    'business_phone' => Business::find(1)->phone_number
                ];

                try {
                    Mail::to($email)->send(new AppointmentMail($emailData));
                } catch(Exception $e) {
                    
                }
            }
        
            return response([
                'message' => "Successfully! Plesase check your email!",
                'appointment' => $appointment
            ], 200);    // Success 200 code

        } catch(Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function appointmentCancel($id) {
        $appointment = Appointment::findOrFail($id);
        $user_noti_id = $appointment->user_noti_id;
        $admin_noti_id = $appointment->admin_noti_id;
        // data for email
        $email = $appointment->email;
        $name = $appointment->name;
        $time = $appointment->time;
        $date = $appointment->date;
        $staff_name = $appointment->user->name;
        $services = $appointment->services;

        try {
            $appointment->delete();

            if(UserAppNoti::findOrFail($user_noti_id)->appointments()->count() == 0) {
                UserAppNoti::findOrFail($user_noti_id)->delete();
            }

            if(AdminAppNoti::findOrFail($admin_noti_id)->appointments()->count() == 0) {
                AdminAppNoti::findOrFail($admin_noti_id)->delete();
            }
    
            // send mail to client if they gave email
            if ($email) {
                $emailData = [
                    'name' => $name,
                    'business_name' => Business::find(1)->name,
                    'time' => date('g:i A', strtotime($time)),
                    'date' => date('D, M d, Y', strtotime($date)),
                    'staff' => $staff_name,
                    'services' => $services,
                    'business_address' => Business::find(1)->address,
                    'business_phone' => Business::find(1)->phone_number
                ];

                try {
                    Mail::to($email)->send(new CancelAppointmentMail($emailData));
                } catch(Exception $e) {
                    
                }
            }
            return response([
                'message' => "Appointment canceled successfully!",
            ], 200);    // Success 200 code
        }  catch(Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    // return timeslots picked on that date
    public function SlottimeCheck(Request $request) {
        $date = $request->date;
        $all_slot_time = Appointment::where('date', $date)->select('time')->get();

        $picked_slot_time = [];
        foreach($all_slot_time as $item) {
            $picked_slot_time[] = date('g:i A', strtotime($item['time']));
        }

        return $picked_slot_time;
    }
}
