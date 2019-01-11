<?php

namespace App\Http\Controllers\Web;

use Auth;
use Lang;
use Hash;
use App\Customer;
use App\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\SendResetPassword;
use Illuminate\Support\Facades\Notification;  
use App\Http\Requests\UpdateResetPasswordRequest;

class CustomerForgotPasswordController extends DataController
{
	
	/**
	*forgot-password page
	**/
	public function forgotPwd() 
	{

		if(auth()->guard('customer')->check()) {

			return redirect('/');

		} else {
			
			$title = array('pageTitle' => Lang::get("website.Forgot Password"));
			$result = array();		

			$result['commonContent'] = $this->commonContent();

			return view("forgotpassword", $title)->with('result', $result);   
		} 
	}
	/***
	*forgot-password email send
	*/
	public function passwordEmail(Request $request) 
	{
 
		$title = array('pageTitle' => Lang::get("website.Forgot Password"));
		
		$password =createRandomPassword();
		
		$email =   $request->email;

		$postData = array();
				
		//check email exist
		$users = Customer::where('email', $email)->first();	

		if( count($users) > 0 ) {
 
			$token =str_random(60);

			$password_resets = PasswordReset::create([
		        'email' => $request->email,
		        'token' => $token, //change 60 to any length you want
		    ]);
		   
			//$this->notify(new SendResetPassword($token));
			Notification::send($password_resets, new SendResetPassword($users));
			// \Notification::route('mail', 'dharmender.chauhan@servercenter.ca')
   //          ->route('nexmo', '5555555555')
   //          ->notify(new SendResetPassword($token));

			//$myVar = new AlertController();
			//$alertSetting = $myVar->forgotPasswordAlert($existUser);
			
			return redirect('login')->with('success', Lang::get("website.Password has been sent to your email address"));
		} else {	

			return redirect('forgot/password')->with('error', Lang::get("website.Email address does not exist"));
		}
		
	}

    //forgotPassword
	public function passwordResetToken(Request $request) 
	{

		$title = array('pageTitle' => Lang::get("website.Reset Password"));
		$result = PasswordReset::where('token',$request->token)->first();
		
		if( count($result) > 0 ) {
			 
			$result['commonContent'] = $this->commonContent();

			return view("recoverpassword", $title)->with('result', $result);
		} else {	

			return redirect('forgot/password')->with('error', Lang::get("website.Invalid Token"));
		}
	}
	/***
	**update reset password
	***/
	public function updateResetPassword(UpdateResetPasswordRequest $request) 
	{
  
		$token = PasswordReset::where('token', $request->token)->first();
		
		if( !$token ) {

			 return redirect('')->back()->with('error', Lang::get("website.something is wrong"));
		}

		Customer::where('email', $token->email)
				->update(['password' =>  Hash::make($request->new_password)]);
		PasswordReset::where('token', $request->token)->delete();
		
		//check authentication of email and password
		$customerInfo = array("email" => $token->email, "password" => $request->new_password);
		
		if(Auth::guard('customer')->attempt($customerInfo)) {
  
  			$message = Lang::get("website.Password has been updated successfully");
			return redirect('profile')->with('success', $message);
		} 
 
		// return redirect('/signup')->with('error', Lang::get("website.something is wrong"));
	}
}
