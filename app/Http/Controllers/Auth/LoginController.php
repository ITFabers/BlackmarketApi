<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\BannerImage;
use App\Models\User;
use Auth;
use Hash;
use App\Mail\UserForgetPassword;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use Mail;
use Str;
use Validator,Redirect,Response,File;
use Carbon\Carbon;
use Exception;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/user/dashboard';

    public function __construct()
    {
        $this->middleware('auth:api')->except('userLogout');
    }

    public function loginPage(){
        $background = BannerImage::whereId('13')->first();
        return view('login', compact('background'));
    }

    public function storeLogin(Request $request){
        $rules = [
            'email'=>'required',
            'password'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
              return response()->json(['message' => $validator->messages()],402);


          }
        $login_by = 'email';
        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            $login_by = 'email';
            $user = User::where('email',$request->email)->first();

        }else if(is_numeric($request->email)){
            $login_by = 'phone';
            $user = User::where('phone',$request->email)->first();
        }else{
            return response()->json(['message' => trans('user_validation.Please provide valid email or phone')],422);
        }

        if($user){
            if($user->email_verified == 0){
                $notification = trans('user_validation.Please verify your acount. If you didn\'t get OTP, please resend your OTP and verify');
                return response()->json(['notification' => $notification],402);
            }
            if($user->status==1){
                if(Hash::check($request->password,$user->password)){

                    if($login_by == 'email'){
                        $credential=[
                            'email'=> $request->email,
                            'password'=> $request->password
                        ];
                    }else{
                        $credential=[
                            'phone'=> $request->email,
                            'password'=> $request->password
                        ];
                    }
                    if (! $token = Auth::guard('api')->attempt($credential, ['exp' => Carbon::now()->addDays(365)->timestamp])) {
                        return response()->json(['error' => 'Unauthorized'], 401);
                    }
// $token = Auth::guard('api')->attempt($credential);
// return $token;
                    if($login_by == 'email'){
                        $user = User::where('email',$request->email)->select('id','name','email','phone','image','status')->first();
                    }else{
                        $user = User::where('phone',$request->email)->select('id','name','email','phone','image','status')->first();
                    }


                        return $this->respondWithToken($token,$user);


                }else{
                    $notification = trans('user_validation.Credentials does not exist');
                    return response()->json(['notification' => $notification],402);
                }

            }else{
                $notification = trans('user_validation.Disabled Account');
                return response()->json(['notification' => $notification],402);
            }
        }else{
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['notification' => $notification],402);
        }
    }


    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }



    public function forgetPage(){
        return view('forget_password');
    }

    public function sendForgetPassword(Request $request){
        $rules = [
            'email'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
              return response()->json(['message' => $validator->messages()]);
        }
        $user = User::where('email', $request->email)->first();
        if($user){
            $user->forget_password_token = random_int(100000, 999999);
            $user->save();

            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id',1)->first();
            $subject = $template->subject;
            $message = $template->description;
            $message = str_replace('{{name}}',$user->name,$message);
            Mail::to($user->email)->send(new UserForgetPassword($message,$subject,$user));

            $template=SmsTemplate::where('id',2)->first();
            $message=$template->description;
            $message = str_replace('{{name}}',$user->name,$message);
            $message = str_replace('{{otp_code}}', $user->forget_password_token ,$message);

            $notification = trans('user_validation.Reset password link send to your email.');
            return response()->json(['notification' => $notification],200);

        }else{
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['notification' => $notification],402);
        }
    }


    public function resetPasswordPage($token){
        $user = User::where('forget_password_token', $token)->first();

        return response()->json(['user' => $user],200);

        return view('reset_password', compact('user','token'));
    }

    public function storeResetPasswordPage(Request $request, $token){
        $rules = [
            'email'=>'required',
            'password'=>'required|min:4|confirmed',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()]);
        }
        $user = User::where(['email' => $request->email, 'forget_password_token' => $token])->first();
        if($user){
            $user->password=Hash::make($request->password);
            $user->forget_password_token=null;
            $user->save();

            $notification = trans('user_validation.Password Reset successfully');
            return response()->json(['notification' => $notification],200);
        }else{
            $notification = trans('user_validation.Email or token does not exist');
            return response()->json(['notification' => $notification],402);
        }
    }

    public function userLogout(){
        Auth::guard('api')->logout();


        $notification= trans('user_validation.Logout Successfully');
        return response()->json(['notification' => $notification],200);
    }

    function createUser($getInfo,$provider){
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'provider_avatar' => $getInfo->avatar,
                'status' => 1,
                'email_verified' => 1,
            ]);
        }
        return $user;
    }
}
