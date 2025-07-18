<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;
use File;
use Hash;
use Illuminate\Http\Request;
use Image;
use Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $admins = Admin::orderBy('id','asc')->get();
        return view('admin.admin', compact('admins'));

    }

    public function create(){
        $logedInAdmin = Auth::guard('admin')->user();
        if($logedInAdmin->admin_type == 1){
            return view('admin.create_admin');
        }else return abort(404);


    }

    public function store(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required|min:4',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'password.required' => trans('admin_validation.Password is required'),
            'password.min' => trans('admin_validation.Password Must be 4 characters'),
        ];
        $this->validate($request, $rules,$customMessages);

        $admin = new Admin();
        $admin->name =$request->name;
        $admin->email =$request->email;
        $admin->status =$request->status;
        $admin->password =Hash::make($request->status);
        $admin->save();

        $notification = trans('admin_validation.Create Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
    public function edit($id)
    {

        $admin = Admin::find($id);
        return view('admin.edit_admin',compact('admin'));

    }
    public function update(Request $request, $id){
        $admin = Admin::find($id);
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email,'. $admin->id,
            'password' => 'required|min:4',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'password.required' => trans('admin_validation.Password is required'),
            'password.min' => trans('admin_validation.Password Must be 4 characters'),
        ];
        $this->validate($request, $rules,$customMessages);
        if($request->file('image')){
            $old_image=$admin->image;
            $user_image=$request->image;
            $extention=$user_image->getClientOriginalExtension();
            $image_name= Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;

            Image::make($user_image)
                ->save(public_path().'/'.$image_name);

            $admin->image=$image_name;
            $admin->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }


        $admin->name =$request->name;
        $admin->email =$request->email;
        $admin->status =$request->status;
        $admin->password =Hash::make($request->password);
        $admin->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function show($id){
        $admin = Admin::find($id);
        return response()->json(['admin' => $admin], 200);
    }

    public function destroy($id){
        $admin = Admin::find($id);
        $old_image = $admin->image;
        $admin->delete();
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function changeStatus($id){
        $admin = Admin::find($id);
        if($admin->status == 1){
            $admin->status = 0;
            $admin->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $admin->status = 1;
            $admin->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
