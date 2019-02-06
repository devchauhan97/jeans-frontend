<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use DB;
use Auth;
use Lang;
use Hash;
use App\Setting;
use App\Customer;
use App\Notifications\ContactUs;
use Illuminate\Support\Facades\Notification;
use Mail;
use App\Events\ContactUsMail;
use Event;
class ContactController extends DataController
{
    //myContactUs

	public function contactUs(Request $request)
	{
		$title = array('pageTitle' => Lang::get("website.Contact Us"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
		
		return view("contact-us", $title)->with('result', $result); 
	}
	
	//processContactUs
	public function processContactUs(ContactFormRequest $request)
	{
		$name 		=  $request->name;
		$email 		=  $request->email;
		$subject 	=  $request->subject;
		$message 	=  $request->message;
		
		// $setting= Setting::get();
		// $app_name = $setting[18]->value;	
		// $admin_email = $setting[3]->value;
 
		$data = array(	'name'=>$name, 'email'=>$email, 
						'subject'=>$subject, 'message'=>$message, 
						//'adminEmail'=>$admin_email
					);
		 
		// \Mail::send('/mail/contactUs', ['data' => $data], function($m) use ($data,$app_name){
		// 	$m->to($data['adminEmail'])->subject($app_name.Lang::get("website.contact us title"))->getSwiftMessage()
		// 	->getHeaders()
		// 	->addTextHeader('x-mailgun-native-send', 'true');	
		// });
		 
		// $site_setting= Setting::select('value as email ')->where('name','contact_us_email')->first();
		// //$user=Customer::where('customers_id',4)->first();
		// $site_setting->notify(new ContactUs($data));
		//Notification::send(new ContactUs($data));
		//$administrators= Admin::where(['adminType' => 1,'isActive' => 1])->get();
		//Notification::send($administrators, new ContactUs($data));
		 
		Event::fire(new ContactUsMail($data));
		return redirect()->back()->with('success', Lang::get("website.contact us message"));
	}
}
