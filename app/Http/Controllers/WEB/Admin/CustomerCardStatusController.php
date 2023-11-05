<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CardStatus;
use  Image;
use File;

class CustomerCardStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $perPage = 25;
        $cardStatus = CardStatus::paginate($perPage);

        return view('admin.card-status.index', compact('cardStatus'));
    }

    public function create(){     
        return view('admin.card-status.create');
    }    

    public function store(Request $request){
        $rules = [
            'title'=>'required',
            'image'=>'required',
            'image_alt'=>'required',
            'percentage'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'image_alt.required' => trans('admin_validation.image alt is required'),
            'image.required' => trans('admin_validation.Image is required'),
            'percentage.required' => trans('admin_validation.Percentage is required'),
        ];
        $this->validate($request, $rules,$customMessages);
        $cardStatus = new CardStatus();
        if($request->image){
            $extention=$request->image->getClientOriginalExtension();
            $image_name = 'card-status'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->image)
                ->save(public_path().'/'.$image_name);
            $cardStatus->image = $image_name;
        }

        $cardStatus->title = $request->title;
        $cardStatus->image_alt = $request->image_alt;
        $cardStatus->percentage = $request->percentage;
        $cardStatus->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }  

    public function edit(Request $request, $id){
        $cardStatus = CardStatus::find($id);
        return view('admin.card-status.edit', compact('cardStatus'));
    }

    public function show($id)
    {
        $cardStatus = CardStatus::findOrFail($id);
        return view('admin.card-status.show', compact('cardStatus'));
    }

    public function update(Request $request, $id){
        $cardStatus = CardStatus::find($id);
        $rules = [
            'title'=>'required',
            'image_alt'=>'required',
            'percentage'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'image_alt.required' => trans('admin_validation.image alt is required'),
            'percentage.required' => trans('admin_validation.Percentage is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->image){
            $old_image = $cardStatus->image;
            $extention=$request->image->getClientOriginalExtension();
            $image_name = 'cardStatus-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name ='uploads/custom-images/'.$image_name;
            Image::make($request->image)
                ->save(public_path().'/'.$image_name);
            $cardStatus->image = $image_name;
            $cardStatus->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $cardStatus->title = $request->title;
        $cardStatus->image_alt = $request->image_alt;
        $cardStatus->percentage = $request->percentage;
        $cardStatus->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.customer-card-status.index')->with($notification);
    }

    public function destroy(Request $request, $id){
        $cardStatus = CardStatus::find($id);
        $old_image = $cardStatus->image;
        $cardStatus->delete();
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $notification=  trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}