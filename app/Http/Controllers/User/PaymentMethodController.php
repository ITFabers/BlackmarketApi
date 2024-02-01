<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankPayment;
use App\Models\CurrencyCountry;
use App\Models\Currency;
use App\Models\Setting;
use Image;
use File;
use Exception;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $bank = BankPayment::first();
        $countires = CurrencyCountry::orderBy('name','asc')->get();
        $currencies = Currency::orderBy('name','asc')->get();
        $setting = Setting::first();
        return view('admin.payment_method', compact('bank','countires','currencies','setting'));
    }

    public function updateCashOnDelivery(Request $request){
        $bank = BankPayment::first();
        if($bank->cash_on_delivery_status==1){
            $bank->cash_on_delivery_status=0;
            $bank->save();
            $message= trans('user_validation.Inactive Successfully');
        }else{
            $bank->cash_on_delivery_status=1;
            $bank->save();
            $message= trans('user_validation.Active Successfully');
        }
        return response()->json($message);
    }

}
