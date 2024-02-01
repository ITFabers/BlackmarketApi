<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Country;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Setting;
use App\Mail\OrderSuccessfully;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\Coupon;
use App\Models\ShoppingCart;
use App\Models\ProductVariantItem;
use App\Models\Shipping;
use App\Models\Address;
use App\Models\ShoppingCartVariant;
use Mail;
use Cart;
use Session;
use Str;
use Exception;
use Redirect;

class PaymentController extends Controller
{
    public $mfObj;

    public function __construct()
    {
        $this->middleware('auth:api');

    }


    public function cashOnDelivery(Request $request){

        $rules = [
            'shipping_address_id'=>'required',
            'billing_address_id'=>'required',
            'shipping_method_id'=>'required',
        ];
        $customMessages = [
            'shipping_address_id.required' => trans('user_validation.Shipping address is required'),
            'billing_address_id.required' => trans('user_validation.Billing address is required'),
            'shipping_method_id.required' => trans('user_validation.Shipping method is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('api')->user();

        $total = $this->calculateCartTotal($user, $request->coupon, $request->shipping_method_id);

        $total_price = $total['total_price'];
        $coupon_price = $total['coupon_price'];
        $shipping_fee = $total['shipping_fee'];
        $productWeight = $total['productWeight'];
        $shipping = $total['shipping'];

        $totalProduct = ShoppingCart::with('variants')->where('user_id', $user->id)->sum('qty');
        $setting = Setting::first();

        $amount_real_currency = $total_price;
        $amount_usd = round($total_price / $setting->currency_rate,2);
        $currency_rate = $setting->currency_rate;
        $currency_icon = $setting->currency_icon;
        $currency_name = $setting->currency_name;

        $order_result = $this->orderStore($user, $total_price, $totalProduct, 'Cash on Delivery', 'cash_on_delivery', 0, $shipping, $shipping_fee, $coupon_price, 1, $request->billing_address_id, $request->shipping_address_id);

        $this->sendOrderSuccessMail($user, $total_price, 'Cash on Delivery', 0, $order_result['order'], $order_result['order_details']);

        $this->sendOrderSuccessSms($user, $order_result['order']);

        $notification = trans('user_validation.Order submited successfully. please wait for admin approval');

        $order = $order_result['order'];
        $order_id = $order->order_id;

        return response()->json(['message' => $notification, 'order_id' => $order_id],200);

    }

    public function calculateCartTotal($user, $request_coupon, $request_shipping_method_id){
        $total_price = 0;
        $coupon_price = 0;
        $shipping_fee = 0;
        $productWeight = 0;

        $cartProducts = ShoppingCart::with('product','variants.variantItem')->where('user_id', $user->id)->select('id','product_id','qty')->get();
        if($cartProducts->count() == 0){
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification],403);
        }
        foreach($cartProducts as $index => $cartProduct){
            $variantPrice = 0;
            if($cartProduct->variants){
                foreach($cartProduct->variants as $item_index => $var_item){
                  $item = ProductVariantItem::find($var_item->variant_item_id);
                  if($item){
                    $variantPrice += $item->price;
                  }
                }
            }

            $product = Product::select('id','price','offer_price')->find($cartProduct->product_id);
            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;
            $today = date('Y-m-d H:i:s');

            $price = $price * $cartProduct->qty;
            $total_price += $price;
        }

        // calculate coupon coast
        if($request_coupon){
            $coupon = Coupon::where(['code' => $request_coupon, 'status' => 1])->first();
            if($coupon){
                if($coupon->expired_date >= date('Y-m-d')){
                    if($coupon->apply_qty <  $coupon->max_quantity ){
                        if($coupon->offer_type == 1){
                            $couponAmount = $coupon->discount;
                            $couponAmount = ($couponAmount / 100) * $total_price;
                        }elseif($coupon->offer_type == 2){
                            $couponAmount = $coupon->discount;
                        }
                        $coupon_price = $couponAmount;

                        $qty = $coupon->apply_qty;
                        $qty = $qty +1;
                        $coupon->apply_qty = $qty;
                        $coupon->save();

                    }
                }
            }
        }

        $shipping = Shipping::find($request_shipping_method_id);
        if(!$shipping){
            return response()->json(['message' => trans('user_validation.Shipping method not found')],403);
        }

        if($shipping->shipping_fee == 0){
            $shipping_fee = 0;
        }else{
            $shipping_fee = $shipping->shipping_fee;
        }

        $total_price = ($total_price - $coupon_price) + $shipping_fee;
        $total_price = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $total_price);
        $total_price = number_format($total_price, 2, '.', '');

