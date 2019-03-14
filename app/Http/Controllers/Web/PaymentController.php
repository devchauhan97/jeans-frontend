<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
 
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
use App\Libraries\moneris\mpgTransaction;
use App\Libraries\moneris\mpgRequest;
use App\Libraries\moneris\mpgHttpsPost;
use App\Libraries\moneris\mpgCvdInfo;
use App\Libraries\moneris\mpgAvsInfo;
use App\Transaction;
use App\Events\SendProductOrderMail;
use App\AddressBook;
use Event;
use App\PaymentHistory;

class PaymentController extends DataController
{

    public function payment(Request $request) {

		$title = array('pageTitle' => Lang::get('website.Payment'));
		$result = array();	
		$myVar = new CartController();
		$result['cart'] = $myVar->myCart($result);
		$result['commonContent'] = $this->commonContent();
		
		if( !count( $result['cart']) ){

			return redirect()->route('404')->withErrors('Does not found cart items.');

		}

		$orders = Order::where('unique_order_id', session()->get('unique_order_id'))->first();

		if( !count($orders) ) {
		
			return redirect('/400')->withErrors(['Order not Found.']);
		
		}

		//$ord_details = $this->getOrderDetails($request->order_id);
		
		//$result['orders_data']	 = @$ord_details['orders_data'][0];
		//$data1['order_price']	 = $ord_details['orders_data'][0]->order_price;
		
		$result['data_key'] = DB::table('data_key')
								->where([
									['CustID', '=', Auth::guard('customer')->user()->customers_id],
									['dataKey', '!=', '0'],
									['status', '=', 'Active']
								])
								->get();
		//$transactions= Transaction::create(['customers_id'=>Auth::guard('customer')->user()->customers_id])->id;
		
		//$orders = Order::where('orders_id', session()->get('unique_order_id'))->first();

		// $order_id = $this->createOrder();
		// $orders = Order::where('orders_id', $order_id)->first();

		// if( !$orders ) {
		// 	dd($order_id);
		// 	$unique_order_id = $orders->unique_order_id;
		// } else {
		// 	$unique_order_id = $orders->unique_order_id;
		// }
		//*********
		//************
		///session()->put('unique_order_id', $unique_order_id);
		//session()->put('payment', 1);
		//$result['p_sess'] = $request->session()->get('payment_' . $);

		return view('payment', $title)->with('result', $result);
		 
	}

