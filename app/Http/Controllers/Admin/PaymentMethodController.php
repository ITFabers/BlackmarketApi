<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankPayment;
use App\Models\Setting;
use File;
use Illuminate\Http\Request;
use Image;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $bank = BankPayment::first();

        $setting = Setting::first();

        return view('admin.payment_method', compact('bank','setting'));
    }

    public function updateCashOnDelivery(Request $request){
        $bank = BankPayment::first();
        if($bank->cash_on_delivery_status==1){
            $bank->cash_on_delivery_status=0;
            $bank->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $bank->cash_on_delivery_status=1;
            $bank->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
