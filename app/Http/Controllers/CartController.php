<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariantItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use Cart;
use Session;
use Auth;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function cart()
    {
        // $user =User::first();
        $user = Auth::guard('api')->user();

        $cartProducts = ShoppingCart::with('product', 'variants.variantItem')->where('user_id', $user->id)->select('id', 'product_id', 'qty')->get();

        return response()->json(['cartProducts' => $cartProducts], 200);
    }

    public function addToCart(Request $request)
    {
        $user = Auth::guard('api')->user();

         $itemExist = false;
         $countProduct = ShoppingCart::where(['user_id' => $user->id, 'product_id' => $request->product_id])->count();
         if($countProduct > 0) $itemExist = true;
         if($itemExist) {
             $notification = trans('user_validation.Item already exist');
             return response()->json(['message' => $notification],403);
         }

        $item = new ShoppingCart();
        $item->user_id = $user->id;
        $item->product_id = $request->product_id;
        $item->qty = $request->quantity;
        $item->coupon_name = '';
        $item->offer_type = 0;
        $item->save();


        if ($request->variants && $request->items) {
            foreach ($request->variants as $index => $varr) {
                if ($request->items[$index] != '-1' && $request->variants[$index] != '-1') {
                    $variant = new ShoppingCartVariant();
                    $variant->shopping_cart_id = $item->id;
                    $variant->variant_id = $varr;
                    $variant->variant_item_id = $request->items[$index];
                    $variant->save();
                }
            }
        }

        $notification = trans('user_validation.Item added successfully');
        return response()->json(['message' => $notification]);
    }

    public function cartItemIncrement($id)
    {
        $item = ShoppingCart::find($id);
        $current_qty = $item->qty;

        $productStock = Product::find($item->product_id);
        $stock = $productStock->qty - $productStock->sold_qty;

        if ($stock < $current_qty) {
            $notification = trans('user_validation.Quantity not available in our stock');
            return response()->json(['message' => $notification], 403);
        }

        $item->qty = $item->qty + 1;
        $item->save();

        $notification = trans('user_validation.Update successfully');
        return response()->json(['message' => $notification]);
    }

    public function cartItemDecrement($id)
    {
        $item = ShoppingCart::find($id);
        if ($item->qty > 1) {
            $item->qty = $item->qty - 1;
            $item->save();

            $notification = trans('user_validation.Update successfully');
            return response()->json(['message' => $notification]);
        } else {
            $notification = trans('user_validation.Something went wrong');
            return response()->json(['message' => $notification], 403);
        }

    }

    public function cartItemRemove($rowId)
    {
        $user = Auth::guard('api')->user();
        // $user =User::first();

        $cartProduct = ShoppingCart::where(['user_id' => $user->id, 'id' => $rowId])->first();
        ShoppingCartVariant::where('shopping_cart_id', $rowId)->delete();
        $cartProduct->delete();

        $notification = trans('user_validation.Remove successfully');
        return response()->json(['message' => $notification]);
    }

    public function cartClear()
    {
        $user = Auth::guard('api')->user();
        // $user =User::first();

        $cartProducts = ShoppingCart::where(['user_id' => $user->id])->get();
        foreach ($cartProducts as $cartProduct) {
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }

        $notification = trans('user_validation.Cart clear successfully');
        return response()->json(['message' => $notification]);
    }


    public function applyCoupon(Request $request)
    {
        if ($request->coupon == null) {
            $notification = trans('user_validation.Coupon Field is required');
            return response()->json(['message' => $notification], 403);
        }

        $user = Auth::guard('api')->user();
        $count = ShoppingCart::where('user_id', $user->id)->count();
        if ($count == 0) {
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification], 403);
        }

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if (!$coupon) {
            $notification = trans('user_validation.Invalid Coupon');
            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->expired_date < date('Y-m-d')) {
            $notification = trans('user_validation.Coupon already expired');
            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->apply_qty >= $coupon->max_quantity) {
            $notification = trans('user_validation.Sorry! You can not apply this coupon');
            return response()->json(['message' => $notification], 403);
        }

        return response()->json(['coupon' => $coupon]);
    }


    public function calculateProductPrice(Request $request)
    {
        $prices = [];
        $variantPrice = 0;
        if ($request->variants) {
            foreach ($request->variants as $index => $varr) {
                $item = ProductVariantItem::where(['id' => $request->items[$index]])->first();
                $prices[] = $item->price;
            }
            $variantPrice = $variantPrice + array_sum($prices);
        }

        $product = Product::with('tax')->find($request->product_id);


        $totalPrice = $product->price;

        if ($product->offer_price == null) {
            $productPrice = $totalPrice + $variantPrice;
        } else {
            $productPrice = $product->offer_price + $variantPrice;
        }

        $productPrice = round($productPrice, 2);
        return response()->json(['productPrice' => $productPrice]);
    }

}
