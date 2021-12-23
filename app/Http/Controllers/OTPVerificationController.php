<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use App\User;
use Auth;
use Nexmo;
use App\OtpConfiguration;
use App\BusinessSetting;
use Twilio\Rest\Client;
use Hash;

class OTPVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verification(Request $request){

        if (Auth::check() && Auth::user()->email_verified_at == null) {
            return view('otp_systems.frontend.user_verification');
        }
        else {
            flash('You have already verified your number')->warning();
            return redirect()->route('home');
        }
    }

    public function show_reset_password_form(Request $request){
        return view('otp_systems.frontend.reset_with_phone');
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function verify_phone(Request $request){
        if ($request->verification_code != '') {
            $user = User::where('otp_code',$request->verification_code)->where('user_type', 'customer')->first();
            if ($user) {
                if ($user->active == 1) {
                    flash("You are already an active user, please login to get your dashboard")->warning();
                    return back();
                } else {
                    $user->active = 1;
                    $user->save();
                    flash("Thanks for varification, Please login to continue with us.")->warning();
                    return redirect()->route('user.login');
                }
                
            } else {
                flash("Wrong Varification Code.")->warning();
                return back();
            }
        } else {
            return back();
        }
        
        // $user = Auth::user();
        // if ($user->verification_code == $request->verification_code) {
        //     $user->email_verified_at = date('Y-m-d h:m:s');
        //     $user->save();

        //     flash('Your phone number has been verified successfully')->success();
        //     return redirect()->route('home');
        // }
        // else{
        //     flash('Invalid Code')->error();
        //     return back();
        // }
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function resend_verificcation_code(Request $request){
if ($request->mobile != '') {
    $user = User::where('phone',$request->mobile)->where('user_type', 'customer')->first();
        
        if ($user) {
            if ($user->active == 1) {
                flash("You are already an active user, please login to get your dashboard")->warning();
                return back();
            } else {
                $otp_code = rand(10001,99999);
                $user->otp_code = $otp_code;
                $user->save();
                $phone = $request->mobile;
                // otp
                $business_settings = BusinessSetting::where('type', 'alpha_username')->first();
                $business_settings2 = BusinessSetting::where('type', 'alpha_token')->first();
                if($business_settings AND $business_settings2){
                $username = $business_settings->value;
                $hash = $business_settings2->value;
                $numbers = $phone;
                $message = "Dear User, Your OTP for login is: ".$otp_code.". Please do not share with anyone";
            
                $params = array('u'=>$username, 'h'=>$hash, 'op'=>'pv', 'to'=>$numbers, 'msg'=>$message);
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://alphasms.biz/index.php?app=ws");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($ch);
                curl_close ($ch);
            }
            // otp
            flash("OTP varification code has been send to your mobiule, please wait for sometimes")->success();
            return back();
            }
        } else {
            flash("Phone number didn't match")->warning();
                return back();
        }
        // $user = Auth::user();
        // $user->verification_code = rand(100000,999999);
        // $user->save();

        // sendSMS($user->phone, env("APP_NAME"), $user->verification_code.' is your verification code for '.env('APP_NAME'));

        return back();
} else {
    return back();
}
        
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function reset_password_with_code(Request $request){
        if (($user = User::where('phone', $request->phone)->where('otp_code', $request->code)->first()) != null) {
            if($request->password == $request->password_confirmation){
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();

                flash("Your password reset complete, pLease login to continue")->warning();
                return redirect()->route('home');
            }
            else {
                flash("Password and confirm password didn't match")->warning();
                return back();
            }
        }
        else {
            flash("Verification code mismatch")->error();
            return back();
        }
    }

    /**
     * @param  User $user
     * @return void
     */

    public function send_code($user){
        sendSMS($user->phone, env('APP_NAME'), $user->verification_code.' is your verification code for '.env('APP_NAME'));
    }

    public function reset_password_send(Request $request){
        $otp_code = rand(10001,99999);

            $user = User::where('phone', $request['mobile'])->first();
            if($user){
                
                $user->otp_code = $otp_code;
                $user->save();
                
                
                $phone = $request['mobile'];
                // otp
                $business_settings = BusinessSetting::where('type', 'alpha_username')->first();
                $business_settings2 = BusinessSetting::where('type', 'alpha_token')->first();
                if($business_settings AND $business_settings2){
                    $username = $business_settings->value;
                    $hash = $business_settings2->value;
                    $numbers = $phone;
                    $message = "Dear User, Your OTP for reset password is: ".$otp_code.". Please do not share with anyone";
                
                    $params = array('u'=>$username, 'h'=>$hash, 'op'=>'pv', 'to'=>$numbers, 'msg'=>$message);
                
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "http://alphasms.biz/index.php?app=ws");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $response = curl_exec($ch);
                    curl_close ($ch);
                }
                flash("OTP code has been sed to your mobile no, Please reset your password")->warning();
                return back();
                
            } else {
                flash("Mobile number not found")->warning();
                return back();
            }
            
    }

    /**
     * @param  Order $order
     * @return void
     */
    public function send_order_code($order){
        if(json_decode($order->shipping_address)->phone != null){
            sendSMS(json_decode($order->shipping_address)->phone, env('APP_NAME'), 'You order has been placed and Order Code is : '.$order->code);
        }
    }

    /**
     * @param  Order $order
     * @return void
     */
    public function send_delivery_status($order){
        if(json_decode($order->shipping_address)->phone != null){
            sendSMS(json_decode($order->shipping_address)->phone, env('APP_NAME'), 'Your delivery status has been updated to '.$order->orderDetails->first()->delivery_status.' for Order code : '.$order->code);
        }
    }

    /**
     * @param  Order $order
     * @return void
     */
    public function send_payment_status($order){
        if(json_decode($order->shipping_address)->phone != null){
            sendSMS(json_decode($order->shipping_address)->phone, env('APP_NAME'), 'Your payment status has been updated to '.$order->payment_status.' for Order code : '.$order->code);
        }
    }

    
}
