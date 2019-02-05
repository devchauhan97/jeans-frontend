<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 3.0
*/
namespace App\Http\Controllers\Web;
use App\User;
use Socialite;
//use Mail;
//validator is builtin class in laravel
use Validator;
use Services;
use File; 

use Illuminate\Contracts\Auth\Authenticatable;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;


//for requesting a value 
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;
//for Carbon a value 
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Session;
use Lang;
 
use App\Basket;
use App\Device;
use App\Customer;
use App\BasketAttribute;
use App\CustomerInfo;
use App\WhosOnline;
use App\Product;
use App\LikedProduct;
//email
use Illuminate\Support\Facades\Mail;
//use Illuminate\Notifications\Notification;

use App\PasswordReset;
use App\Http\Requests\CustomerSignupRequest;

use App\Http\Requests\CustomerPasswordUpdateRequest;
use App\Http\Requests\CustomerLoginRequest;
use App\Events\CustomerRegisterMail;
use Event;

class CustomersController extends DataController
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	//signup 
	public function signup(Request $request) {	
 
		if(auth()->guard('customer')->check()) {

			return redirect('/');

		} else {

			$title = array('pageTitle' => Lang::get("website.Sign Up"));
			$result = array();						
			$result['commonContent'] = $this->commonContent();		
			return view("signup", $title)->with('result', $result);   
		} 			
	}
	//login 
	public function login(Request $request) {

		if(auth()->guard('customer')->check()){

			return redirect('/');
		} else {
			
			$title = array('pageTitle' => Lang::get("website.Login"));
			$result = array();				
			$result['commonContent'] = $this->commonContent();		
			return view("login", $title)->with('result', $result);   
		} 		
	}
	//login LoginRequest
	public function customerLogin(CustomerLoginRequest $request) {
		$old_session = Session::getId();

	 	$result = array();		
		//check authentication of email and password
		$customerInfo = array("email" => $request->log_email, "password" => $request->log_password);
		
		if(auth()->guard('customer')->attempt($customerInfo)) {

			$customer = auth()->guard('customer')->user();
			/**
			*update session cart
			**/
			$this->makeSessionCart($customer,$old_session);
			$result['customers'] = DB::table('customers')->where('customers_id', $customer->customers_id)->get();					
			return redirect()->intended('/')->with('result', $result);

 		} else {

 			return redirect('login')->with('loginError',Lang::get("website.Email or password is incorrect"));
 			
 		}
	}
	
	public function makeSessionCart($customer,$old_session) {

		//set session				
		session(['customers_id' => $customer->customers_id]);
		
		//cart 				
		$cart = Basket::where([
					['session_id', '=', $old_session],
				])->get();
		
		if(count($cart)>0) {

			foreach($cart as $cart_data) {

				$exist = Basket::where([
					['customers_id', '=', $customer->customers_id],
					['products_id', '=', $cart_data->products_id],
					['is_order', '=', '0'],
				])->delete();

			}	

		}
		
		Basket::where('session_id','=', $old_session)->update([
					'customers_id'	=>	$customer->customers_id
				]);

		BasketAttribute::where('session_id','=', $old_session)->update([
				'customers_id'	=>	$customer->customers_id
				]);
		//insert device id
		if(!empty(session('device_id'))) {

			DB::table('devices')->where('device_id', session('device_id'))->update(['customers_id'	=>	$customer->customers_id]);		
		}
	}

	public function profile(Request $request) {

		$title = array('pageTitle' => Lang::get("website.Profile"));
		$result = array();	
		$result['commonContent'] = $this->commonContent();
		
		return view("profile", $title)->with('result', $result); 
	}
	
	public function updateProfile(Request $request) {
		
		$customers_id								=	auth()->guard('customer')->user()->customers_id; 
		//$customers_info_date_account_last_modified 	=   date('y-m-d h:i:s');
		$extensions = array('gif','jpg','jpeg','png');

		if($request->hasFile('picture') and in_array($request->picture->extension(), $extensions)) {

			$image = $request->picture;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move(storage_path('app/public').'/user_profile/', $fileName);
			$customers_picture = 'user_profile/'.$fileName; 

			storeImage($customers_picture);

		}	else{

			$customers_picture = $request->customers_old_picture;

		}	
		//update into customer
		Customer::where('customers_id', $customers_id)->update([
			'customers_firstname'			 =>  $request->customers_firstname,
			'customers_lastname'			 =>  $request->customers_lastname,
			'customers_fax'					 =>  $request->customers_fax,
			'customers_newsletter'			 =>  $request->customers_newsletter,
			'customers_telephone'			 =>  $request->customers_telephone,
			'customers_gender'				 =>  $request->customers_gender,
			'customers_dob'					 =>  $request->customers_dob,
			'customers_picture'				 =>  $customers_picture
		]);
				
		// CustomerInfo::where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   => $customers_info_date_account_last_modified]);	
		$message = Lang::get("website.Prfile has been updated successfully");
		
		return redirect()->back()->with('success', $message);
			
	}
	
	public function updatePassword(CustomerPasswordUpdateRequest $request) {

		$old_session = Session::getId();
		
		$customers_id =   auth()->guard('customer')->user()->customers_id;
 
		$userData = Customer::where('customers_id', $customers_id)->update(['password'			=>  Hash::make($request->new_password)]);

		$user = Customer::where('customers_id', $customers_id)->get();
		
		//check authentication of email and password
		$customerInfo = array("email" => $user[0]->email, "password" => $request->new_password);
		

		if(Auth::guard('customer')->attempt($customerInfo)) {
 			
			$message = Lang::get("website.Password has been updated successfully");
			return redirect()->back()->with('success', $message);
		}
				
		//$userData = Customer::where('customers_id', $customers_id)->update($customer_data);
				
		// CustomerInfo::where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   =>   $customers_info_date_account_last_modified]);

		$message = Lang::get("website.Password has been updated successfully");
		
	}
	
	//logout
	public function logout(REQUEST $request){

		Auth::logout();
		Auth::guard('customer')->logout();
		session()->flush();
		$request->session()->forget('customers_id');
		$request->session()->regenerate();		
		return redirect()->intended('/');
	}
	
	 /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialLogin($social){
		//print_r($social);
		
        return Socialite::driver($social)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleSocialLoginCallback($social) 
    {

		$old_session = Session::getId();
		
		$user =Socialite::driver($social)->stateless()->user();
		$password = createRandomPassword();	
		
		// OAuth Two Providers
		$token = $user->token;
		 
		//$customers_gender = @$user['gender'] ;
		// All Providers
		$social_id = $user->getId();	
		
		$customers_firstname = substr($user->getName(), 0, strpos($user->getName(), ' '));
		$customers_lastname = str_replace($customers_firstname.' ', '', $user->getName());
		
		$email = $user->getEmail();	

		if(empty($email)) {
			$email = '';	
		}			
		
		$img = file_get_contents($user->getAvatar());
		$dir="user_profile/";
		if (!file_exists($dir) and !is_dir($dir)) {
			mkdir($dir);
		} 

		$uploadfile = $dir."pic_".time().".jpg";
		$temp_upload_path = $uploadfile;
		file_put_contents(storage_path('app/public').'/'.$temp_upload_path, $img);
		$profile_photo=$uploadfile;	
		/**store to local storage*/	
		storeImage($uploadfile);

		$customer_data = array(
				'customers_firstname' => $customers_firstname,
				'customers_lastname' => $customers_lastname,
				'email' => $email,
				'isActive' => '1',
				'customers_picture' => $profile_photo,
			);

		if($social == 'facebook') {
			$customer_data['fb_id'] = $social_id;
		}
		
		if($social == 'google') {
	 		$customer_data['google_id'] = $social_id;
		}

		$existUser = Customer::Where('email', '=', $email)
								->orWhere('fb_id', '=', $social_id)
								->orWhere('google_id', '=', $social_id)
								->first();
		 
		if( count($existUser)>0 ) {
			
			$customers_id = $existUser->customers_id;
			//update data of customer
			Customer::where('customers_id','=',$customers_id)->update($customer_data);
			$user_data = $existUser;

		} else {
			//insert data of customer
			$customer_data['password'] =  Hash::make($password);
			$user_data = Customer::create($customer_data);
			$customers_id = $user_data->customers_id;
			//$user_data = $existUser;
			if (filter_var($user_data->email, FILTER_VALIDATE_EMAIL)) {
			 	Event::fire(new CustomerRegisterMail($user_data));
			}
		}

		//$user_data = Customer::where('customers_id', '=', $customers_id)->get();
		 
		/*
		$existUserInfo = CustomerInfo::where('customers_info_id', $customers_id)->get();
		$customers_info_id 							= $customers_id;
		$customers_info_date_of_last_logon  		= date('Y-m-d H:i:s');
		$customers_info_number_of_logons     		= '1';
		$customers_info_date_account_created 		= date('Y-m-d H:i:s');
		$global_product_notifications 				= '1';
		
		if( count($existUserInfo)>0) {
			//update customers_info table
			CustomerInfo::where('customers_info_id', $customers_info_id)->update([
				'customers_info_date_of_last_logon' => $customers_info_date_of_last_logon,
				'global_product_notifications' => $global_product_notifications,
				'customers_info_number_of_logons'=> DB::raw('customers_info_number_of_logons + 1')
			]);
			
		}else{
			
			//insert customers_info table
			  CustomerInfo::updateOrInsert([
					'customers_info_id' => $customers_info_id,
					'customers_info_date_of_last_logon' => $customers_info_date_of_last_logon,
					'customers_info_number_of_logons' =>  $customers_info_number_of_logons,
					'customers_info_date_account_created' => $customers_info_date_account_created,
					'global_product_notifications' => $global_product_notifications
			]);	
			
		}		
		*/
		
		//check if already login or not
		// /$already_login = WhosOnline::where('customer_id', '=', $customers_id)->get();	
		// if( count($already_login)>0) {

		// 	WhosOnline::where('customer_id', $customers_id)
		// 		->update([
		// 				'full_name'  => $user_data->customers_firstname.' '.$user_data->customers_lastname,
		// 				'time_entry'   => date('Y-m-d H:i:s'),							
		// 		]);
		// } else {
			// WhosOnline::updateOrInsert(['customer_id'    => $customers_id],[
			// 			'full_name'  => $user_data->customers_firstname.' '.$user_data->customers_lastname,
			// 			'time_entry' => date('Y-m-d H:i:s'),
			// 			'customer_id'    => $customers_id,
			// 	]);
		//}
		
		//$customerInfo = array("email" => $email, "password" => $password);
		$old_session = Session::getId();
		
		if(auth()->guard('customer')->loginUsingId($customers_id)) {

				$customer = auth()->guard('customer')->user();
				//set session				
				$this->makeSessionCart($customer,$old_session);
						
				// $result['customers'] = Customer::where('customers_id', $customer->customers_id)->get();					
				return redirect()->intended('/')->with('result', $user_data);
		}
//		
//		auth()->login($user_data);
//		
//		return redirect()->intended('/');
		/*Mail::send('/mail/createAccount', ['user_data' => $user_data], function($m) use ($user_data){
				$m->to($user_data[0]->email)->subject('Welcome to Ecommerce App"')->getSwiftMessage()
				->getHeaders()
				->addTextHeader('x-mailgun-native-send', 'true');	
			});*/
    }
	 
	// likeProduct 
	public function likeMyProduct(Request $request){		
		
		if(!empty(session('customers_id'))){
		
			$liked_products_id  = $request->products_id;
			
			$liked_customers_id = session('customers_id');
			$date_liked			= date('Y-m-d H:i:s');
			
			//to avoide duplicate record
			$record = LikedProduct::where([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id
				])->get();
			
				
			if(count($record)>0){
				
				LikedProduct::where([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id
				])->delete();				
				
				
				
				Product::where('products_id','=',$liked_products_id)->decrement('products_liked');
				$products = Product::where('products_id','=',$liked_products_id)->get();
				
				$responseData = array('success'=>'1', 'message'=>Lang::get("website.Product is disliked"), 'total_likes' => $products[0]->products_liked);
			}else{
				
				LikedProduct::insert([
					'liked_products_id'  => $liked_products_id,
					'liked_customers_id' => $liked_customers_id,
					'date_liked' 		 => $date_liked
				]);				
				Product::where('products_id','=',$liked_products_id)->increment('products_liked');
				$products = Product::where('products_id','=',$liked_products_id)->get();
				
				$responseData = array('success'=>'2', 'message'=>Lang::get("website.Product is liked"), 'total_likes' => $products[0]->products_liked);
			}
			
		}else{
			$responseData = array('success'=>'0', 'message'=>Lang::get("website.Please login first to like this product"));
		}
		
		$cartResponse = json_encode($responseData);
		print $cartResponse;
	}
	
	// likeProduct 
	public function unlikeMyProduct(Request $request){
		
		if(!empty(session('customers_id'))){
		
			$liked_products_id  = $request->product_id;
			
			$liked_customers_id = session('customers_id');
			
			LikedProduct::where([
				'liked_products_id'  => $liked_products_id,
				'liked_customers_id' => $liked_customers_id
			])->delete();
			
			Product::where('products_id','=',$liked_products_id)->decrement('products_liked');					
			$message = Lang::get("website.Product is unliked");
			return redirect()->back()->with('success', $message);
		}else{
			return redirect('login')->with('loginError','Please login to like product!');
		}
		
	} 
	//wishlist
	public function wishlist(Request $request){
		$title = array('pageTitle' => Lang::get("website.Wishlist"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
			
		
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}	
		
		$myVar = new DataController();
		$data = array('page_number'=>0, 'type'=>'wishlist', 'limit'=>$limit, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );			
		$products = $myVar->products($data);
		$result['products'] = $products;
								
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);		
		//liked products
		$result['liked_products'] = $this->likedProducts();
		if($limit > $result['products']['total_record']){		
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}
		
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return view("wishlist", $title)->with('result', $result); 
	}
	
	
	public function loadMoreWishlist(Request $request){
		
		$limit = $request->limit;
						
		$myVar = new DataController();
		$data = array('page_number'=>$request->page_number, 'type'=>'wishlist', 'limit'=>$limit, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );	
		$products = $myVar->products($data);
		$result['products'] = $products;	
				
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		$result['limit'] = $limit;
		return view("wishlistproducts")->with('result', $result);	
		
	}
	//generate random password
	function subscribeNotification(Request $request) {
			
		$setting = $this->commonContent();
		 
		/* Desktop */
		$type = 3;
		
		session(['device_id' => $request->device_id]);
		
		if(!empty(auth()->guard('customer')->user()->customers_id)){
		
			$device_data = array(
				'device_id' => $request->device_id,
				'device_type' =>  $type,
				'register_date' => time(),
				'update_date' => time(),
				'ram' =>  '',
				'status' => '1',
				'processor' => '',
				'device_os' => '',
				'location' => '',
				'device_model'=>'',
				'customers_id'=>auth()->guard('customer')->user()->customers_id,
				'manufacturer'=>'',
				$setting['setting'][54]->value=>'1'
			);
			
		
		}else{
			
			$device_data = array(
				'device_id' => $request->device_id,
				'device_type' =>  $type,
				'register_date' => time(),
				'update_date' => time(),
				'ram' =>  '',
				'status' => '1',
				'processor' => '',
				'device_os' => '',
				'location' => '',
				'device_model'=>'',
				'manufacturer'=>'',
				$setting['setting'][54]->value=>'1'
			);
						
		}
		
		//check device exist
		$device_id = Device::where('device_id','=', $request->device_id)->get();
	
		if(count($device_id)>0){			
			$dataexist = Device::where('device_id','=', $request->device_id)->where('customers_id','==', '0')->get();
			Device::where('device_id', $request->device_id)
				->update($device_data);			
		}
		else{
			$device_id = Device::insertGetId($device_data);	
		}

		print 'success';	
	}

	public function store(CustomerSignupRequest $request) {

		$password = $request->password;

		$data =[				
					'customers_firstname' 	=> $request->first_name,
					'customers_lastname' 	=> $request->last_name,
					'email' 				=> $request->email,
					'password' 				=> Hash::make($password),				
				];

		if($request->hasFile('picture') and in_array($request->picture->extension(), $extensions)) {

			$image = $request->picture;
			$fileName = time().'.'.$image->getClientOriginalName();
			$image->move(storage_path('app/public').'/user_profile/', $fileName);
			$customers_picture = 'user_profile/'.$fileName; 
			storeImage($customers_picture);
			$data['customers_picture'] = $customers_picture;
		} 

		$created = Customer::insert($data);
		return $this->customerSignup($request);
	}

	public function customerSignup($request)	{

		$old_session = Session::getId();
		  
		//check authentication of email and password
		$customerInfo = array("email" => $request->email, "password" => $request->password);

		if(auth()->guard('customer')->attempt($customerInfo)) {
		 						
			$customer = auth()->guard('customer')->user();

			$this->makeSessionCart($customer,$old_session);

			//$customers = Customer::where('customers_id', $customer->customers_id)->get();

			//$result['customers'] = $customers;
			//email and notification			
			//$myVar = new AlertController();
			//$alertSetting = $myVar->createUserAlert($customers);
			
			if (filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
				Event::fire(new CustomerRegisterMail($customer));
			}
			return redirect()->intended('/')->with('result');

		} else {

			return redirect('login')->with('loginError', Lang::get("website.Email or password is incorrect"));
		}
  	}
	
}
