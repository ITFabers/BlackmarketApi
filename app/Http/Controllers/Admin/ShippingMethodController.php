<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Setting;
use App\Models\Shipping;
use Exception;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $shippings = Shipping::with('city')->orderBy('id','asc')->get();
        $setting = Setting::first();
        $cities = City::where('status',1)->orderBy('name','asc')->get();
        return view('admin.shipping_method', compact('shippings','setting','cities'));
    }

    public function create(){
        $cities = City::where('status',1)->orderBy('name','asc')->get();
        return response()->json(['cities' => $cities], 200);
    }

    public function store(Request $request){
        $rules = [
            'city_id' => 'required',
            'shipping_rule' => 'required',
            'type' => 'required',
            'shipping_fee' => 'required|numeric',
            'condition_from' => 'required|numeric',
            'condition_to' => 'required|numeric',
        ];
        $customMessages = [
            'city_id.required' => trans('admin_validation.City is required'),
            'shipping_rule.required' => trans('admin_validation.Shipping rule is required'),
            'type.required' => trans('admin_validation.Type is required'),
            'shipping_fee.required' => trans('admin_validation.Shipping fee is required'),
            'condition_from.required' => trans('admin_validation.Condition from is required'),
            'condition_to.required' => trans('admin_validation.Condition to is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $shipping = new Shipping();
        $shipping->city_id = $request->city_id;
        $shipping->shipping_rule = $request->shipping_rule;
        $shipping->type = $request->type;
        $shipping->shipping_fee = $request->shipping_fee;
        $shipping->condition_from = $request->condition_from;
        $shipping->condition_to = $request->condition_to;
        $shipping->save();

        $notification=trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.shipping.index')->with($notification);
    }

    public function show($id){
        $shipping = Shipping::find($id);
        $setting = Setting::first();
        $cities = City::where('status',1)->orderBy('name','asc')->get();
        return view('admin.edit_shipping', compact('shipping','setting','cities'));
    }

    public function cityWiseShipping($city_id){
        $shippings = Shipping::with('city')->where('city_id', $city_id)->get();
        return response()->json(['shippings' => $shippings], 200);
    }


    public function edit($id){
        $setting = Setting::first();
        return view('admin.edit_shipping', compact('setting'));
    }



    public function update(Request $request, $id){

        $rules = [
            'shipping_rule' => 'required',
            'type' => 'required',
            'shipping_fee' => 'required|numeric',
            'condition_from' => 'required|numeric',
            'condition_to' => 'required|numeric',
        ];
        $customMessages = [
            'shipping_rule.required' => trans('admin_validation.Shipping rule is required'),
            'type.required' => trans('admin_validation.Type is required'),
            'shipping_fee.required' => trans('admin_validation.Shipping fee is required'),
            'condition_from.required' => trans('admin_validation.Condition from is required'),
            'condition_to.required' => trans('admin_validation.Condition to is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $shipping = Shipping::find($id);
        $shipping->shipping_rule = $request->shipping_rule;
        $shipping->type = $request->type;
        $shipping->shipping_fee = $request->shipping_fee;
        $shipping->condition_from = $request->condition_from;
        $shipping->condition_to = $request->condition_to;
        $shipping->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.shipping.index')->with($notification);

    }

    public function destroy($id){
        $shipping = Shipping::find($id);
        $shipping->delete();
        $notification=trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.shipping.index')->with($notification);
    }
}