	public function checkoutOrderPayment() {

		//**************
		// ****Create order
		// ************************* 
		$date_added								=	date('Y-m-d h:i:s');		
		$customers_id            				=   session('customers_id');
		$customers_telephone            		=   session('shipping_address')->phone_no;
		
		$email            						=   auth()->guard('customer')->user()->email;	
		$delivery_company 						=	'';
		$delivery_firstname  	          		=   session('shipping_address')->firstname;
		
		$delivery_lastname            			=   session('shipping_address')->lastname;
		$delivery_street_address            	=   session('shipping_address')->street;
		$delivery_suburb            			=   '';
		$delivery_city            				=   session('shipping_address')->city;
		$delivery_postcode            			=   session('shipping_address')->postcode;
		
		$delivery = Zone::where('zone_id', '=', session('shipping_address')->zone_id)->get();
		
		if( count($delivery) > 0 ) {
			$delivery_state            				=   $delivery[0]->zone_code;
		} else {
			$delivery_state            				=   'other';
		}
				
		$country = Country::where('countries_id','=', session('shipping_address')->countries_id)->get();
		
		$delivery_country            			=   $country[0]->countries_name;		
		
		/*$billing_firstname            			=   session('billing_address')->billing_firstname;
		$billing_lastname            			=   session('billing_address')->billing_lastname;
		$billing_street_address            		=   session('billing_address')->billing_street;
		$billing_suburb	            			=   '';
		$billing_city            				=   session('billing_address')->billing_city;
		$billing_postcode            			=   session('billing_address')->billing_zip;
		
		if(!empty(session('billing_company')->company)) {
			$billing_company 						=	session('billing_address')->company;
		}
		
		$billing =Zone::where('zone_id', '=', session('billing_address')->billing_zone_id)->get();
		
		if(count($billing)>0) {
			$billing_state            			=   $billing[0]->zone_code;
		} else {
			$billing_state         				=   'other';
		}
				
		$country = Country::where('countries_id','=', session('billing_address')->billing_countries_id)->get();
		
		$billing_country            			=   $country[0]->countries_name;
		*/
		$payment_method            				=   'moneris';
		$order_information 						=	array();
		
		if( !empty($request->cc_type) ) {
			$cc_type            				=   $request->cc_type;
			$cc_owner            				=   $request->cc_owner;
			$cc_number            				=   $request->cc_number;
			$cc_expires            				=   $request->cc_expires;
		} else {
			$cc_type            				=   '';
			$cc_owner            				=   '';
			$cc_number            				=   '';
			$cc_expires            				=   '';
		
		}
		
		$last_modified            			=   date('Y-m-d H:i:s');
		$date_purchased            			=   date('Y-m-d H:i:s');
		
		//price
		if( !empty(session('shipping_detail')) and count(session('shipping_detail')) > 0 ) {
			$shipping_price = session('shipping_detail')->shipping_price;
		} else {
			$shipping_price = 0;
		}				
		$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
		$coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
		$order_price = (session('products_price')+$tax_rate+$shipping_price)-$coupon_discount;	
								
		$shipping_cost            			=   0;//session('shipping_detail')->shipping_price;
		$shipping_method            		=  '';// session('shipping_detail')->mehtod_name;
		$orders_status            			=   1;
		//$orders_date_finished            	=   $request->orders_date_finished;
		
		if(!empty(session('order_comments'))) {
			$comments						=	session('order_comments');
		} else {
			$comments            			=   '';
		}
		
		$web_setting = Setting::get();
		$currency            				=   $web_setting[19]->value;		
		$total_tax							=	number_format((float)session('tax_rate'), 2, '.', '');		
		$products_tax 						= 	1;		
		
		$coupon_amount = 0;	

		if(!empty(session('coupon')) and count(session('coupon'))>0) {
			
			$code = array();	
			$exclude_product_ids = array();
			$product_categories = array();
			$excluded_product_categories = array();
			$exclude_product_ids = array();
			
			$coupon_amount    =		number_format((float)session('coupon_discount'), 2, '.', '')+0;
			
			$code = json_encode(session('coupon'));
			
		} else {

			$code            					=   '';
			$coupon_amount            			=   '';

		}	

		//payment methods 
		$payments_setting = PaymentsSetting::get();

		$unique_order_id = getNextOrderNumber();//genrate random order ids

		try {

			DB::beginTransaction();
			$orders_id = Order::create(
					[	 'customers_id' => $customers_id,
						 'customers_name'  => $delivery_firstname.' '.$delivery_lastname,
						 'customers_street_address' => $delivery_street_address,
						 'customers_suburb'  =>  $delivery_suburb,
						 'customers_city' => $delivery_city,
						 'customers_postcode'  => $delivery_postcode,
						 'customers_state' => $delivery_state,
						 'customers_country'  =>  $delivery_country,
						 'customers_telephone' => $customers_telephone,
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
						 
						/* 'billing_name'  => $billing_firstname.' '.$billing_lastname,
						 'billing_street_address' => $billing_street_address,
						 'billing_suburb'  =>  $billing_suburb,
						 'billing_city' => $billing_city,
						 'billing_postcode'  => $billing_postcode,
						 'billing_state' => $billing_state,
						 'billing_country'  =>  $billing_country,*/
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
						 'order_information' =>	'',// json_encode($order_information),
						 'coupon_code'		 =>		$code,
						 'coupon_amount' 	 =>		$coupon_amount,
					 	 'total_tax'		 =>		$total_tax,
						 'ordered_source' 	 => 	'1',
						 'unique_order_id'	=> $unique_order_id,
					])->orders_id;
				
			 	//orders status history
				OrdersStatusHistory::create(
					[	 'orders_id'  => $orders_id,
						 'orders_status_id' => $orders_status,
						 'date_added'  => $date_added,
						 'customer_notified' =>'1',
						 'comments'  =>  $comments
					]);

			$myVar = new CartController();
			$cart = $myVar->myCart(array());		 
			 
			foreach( $cart as $products ) {
			//get produt info	

				$orders_products_id = OrdersProduct::create(
					[		 		
						 'orders_id' 		 => 	$orders_id,
						 'products_id' 	 	 =>		$products->products_id,
						 'products_model'	 => 	$products->products_model,
						 'semi_stitched'	 => 	$products->semi_stitched,
						 'products_name'	 => 	$products->products_name,
						 'products_price'	 =>  	$products->final_price,
						 'final_price' 		 =>  	$products->final_price*$products->customers_basket_quantity,
						 'products_tax' 	 =>  	$products_tax,
						 'products_quantity' =>  	$products->customers_basket_quantity,
					])->orders_products_id;
				/*
				*Reduce product quantity
				******/ 
				Product::where('products_id',$products->products_id)->decrement('products_quantity',$products->customers_basket_quantity);

				if(!empty($products->attributes)){
					foreach($products->attributes as $attribute){
						OrdersProductsAttribute::create(
						[
							 'orders_id' 					=> $orders_id,
							 'products_id'  				=> $products->products_id,
							 'orders_products_id'  			=> $orders_products_id,
							 'products_options_values_id' 	=>$attribute->products_options_values_id,
							 'products_options' 			=>$attribute->attribute_name,
							 'products_options_values'  	=>  $attribute->attribute_value,
							 'options_values_price'  		=>  $attribute->values_price,
							 'price_prefix'  				=>  $attribute->prefix
						]);						
					}
				}
						
			}	
			
			$orders = Order::where('orders_id', $orders_id)->first();

			//*********
			//************
			session()->put('orders_id', $orders_id);
			session()->put('unique_order_id', $orders->unique_order_id);
			DB::commit();
			return redirect('payment');
			//return $orders_id;
		} catch (\Exception $e) {
		    
		    DB::rollback();
		    $msg = $e->getMessage();
		    return redirect('/checkout')->withErrors([$msg]);
		}
	}
	
