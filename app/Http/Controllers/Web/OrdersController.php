<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 1.0
*/
namespace App\Http\Controllers\Web;
//use Mail;
//validator is builtin class in laravel
use Validator;

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
use Carbon;
use Session;
use Lang;
use App\PaymentsSetting;
use App\Country;
use App\Zone;
use App\PaymentDescription;
use App\Order;
use App\OrdersStatusHistory;
use App\Setting;
use App\OrdersProductsAttribute;
use App\OrdersStatus;
use App\Basket;
use App\OrdersProduct;
use App\Product;
use App\ShippingMethod;
use App\ShippingDescription;
use App\UpsShipping;
use App\FlateRate;
//email
use Illuminate\Support\Facades\Mail;

class OrdersController extends DataController
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
	
	//test stripe
	public function stripeForm(Request $request){
		$title = array('pageTitle' => Lang::get('website.Checkout'));
		$result = array();
		$result['commonContent'] = $this->commonContent();
		return view("stripeForm", $title)->with('result', $result); 
	}
	
	//checkout
	public function checkout(Request $request){
		
		$title = array('pageTitle' => Lang::get('website.Checkout'));
		$result = array();	
		//cart data
		$myVar = new CartController();
		$result['cart'] = $myVar->myCart($result);
				
		if(count($result['cart'])==0){			
			return redirect("/");	
		}else{
		
			//apply coupon
			if(!empty(session('coupon')) and count(session('coupon'))>0){
				$session_coupon_data = session('coupon');
				session(['coupon' => array()]);		
				$response = array();	
				if(!empty($session_coupon_data)){		
					foreach($session_coupon_data as $key=>$session_coupon){	
							$response = $myVar->common_apply_coupon($session_coupon->code);
					}
				}	
				
			}
			
			$result['commonContent'] = $this->commonContent();
			$address = array();
			
			if(empty(session('step'))){
				session(['step' => '0']);
			}
						
			//shipping address
			$myVar = new ShippingAddressController();
			if(!empty(auth()->guard('customer')->user()->customers_default_address_id)){
				$address_id = auth()->guard('customer')->user()->customers_default_address_id;
				$address = $myVar->getShippingAddress($address_id);
				if(!empty($address)){
					$address = $address[0];
				}else{
					$address = '';
				}
			}
			
			if(count(session('shipping_address'))==0){
				session(['shipping_address' => $address]);
			}	
						
			//shipping counties
			if(!empty(session('shipping_address')->countries_id)){			
				$countries_id = session('shipping_address')->countries_id;			
			}else{
				$countries_id	   = '';
			}
			
			
			$result['countries'] = $myVar->countries();
			$result['zones'] = $myVar->zones($countries_id);					
			
			
			//get tax
			if(!empty(session('shipping_address')->zone_id)){
				$tax_zone_id = session('shipping_address')->zone_id;
				$tax = $this->calculateTax($tax_zone_id);
				session(['tax_rate' => $tax]);
			}else{
				session(['tax_rate' => '0']);
			}
			//shipping methods
			$result['shipping_methods'] = $this->shipping_methods();
			
			//payment methods
			$result['payment_methods'] = $this->getPaymentMethods();
			
			
			//price
			$price=0;
			if(count($result['cart']) > 0){
				foreach( $result['cart'] as $products){
					$price+= $products->final_price * $products->customers_basket_quantity;		
				}
				session(['products_price' => $price]);
			}
        	
			
			//breaintree token
			$token = $this->generateBraintreeTokenWeb();
			session(['braintree_token' => $token]);
			return view("checkout", $title)->with('result', $result); 
		}
		
	}
	
	//checkout
	public function checkout_shipping_address(Request $request){
		
		$title = array('pageTitle' => Lang::get('website.Checkout'));
		$result = array();	
		$result['commonContent'] = $this->commonContent();
		
		if(session('step')=='0'){
			session(['step' => '1']);
		}		
				
		foreach($request->all() as $key=>$value)
		{
		  $shipping_data[$key] = $value;
		  
		  //billing address 
		  if($key=='firstname'){
			 $billing_data['billing_firstname'] = $value;
		  }else if($key=='lastname'){
			 $billing_data['billing_lastname'] = $value;
		  }else if($key=='company'){
			 $billing_data['billing_company'] = $value;
		  }else if($key=='street'){
			 $billing_data['billing_street'] = $value;
		  }else if($key=='countries_id'){
			 $billing_data['billing_countries_id'] = $value;
		  }else if($key=='zone_id'){
			 $billing_data['billing_zone_id'] = $value;
		  }else if($key=='city'){
			 $billing_data['billing_city'] = $value;
		  }else if($key=='postcode'){
			 $billing_data['billing_zip'] = $value;
		  }
		  
		}
		
		if(empty(session('billing_address')) or session('billing_address')->same_billing_address==1){
			$billing_address = (object) $billing_data;
			$billing_address->same_billing_address = 1;
			session(['billing_address' => $billing_address]);			
		}
				
		$address = (object) $shipping_data;
		session(['shipping_address' => $address]);
				
		return redirect()->back();	 
	}
	
	
	//checkout_billing_address
	public function checkout_billing_address(Request $request){
				
		if(session('step')=='1'){
			session(['step' => '2']);
		}
		
		if(empty($request->same_billing_address)){
			
			foreach($request->all() as $key=>$value)
			{
			  $billing_data[$key] = $value;		 		  
			}
			
			$billing_address = (object) $billing_data;
			$billing_address->same_billing_address = 0;
			session(['billing_address' => $billing_address]);
		}else{
			
			$billing_address = session('billing_address');
			$billing_address->same_billing_address = 1;
			session(['billing_address' => $billing_address]);
		}
			
		return redirect()->back();		
	}
	
	//checkout_payment_method
	public function checkout_payment_method(Request $request){
		
		if(session('step')=='2'){
			session(['step' => '3']);
		}
		
		$shipping_detail = array();
		foreach($request->all() as $key=>$value){
		  $shipping_detail[$key] = $value;
		}
		
		session(['shipping_detail' => (object) $shipping_detail]);		
		return redirect()->back();		
		
	}
	
	//order_detail
	public function paymentComponent(Request $request){		
		session(['payment_method' => $request->payment_method]);
		return view('paymentComponent');		
	}
	
	//generate token 
	public function generateBraintreeTokenWeb(){
		
		$payments_setting = PaymentsSetting::get();
		
		//braintree transaction get nonce
		$is_transaction  = '0'; 			# For payment through braintree
		
		if($payments_setting[0]->braintree_enviroment == '0'){
			$braintree_environment = 'sandbox';	
		}else{
			$environment = 'production';	
		}
		
		$braintree_merchant_id = $payments_setting[0]->braintree_merchant_id;
		$braintree_public_key  = $payments_setting[0]->braintree_public_key;
		$braintree_private_key = $payments_setting[0]->braintree_private_key;		
		
		//for token please check braintree.php file
		require_once app_path('braintree/Braintree.php');
		
		return $clientToken;
	}
	
	//place_order
	public function place_order(Request $request){		
		
		$date_added								=	date('Y-m-d h:i:s');		
		$customers_id            				=   session('customers_id');
		//$customers_telephone            		=   $request->customers_telephone;
		
		$email            						=   auth()->guard('customer')->user()->email;	
		$delivery_company 						=	session('shipping_address')->company;
		$delivery_firstname  	          		=   session('shipping_address')->firstname;
		
		$delivery_lastname            			=   session('shipping_address')->lastname;
		$delivery_street_address            	=   session('shipping_address')->street;
		$delivery_suburb            			=   '';
		$delivery_city            				=   session('shipping_address')->city;
		$delivery_postcode            			=   session('shipping_address')->postcode;
		
		$delivery = Zone::where('zone_id', '=', session('shipping_address')->zone_id)->get();
		
		if(count($delivery)>0){
			$delivery_state            				=   $delivery[0]->zone_code;
		}else{
			$delivery_state            				=   'other';
		}
				
		$country = Country::where('countries_id','=', session('shipping_address')->countries_id)->get();
		
		$delivery_country            			=   $country[0]->countries_name;		
		
		$billing_firstname            			=   session('billing_address')->billing_firstname;
		$billing_lastname            			=   session('billing_address')->billing_lastname;
		$billing_street_address            		=   session('billing_address')->billing_street;
		$billing_suburb	            			=   '';
		$billing_city            				=   session('billing_address')->billing_city;
		$billing_postcode            			=   session('billing_address')->billing_zip;
		
		if(!empty(session('billing_company')->company)){
			$billing_company 						=	session('billing_address')->company;
		}
		
		$billing =Zone::where('zone_id', '=', session('billing_address')->billing_zone_id)->get();
		
		if(count($billing)>0){
			$billing_state            			=   $billing[0]->zone_code;
		}else{
			$billing_state         				=   'other';
		}
				
		$country = Country::where('countries_id','=', session('billing_address')->billing_countries_id)->get();
		
		$billing_country            			=   $country[0]->countries_name;
		
		$payment_method            				=   session('payment_method');
		$order_information 						=	array();
		
		if(!empty($request->cc_type)){
			$cc_type            				=   $request->cc_type;
			$cc_owner            				=   $request->cc_owner;
			$cc_number            				=   $request->cc_number;
			$cc_expires            				=   $request->cc_expires;
		}else{
			$cc_type            				=   '';
			$cc_owner            				=   '';
			$cc_number            				=   '';
			$cc_expires            				=   '';
		
		}
		
		$last_modified            			=   date('Y-m-d H:i:s');
		$date_purchased            			=   date('Y-m-d H:i:s');
		
		//price
		if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
			$shipping_price = session('shipping_detail')->shipping_price;
		}else{
			$shipping_price = 0;
		}				
		$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
		$coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
		$order_price = (session('products_price')+$tax_rate+$shipping_price)-$coupon_discount;	
								
		$shipping_cost            			=   session('shipping_detail')->shipping_price;
		$shipping_method            		=   session('shipping_detail')->mehtod_name;
		$orders_status            			=   '1';
		//$orders_date_finished            	=   $request->orders_date_finished;
		
		if(!empty(session('order_comments'))){
			$comments						=	session('order_comments');
		}else{
			$comments            			=   '';
		}
		
		$web_setting = Setting::get();
		$currency            				=   $web_setting[19]->value;		
		$total_tax							=	number_format((float)session('tax_rate'), 2, '.', '');		
		$products_tax 						= 	1;		
		
		$coupon_amount = 0;	
		if(!empty(session('coupon')) and count(session('coupon'))>0){
			
			$code = array();	
			$exclude_product_ids = array();
			$product_categories = array();
			$excluded_product_categories = array();
			$exclude_product_ids = array();
			
			$coupon_amount    =		number_format((float)session('coupon_discount'), 2, '.', '')+0;
			
			foreach(session('coupon') as $coupons_data){
				
				//update coupans		
				$coupon_id = DB::statement("UPDATE `coupons` SET `used_by`= CONCAT(used_by,',$customers_id') WHERE `code` = '".$coupons_data->code."'");
							
			}
			$code = json_encode(session('coupon'));
			
		}else{
			$code            					=   '';
			$coupon_amount            			=   '';
		}	
		
		
		//payment methods 
		$payments_setting =PaymentsSetting::get();
		
		if($payment_method == 'braintree'){
			
			//braintree transaction with nonce
			$is_transaction  = '1'; 			# For payment through braintree
			$nonce    		 =   $request->payment_method_nonce;
			
			if($payments_setting[0]->braintree_enviroment == '0'){
				$braintree_environment = 'sandbox';	
			}else{
				$braintree_environment = 'production';	
			}
			
			$braintree_merchant_id = $payments_setting[0]->braintree_merchant_id;
			$braintree_public_key  = $payments_setting[0]->braintree_public_key;
			$braintree_private_key = $payments_setting[0]->braintree_private_key;
			
			//brain tree credential
			require_once app_path('braintree/Braintree.php');
			
			if ($result->success) 
			{
				
			if($result->transaction->id)
				{
					$order_information = array(
						'braintree_id'=>$result->transaction->id,
						'status'=>$result->transaction->status,
						'type'=>$result->transaction->type,
						'currencyIsoCode'=>$result->transaction->currencyIsoCode,
						'amount'=>$result->transaction->amount,
						'merchantAccountId'=>$result->transaction->merchantAccountId,
						'subMerchantAccountId'=>$result->transaction->subMerchantAccountId,
						'masterMerchantAccountId'=>$result->transaction->masterMerchantAccountId,
						//'orderId'=>$result->transaction->orderId,
						'createdAt'=>time(),
//						'updatedAt'=>$result->transaction->updatedAt->date,
						'token'=>$result->transaction->creditCard['token'],
						'bin'=>$result->transaction->creditCard['bin'],
						'last4'=>$result->transaction->creditCard['last4'],
						'cardType'=>$result->transaction->creditCard['cardType'],
						'expirationMonth'=>$result->transaction->creditCard['expirationMonth'],
						'expirationYear'=>$result->transaction->creditCard['expirationYear'],
						'customerLocation'=>$result->transaction->creditCard['customerLocation'],
						'cardholderName'=>$result->transaction->creditCard['cardholderName']
					);
					
					$payment_status = "success";
				}
			} 
			else
				{
					$payment_status = "failed";
				}
				
		}
		else if($payment_method == 'stripe'){				#### stipe payment
		
			//require file
			require_once app_path('stripe/config.php');
			
			//get token from app
			$token  = $request->token;
			
			$customer = \Stripe\Customer::create(array(
			  'email' => $email,
			  'source'  => $token
			));
			
			$charge = \Stripe\Charge::create(array(
			  'customer' => $customer->id,
			  'amount'   => 100*$order_price,
			  'currency' => 'usd'
			));
			
			 if($charge->paid == true){
				 $order_information = array(
						'paid'=>'true',
						'transaction_id'=>$charge->id,
						'type'=>$charge->outcome->type,
						'balance_transaction'=>$charge->balance_transaction,
						'status'=>$charge->status,
						'currency'=>$charge->currency,
						'amount'=>$charge->amount,
						'created'=>date('d M,Y', $charge->created),
						'dispute'=>$charge->dispute,
						'customer'=>$charge->customer,
						'address_zip'=>$charge->source->address_zip,
						'seller_message'=>$charge->outcome->seller_message,
						'network_status'=>$charge->outcome->network_status,
						'expirationMonth'=>$charge->outcome->type
					);
					
					$payment_status = "success";
					
			 }else{
					$payment_status = "failed";	 
			 }
			
		}
		else if($payment_method == 'cash_on_delivery'){
			$cod_description = PaymentDescription::where([['payment_name','Cash On Delivery'],['language_id',Session::get('language_id')]])->get();
			$payment_method = $cod_description[0]->name;
			$payment_status='success';
			
		} 
		else if($payment_method == 'paypal'){
			$paypal_description = PaymentDescription::where([['payment_name','Paypal'],['language_id',Session::get('language_id')]])->get();
			$payment_method = $cod_description[0]->name;
			$payment_status='success';
			$order_information = json_decode($request->nonce, JSON_UNESCAPED_SLASHES);				
		} 
		
		//check if order is verified
		if($payment_status=='success'){
			
			$orders_id = Order::insertGetId(
				[	 'customers_id' => $customers_id,
					 'customers_name'  => $delivery_firstname.' '.$delivery_lastname,
					 'customers_street_address' => $delivery_street_address,
					 'customers_suburb'  =>  $delivery_suburb,
					 'customers_city' => $delivery_city,
					 'customers_postcode'  => $delivery_postcode,
					 'customers_state' => $delivery_state,
					 'customers_country'  =>  $delivery_country,
					 //'customers_telephone' => $customers_telephone,
					 'email'  => $email,
					// 'customers_address_format_id' => $delivery_address_format_id,
					 
					 'delivery_name'  =>  $delivery_firstname.' '.$delivery_lastname,
					 'delivery_street_address' => $delivery_street_address,
					 'delivery_suburb'  => $delivery_suburb,
					 'delivery_city' => $delivery_city,
					 'delivery_postcode'  =>  $delivery_postcode,
					 'delivery_state' => $delivery_state,
					 'delivery_country'  => $delivery_country,
					// 'delivery_address_format_id' => $delivery_address_format_id,
					 
					 'billing_name'  => $billing_firstname.' '.$billing_lastname,
					 'billing_street_address' => $billing_street_address,
					 'billing_suburb'  =>  $billing_suburb,
					 'billing_city' => $billing_city,
					 'billing_postcode'  => $billing_postcode,
					 'billing_state' => $billing_state,
					 'billing_country'  =>  $billing_country,
					 //'billing_address_format_id' => $billing_address_format_id,
					 
					 'payment_method'  =>  $payment_method,
					 'cc_type' => $cc_type,
					 'cc_owner'  => $cc_owner,
					 'cc_number' =>$cc_number,
					 'cc_expires'  =>  $cc_expires,
					 'last_modified' => $last_modified,
					 'date_purchased'  => $date_purchased,
					 'order_price'  => $order_price,
					 'shipping_cost' =>$shipping_cost,
					 'shipping_method'  =>  $shipping_method,
					// 'orders_status' => $orders_status,
					 //'orders_date_finished'  => $orders_date_finished,
					 'currency'  =>  $currency,
					 'order_information' => json_encode($order_information),
					 'coupon_code'		 =>		$code,
					 'coupon_amount' 	 =>		$coupon_amount,
				 	 'total_tax'		 =>		$total_tax,
					 'ordered_source' 	 => 	'1',
				]);
			
			 //orders status history
			 $orders_history_id = OrdersStatusHistory::insertGetId(
				[	 'orders_id'  => $orders_id,
					 'orders_status_id' => $orders_status,
					 'date_added'  => $date_added,
					 'customer_notified' =>'1',
					 'comments'  =>  $comments
				]);
				
				
			 $myVar = new CartController();
			 $cart = $myVar->myCart(array());		 
			 
			 foreach($cart as $products){
				//get produt info	
		
				$orders_products_id = OrdersProduct::insertGetId(
					[		 		
						 'orders_id' 		 => 	$orders_id,
						 'products_id' 	 	 =>		$products->products_id,
						 'products_name'	 => 	$products->products_name,
						 'products_price'	 =>  	$products->price,
						 'final_price' 		 =>  	$products->final_price*$products->customers_basket_quantity,
						 'products_tax' 	 =>  	$products_tax,
						 'products_quantity' =>  	$products->customers_basket_quantity,
					]);
				 
				 
				if(!empty($products->attributes)){
					foreach($products->attributes as $attribute){
						OrdersProductsAttribute::insert(
						[
							 'orders_id' => $orders_id,
							 'products_id'  => $products->products_id,
							 'orders_products_id'  => $orders_products_id,
							 'products_options' =>$attribute->attribute_name,
							 'products_options_values'  =>  $attribute->attribute_value,
							 'options_values_price'  =>  $attribute->values_price,
							 'price_prefix'  =>  $attribute->prefix
						]);						
					}
				}
							
			 }
			
			$responseData = array('success'=>'1', 'data'=>array(), 'message'=>"Order has been placed successfully.");
			
			//send order email to user			
			$order =Order::LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
				->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
			
		//foreach
		foreach($order as $data){
			$orders_id	 = $data->orders_id;
			
			$orders_products =OrdersProduct::join('products', 'products.products_id','=', 'orders_products.products_id')
				->select('orders_products.*', 'products.products_image as image')
				->where('orders_products.orders_id', '=', $orders_id)->get();
				$i = 0;
				$total_price  = 0;
				$product = array();
				$subtotal = 0;
				foreach($orders_products as $orders_products_data){
					$product_attribute = OrdersProductsAttribute::where([
							['orders_products_id', '=', $orders_products_data->orders_products_id],
							['orders_id', '=', $orders_products_data->orders_id],
						])
						->get();
						
					$orders_products_data->attribute = $product_attribute;
					$product[$i] = $orders_products_data;
					//$total_tax	 = $total_tax+$orders_products_data->products_tax;
					$total_price = $total_price+$orders_products[$i]->final_price;					
					$subtotal += $orders_products[$i]->final_price;					
					$i++;
				}
				
			$data->data = $product;
			$orders_data[] = $data;
		}
		
			$orders_status_history = OrdersStatusHistory::LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
				->orderBy('orders_status_history.date_added', 'desc')
				->where('orders_id', '=', $orders_id)->get();
					
			$orders_status = OrdersStatus::get();
					
			$ordersData['orders_data']		 	 	=	$orders_data;
			$ordersData['total_price']  			=	$total_price;
			$ordersData['orders_status']			=	$orders_status;
			$ordersData['orders_status_history']    =	$orders_status_history;
			$ordersData['subtotal']    				=	$subtotal;
			
			// notification/email
			// $myVar = new AlertController();
			//$alertSetting = $myVar->orderAlert($ordersData);
			
			if(session('step')=='4'){
				session(['step' => array()]);
			}	
			
			//change status of cart products
			Basket::where('customers_id',session('customers_id'))->update(['is_order'=>'1']);			
			return redirect('orders')->with('success', Lang::get("website.Payment has been processed successfully"));
		}else if($payment_status == "failed"){
			return redirect()->back()->with('error', Lang::get("website.Error while placing order"));		
		}	
		
	}
	
	
	//orders
	public function orders(Request $request){
		
		$title = array('pageTitle' => Lang::get("website.My Orders"));
		$result = array();			
		
		$result['commonContent'] = $this->commonContent();
		
		//orders		
		$orders = Order::orderBy('date_purchased','DESC')->where('customers_id','=', session('customers_id'))->get();	
		
		$index = 0;
		$total_price = array();
		
		foreach($orders as $orders_data){
			$orders_products = OrdersProduct::select('final_price', DB::raw('SUM(final_price) as total_price'))
				->where('orders_id', '=' ,$orders_data->orders_id)
				->get();
				
			$orders[$index]->total_price = $orders_products[0]->total_price;		
			
			$orders_status_history =OrdersStatusHistory::LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', $orders_data->orders_id)->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->get();
				
			if(!empty($orders_status_history[0]->orders_status_id)){
			$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
		}
		else{
		 $orders[$index]->orders_status_id =0;
			
		}
			$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
			$index++;
		
		}
				
		$result['orders'] = $orders;
		return view("orders", $title)->with('result', $result); 
	}
	
	//viewMyOrder
	public function viewOrder(Request $request){
		
		$title = array('pageTitle' => Lang::get("website.View Order"));
		$result = array();	
		
		$result['commonContent'] = $this->commonContent();
		
		//orders		
		$orders = Order::orderBy('date_purchased','DESC')->where('orders_id','=', $request->id)->where('customers_id',Session('customers_id'))->get();	
		if(count($orders)>0){
		$index = 0;		
		foreach($orders as $orders_data){
				
			$orders_status_history = OrdersStatusHistory::LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', $orders_data->orders_id)->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->get();
			
			$products_array = array();
			$index2 = 0;
			$order_products = OrdersProduct::join('products','products.products_id','=','orders_products.products_id')
				->select('products.products_image as image', 'products.products_model as model', 'orders_products.*')
				->where('orders_id',$orders_data->orders_id)->get();
			
			foreach($order_products as $products){
				array_push($products_array,$products);
				$attributes = OrdersProductsAttribute::where([['orders_id',$products->orders_id],['orders_products_id',$products->orders_products_id]])->get();
				if(count($attributes)==0){
					$attributes = $attributes;
				}
				
				$products_array[$index2]->attributes = $attributes;
				$index2++;
				
			}
			
			$orders_status_history = OrdersStatusHistory::LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
			->orderBy('orders_status_history.date_added', 'desc')
			->where('orders_id', '=', $orders_data->orders_id)->get();
			
			$orders[$index]->statusess = $orders_status_history;
			$orders[$index]->products = $products_array;
			$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
			$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
			$index++;
		
		}
				
		$result['orders'] = $orders;
			return view("view-order", $title)->with('result', $result); 
		}else{
			return redirect('orders');
		}
	}
	
	
	
	//calculate tax
	public function calculateTax($tax_zone_id){
		
		$result = array();
		
		if($tax_zone_id=='Other'){
			$tax = 0;
		}else{
			
			$myVar = new CartController();
			$cart = $myVar->myCart($result);
			
			$index = '0';
			$total_tax = '0';
			
			foreach($cart as $products_data){
				
				$final_price = $products_data->final_price;
				
				$products = Product::LeftJoin('tax_rates', 'tax_rates.tax_class_id','=','products.products_tax_class_id')
					->where('tax_rates.tax_zone_id', $tax_zone_id)
					->where('products_id', $products_data->products_id)->get();
					
				if(count($products)>0){
					$tax_value = $products[0]->tax_rate/100*$final_price;
					$total_tax = $total_tax+$tax_value;
					$index++;	
				}
				
			}
			
			if($total_tax>0){
				$tax = $total_tax;		
			}else{
				$tax = '0';
			}
		}	
		
		return $tax;	
		
	}
	
	
	//shipping methods
	public function shipping_methods(){
		
		$result		  = array();
		if(!empty(session('shipping_address'))){
			$countries_id = session('shipping_address')->countries_id;
			$toPostalCode = session('shipping_address')->postcode;
			$toCity		  = session('shipping_address')->city;
			$toAddress	  = session('shipping_address')->street;
			$countries = Country::where('countries_id','=',$countries_id)->get();
			$toCountry = $countries[0]->countries_iso_code_2;			
			$zone_id = session('shipping_address')->zone_id;					
			if($zone_id!='Other' and !empty($zone_id)){
				$zones = Zone::where('zone_id','=',$zone_id)->get();	
				$toState = $zones[0]->zone_code;			
			}

		}else{
			$countries_id = '';
			$toPostalCode = '';
			$toCity		  = '';
			$toAddress	  = '';
			$toCountry = '';	
			$zone_id = '';
				
		}
		
		
		
		//product weight		
		$myVar = new CartController();
		$cart = $myVar->myCart($result);
		
		$index = '0';
		$total_weight = '0';
			
		foreach($cart as $products_data){	
			if($products_data->unit=='Gram'){
				$productsWeight = $products_data->weight/453.59237;
			}else if($products_data->unit=='Kilogram'){
				$productsWeight = $products_data->weight/0.45359237;
			}else{				
				$productsWeight = $products_data->weight;
			}
						
			$total_weight+=$productsWeight;
		}
		
		$products_weight = $total_weight;
		
		
		//website path
		//$websiteURL =  "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$websiteURL =  "http://" . $_SERVER['SERVER_NAME'].'/demos/website/';
		$replaceURL = str_replace('getRate','', $websiteURL);
		$requiredURL = $replaceURL.'app/ups/ups.php';
		
		//default shipping method
		$shippings = ShippingMethod::get();
		$result = array();
		$mainIndex = 0;
		foreach($shippings as $shipping_methods){
			
			$shippings_detail = ShippingDescription::where('language_id',Session::get('language_id'))->where('table_name',$shipping_methods->table_name)->get();
			
			//ups shipping rate
			if($shipping_methods->methods_type_link == 'upsShipping' and $shipping_methods->status == '1'){
				
				$result2= array();
				$is_transaction = '0';
				
				$ups_shipping = UpsShipping::where('ups_id', '=', '1')->get();
				
				//shipp from and all credentials
				$accessKey  = $ups_shipping[0]->access_key; 	
				$userId 	= $ups_shipping[0]->user_name;			
				$password 	= $ups_shipping[0]->password;
				
				//ship from address
				$fromAddress  = $ups_shipping[0]->address_line_1;
				$fromPostalCode  = $ups_shipping[0]->post_code;
				$fromCity  = $ups_shipping[0]->city;
				$fromState  = $ups_shipping[0]->state;
				$fromCountry  = $ups_shipping[0]->country; 
						
				//production or test mode
				if($ups_shipping[0]->shippingEnvironment == 1){ 			#production mode
					$useIntegration = true;				
				}else{
					$useIntegration = false;								#test mode
				}
				
				$serviceData = explode(',',$ups_shipping[0]->serviceType);				
				
				$index = 0; 
				foreach($serviceData as $value){
					if($value == "US_01")
					{
						$name = Lang::get('website.Next Day Air');
						$serviceTtype = "1DA";
					}
					else if ($value == "US_02")
					{
						$name = Lang::get('website.2nd Day Air');
						$serviceTtype = "2DA";
					}
						else if ($value == "US_03")
					{
						$name = Lang::get('website.Ground');
						$serviceTtype = "GND";
					}
					else if ($value == "US_12")
					{
						$name = Lang::get('website.3 Day Select');
						$serviceTtype = "3DS";
					}
					else if ($value == "US_13")
					{
						$name = Lang::get('website.Next Day Air Saver');
						$serviceTtype = "1DP";
					}
					else if ($value == "US_14")
					{
						$name = Lang::get('website.Next Day Air Early A.M.');
						$serviceTtype = "1DM";
					}
					else if ($value == "US_59")
					{
						$name = Lang::get('website.2nd Day Air A.M.');
						$serviceTtype = "2DM";
					}
					else if($value == "IN_07")
					{
						$name = Lang::get('website.Worldwide Express');
						$serviceTtype = "UPSWWE";
					}
					else if ($value == "IN_08")
					{
						$name = Lang::get('website.Worldwide Expedited');
						$serviceTtype = "UPSWWX";
					}
					else if ($value == "IN_11")
					{
						$name = Lang::get('website.Standard');
						$serviceTtype = "UPSSTD";
					}
					else if ($value == "IN_54")
					{
						$name = Lang::get('website.Worldwide Express Plus');
						$serviceTtype = "UPSWWEXPP";
					}
					
				$some_data = array(
					'access_key' => $accessKey,  						# UPS License Number
					'user_name' => $userId,								# UPS Username
					'password' => $password, 							# UPS Password
					'pickUpType' => '03',								# Drop Off Location
					'shipToPostalCode' => $toPostalCode, 				# Destination  Postal Code
					'shipToCountryCode' => $toCountry,					# Destination  Country
					'shipFromPostalCode' => $fromPostalCode, 			# Origin Postal Code
					'shipFromCountryCode' => $fromCountry,				# Origin Country
					'residentialIndicator' => 'IN', 					# Residence Shipping and for commercial shipping "COM"
					'cServiceCodes' => $serviceTtype, 					# Sipping rate for UPS Ground 
					'packagingType' => '02',
					'packageWeight' => $productsWeight
				  );  
				 
				  $curl = curl_init();
				  // You can also set the URL you want to communicate with by doing this:
				  // $curl = curl_init('http://localhost/echoservice');
				   
				  // We POST the data
				  curl_setopt($curl, CURLOPT_POST, 1);
				  // Set the url path we want to call
				  curl_setopt($curl, CURLOPT_URL, $requiredURL);  
				  // Make it so the data coming back is put into a string
				  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				  // Insert the data
				  curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
				   
				  // You can also bunch the above commands into an array if you choose using: curl_setopt_array
				   
				  // Send the request
				  $rate = curl_exec($curl);
				  // Free up the resources $curl is using
				  curl_close($curl);
				  
				 if (is_numeric($rate)){
					$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$shippings_detail[0]->name, 'is_default'=>$shipping_methods->isDefault);
					$result2[$index] = array('name'=>$name,'rate'=>$rate,'currencyCode'=>'USD','shipping_method'=>'upsShipping');
					$index++;
				 }
				 else{
					$success = array('success'=>'0','message'=>"Selected regions are not supported for UPS shipping", 'name'=>$shippings_detail[0]->name);
				 }
				  $success['services'] = $result2;	
				}
				$result[$mainIndex] = $success;
				$mainIndex++;
				
				
			}else if($shipping_methods->methods_type_link == 'flateRate' and $shipping_methods->status == '1'){
				$ups_shipping = FlateRate::where('id', '=', '1')->get();
				$data2 =  array('name'=>$shippings_detail[0]->name,'rate'=>$ups_shipping[0]->flate_rate,'currencyCode'=>$ups_shipping[0]->currency,'shipping_method'=>'flateRate');
				if(count($ups_shipping)>0){
					$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$shippings_detail[0]->name, 'is_default'=>$shipping_methods->isDefault);
					$success['services'][0] = $data2;
					$result[$mainIndex] = $success;
				 	$mainIndex++;
				}
				
				
			}else if($shipping_methods->methods_type_link == 'localPickup' and $shipping_methods->status == '1') {
							
				$data2 =  array('name'=>$shippings_detail[0]->name, 'rate'=>'0', 'currencyCode'=>'USD', 'shipping_method'=>'localPickup');
				$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$shippings_detail[0]->name, 'is_default'=>$shipping_methods->isDefault);
				$success['services'][0] = $data2;
				$result[$mainIndex] = $success;
				$mainIndex++;
					
			}else if($shipping_methods->methods_type_link == 'freeShipping'  and $shipping_methods->status == '1'){
						
				$data2 =  array('name'=>$shippings_detail[0]->name,'rate'=>'0','currencyCode'=>'USD','shipping_method'=>'freeShipping');
				$success = array('success'=>'1', 'message'=>"Rate is returned.", 'name'=>$shippings_detail[0]->name, 'is_default'=>$shipping_methods->isDefault);
				$success['services'][0] = $data2;
				$result[$mainIndex] = $success;
				$mainIndex++;
			}
		}
		
		return $result;
	}
		
	//get default payment method
	public function getPaymentMethods(){
		$result = array();
		$payments_setting = DB::table('payments_setting')->get();
		
		if($payments_setting[0]->braintree_enviroment=='0'){
			$braintree_enviroment = 'Test';
		}else{
			$braintree_enviroment = 'Live';
		}
		
		$braintree_description = PaymentDescription::where([['payment_name','Braintree'],['language_id',Session::get('language_id')]])->get();
		$braintree = array(
			'environment' => $braintree_enviroment, 
			'name' => $braintree_description[0]->name, 
			'public_key' => $payments_setting[0]->braintree_public_key,
			'active' => $payments_setting[0]->brantree_active,
			'payment_currency' => $payments_setting[0]->payment_currency,
			'payment_method'=>'braintree',
		);
		
		if($payments_setting[0]->stripe_enviroment=='0'){
			$stripe_enviroment = 'Test';
		}else{
			$stripe_enviroment = 'Live';
		}
		
		$stripe_description = PaymentDescription::where([['payment_name','Stripe'],['language_id',Session::get('language_id')]])->get();
		$stripe = array(
			'environment' => $stripe_enviroment,
			'name' => $stripe_description[0]->name, 
			'public_key' => $payments_setting[0]->publishable_key,
			'active' => $payments_setting[0]->stripe_active,
			'payment_currency' => $payments_setting[0]->payment_currency,
			'payment_method'=>'stripe'
		);
		
		$cod_description = PaymentDescription::where([['payment_name','Cash On Delivery'],['language_id',Session::get('language_id')]])->get();
		$cod = array(
			'environment' => '', 
			'name' => $cod_description[0]->name, 
			'public_key' => '',
			'active' => $payments_setting[0]->cash_on_delivery,
			'payment_currency' => $payments_setting[0]->payment_currency,
			'payment_method'=>'cash_on_delivery'
		);
		
		if($payments_setting[0]->paypal_enviroment=='0'){
			$paypal_enviroment = 'Test';
		}else{
			$paypal_enviroment = 'Live';
		}		
		
		$paypal_description = PaymentDescription::where([['payment_name','Paypal'],['language_id',Session::get('language_id')]])->get();
		$paypal = array(
			'environment' => $paypal_enviroment, 
			'name' => $paypal_description[0]->name, 
			'public_key' => $payments_setting[0]->paypal_id,
			'active' => $payments_setting[0]->paypal_status,
			'payment_currency' => $payments_setting[0]->payment_currency,
			'payment_method'=>'paypal'
		);
		
		$result[0] = $braintree;
		$result[1] = $stripe;
		$result[2] = $cod;
		$result[3] = $paypal;
		
		return $result;
	}
	
	public function commentsOrder(Request $request){
		session(['order_comments' => $request->comments]);
	}
	
}
