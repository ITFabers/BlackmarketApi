<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use File;
use Illuminate\Http\Request;
use Image;
use Mail;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $customers = User::with('city','state', 'country')->orderBy('id','desc')->where('status',1)->get();
        $orders = Order::all();

        return view('admin.customer', compact('customers','orders'));
    }

    public function pendingCustomerList(){
        $customers = User::with('city','state', 'country')->orderBy('id','desc')->where('status',0)->get();
        $orders = Order::all();

        return view('admin.customer', compact('customers','orders'));

    }

    public function show($id){
        $customer = User::with('city','state', 'country')->find($id);
        if($customer){
            return view('admin.show_customer',compact('customer'));

        }else{
            $notification= trans('admin_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.customer-list')->with($notification);
        }

    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user_image = $user->image;
        $user->delete();
        if($user_image){
            if(File::exists(public_path().'/'.$user_image))unlink(public_path().'/'.$user_image);
        }
        Address::where('user_id',$id)->delete();
        Wishlist::where('user_id',$id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function changeStatus($id){
        $customer = User::find($id);
        if($customer->status == 1){
            $customer->status = 0;
            $customer->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $customer->status = 1;
            $customer->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function sendEmailToAllUser(){
        return view('admin.send_email_to_all_customer');
    }

    public function sendMailToAllUser(Request $request){
        $rules = [
            'subject'=>'required',
            'message'=>'required'
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $users = User::where('status',1)->get();
        MailHelper::setMailConfig();
        foreach($users as $user){
            Mail::to($user->email)->send(new SendSingleSellerMail($request->subject,$request->message));
        }

        $notification = trans('admin_validation.Email Send Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function sendMailToSingleUser(Request $request, $id){
        $rules = [
            'subject'=>'required',
            'message'=>'required'
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::find($id);
        MailHelper::setMailConfig();
        Mail::to($user->email)->send(new SendSingleSellerMail($request->subject,$request->message));

        $notification = trans('admin_validation.Email Send Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
