<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\City;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Wishlist;
use App\Models\User;
use Image;
use File;
use Str;
use Hash;
use Slug;


use App\Models\OrderAddress;
use App\Models\OrderProductVariant;
use App\Models\Address;

use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function remove_account(){
        $user = Auth::guard('api')->user();
        $id = $user->id;
        $orders = Order::where('user_id', $user->id)->get();
        foreach($orders as $order){
            OrderAddress::where(['order_id' => $order->id])->delete();
            $orderProducts = OrderProduct::where('order_id',$id)->get();
            foreach($orderProducts as $orderProduct){
                OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
                $orderProduct->delete();
            }
            $order->delete();
        }

        Address::where('user_id',$id)->delete();
        Wishlist::where('user_id',$id)->delete();

        $cart_items = ShoppingCart::where(['user_id' => $user->id])->get();
        foreach($cart_items as $cart_item){
            ShoppingCartVariant::where(['shopping_cart_id' => $cart_item->id])->delete();
            $cart_item->delete();
        }

        $user = User::find($id);
        $user_image = $user->image;
        if($user_image){
            if(File::exists(public_path().'/'.$user_image))unlink(public_path().'/'.$user_image);
        }
        $user->delete();

        return response()->json(['message' => trans('Your account has been successfully removed')]);
    }

    public function dashboard(){
        $user = Auth::guard('api')->user();
        $orders = Order::where('user_id',$user->id)->get();
        $totalOrder = $orders->count();
        $completeOrder = $orders->where('order_status',3)->count();
        $pendingOrder = $orders->where('order_status',0)->count();
        $declinedOrder = $orders->where('order_status',4)->count();

        $personInfo = User::select('id','name','phone','email','image','city_id','zip_code','address')->find($user->id);

        return response()->json([
            'personInfo' => $personInfo,
            'totalOrder' => $totalOrder,
            'completeOrder' => $completeOrder,
            'pendingOrder' => $pendingOrder,
            'declinedOrder' => $declinedOrder,
        ]);
    }


    public function order(){
        $user = Auth::guard('api')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->paginate(10);

        return response()->json(['orders' => $orders]);
    }

    public function pendingOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',0)->paginate(10);

        return response()->json(['orders' => $orders]);
    }

    public function completeOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',3)->paginate(10);

        return response()->json(['orders' => $orders]);
    }

    public function declinedOrder(){
        $user = Auth::guard('api')->user();
        $orders = Order::orderBy('id','desc')->where('user_id', $user->id)->where('order_status',4)->paginate(10);
        $setting = Setting::first();
        return response()->json(['orders' => $orders]);
    }

    public function orderShow($orderId){
        $user = Auth::guard('api')->user();
        $order = Order::with('orderProducts.orderProductVariants','orderAddress')->where('user_id', $user->id)->where('order_id',$orderId)->first();

        return response()->json(['order' => $order]);
    }


    public function wishlist(){
        $user = Auth::guard('api')->user();
        $wishlists = Wishlist::with('product')->where(['user_id' => $user->id])->paginate(10);

        return response()->json(['wishlists' => $wishlists]);
    }

    public function myProfile(){
        $user = Auth::guard('api')->user();
        $personInfo = User::select('id','name','email','phone','image','city_id','zip_code','address')->find($user->id);
        $cities = City::orderBy('name','asc')->where(['status' => 1])->get();

        return response()->json([
            'personInfo' => $personInfo,
            'cities' => $cities
        ]);
    }

    public function updateProfile(Request $request){
        $user = Auth::guard('api')->user();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$user->id,
            'phone'=>'required',
            'city'=>'required',
            'address'=>'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
            'city.required' => trans('user_validation.City is required'),
            'address.required' => trans('user_validation.Address is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->city_id = $request->city;
        $user->address = $request->address;
        $user->save();

        if($request->file('image')){
            $old_image=$user->image;
            $user_image=$request->image;
            $extention=$user_image->getClientOriginalExtension();
            $image_name= Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/custom-images/'.$image_name;

            Image::make($user_image)
                ->save(public_path().'/'.$image_name);

            $user->image=$image_name;
            $user->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('user_validation.Update Successfully');
        return response()->json(['notification' => $notification]);
    }


    public function updatePassword(Request $request){
        $rules = [
            'current_password'=>'required',
            'password'=>'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('user_validation.Current password is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password minimum 4 character'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            $notification = 'Password change successfully';
            return response()->json(['notification' => $notification]);
        }else{
            $notification = trans('user_validation.Current password does not match');
            return response()->json(['notification' => $notification],403);
        }
    }

    public function cityByState($id){
        $cities = City::select('id','country_state_id','name')->where(['status' => 1, 'country_state_id' => $id])->get();
        return response()->json(['cities'=>$cities]);
    }
    public function addToWishlist($id){
        $user = Auth::guard('api')->user();
        $product = Product::find($id);
        $isExist = Wishlist::where(['user_id' => $user->id, 'product_id' => $product->id])->count();
        if($isExist == 0){
            $wishlist = new Wishlist();
            $wishlist->product_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();
            $message = trans('user_validation.Wishlist added successfully');
            return response()->json(['message' => $message]);
        }else{
            $message = trans('user_validation.Product Already added');
            return response()->json(['message' => $message],403);
        }
    }

    public function removeWishlist($id){
        $wishlist = Wishlist::find($id);
        $wishlist->delete();
        $notification = trans('user_validation.Removed successfully');
        return response()->json(['notification' => $notification]);
    }

    public function clearWishlist(){
        $user = Auth::guard('api')->user();
        Wishlist::where(['user_id' => $user->id])->delete();

        $notification = trans('user_validation.Clear successfully');
        return response()->json(['notification' => $notification]);
    }
}