	private function getOrderDetails($order_id) {
		//send order email to user			
		$order = DB::table('orders')
			->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
			->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
			->where('orders.orders_id', '=', $order_id)->orderby('orders_status_history.date_added', 'DESC')->get();
			
		//foreach
		foreach($order as $data){
			$orders_id	 = $data->orders_id;
			$orders_products = DB::table('orders_products')
								->join('products', 'products.products_id','=', 'orders_products.products_id')
								->select('orders_products.*', 'products.products_image as image')
								->where('orders_products.orders_id', '=', $orders_id)->get();

			$i = 0;
			$total_price  = 0;
			$product = array();
			$subtotal = 0;
			foreach($orders_products as $orders_products_data) {

				$product_attribute = DB::table('orders_products_attributes')
					->where([
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
		
		return $ordersData;
	}

	public function newCardPayment(Request $request) {

		//$ord_details = $this->getOrderDetails($request->order_id);
		
		//$ord_det = $ord_details['orders_data'][0];
		 
		$user = Auth::guard('customer')->user();
		$cust_id = $user->customers_id;
 
		$orders = Order::where('unique_order_id', session()->get('unique_order_id'))->first();

		if( !count($orders) ) {
		
			return redirect('/400')->withError('Order not Found');
		
		}

		$orders_id = $orders->unique_order_id;

		if( $orders_id ) {
			// Include the moneris library
			require app_path('Libraries/moneris/mpgClasses.php');

			$store_id = env('MONERIS_STORE_ID');
			$api_token = env('MONERIS_API_TOKEN');
			$monerisGatewayMode = env('MONERIS_GATEWAY_MODE');

			if( !empty( $request->cardAccptance ) && $request->cardAccptance == 'Yes' ) {
				$cardAccptance = $request->cardAccptance;
			} else {
				$cardAccptance = 'No';
			}

			$pan 				= $request->pan;
			$expiry_date 		= $request->expiry_date;
			$avs_street_number 	= $request->avs_street_number;
			$avs_street_name 	= $request->avs_street_name;
			$avs_zipcode 		= $request->avs_zipcode;
			$phone 				= $user->customers_telephone;
			$email 				= $user->customers_email_address;
			$note 				= '';

			//price
			/*
			if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
				$shipping_price = session('shipping_detail')->shipping_price;
			} else {
				$shipping_price = 0;
			}	
			$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
			$coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
			$order_price = (session('products_price')+$tax_rate+$shipping_price)-$coupon_discount;
			*/


			if( !empty($cardAccptance) && $cardAccptance == 'Yes' ) {

				$type='res_add_cc';
				$crypt_type='1';	
				// Transactional Associative Array
				$txnArray=array('type' => $type,
					'cust_id' => $cust_id,
					'phone' => $phone,
					'email' => $email,
					'note' => $note,
					'pan' => $pan,
					'expdate' => $expiry_date,
					'crypt_type' => $crypt_type
				);
				// AVS Associative Array
				$avsTemplate = array(
							'avs_street_number' => $avs_street_number,
							'avs_street_name' => $avs_street_name,
							'avs_zipcode' => $avs_zipcode
						);
				// AVS Object
				$mpgAvsInfo = new mpgAvsInfo($avsTemplate);
				// Transaction Object

				$mpgTxn = new mpgTransaction($txnArray);				
				$mpgTxn->setAvsInfo($mpgAvsInfo);
				// Request Object

				$mpgRequest = new mpgRequest($mpgTxn);
				$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
				$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

				// HTTPS Post Object

				$mpgHttpPost  = new mpgHttpsPost($store_id, $api_token, $mpgRequest);

				// Response
				$mpgResponse = $mpgHttpPost->getMpgResponse();	

				//print_r($mpgResponse);exit;
				if( $mpgResponse->getResSuccess() == true ) {

					$data_key	= $mpgResponse->getDataKey();

					$amount 	= $orders->order_price;
					$custid 	= $cust_id;
					$crypt_type = '1';
					$expdate 	= $request->expiry_date;

					$txnArray=array(
						'type' 				=> 'res_purchase_cc',
						'data_key'			=> $data_key,
				        'order_id'			=> $orders_id ,
				        'cust_id'			=> $custid,
				        'amount'			=> $amount,
				        'crypt_type'		=> $crypt_type,
						'expdate'			=> $expdate,
						'dynamic_descriptor'=>'12484'
					   );
					   
				   // CVD Variables

					$cvd_indicator = '1';
					$cvd_value = $request->cvd_value;

					// CVD Associative Array

					$cvdTemplate = array(
							'cvd_indicator' => $cvd_indicator,
							'cvd_value' => $cvd_value
					);

					$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

					$mpgTxn = new mpgTransaction($txnArray);
					$mpgTxn->setCvdInfo($mpgCvdInfo);
					
					$mpgRequest = new mpgRequest($mpgTxn);
					$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
					$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

					$mpgHttpPost  = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

					$mpgResponse = '';
					$mpgResponse = $mpgHttpPost->getMpgResponse();

					$addCardData = array(
							'cardAccptance' => $cardAccptance,
							'dataKey' 		=> $mpgResponse->getDataKey(),
							'ReceiptId' 	=> $mpgResponse->getReceiptId(),
							'ReferenceNum' 	=> $mpgResponse->getReferenceNum(),
							'ResponseCode' 	=> $mpgResponse->getResponseCode(),
							'ISO' 			=> $mpgResponse->getISO(),
							'AuthCode' 		=> $mpgResponse->getAuthCode(),
							'Message' 		=> $mpgResponse->getMessage(),
							'TransDate' 	=> $mpgResponse->getTransDate(),
							'TransTime' 	=> $mpgResponse->getTransTime(),
							'TransType' 	=> $mpgResponse->getTransType(),
							'Complete' 		=> $mpgResponse->getComplete(),
							'TransAmount' 	=> $mpgResponse->getTransAmount(),
							'CardType' 		=> $mpgResponse->getCardType(),
							'TxnNumber' 	=> $mpgResponse->getTxnNumber(),
							'TimedOut' 		=> $mpgResponse->getTimedOut(),
							'AVSResponse' 	=> $mpgResponse->getAvsResultCode(),
							'ResSuccess' 	=> $mpgResponse->getResSuccess(),
							'PaymentType' 	=> $mpgResponse->getPaymentType(),
							'CustID' 		=> $mpgResponse->getResDataCustId(),
							'Phone' 		=> $mpgResponse->getResDataPhone(),
							'Email' 		=> $mpgResponse->getResDataEmail(),
							'Note' 			=> $mpgResponse->getResDataNote(),
							'MaskedPan' 	=> $mpgResponse->getResDataMaskedPan(),
							'ExpDate' 		=> $mpgResponse->getResDataExpDate(),
							'CryptType' 	=> $mpgResponse->getResDataCryptType(),
							'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
							'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
							'AvsZipcode' 	=> $mpgResponse->getResDataAvsZipcode(),
							'CvdResultCode' => $mpgResponse->getCvdResultCode(),
							'IsVisaDebit' 	=> '',
							'Ticket' 		=> '',
							'StatusCode' 	=> '',
							'StatusMessage' => '',
							'payment_method' => 'Vault',
						);

					//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

					if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {

						$data_key_row_count = DB::table('data_key')
												->where('MaskedPan', $addCardData['MaskedPan'])
												->where('CustID', $cust_id)->count();
						if( !$data_key_row_count ) {
							DB::table('data_key')->insert($addCardData);
						}
						$addCardData['unique_order_id'] = $orders_id;
						$this->paymentSuccessUpdate($addCardData);
						session()->put('orders_status', 1);
						//return redirect()->route('paymentSuccessVault', [base64_encode($addCardData['ReceiptId'])]);
					} else {
						//dd($mpgResponse->getMpgResponseData()['Message']);
						$addCardData['unique_order_id'] = $orders_id;
						$this->paymentFailedUpdate($addCardData);
						session()->put('orders_status', 0);
						return redirect('payment-failure/'.$orders_id);
					}

				} else {
					//dd($mpgResponse->getMpgResponseData());
					$addCardData['unique_order_id'] = $orders_id;
					session()->put('orders_status', 1);
					$this->paymentFailedUpdate($addCardData);
					return redirect('payment-failure/'.$orders_id);//->withErrors([$mpgResponse->getMpgResponseData()['Message']]);
				}

			} else { 

				$type 				= 'purchase';
				$amount 	= $orders->order_price;
				$crypt  			='7';
				$dynamic_descriptor ='123';
				$status_check 		= 'false';
				// Transactional Associative Array
				$txnArray =array('type'			=>$type,
				     		    'order_id'		=>$order_id ,
				     		    'cust_id'		=>$cust_id,
				    		    'amount'		=>$amount,
				   			    'pan'			=>$pan,
				   			    'expdate'		=>$expiry_date,
				   			    'crypt_type'	=>$crypt,
				   			    'dynamic_descriptor'=>$dynamic_descriptor
				   		       );
				
				// CVD Variables
				$cvd_indicator = '1';
				$cvd_value = $request->cvd_value;

				// CVD Associative Array
				$cvdTemplate = array(
									'cvd_indicator' => $cvd_indicator,
									'cvd_value' => $cvd_value
								);

				$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
				// AVS Associative Array

				$avsTemplate = array(
									'avs_street_number' => $avs_street_number,
									'avs_street_name' 	=> $avs_street_name,
									'avs_zipcode' 		=> $avs_zipcode
								);

				// AVS Object
				$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

				// Transaction Object
				$mpgTxn = new mpgTransaction($txnArray);
				$mpgTxn->setCvdInfo($mpgCvdInfo);
				$mpgTxn->setAvsInfo($mpgAvsInfo);

				// Request Object
				$mpgRequest = new mpgRequest($mpgTxn);
				$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
				$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

				// HTTPS Post Object
				$mpgHttpPost  = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

				// Response
				$mpgResponse = $mpgHttpPost->getMpgResponse();

				$addCardData = array(
						'cardAccptance' => $cardAccptance,
						'dataKey' => '0',
						'ReceiptId' => $mpgResponse->getReceiptId(),
						'ReferenceNum' => $mpgResponse->getReferenceNum(),
						'ResponseCode' => $mpgResponse->getResponseCode(),
						'ISO' => $mpgResponse->getISO(),
						'AuthCode' => $mpgResponse->getAuthCode(),
						'Message' => $mpgResponse->getMessage(),
						'TransDate' => $mpgResponse->getTransDate(),
						'TransTime' => $mpgResponse->getTransTime(),
						'TransType' => $mpgResponse->getTransType(),
						'Complete' => $mpgResponse->getComplete(),
						'TransAmount' => $mpgResponse->getTransAmount(),
						'CardType' => $mpgResponse->getCardType(),
						'TxnNumber' => $mpgResponse->getTxnNumber(),
						'TimedOut' => $mpgResponse->getTimedOut(),
						'AVSResponse' => $mpgResponse->getAvsResultCode(),
						'ResSuccess' => '',
						'PaymentType' => '',
						'CustID' => '',
						'Phone' => '',
						'Email' => '',
						'Note' => '',
						'MaskedPan' => $mpgResponse->getResDataMaskedPan(),
						'ExpDate' => $mpgResponse->getResDataExpDate(),
						'CryptType' => $mpgResponse->getResDataCryptType(),
						'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
						'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
						'AvsZipcode' => $mpgResponse->getResDataAvsZipcode(),
						'CvdResultCode' => $mpgResponse->getCvdResultCode(),
						'IsVisaDebit' => $mpgResponse->getIsVisaDebit(),
						'Ticket' => $mpgResponse->getTicket(),
						'StatusCode' => $mpgResponse->getStatusCode(),
						'StatusMessage' => $mpgResponse->getStatusMessage(),
						'payment_method' => 'Credit Card',
					);	

				//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

				if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {

					$addCardData['unique_order_id'] = $orders_id;
					$this->paymentSuccessUpdate($addCardData);
					session()->put('orders_status', 1);
					return redirect('payment-success/'.$orders_id);

				} else {
					//dd( $mpgResponse->getMpgResponseData() );
					$addCardData['unique_order_id'] = $orders_id;
					$this->paymentFailedUpdate($addCardData);
					session()->put('orders_status', 0);
					return redirect('payment-failure/'.$orders_id);
				}
			}
		}

		//$data['unique_order_id'] = $ord_det->unique_order_id;
		/*if( session()->get('orders_status') == 1 ) {
			return view('payment-success', $data);
		} else {
			return view('payment-failure', $data);
		}*/
	}

	public function savedCardPayment(Request $request) {

		//$ord_details = $this->getOrderDetails($request->order_id);
		
		//$ord_det = $ord_details['orders_data'][0];

		$custid = Auth::guard('customer')->user()->customers_id;

		$data_key_row = DB::table('data_key')
						->where([
							['CustID', '=', $custid],
							['dataKey', '!=', '0'],
							['status', '=', 'Active'],
							['id', '=', $request->card_id]
						])->first();

		if(!count($data_key_row)) {
		
			return redirect('/400')->withError(['Cardholder no found']);
		
		}

		$orders = Order::where('unique_order_id', session()->get('unique_order_id'))->first();

		if( !count($orders) ) {
		
			return redirect('/400')->withErrors(['Order not Found']);
		
		}

		$orders_id = $orders->unique_order_id;
		 
		if( $orders_id ) {
			// Include the moneris library
			require app_path('Libraries/moneris/mpgClasses.php');

			$store_id = env('MONERIS_STORE_ID');
			$api_token = env('MONERIS_API_TOKEN');
			$monerisGatewayMode = env('MONERIS_GATEWAY_MODE');
			$data_key	= $data_key_row->dataKey;
			//$orderid 	= $ord_det->unique_order_id;

			//price
			if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0) {
				$shipping_price = session('shipping_detail')->shipping_price;
			} else {
				$shipping_price = 0;
			}

			$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
			$coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
			$order_price = (session('products_price')+$tax_rate+$shipping_price)-$coupon_discount;	

			$amount 	= $orders->order_price;
			$expdate 	= $data_key_row->ExpDate;
			$crypt_type = '1';

			$txnArray = array(
							'type' 				=> 'res_purchase_cc',
							'data_key'			=> $data_key,
						    'order_id'			=> $orders_id ,
						    'cust_id'			=> $custid,
						    'amount'			=> $amount,
						    'crypt_type'		=> $crypt_type,
							'expdate'			=> $expdate,
							'dynamic_descriptor'=> '12484'
					    );

			$mpgTxn = new mpgTransaction($txnArray);		

			$mpgRequest = new mpgRequest($mpgTxn);
			$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
			$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions
			
			$mpgHttpPost  = new mpgHttpsPost($store_id, $api_token, $mpgRequest);
			//dd($mpgHttpPost->getMpgResponse());

			$mpgResponse = $mpgHttpPost->getMpgResponse();
			//dd($mpgResponse->getMpgResponseData());
			//print_r($mpgResponse);exit;

			$addCardData = array(
								'cardAccptance' => 'No',
								//'dataKey' 		=> $mpgResponse->getDataKey(),
								'dataKey' 		=> '0',
								'ReceiptId' 	=> $mpgResponse->getReceiptId(),
								'ReferenceNum' 	=> $mpgResponse->getReferenceNum(),
								'ResponseCode' 	=> $mpgResponse->getResponseCode(),
								'ISO' 			=> $mpgResponse->getISO(),
								'AuthCode' 		=> $mpgResponse->getAuthCode(),
								'Message' 		=> $mpgResponse->getMessage(),
								'TransDate' 	=> $mpgResponse->getTransDate(),
								'TransTime' 	=> $mpgResponse->getTransTime(),
								'TransType' 	=> $mpgResponse->getTransType(),
								'Complete' 		=> $mpgResponse->getComplete(),
								'TransAmount' 	=> $mpgResponse->getTransAmount(),
								'CardType' 		=> $mpgResponse->getCardType(),
								'TxnNumber' 	=> $mpgResponse->getTxnNumber(),
								'TimedOut' 		=> $mpgResponse->getTimedOut(),
								'AVSResponse' 	=> $mpgResponse->getAvsResultCode(),
								'ResSuccess' 	=> $mpgResponse->getResSuccess(),
								'PaymentType' 	=> $mpgResponse->getPaymentType(),
								'CustID' 		=> $mpgResponse->getResDataCustId(),
								'Phone' 		=> $mpgResponse->getResDataPhone(),
								'Email' 		=> $mpgResponse->getResDataEmail(),
								'Note' 			=> $mpgResponse->getResDataNote(),
								'MaskedPan' 	=> $mpgResponse->getResDataMaskedPan(),
								'ExpDate' 		=> $mpgResponse->getResDataExpDate(),
								'CryptType' 	=> $mpgResponse->getResDataCryptType(),
								'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
								'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
								'AvsZipcode' 	=> $mpgResponse->getResDataAvsZipcode(),
								'CvdResultCode' => $mpgResponse->getCvdResultCode(),
								'IsVisaDebit' 	=> '',
								'Ticket' 		=> '',
								'StatusCode' 	=> '',
								'StatusMessage' => '',
								'payment_method' => 'Vault',
							);

			//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

			if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {

				$addCardData['unique_order_id'] = $orders_id;
				$this->paymentSuccessUpdate($addCardData);
				$request->session()->put('orders_status', 1);
				return redirect('payment-success/'.$orders_id);

			} else {
				//$mpgResponse->getMpgResponseData()['Message']
				$addCardData['unique_order_id'] = $orders_id;
				$this->paymentFailedUpdate($addCardData);
				session()->put('orders_status', 0);
				//dd($mpgResponse->getMpgResponseData());
				//return redirect()->route('payment')->withErrors([$mpgResponse->getMpgResponseData()['Message']]);
				return redirect('payment-failure/'.$orders_id);
			}

			$request->session()->forget('payment');
			$request->session()->forget('payment_' . $request->order_id);
		}

		$data['unique_order_id'] = $orders->orders_id;

		// if( $request->session()->get('payment_status') == 1 ) {
		// 	return view('payment-success', $data);
		// } else {
		// 	return view('payment-failure', $data);
		// }
	}

	
	public function paymentSuccess($id){
		//$data['unique_order_id'] = base64_decode($id);
		$title = array('pageTitle' => Lang::get('website.Payment Success'));
		$result = array();	
		$myVar = new CartController();
		$result['cart'] = $myVar->myCart($result);
		$result['commonContent'] = $this->commonContent();
		$result['unique_order_id'] = $id;
		return view('payment-success')->with('result',  $result);
	}

	public function paymentFailure($id){
		$title = array('pageTitle' => Lang::get('website.Payment Failed'));
		$result = array();	
		$myVar = new CartController();
		$result['cart'] = $myVar->myCart($result);
		$result['commonContent'] = $this->commonContent();
		$result['unique_order_id'] = $id;
		return view('payment-failure')->with('result', $result);
	}

	public function paymentSuccessUpdate($req) { 

		$data['unique_order_id'] = $req['ReceiptId'];
		$data['date_stamp'] = $req['TransDate'];
		$data['time_stamp'] = $req['TransTime'];		
		$data['bank_transaction_id'] = $req['ReferenceNum'];
		$data['TxnNumber'] = $req['TxnNumber'];
		$data['charge_total'] = $req['TransAmount'];
		$data['bank_approval_code'] = $req['AuthCode'];
		$data['response_code'] = $req['ResponseCode'];
		$data['iso_code'] = $req['ISO'];
		$data['message'] = $req['Message'];
		$data['trans_name'] = 'purchase';
		//$data['cardholder'] = $req['cardholder'];
		$data['card_num'] = $req['MaskedPan'];
		$data['card'] = $req['CardType'];
		$data['expiry_date'] = $req['ExpDate'];
		//$data['result'] = $req['result'];
		$data['payment_status'] = 'Success';
		$data['payment_method'] = $req['payment_method'];
		$data['timestamp'] = date('H:i:s m/d/y');

		

		if( $req['ResponseCode'] < 50 ) {

			DB::table('orders')
				->where('unique_order_id', $req['ReceiptId'])
				->update(['payment_status' => 'Success']);
 	    	 
			foreach(session('coupon') as $coupons_data) {
				
				//update coupans		
				$coupon_id = DB::statement("UPDATE `coupons` SET `used_by`= CONCAT(used_by,',$customers_id') WHERE `code` = '".$coupons_data->code."'");
							
			}
			
			$getOrderData = PaymentHistory::where([['unique_order_id', '=', $data['unique_order_id']]])->first();
			if(!count($getOrderData)) {

				$orders_id = PaymentHistory::create($data);

			}

			session()->forget('step');
			session()->forget('payment');
			Event::fire(new SendProductOrderMail(session()->get('orders_id')));
			session()->forget('orders_id');
			session()->forget('unique_order_id');
			session()->forget('shipping_address');
			Basket::where('customers_id',session('customers_id'))->update(['is_order'=>'1']);	

        }		
		
	}

	public function paymentFailedUpdate($req)
	{
		$data['unique_order_id'] 		= $req['unique_order_id'];
		$data['date_stamp'] 			= @$req['TransDate'];
		$data['time_stamp'] 			= @$req['TransTime'];
		$data['bank_transaction_id'] 	= @$req['ReferenceNum'];
		$data['TxnNumber'] 				= @$req['TxnNumber'];
		$data['charge_total'] 			= @$req['TransAmount'];
		$data['bank_approval_code'] 	= @$req['AuthCode'];
		$data['response_code'] 			= @$req['ResponseCode'];
		$data['iso_code'] 				= @$req['ISO'];
		$data['message'] 				= @$req['Message'];
		$data['trans_name'] 			= 'purchase';
		//$data['cardholder'] = $req['cardholder'];
		$data['card_num'] 				= @$req['MaskedPan'];
		$data['card'] 					= @$req['CardType'];
		$data['expiry_date'] 			= @$req['ExpDate'];
		//$data['result'] = $req['result'];
		$data['payment_status'] 		= 'Failed';
		$data['payment_method'] 		= @$req['payment_method'];
		$data['timestamp'] 				= date('H:i:s m/d/y');

		Order::where('unique_order_id', $req['unique_order_id'])
				->update(['payment_status' => 'Failed']);
				
		$getOrderData = PaymentHistory::where([['unique_order_id', '=', $data['unique_order_id']]])->first();
	
		if(!count($getOrderData)) {

			$orders_id = PaymentHistory::create($data);

		}
		//return view('web.order.payment-failure', $data);
	}
}
