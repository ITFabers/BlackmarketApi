<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use File;
use Illuminate\Http\Request;
use Image;

class HomePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function homepage_section_content(){

        $setting = Setting::first();
        $sections = json_decode($setting->homepage_section_title);
        return view('admin.homepage_section_title', compact('sections'));
    }

    public function update_homepage_section_content(Request $request){

        $sections = array();
        foreach($request->customs as $index => $custom){
            $item = (object) array(
                'key' => $request->keys[$index],
                'default' => $request->defaults[$index],
                'custom' => $request->customs[$index],
            );

            $sections[] = $item;
        }

        $sections = json_encode($sections);

        $setting = Setting::first();
        $setting->homepage_section_title = $sections;
        $setting->save();


        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