        $arr = [];
        $arr['total_price'] = $total_price;
        $arr['coupon_price'] = $coupon_price;
        $arr['shipping_fee'] = $shipping_fee;
        $arr['productWeight'] = $productWeight;
        $arr['shipping'] = $shipping;

        return $arr;
    }

    public function orderStore($user, $total_price, $totalProduct, $payment_method, $transaction_id, $paymetn_status, $shipping, $shipping_fee, $coupon_price, $cash_on_delivery,$billing_address_id,$shipping_address_id){
        $cartProducts = ShoppingCart::with('product','variants.variantItem')->where('user_id', $user->id)->select('id','product_id','qty')->get();
        if($cartProducts->count() == 0){
            $notification = trans('user_validation.Your shopping cart is empty');
            return response()->json(['message' => $notification],403);
        }

        $order = new Order();
        $orderId = substr(rand(0,time()),0,10);
        $order->order_id = $orderId;
        $order->user_id = $user->id;
        $order->total_amount = $total_price;
        $order->product_qty = $totalProduct;
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $paymetn_status;
        $order->shipping_method = $shipping->shipping_rule;
        $order->shipping_cost = $shipping_fee;
        $order->coupon_coast = $coupon_price;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();

        $order_details = '';
        $setting = Setting::first();
        foreach($cartProducts as $key => $cartProduct){

            $variantPrice = 0;
            if($cartProduct->variants){
                foreach($cartProduct->variants as $item_index => $var_item){
                  $item = ProductVariantItem::find($var_item->variant_item_id);
                  if($item){
                    $variantPrice += $item->price;
                  }
                }
            }

            // calculate product price
            $product = Product::select('id','price','offer_price','qty','name')->find($cartProduct->product_id);
            $price = $product->offer_price ? $product->offer_price : $product->price;
            $price = $price + $variantPrice;
            $today = date('Y-m-d H:i:s');


            // store ordre product
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cartProduct->product_id;
            $orderProduct->product_name = $product->name;
            $orderProduct->unit_price = $price;
            $orderProduct->qty = $cartProduct->qty;
            $orderProduct->save();

            // update product stock
            $qty = $product->qty - $cartProduct->qty;
            $product->qty = $qty;
            $product->save();

            // store prouct variant

            // return $cartProduct->variants;
            foreach($cartProduct->variants as $index => $variant){
                $item = ProductVariantItem::find($variant->variant_item_id);
                $productVariant = new OrderProductVariant();
                $productVariant->order_product_id = $orderProduct->id;
                $productVariant->product_id = $cartProduct->product_id;
                $productVariant->variant_name = $item->product_variant_name;
                $productVariant->variant_value = $item->name;
                $productVariant->save();
            }

            $order_details.='Product: '.$product->name. '<br>';
            $order_details.='Quantity: '. $cartProduct->qty .'<br>';
            $order_details.='Price: '.$setting->currency_icon . $cartProduct->qty * $price .'<br>';

        }

        // store shipping and billing address
        $billing = Address::find($billing_address_id);
        $shipping = Address::find($shipping_address_id);
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->billing_name = $billing->name;
        $orderAddress->billing_email = $billing->email;
        $orderAddress->billing_phone = $billing->phone;
        $orderAddress->billing_address = $billing->address;
        $orderAddress->billing_city = $billing->city->name;
        $orderAddress->billing_address_type = $billing->type;
        $orderAddress->shipping_name = $shipping->name;
        $orderAddress->shipping_email = $shipping->email;
        $orderAddress->shipping_phone = $shipping->phone;
        $orderAddress->shipping_address = $shipping->address;
        $orderAddress->shipping_city = $shipping->city->name;
        $orderAddress->shipping_address_type = $shipping->type;
        $orderAddress->save();

        foreach($cartProducts as $cartProduct){
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }

        $arr = [];
        $arr['order'] = $order;
        $arr['order_details'] = $order_details;

        return $arr;
    }


    public function sendOrderSuccessMail($user, $total_price, $payment_method, $payment_status, $order, $order_details){
        $setting = Setting::first();

        MailHelper::setMailConfig();

        $template=EmailTemplate::where('id',6)->first();
        $subject=$template->subject;
        $message=$template->description;
        $message = str_replace('{{user_name}}',$user->name,$message);
        $message = str_replace('{{total_amount}}',$setting->currency_icon.$total_price,$message);
        $message = str_replace('{{payment_method}}',$payment_method,$message);
        $message = str_replace('{{payment_status}}',$payment_status,$message);
        $message = str_replace('{{order_status}}','Pending',$message);
        $message = str_replace('{{order_date}}',$order->created_at->format('d F, Y'),$message);
        $message = str_replace('{{order_detail}}',$order_details,$message);
        Mail::to($user->email)->send(new OrderSuccessfully($message,$subject));
    }
}


