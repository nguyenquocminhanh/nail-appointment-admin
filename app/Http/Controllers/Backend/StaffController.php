<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\StaffDay;
use App\Models\StaffTime;
use App\Models\Appointment;
use App\Models\AdminUpdateNoti;
use Image;
use DB;

class StaffController extends Controller
{
    public function StaffAll() {
        // get staff only
        $role_id = Role::where('name', 'staff')->first()->id;
        $staffs = User::orderBy('name', 'ASC')->where('role_id', $role_id)->get();

        return view('admin.staff.staff_all', compact('staffs'));
    }

    public function StaffAdd() {
        $services = Service::all();
        return view('admin.staff.staff_add', compact('services'));
    }

    public function StaffStore(Request $request) {
        $request->validate([
            'email' => ['unique:users'],
            'username' => ['unique:users'],
            'to_time.*' => 'nullable|after:from_time.*',
        ], [
            'to_time.*.after' => 'To time must be a time after from time'
        ]);

        $role_id = Role::where('name', 'staff')->first()->id;
        $staff = new User();

        if ($request->file('profile_image')) {
            $file = $request->file('profile_image');
            $name_gen = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $img = Image::make($file);
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resource = $img->stream()->detach();
            $folder = 'images/staff/';

            $path = \Storage::disk('s3')->put(
                // location and file name to save
                $folder . $name_gen,
                // file
                $resource
            );
            $path = \Storage::disk('s3')->url($path);

            $staff->profile_image = 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen;
        }

        if ($request->visible) {
            $visible = '1';
        } else {
            $visible = '0';
        }

        $staff->name = $request->name;
        $staff->username = $request->username;
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->phone_number = $request->phone_number;
        $staff->role_id = $role_id;
        $staff->visible = $visible;

        DB::transaction(function() use($request, $staff){
            if($staff->save()) {
                if($request->service_id) {
                    // add service via service_id[]
                    foreach($request->service_id as $key => $service_id) {
                        ServiceUser::insert([
                            'user_id' => $staff->id,
                            'service_id' => $service_id
                        ]);
                    }
                }

                if($request->day) {
                    // add working day and hour via day[]
                    foreach($request->day as $key_day) {
                        // add to StaffDay
                        $staff_day = new StaffDay();
                        $staff_day->user_id = $staff->id;
                        $staff_day->day_of_week = $this->keyToDay($key_day);
                        $staff_day->from_time = date('g:i A', strtotime($request->from_time[$key_day]));
                        $staff_day->to_time = date('g:i A', strtotime($request->to_time[$key_day]));;

                        DB::transaction(function() use($request, $staff_day, $key_day) {
                            if ($staff_day->save()) {
                                $from_time = strtotime($request->from_time[$key_day]);
                                $to_time = strtotime($request->to_time[$key_day]);

                                // initialize timeslot array
                                $timeslots[] = date('g:i A', $from_time);

                                // generate time slots
                                while ($from_time < strtotime('-15 minutes', $to_time)) {
                                    $from_time = strtotime('+15 minutes', $from_time);
                                    // add to timeslot array
                                    $timeslots[] = date('g:i A', $from_time);
                                }

                                // add timeslots to StaffTime
                                foreach ($timeslots as $slot) {
                                    StaffTime::insert([
                                        'staff_day_id' => $staff_day->id,
                                        'time' => $slot
                                    ]);
                                }
                            }
                        });
                    }
                }
            }
        });

        $notification = array(
            'message' => 'Staff Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('staff.all')->with($notification);
    }

    public function StaffEdit($id) {
        $staff = User::findOrFail($id);
        $all_services = Service::all();
        // relation many
        $services_user = User::findOrFail($id)->services()->get();
        $staff_days = User::findOrFail($id)->staff_days()->get();

        return view('admin.staff.staff_edit', compact('staff', 'all_services', 'services_user', 'staff_days'));
    }

    public function StaffUpdate(Request $request) {
        $request->validate([
            'email' => 'unique:users,email,'.$request->staff_id,
            'username' => 'required|unique:users,username,'.$request->staff_id,
            'to_time.*' => 'nullable|after:from_time.*',
        ], [
            'to_time.*.after' => 'To time must be a time after from time'
        ]);

        $role_id = Role::where('name', 'staff')->first()->id;
        $staff = User::findOrFail($request->staff_id);
        
        if($request->file('profile_image')) {
            $file = $request->file('profile_image');
            $name_gen = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $img = Image::make($file);
            $img->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resource = $img->stream()->detach();
            $folder = 'images/staff/';

            $path = \Storage::disk('s3')->put(
                // location and file name to save
                $folder . $name_gen,
                // file
                $resource
            );
            $path = \Storage::disk('s3')->url($path);

            $staff->profile_image = 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen;
        }

        $staff_password = $staff->password;
        if($request->password) {
            $staff->password = bcrypt($request->password);     // new password
        } else {
            $staff->password = $staff_password;     // old password
        }

        if ($request->visible) {
            $visible = '1';
        } else {
            $visible = '0';
        }

        $staff->name = $request->name;
        $staff->username = $request->username;
        $staff->email = $request->email;
        $staff->phone_number = $request->phone_number;
        $staff->role_id = $role_id;
        $staff->visible = $visible;

        DB::transaction(function() use($request, $staff){
            if($staff->save()) {
                // delete all
                ServiceUser::where('user_id', $staff->id)->delete();

                if($request->service_id) {
                    // add again
                    foreach($request->service_id as $key => $service_id) {
                        ServiceUser::insert([
                            'user_id' => $staff->id,
                            'service_id' => $service_id
                        ]);
                    }
                }

                if($request->day) {
                    // add working day and hour via day[]
                    // delete all old datas
                    $staff_days = StaffDay::where('user_id', $staff->id)->get();
                    foreach($staff_days as $staff_day) {
                        $staff_day_id = $staff_day->id;
                        $staff_times = StaffTime::where('staff_day_id', $staff_day_id)->get();
                        $staff_times->each->delete();
                        $staff_day->delete();
                    }

                    foreach($request->day as $key_day) {
                        // add to StaffDay
                        $staff_day = new StaffDay();
                        $staff_day->user_id = $staff->id;
                        $staff_day->day_of_week = $this->keyToDay($key_day);
                        $staff_day->from_time = date('g:i A', strtotime($request->from_time[$key_day]));
                        $staff_day->to_time = date('g:i A', strtotime($request->to_time[$key_day]));;

                        DB::transaction(function() use($request, $staff_day, $key_day) {
                            if ($staff_day->save()) {
                                $from_time = strtotime($request->from_time[$key_day]);
                                $to_time = strtotime($request->to_time[$key_day]);

                                // initialize timeslot array
                                $timeslots[] = date('g:i A', $from_time);

                                // generate time slots
                                while ($from_time < strtotime('-15 minutes', $to_time)) {
                                    $from_time = strtotime('+15 minutes', $from_time);
                                    // add to timeslot array
                                    $timeslots[] = date('g:i A', $from_time);
                                }

                                // add timeslots to StaffTime
                                foreach ($timeslots as $slot) {
                                    StaffTime::insert([
                                        'staff_day_id' => $staff_day->id,
                                        'time' => $slot
                                    ]);
                                }
                            }
                        });
                    }
                }
            }
        });

        $notification = array(
            'message' => 'Staff Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('staff.all')->with($notification);
    }

    public function StaffView($id) {
        $staff = User::findOrFail($id);
        return view('admin.staff.staff_view', compact('staff'));
    }

    public function StaffDelete($id) {
        $staff = User::findOrFail($id);
        $services_user = ServiceUser::where('user_id', $id)->get();
        $staff_days = StaffDay::where('user_id', $id)->get();
        foreach($staff_days as $staff_day) {
            $staff_day_id = $staff_day->id;
            $staff_times = StaffTime::where('staff_day_id', $staff_day_id)->get();
            $staff_times->each->delete();
            $staff_day->delete();
        }

        $services_user->each->delete();

        $appointments = Appointment::where('user_id', $id)->get();
        $appointments->each->delete();

        $notifications = AdminUpdateNoti::where('user_id', $id)->get();
        $notifications->each->delete();

        $staff->delete();
        $notification = array(
            'message' => 'Staff Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('staff.all')->with($notification);
    }

    // utility function
    public function keyToDay($key) {
        $day = "Monday";
        switch ($key) {
            case 0:
                $day = "Monday";
                break;
            case 1:
                $day = "Tuesday";
                break;
            case 2:
                $day = "Wednesday";
                break;
            case 3:
                $day = "Thursday";
                break;
            case 4:
                $day = "Friday";
                break;
            case 5:
                $day = "Saturday";
                break;
            case 6:
                $day = "Sunday";
                break;
        }
        return $day;
    }


    // API
    public function GetStaffAll() {
        $role_id = Role::where('name', 'staff')->first()->id;
        $staffs = User::where('role_id', $role_id)->where('visible', '1')->get();
        return $staffs;
    }

    public function StaffByService(Request $request) {
        $service_id = $request->serviceID;
        $staffs = Service::findOrFail($service_id)->users()->where('visible', '1')->get();
        return $staffs;
    }

    public function TimeslotsByStaff(Request $request) {
        $results = User::findOrFail($request->staffID)->staff_days()->where('day_of_week', $request->dayOfWeek)->with('staff_times')->get();
        return $results;
    }
}
