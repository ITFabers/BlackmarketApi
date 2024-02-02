<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Notification;
use File;
use Illuminate\Http\Request;
use Image;
use Str;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = Category::get();


        return view('admin.product_category',compact('categories'));

    }


    public function create()
    {
        $categories = Category::get();

        return view('admin.create_product_category',compact('categories'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name'=>'required|unique:categories',
            'slug'=>'required|unique:categories',
            'icon'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $category = new Category();

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status??0;
        $category->icon = $request->icon;
        $category->parent_id = $request->parent_id??0;
        $category->save();

        if($request->image){
            $extention = $request->image->getClientOriginalExtension();
            $logo_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $logo_name = 'uploads/custom-images/'.$logo_name;
            Image::make($request->image)
                ->save(public_path().'/'.$logo_name);
            $category->image=$logo_name;
            $category->save();
        }
        $adminsWithType1 = Admin::where('admin_type', 1)->get();

        foreach ($adminsWithType1 as $admin) {
            $notification = new Notification([
                'admin_id' => $admin->id,
                'message' => 'Created new category'.' '.$category->name,
                'link' => '',
            ]);
            $notification->save();
        }
        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }


    public function show($id){
        $category = Category::find($id);
        return response()->json(['category' => $category],200);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $categories = Category::get();
        return view('admin.edit_product_category',compact('category','categories'));
    }


    public function update(Request $request,$id)
    {
        $category = Category::find($id);
        $rules = [
            'name'=>'required|unique:categories,name,'.$category->id,
            'slug'=>'required|unique:categories,name,'.$category->id,
            'icon'=>'required'
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status??0;
        $category->parent_id = $request->parent_id??0;
        $category->save();

        if($request->image){
            $old_logo = $category->image;
            $extention = $request->image->getClientOriginalExtension();
            $logo_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $logo_name = 'uploads/custom-images/'.$logo_name;
            Image::make($request->image)
                ->save(public_path().'/'.$logo_name);
            $category->image=$logo_name;
            $category->save();

            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $old_logo = $category->image;
        $category->delete();

        if($old_logo){
            if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product-category.index')->with($notification);
    }

    public function changeStatus($id){
        $category = Category::find($id);
        if($category->status==1){
            $category->status=0;
            $category->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $category->status=1;
            $category->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

}
