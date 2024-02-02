<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $cities = City::with('addressCities')->get();

        return view('admin.city', compact('cities'));
    }

    public function create()
    {
        return view('admin.create_city');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:cities',
            'status' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $city = new City();
        $city->name = $request->name;
        $city->slug = Str::slug($request->name);
        $city->status = $request->status;
        $city->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function show($id)
    {
        $city = City::find($id);

        return response()->json(['city' => $city], 200);
    }


    public function update(Request $request, $id)
    {
        $city = City::find($id);
        $rules = [
            'state' => 'required',
            'name' => 'required|unique:cities,name,' . $city->id,
            'status' => 'required',
        ];
        $customMessages = [
            'state.required' => trans('admin_validation.State is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $city->name = $request->name;
        $city->slug = Str::slug($request->name);
        $city->status = $request->status;
        $city->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function edit($id)
    {
        $city = City::find($id);
        return view('admin.edit_city', compact('city'));
    }


    public function destroy($id)
    {
        $city = City::find($id);
        $city->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $city = City::find($id);
        if ($city->status == 1) {
            $city->status = 0;
            $city->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $city->status = 1;
            $city->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }


    public function city_import_page()
    {
        return view('admin.city_import_page');
    }

    public function city_export()
    {
        $is_dummy = false;
        return Excel::download(new CityExport($is_dummy), 'cities.xlsx');
    }


    public function demo_city_export()
    {
        $is_dummy = true;
        return Excel::download(new CityExport($is_dummy), 'cities.xlsx');
    }


    public function city_import(Request $request)
    {

        try {
            Excel::import(new CityImport, $request->file('import_file'));

            $notification = trans('Uploaded Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);

        } catch (Exception $ex) {
            $notification = trans('Please follow the instruction and input the value carefully');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }


    }
}
