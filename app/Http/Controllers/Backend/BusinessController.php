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
        $business = Business::first();
        return view('admin.business.business_all', compact('business'));
    }

    public function BusinessAdd() {
        return view('admin.business.business_add');
    }

    public function BusinessStore(Request $request) {
        $file = $request->file('logo_image');
        $name_gen_logo = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
        $logo_img = Image::make($file);
        $logo_img->resize(128, 128, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resource = $logo_img->stream()->detach();
        $folder = 'images/business/';

        $path = \Storage::disk('s3')->put(
            // location and file name to save
            $folder . $name_gen_logo,
            // file
            $resource
        );
        $path = \Storage::disk('s3')->url($path);

        // cover image
        $name_gen_cover = hexdec(uniqid()).'.'.$request->file('cover_image')->getClientOriginalExtension();
        $cover_img = Image::make($request->file('cover_image'));
        $cover_img->resize(1025, 158, function ($constraint) {
            $constraint->aspectRatio();
        });

        $resource_cover = $cover_img->stream()->detach();
   
        $path_cover = \Storage::disk('s3')->put(
            // location and file name to save
            $folder . $name_gen_cover,
            // file
            $resource_cover
        );
        $path_cover = \Storage::disk('s3')->url($path_cover);

        Business::insert([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'logo_image' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen_logo,
            'cover_image' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen_cover,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Business Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('business')->with($notification);
    }

    public function BusinessEdit() {
        $business = Business::first();
        return view('admin.business.business_edit', compact('business'));
    }

    public function BusinessUpdate(Request $request) {
        if ($request->file('logo_image')) {
            $business = Business::first();

            $file = $request->file('logo_image');
            $name_gen_logo = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            $logo_img = Image::make($file);
            $logo_img->resize(128, 128, function ($constraint) {
                $constraint->aspectRatio();
            });
            $resource = $logo_img->stream()->detach();
            $folder = 'images/business/';

            $path = \Storage::disk('s3')->put(
                // location and file name to save
                $folder . $name_gen_logo,
                // file
                $resource
            );
            $path = \Storage::disk('s3')->url($path);

            if ($request->file('cover_image')) {
                // cover image
                $name_gen_cover = hexdec(uniqid()).'.'.$request->file('cover_image')->getClientOriginalExtension();
                $cover_img = Image::make($request->file('cover_image'));
                $cover_img->resize(1025, 158, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $resource_cover = $cover_img->stream()->detach();

                $path_cover = \Storage::disk('s3')->put(
                    // location and file name to save
                    $folder . $name_gen_cover,
                    // file
                    $resource_cover
                );
                $path_cover = \Storage::disk('s3')->url($path_cover);

                Business::first()->update([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'logo_image' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen_logo,
                    'cover_image' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen_cover,
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                Business::first()->update([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'logo_image' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com/'.$folder.$name_gen_logo,
                    'updated_at' => Carbon::now(),
                ]);
            }

            $notification = array(
                'message' => 'Business Updated With Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('business')->with($notification);
        } else {
            Business::first()->update([
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
        $result = Business::first();
        return $result;
    }
}
