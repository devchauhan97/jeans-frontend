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
		
		//$result['commonContent'] = $this->commonContent();
		$site_setting= Setting::where('name','contact_us_email')->first();

		$data = array('name'=>$name, 'email'=>$email, 'subject'=>$subject, 'message'=>$message, 'adminEmail'=>$site_setting['value']);
		 
		\Mail::send('/mail/contactUs', ['data' => $data], function($m) use ($data){
			$m->to($data['adminEmail'])->subject(Lang::get("website.contact us title"))->getSwiftMessage()
			->getHeaders()
			->addTextHeader('x-mailgun-native-send', 'true');	
		});
		 
		// $site_setting= Setting::select('value as email ')->where('name','contact_us_email')->first();
		// //$user=Customer::where('customers_id',4)->first();
		// $site_setting->notify(new ContactUs($data));
		//Notification::send(new ContactUs($data));
		// Notification::send($data, new ContactUs($site_setting));

		return redirect()->back()->with('success', Lang::get("website.contact us message"));
	}
}
