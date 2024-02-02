<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use File;
use Illuminate\Http\Request;
use Image;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function headerPhoneNumber(){
        $setting = Setting::select('topbar_phone','topbar_email')->first();

        return response()->json(['setting' => $setting], 200);
    }

    public function updateHeaderPhoneNumber(Request $request){
        $rules = [
            'topbar_phone'=>'required',
            'topbar_email'=>'required',
        ];
        $customMessages = [
            'topbar_phone.required' => trans('admin_validation.Topbar phone is required'),
            'topbar_email.required' => trans('admin_validation.Topbar email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting->topbar_phone = $request->topbar_phone;
        $setting->topbar_email = $request->topbar_email;
        $setting->save();

        $notification= trans('admin_validation.Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function productProgressbar(){
        $setting = Setting::select('show_product_progressbar')->first();
        return response()->json(['setting' => $setting], 200);
    }


    public function updateProductProgressbar(){
        $setting = Setting::first();
        if($setting->show_product_progressbar == 1){
            $setting->show_product_progressbar = 0;
            $setting->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $setting->show_product_progressbar = 1;
            $setting->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function image_content(){
        $image_content = Setting::select('empty_cart','empty_wishlist', 'change_password_image','login_image','error_page')->first();
        return view('admin.image_content', compact('image_content'));
    }

    public function updateImageContent(Request $request){
        $image_content = Setting::first();
        if($request->empty_cart){
            $existing_banner = $image_content->empty_cart;
            $extention = $request->empty_cart->getClientOriginalExtension();
            $banner_name = 'empty_cart'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->empty_cart)
                ->save(public_path().'/'.$banner_name);
            $image_content->empty_cart = $banner_name;
            $image_content->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        if($request->empty_wishlist){
            $existing_banner = $image_content->empty_wishlist;
            $extention = $request->empty_wishlist->getClientOriginalExtension();
            $banner_name = 'empty_wishlist'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->empty_wishlist)
                ->save(public_path().'/'.$banner_name);
            $image_content->empty_wishlist = $banner_name;
            $image_content->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        if($request->change_password_image){
            $existing_banner = $image_content->change_password_image;
            $extention = $request->change_password_image->getClientOriginalExtension();
            $banner_name = 'change_password_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->change_password_image)
                ->save(public_path().'/'.$banner_name);
            $image_content->change_password_image = $banner_name;
            $image_content->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }



        if($request->login_image){
            $existing_banner = $image_content->login_image;
            $extention = $request->login_image->getClientOriginalExtension();
            $banner_name = 'login_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->login_image)
                ->save(public_path().'/'.$banner_name);
            $image_content->login_image = $banner_name;
            $image_content->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        if($request->error_page){
            $existing_banner = $image_content->error_page;
            $extention = $request->error_page->getClientOriginalExtension();
            $banner_name = 'error_page'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->error_page)
                ->save(public_path().'/'.$banner_name);
            $image_content->error_page = $banner_name;
            $image_content->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
