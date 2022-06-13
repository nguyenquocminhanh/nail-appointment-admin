<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use Image;
use Illuminate\Support\Carbon;

class BusinessController extends Controller
{
    public function BusinessAll() {
        $business = Business::find(1);
        return view('admin.business.business_all', compact('business'));
    }

    public function BusinessAdd() {
        return view('admin.business.business_add');
    }

    public function BusinessStore(Request $request) {
        $logo_image = $request->file('logo_image');
        $name_gen_logo = hexdec(uniqid()).'.'.$logo_image->getClientOriginalExtension();
        Image::make($logo_image)->resize(100, 100)->save('upload/business/logo/'.$name_gen_logo);
        $save_url_logo = 'http://127.0.0.1:8001/upload/business/logo/'.$name_gen_logo;

        $cover_image = $request->file('cover_image');
        $name_gen_cover = hexdec(uniqid()).'.'.$cover_image->getClientOriginalExtension();
        Image::make($cover_image)->resize(500, 200)->save('upload/business/cover/'.$name_gen_cover);
        $save_url_cover = 'http://127.0.0.1:8001/upload/business/cover/'.$name_gen_cover;

        Business::insert([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'logo_image' => $save_url_logo,
            'cover_image' => $save_url_cover,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Business Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('business')->with($notification);
    }

    public function BusinessEdit() {
        $business = Business::find(1);
        return view('admin.business.business_edit', compact('business'));
    }

    public function BusinessUpdate(Request $request) {
        if ($request->file('logo_image')) {
            $business = Business::find(1);
            $img_logo = $business->logo_image;
            unlink($img_logo);

            $logo_image = $request->file('logo_image');
            $name_gen_logo = hexdec(uniqid()).'.'.$logo_image->getClientOriginalExtension();
            Image::make($logo_image)->resize(100, 100)->save('upload/business/logo/'.$name_gen_logo);
            $save_url_logo = 'http://127.0.0.1:8001/upload/business/logo/'.$name_gen_logo;

            if ($request->file('cover_image')) {
                $img_cover = $business->cover_image;
                unlink($img_cover);

                $cover_image = $request->file('cover_image');
                $name_gen_cover = hexdec(uniqid()).'.'.$cover_image->getClientOriginalExtension();
                Image::make($cover_image)->resize(500, 200)->save('upload/business/cover/'.$name_gen_cover);
                $save_url_cover = 'http://127.0.0.1:8001/upload/business/cover/'.$name_gen_cover;

                Business::find(1)->update([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'logo_image' => $save_url_logo,
                    'cover_image' => $save_url_cover,
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                Business::find(1)->update([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'logo_image' => $save_url_logo,
                    'updated_at' => Carbon::now(),
                ]);
            }

            $notification = array(
                'message' => 'Business Updated With Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('business')->with($notification);
        } else {
            Business::find(1)->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Business Updated Without Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('business')->with($notification);
        }
    }


    // API
    public function BusinessInfo() {
        $result = Business::find(1);
        return $result;
    }
}
