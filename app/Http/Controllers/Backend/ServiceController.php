<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceUser;
use App\Models\User;

class ServiceController extends Controller
{
    public function ServiceAll() {
        $services = Service::all();
        return view('admin.service.service_all', compact('services'));
    }

    public function ServiceAdd() {
        return view('admin.service.service_add');
    }

    public function ServiceStore(Request $request) {
        $request->validate([
            'name' => 'unique:services',
        ]);

        $data = $request->except(['_token']);
        Service::insert($data);
        $notification = array(
            'message' => 'Service Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('service.all')->with($notification);
    }

    public function ServiceEdit($id) {
        $service = Service::findOrFail($id);
        return view('admin.service.service_edit', compact('service'));
    }

    public function ServiceUpdate(Request $request) {
        $request->validate([
            'name' => 'unique:services,name,'.$request->service_id,
        ], [
            'name.unique' => 'This service has already existed'
        ]);

        $service = Service::findOrFail($request->service_id);
        $data = $request->except(['_token', 'service_id']);
        $service->update($data);
        
        $notification = array(
            'message' => 'Service Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('service.all')->with($notification);
    }

    public function ServiceDelete($id) {
        $service = Service::findOrFail($id);
        $services_user = ServiceUser::where('service_id', $id)->get();

        $services_user->each->delete();
        $service->delete();

        $notification = array(
            'message' => 'Service Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('service.all')->with($notification);
    }

    // API
    public function AllService() {
        $result = Service::all();
        return $result;
    }

    public function ServiceByStaff(Request $request) {
        $staff_id = $request->staffID;
        $staff = User::findOrFail($staff_id);
        $services = $staff->services;
        return $services;
    }
}
