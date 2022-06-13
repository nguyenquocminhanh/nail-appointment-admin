<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\StaffDay;
use App\Models\StaffTime;
use App\Models\AdminUpdateNoti;
use Image;
use Auth;
use DB;
use Carbon\Carbon;

class StaffProfileController extends Controller
{
    public function ViewMyProfile() {
        $staff = Auth::user();
        return view('staff.profile.profile_view', compact('staff'));
    }

    public function EditMyProfile() {
        $staff = Auth::user();

        $all_services = Service::all();
        // relation many
        $services_user = $staff->services()->get();
        $staff_days = $staff->staff_days()->get();

        return view('staff.profile.profile_edit', compact('staff', 'all_services', 'services_user', 'staff_days'));
    }

    public function UpdateMyProfile(Request $request) {
        $request->validate([
            'email' => 'unique:users,email,'.$request->staff_id,
            'username' => 'required|unique:users,username,'.$request->staff_id,
            'to_time.*' => 'nullable|after:from_time.*',
        ], [
            'to_time.*.after' => 'To time must be a time after from time'
        ]);

        $role_id = Role::where('name', 'staff')->first()->id;
        $staff = Auth::user();
        
        if($request->file('profile_image')) {
            $image = $request->file('profile_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200, 200)->save('upload/user/'.$name_gen);
            $save_url = 'upload/user/'.$name_gen;
            // remove old image
            if ($staff->profile_image) {
                unlink($staff->profile_image);
            }
            $staff->profile_image = $save_url;

            // send noti to admin
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'profile image',
                'created_at' => Carbon::now()
            ]);
        }

        $staff_password = $staff->password;
        if($request->password) {
            $staff->password = bcrypt($request->password);     // new password
            // send noti to admin
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'password',
                'created_at' => Carbon::now()
            ]);
        } else {
            $staff->password = $staff_password;     // old password
        }

        // send noti to admin
        if ($request->name !== $staff->name) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'name',
                'created_at' => Carbon::now()
            ]);
        } 
        if ($request->email !== $staff->email) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'email',
                'created_at' => Carbon::now()
            ]);
        }
        if ($request->username !== $staff->username) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'user name',
                'created_at' => Carbon::now()
            ]);
        }
        if ($request->phone_number !== $staff->phone_number) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'phone number',
                'created_at' => Carbon::now()
            ]);
        }

        if ($request->service_id != ServiceUser::where('user_id', $staff->id)->pluck('service_id')->toArray()) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'service',
                'created_at' => Carbon::now()
            ]);
        }


        if ($request->visible) {
            $visible = $request->visible;
        } else {
            $visible = '0';
        }

        if ($visible != $staff->visible) {
            AdminUpdateNoti::insert([
                'user_id' => $staff->id,
                'update_thing' => 'visible status',
                'created_at' => Carbon::now()
            ]);
        }
        // dd($request->from_time, StaffDay::where('user_id', $staff->id)->pluck('day_of_week')->toArray());

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
                    $arr_timeslots = []; // clone for send noti to admin
                    foreach($staff_days as $staff_day) {
                        $staff_day_id = $staff_day->id;
                        $staff_times = StaffTime::where('staff_day_id', $staff_day_id)->get();
                         // clone for send noti to admin
                        foreach($staff_times as $item) {
                            $arr_timeslots[] = $item['time'];
                        }
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
                    // clone for send noti to admin
                    $new_arr_timeslots = [];
                    foreach(StaffDay::where('user_id', $staff->id)->get() as $staff_day) {
                        $staff_day_id = $staff_day->id;
                        $staff_times = StaffTime::where('staff_day_id', $staff_day_id)->get();
                        foreach($staff_times as $item) {
                            $new_arr_timeslots[] = $item['time'];
                        }
                    }
                    if($arr_timeslots != $new_arr_timeslots) {
                        // send noti to admin
                        AdminUpdateNoti::insert([
                            'user_id' => $staff->id,
                            'update_thing' => 'working time',
                            'created_at' => Carbon::now()
                        ]);
                    }
                }
            }
        });

        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('my.profile')->with($notification);
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
}
