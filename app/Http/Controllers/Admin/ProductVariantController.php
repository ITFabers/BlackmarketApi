<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\ShoppingCartVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $variants = ProductVariant::with('variantItems')->get();
        if($variants){
            return view('admin.variant',compact('variants'));
        }else{
            $notification = trans('admin_validation.Something went wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.product.index')->with($notification);
        }

    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $variant = new ProductVariant();
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function update(Request $request,$id){

        $rules = [
            'name' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $variant = ProductVariant::find($id);
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();

        ProductVariantItem::where('product_variant_id',$variant->id)->update(['product_variant_name' => $variant->name]);

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id)
    {
        $variant = ProductVariant::find($id);
        $variant->delete();

        ShoppingCartVariant::where('variant_id', $id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function changeStatus($id){
        $variant = ProductVariant::find($id);
        if($variant->status == 1){
            $variant->status = 0;
            $variant->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $variant->status = 1;
            $variant->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function show($id){
        $variant = ProductVariant::find($id);
        return response()->json(['variant' => $variant],200);
    }
}
