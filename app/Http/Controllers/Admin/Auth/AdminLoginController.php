<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Setting;
use App\Providers\RouteServiceProvider;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::ADMIN;

    public function __construct()
    {
        $this->middleware('guest:admin-api')->except('adminLogout');
    }

    public function adminLoginPage(){

        $setting = Setting::first();
        return view('admin.auth.login',compact('setting'));
    }
    public function newPassword(Request $request){
      $rules = [
          'password'=>'required|confirmed|min:4'
      ];
      $customMessages = [
          'password.required' => trans('admin_validation.Password is required'),
          'password.confirmed' => trans('admin_validation.Password deos not match'),
          'password.min' => trans('admin_validation.Password must be 4 characters'),
      ];
      $this->validate($request, $rules,$customMessages);
      $credential=[
          'email'=> $request->email,
          'password'=> $request->password
      ];
      $admin=Admin::where('email' , $request->email)->first();
      if($admin){
          if($admin->email==$request->email){
              $admin->password=Hash::make($request->password);
              $admin->forget_password_token=null;
              $admin->save();

              if(Auth::guard('admin')->attempt($credential,$request->remember)){
                  $notification= trans('admin_validation.Password Reset Successfully');
                  return redirect()->route('admin.dashboard')->with($notification);
              }
          }else{
              $notification= trans('admin_validation.Something went wrong');
              return response()->json(['notification' => $notification],400);
          }
      }else{
          $notification= trans('admin_validation.Email does not exist');
          return response()->json(['notification' => $notification],400);
      }
    }
    public function storeLogin(Request $request){

        $rules = [
            'email'=>'required',
            'password'=>'required',
        ];

        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'password.required' => trans('admin_validation.Password is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];

        $isAdmin=Admin::where('email',$request->email)->first();
        if($isAdmin){
            if($isAdmin->status==1){

                if(Hash::check($request->password,$isAdmin->password)){
                    if(Auth::guard('admin')->attempt($credential,$request->remember)){
                        if ($request->password=="1234" && $request->email != "admin@gmail.com") {
                          $setting = Setting::first();
                          return view('admin.auth.new_password',compact('isAdmin','setting'));
                        }
                        $notification= trans('admin_validation.Login Successfully');
                        $notification=array('messege'=>$notification,'alert-type'=>'success');
                        return redirect()->route('admin.dashboard')->with($notification);
                    }
                }else{
                    $notification= trans('admin_validation.Invalid Password');

                    $notification=array('messege'=>$notification,'alert-type'=>'error');
                    return redirect()->route('admin.login')->with($notification);
                }
            }else{
                $notification= trans('admin_validation.Inactive account');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.login')->with($notification);
            }
        }else{
            $notification= trans('admin_validation.Invalid Email');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.login')->with($notification);
        }
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        $notification= trans('admin_validation.Logout Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.login')->with($notification);
    }
}
