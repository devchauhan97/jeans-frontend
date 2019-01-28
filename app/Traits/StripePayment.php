<?php

namespace App\Traits;
use Lang;
use URL;
use Session;
use Redirect;
use Input;
use App\User;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use App\Http\Requests\StripeRequest;
use Response;
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
use App\Events\SendProductOrderMail;
use Event;
use DB;

trait StripePayment
{	

	 
    public function postPaymentWithStripe($token,$email,$order_price)
    {
    	 
    	//$order_price =$this->getFinalPrice();
        //$input = $request->all();
	    //$input = array_except($input,array('_token')); 
	    $payments_setting = PaymentsSetting::first();  
	    $stripe = Stripe::make($payments_setting['secret_key']); //Please make live
        
        try {
 
			$customer = $stripe->customers()->create(array(
			  'email' => $email,
			  'source'  => $token
			));
			
			$charge = $stripe->charges()->create(array(
			  'customer' => $customer['id'],
			  'amount'   => 100*$order_price,
			  'currency' => 'usd'
			)); 

            if($charge['status'] == 'succeeded') {
                 
                $order_information = array(
					'paid'=>'true',
					'transaction_id'=>$charge['id'],
					'type'=>$charge['outcome']['type'],
					'balance_transaction'=>$charge['balance_transaction'],
					'status'=>$charge['status'],
					'currency'=>$charge['currency'],
					'amount'=>$charge['amount'],
					'created'=>date('d M,Y', $charge['created']),
					'dispute'=>$charge['dispute'],
					'customer'=>$charge['customer'],
					'address_zip'=>$charge['source']['address_zip'],
					'seller_message'=>$charge['outcome']['seller_message'],
					'network_status'=>$charge['outcome']['network_status'],
					//'expirationMonth'=>$charge['outcome']['type'],
					'brand'		=>$charge['source']['brand'],
					'exp_month'		=>$charge['source']['exp_month'],
					'exp_year'		=>$charge['source']['exp_year'],
				);
				//$res = $this->payment_status($order_information,$request);
				//return Response::json(['success'=> true,'order_information'=>$order_information],200);

                return  ['success'=> true,'order_information'=>$order_information];

            } else {

                return Response::json('Payment fail!!',403);

            }

        } catch (Exception $e) {
            return Response::json($e->getMessage(),403);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return Response::json($e->getMessage(),403);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return Response::json($e->getMessage(),403);
        } 
       return redirect()->back()->with('error','Invaild request.');
    }
     public function payment_status($order_information,$request)
    {
    	try {

	    	$date_added								=	date('Y-m-d h:i:s');		
			$customers_id            				=   session('customers_id');
			/***
			*****make shipping address details
			******************/
			$email            						=   auth()->guard('customer')->user()->email;	
			$delivery_company 						=	session('shipping_address')->company;
			$delivery_firstname  	          		=   session('shipping_address')->firstname;
			$delivery_lastname            			=   session('shipping_address')->lastname;
			$delivery_street_address            	=   session('shipping_address')->street;
			$delivery_suburb            			=   '';
			$delivery_city            				=   session('shipping_address')->city;
			$delivery_postcode            			=   session('shipping_address')->postcode;
			
			$delivery = Zone::where('zone_id', '=', session('shipping_address')->zone_id)->first();
			
			if($delivery) {
				$delivery_state            				=   $delivery['zone_code'];
			} else {
				$delivery_state            				=   'other';
			}
				
			$delivery_country = Country::where('countries_id','=', session('shipping_address')->countries_id)->first()->countries_name;

			/***
			*****make billaddress details
			******************/
			$billing_firstname            			=   session('billing_address')->billing_firstname;
			$billing_lastname            			=   session('billing_address')->billing_lastname;
			$billing_street_address            		=   session('billing_address')->billing_street;
			$billing_suburb	            			=   '';
			$billing_city            				=   session('billing_address')->billing_city;
			$billing_postcode            			=   session('billing_address')->billing_zip;
			
			if(!empty(session('billing_company')->company)) {
				$billing_company 						=	session('billing_address')->company;
			}
			
			$billing =Zone::where('zone_id', '=', session('billing_address')->billing_zone_id)->first();
			
			if($billing) {
				$billing_state            			=   $billing['zone_code'];
			} else {
				$billing_state         				=   'other';
			}
			 
			$billing_country = Country::where('countries_id','=', session('billing_address')->billing_countries_id)->first()->countries_name;
			
			/****
			*****make card details
			***/
			$cc_type            				=  $order_information['brand'];//brand
			$cc_owner            				=  '';//$request->cc_owner;
			$cc_number            				=  '';$request->cc_number;
			$cc_expires            				=  $order_information['exp_month'].'/'.$order_information['exp_year']; //::
			 
			
			$last_modified            			=   date('Y-m-d H:i:s');
			$date_purchased            			=   date('Y-m-d H:i:s');
			
			//price
			if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
				$shipping_price = session('shipping_detail')->shipping_price;
			} else {
				$shipping_price = 0;
			}

			$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
			$coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
			$order_price = (session('products_price')+$tax_rate+$shipping_price)-$coupon_discount;	
									
			$shipping_cost            			=   session('shipping_detail')->shipping_price;
			$shipping_method            		=   session('shipping_detail')->mehtod_name;
			$orders_status            			=   '1';
			 
			if(!empty(session('order_comments'))){
				$comments						=	session('order_comments');
			} else {
				$comments            			=   '';
			}
			
			$web_setting = Setting::where('name','currency_symbol')->first();
			$currency            				=   $web_setting['value'];		
			$total_tax							=	number_format((float)session('tax_rate'), 2, '.', '');		
			$products_tax 						= 	1;	

			$coupon_amount = 0;	
			$code            					=   '';
			//$coupon_amount            			=   '';
			if(!empty(session('coupon')) and count(session('coupon'))>0) {
				
				$code = array();	
				$exclude_product_ids = array();
				$product_categories = array();
				$excluded_product_categories = array();
				$exclude_product_ids = array();
				
				$coupon_amount    =		number_format((float)session('coupon_discount'), 2, '.', '')+0;
				
				foreach(session('coupon') as $coupons_data) {
					//update coupans		
					$coupon_id = DB::statement("UPDATE `coupons` SET `used_by`= CONCAT(used_by,',$customers_id') WHERE `code` = '".$coupons_data->code."'");
				}
				$code = json_encode(session('coupon'));
				
			}  	
				
			$orders_id = Order::create(
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
					 
					 'payment_method'  =>  'stripe',
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
			 
			foreach($cart as $products) {
				//get produt info	
		
				$orders_products_id = OrdersProduct::create(
					[		 		
						 'orders_id' 		 => 	$orders_id,
						 'products_id' 	 	 =>		$products->products_id,
						 'products_name'	 => 	$products->products_name,
						 'products_price'	 =>  	$products->price,
						 'final_price' 		 =>  	$products->final_price*$products->customers_basket_quantity,
						 'products_tax' 	 =>  	$products_tax,
						 'products_quantity' =>  	$products->customers_basket_quantity,
					])->orders_products_id;
				 
				 
				if(!empty($products->attributes)){
					foreach($products->attributes as $attribute){
						OrdersProductsAttribute::create(
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
			/******
			distory sesstion step
			***/
			session(['step' => array()]);
			//change status of cart products
			Basket::where('customers_id',session('customers_id'))->update(['is_order'=>'1']);
			/*****
			******Send product sale notification 
			*********/
			Event::fire(new SendProductOrderMail($orders_id));
			return true;
		} catch (Exception $e) {

            return Response::json($e->getMessage(),403);

        }
    }
    
}
