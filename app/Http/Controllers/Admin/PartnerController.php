<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use File;
use Illuminate\Http\Request;
use Image;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $partners = Partner::all();
        return view('admin.partner', compact('partners'));
    }

    public function create(){
        return view('admin.create_partner');
    }

    public function store(Request $request){
        $rules = [
            'image' => 'required',
            'status' => 'required',
            'serial' => 'required',
        ];
        $customMessages = [
            'image.required' => trans('admin_validation.Image is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $partner = new Partner();
        if($request->image){
            $extention = $request->image->getClientOriginalExtension();
            $partner_image = 'partner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $partner_image = 'uploads/custom-images/'.$partner_image;
            Image::make($request->image)
                ->save(public_path().'/'.$partner_image);
            $partner->image = $partner_image;
        }

        $partner->serial = $request->serial;
        $partner->status = $request->status;
        $partner->title = $request->title;
        $partner->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function show($id){
        $partner = Partner::find($id);
        return response()->json(['partner' => $partner], 200);
    }

    public function edit($id){
        $partner = Partner::find($id);
        return view('admin.edit_partner', compact('partner'));
    }

    public function update(Request $request, $id){
        $rules = [
            'status' => 'required',
            'serial' => 'required',

        ];
        $customMessages = [
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $partner = Partner::find($id);
        if($request->image){
            $existing_partner = $partner->image;
            $extention = $request->image->getClientOriginalExtension();
            $partner_image = 'partner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $partner_image = 'uploads/custom-images/'.$partner_image;
            Image::make($request->image)
                ->save(public_path().'/'.$partner_image);
            $partner->image = $partner_image;
            $partner->save();
            if($existing_partner){
                if(File::exists(public_path().'/'.$existing_partner))unlink(public_path().'/'.$existing_partner);
            }
        }

        $partner->title = $request->title;
        $partner->serial = $request->serial;
        $partner->status = $request->status;
        $partner->save();

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.partner.index')->with($notification);
    }

    public function destroy($id){
        $partner = Partner::find($id);
        $existing_partner = $partner->image;
        $partner->delete();
        if($existing_partner){
            if(File::exists(public_path().'/'.$existing_partner))unlink(public_path().'/'.$existing_partner);
        }

        $notification= trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function changeStatus($id){
        $partner = Partner::find($id);
        if($partner->status==1){
            $partner->status=0;
            $partner->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $partner->status=1;
            $partner->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }


}
