<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\User;
use App\Address;
use DB;

class OrderController extends Controller
{
    public function processOrder(Request $request)
    {
        $shippingAddress = json_decode($request->shipping_address);
        // create an order
        $order = Order::create([
            'user_id' => $request->user_id,
            'shipping_address' => json_encode($shippingAddress),
            'payment_type' => $request->payment_type,
            'payment_status' => $request->payment_status,
            'grand_total' => $request->grand_total - $request->coupon_discount,
            'coupon_discount' => $request->coupon_discount,
            'code' => date('Ymd-his'),
            'date' => strtotime('now')
        ]);

        $cartItems = Cart::where('user_id', $request->user_id)->get();
        // save order details

        $shipping = 0;
        $admin_products = array();
        $seller_products = array();
        //

        if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
            $shipping = \App\BusinessSetting::where('type', 'flat_rate_shipping_cost')->first()->value;
        }
        elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
            foreach ($cartItems as $cartItem) {
                $product = \App\Product::find($cartItem->product_id);
                if($product->added_by == 'admin'){
                    array_push($admin_products, $cartItem->product_id);
                }
                else{
                    $product_ids = array();
                    if(array_key_exists($product->user_id, $seller_products)){
                        $product_ids = $seller_products[$product->user_id];
                    }
                    array_push($product_ids, $cartItem->product_id);
                    $seller_products[$product->user_id] = $product_ids;
                }
            }
                if(!empty($admin_products)){
                    $shipping = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value;
                }
                if(!empty($seller_products)){
                    foreach ($seller_products as $key => $seller_product) {
                        $shipping += \App\Shop::where('user_id', $key)->first()->shipping_cost;
                    }
                }
        }

        foreach ($cartItems as $key =>$cartItem) {
            $product = Product::findOrFail($cartItem->product_id);
            if ($cartItem->variation) {
                $cartItemVariation = $cartItem->variation;
                $product_stocks = $product->stocks->where('variant', $cartItem->variation)->first();
                $product_stocks->qty -= $cartItem->quantity;
                $product_stocks->save();
            } else {
                $product->update([
                    'current_stock' => DB::raw('current_stock - ' . $cartItem->quantity)
                ]);
            }

            $order_detail_shipping_cost= 0;
            

            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'flat_rate') {
                $order_detail_shipping_cost = $shipping/count($cartItems);
            }
            elseif (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'seller_wise_shipping') {
                if($product->added_by == 'admin'){
                    $order_detail_shipping_cost = \App\BusinessSetting::where('type', 'shipping_cost_admin')->first()->value/count($admin_products);
                }
                else {
                    $order_detail_shipping_cost = \App\Shop::where('user_id', $product->user_id)->first()->shipping_cost/count($seller_products[$product->user_id]);
                }
            }
            else{
                $order_detail_shipping_cost = $product->shipping_cost;
            }
            
            $address = Address::where('user_id',$request->user_id)->get();
            $user = User::findOrFail($request->user_id);
            if(count($address) == 1){
                
                if($address[0]->shipping == 'inside_dhaka'){
                    $order_detail_shipping_cost = $product->shipping_cost ?? 0;
                    // $order_detail_shipping_cost = BusinessSetting::where('type','inside_dhaka')->first()->value;
                } else {
                    // $order_detail_shipping_cost = BusinessSetting::where('type','outside_dhaka')->first()->value;
                    $order_detail_shipping_cost = $product->outside_shipping_cost ?? 0;
                }
                
            } else {
                foreach ($address as $key1 =>$addres) {
                    if($user->name == $shippingAddress->name and $addres->email == $shippingAddress->email and $addres->country == $shippingAddress->country and $addres->city == $shippingAddress->city and $addres->postal_code == $shippingAddress->postal_code and $addres->phone == $shippingAddress->phone){
                        
                        if($addres->shipping == 'inside_dhaka'){
                            $order_detail_shipping_cost = $product->shipping_cost ?? 0;
                        } else {
                            $order_detail_shipping_cost = $product->outside_shipping_cost ?? 0;
                        }
                                
                    }
                    
                }
                
            }
            
            if($key == 0){
                $order_detail_shipping_cost = $order_detail_shipping_cost;
            } else {
                $order_detail_shipping_cost = 0;
            }

            OrderDetail::create([
                'order_id' => $order->id,
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
                'variation' => $cartItem->variation,
                'price' => $cartItem->price * $cartItem->quantity,
                'tax' => $cartItem->tax * $cartItem->quantity,
                'shipping_cost' => $order_detail_shipping_cost,
                'quantity' => $cartItem->quantity,
                'payment_status' => $request->payment_status
            ]);
            $product->update([
                'num_of_sale' => DB::raw('num_of_sale + ' . $cartItem->quantity)
            ]);
        }
        // apply coupon usage
        if ($request->coupon_code != '') {
            CouponUsage::create([
                'user_id' => $request->user_id,
                'coupon_id' => Coupon::where('code', $request->coupon_code)->first()->id
            ]);
        }
        // calculate commission
        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->product->user->user_type == 'seller') {
                $seller = $orderDetail->product->user->seller;
                $price = $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                $seller->admin_to_pay = ($request->payment_type == 'cash_on_delivery') ? $seller->admin_to_pay - ($price * $commission_percentage) / 100 : $seller->admin_to_pay + ($price * (100 - $commission_percentage)) / 100;
                $seller->save();
            }
        }
        // clear user's cart
        $user = User::findOrFail($request->user_id);
        $user->carts()->delete();
        // $phone = $shippingAddress['phone'] ?? null;
        // $business_settings = BusinessSetting::where('type', 'alpha_username')->first();
        // $business_settings2 = BusinessSetting::where('type', 'alpha_token')->first();
        // if($phone != null) {
        //         $username = $business_settings->value;
        //         $hash = $business_settings2->value;
        //         $numbers = $phone;
        //         $message = "Dear ". $shippingAddress['name'].", Your order is successfully placed by B71bd.com";
            
        //         $params = array('u'=>$username, 'h'=>$hash, 'op'=>'pv', 'to'=>$numbers, 'msg'=>$message);
            
        //         $ch = curl_init();
        //         curl_setopt($ch, CURLOPT_URL, "http://alphasms.biz/index.php?app=ws");
        //         curl_setopt($ch, CURLOPT_POST, 1);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //         $response = curl_exec($ch);
        //         curl_close ($ch);
        //     }

        return response()->json([
            'success' => true,
            'message' => 'Your order has been placed successfully'
        ]);
    }

    public function store(Request $request)
    {
        return $this->processOrder($request);
    }
}
