<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\UserVerify;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Mail;

class AuthController extends BaseController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    // }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();
            if ($user->is_email_verified==0) {
              return $this->sendError('Unverified.', ['error'=>'Unverified']);
            }
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Error.', ['error'=>'Invalid email or password']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['name'] = $input['firstName'];
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token = $user->createToken('MyApp')->plainTextToken;
        $success['token'] =  $token;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;
        $email =  $user->email;
        UserVerify::create([
             'user_id' => $user->id,
             'token' => $token
           ]);

         Mail::send('emails.emailVerificationEmail', ['token' => $token], function($message) use($request){
               $message->to($request->email);
               $message->subject('Email Verification Mail');
           });
        return $this->sendResponse($success, 'User successfully registered.');
    }

    public function resendVerificationToken(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if(!$user) {
          return $this->sendError('Error.',"User does not exists");

        }
        $old_token = UserVerify::where('user_id',$user->id)->first()->delete();
        $token = $user->createToken('MyApp')->plainTextToken;
        UserVerify::create([
             'user_id' => $user->id,
             'token' => $token
           ]);

         Mail::send('emails.emailVerificationEmail', ['token' => $token], function($message) use($request){
               $message->to($request->email);
               $message->subject('Email Verification Mail');
           });
           $success['name'] =  $user->name;
           $success['email'] =  $user->email;
        return $this->sendResponse($success,  'Success');
    }
    public function activateAccount(Request $request) {
        if (!isset($request->token)) {
          return $this->sendError('Error.',"The token field is required.");
        }elseif (!isset($request->email)) {
          return $this->sendError('Error.',"The email field is required.");
        }
        $user_verify = UserVerify::where('token',$request->token)->first();
        if(!$user_verify) {
          return $this->sendError('Error.',"You can not use this token for activate");
        }else if($user_verify->token == $request->token) {
            $user = User::find($user_verify->user_id);
            $user->update([
                'is_email_verified' => 1,
            ]);
            $message = "Success";
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            return $this->sendResponse($success,  $message);
        }else {
          return $this->sendError('Error.',"You can not use this token for activate");
        }
    }
    public function sendResetLinkEmail(Request $request)
      {
          $user = User::where('email',$request->email)->first();
          if(!$user) {
            return $this->sendError('Error.',"User does not exists");
          }
          $messages = [
             'email.required' => "The email field is required",
             'email.exists' => "An account with this email do not exists",
             'email.email' => "Please enter a valid email address",
         ];
          $validator = Validator::make($request->all(), ['email' => 'required|email|exists:users'],$messages);
          if($validator->fails()){
              return $this->sendError('Validation Error.', $validator->errors());
          }
          $email = $request->email;
          $token = $user->createToken('MyApp')->plainTextToken;
          $pass_reset = PasswordReset::where('user_id',$user->id)->first();
          if (isset($pass_reset)) {
            $pass_reset->delete();
          }
          PasswordReset::create([
              'user_id' => $user->id,
              'token' => $token
          ]);
          Mail::send('emails.passwordResetEmail', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Password Reset Mail');
          });
          $success['name'] =  $user->name;
          $success['email'] =  $user->email;
          return $this->sendResponse($success, 'Please go to your email and reset your password.');
      }
      public function reset(Request $request) {
         $user = User::where('email',$request->email)->first();
         if(!$user) {
           return $this->sendError('Error.',"User does not exists");
         }
          $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
            'c_password' => 'required|same:password',
         ]);
         if($validator->fails()){
             return $this->sendError('Validation Error.', $validator->errors());
         }
         $password_reset = PasswordReset::where('token', $request->token)->first();
         if(!isset($password_reset)){
           return $this->sendError('Error.',"Incorrect token");
         }
         $user['password'] = bcrypt($request->password);
         $user->save();
         $success['name'] =  $user->name;
         $success['email'] =  $user->email;
         return $this->sendResponse($success, 'Password changed.');

     }
}
