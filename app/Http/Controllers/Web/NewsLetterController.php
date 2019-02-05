<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsLetterRequest;
use DB;
use App\Newsletter;
use Response;
use Carbon;
class NewsLetterController extends Controller
{
    //
    public function addEmail(NewsLetterRequest $request) 
    {
    	try{

			$tipsy_newslatter =Newsletter:: where(['email' => $request->email, 'company_name'=> 'jeans'])->exists();
			
			if( !$tipsy_newslatter ) {

				$ip_address=$_SERVER['REMOTE_ADDR'];
				/*Get user ip address details with geoplugin.net*/
				$details = unserialize(file_get_contents("http://ip-api.com/php/{$ip_address}"));
				
				$city = $details['city']; 
				$state = $details['region']; 
				$country = $details['country'];

				$new_letter = //Newsletter::create(
					['email' => $request->email,'company_name' => 'jeans','page_url' => env('APP_URL'),'city'=>$city,'ip'=>$ip_address,'date_added'=>Carbon::now(),'subscribe'=> 1 ]
				//)
				;

				return Response::json(['success'=>true,'message'=>'Email successfully register for news letter',$new_letter],200);
	    		//redirect()->back()->withSuccess('Send email for news subscription.');
			
			} else {
				return Response::json(['email'=>['Email allready register for news letter']],422);
				//return redirect()->back()->withSuccess('News subscription already exits.');
			}

    	}catch(exception $e){
    		
    		return Response::json($e->getMessage(),403);

    	}
    }
}
