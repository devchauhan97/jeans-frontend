<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
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
class StripeController extends DataController
{
    /**
     * Show the application paywith stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public $total_price=null;
 
	public function getFinalPrice()
	{

		$price = session('products_price');

		if(!empty(session('shipping_detail')) and count(session('shipping_detail'))>0){
            $shipping_price = session('shipping_detail')->shipping_price;
		}else{
            $shipping_price = 0;
		}	

    	$tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
        $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');				
        return ($price+$tax_rate+$shipping_price)-$coupon_discount;

	}
    public function payWithStripe()
    {
    	/*$title = array('pageTitle' => Lang::get('website.Checkout'));
    	$result['commonContent'] = $this->commonContent();
    	$myVar = new CartController();
		$result['total_price'] =$this->getFinalPrice();

		return view('paywithstripe' ,$title)->with('result', $result); */
    }
    
    public function postPaymentWithStripe(StripeRequest $request)
    {
    	
    	$total_price =$this->getFinalPrice();
        $input = $request->all();
	    $input = array_except($input,array('_token')); 
	    $payments_setting = PaymentsSetting::first();  
	    $stripe = Stripe::make($payments_setting['secret_key']); //Please make live
        
        try {

            $token = $stripe->tokens()->create([
                'card' => [
                    'number'    => $request->get('card_no'),
                    'exp_month' => $request->get('cc_expiry_month'),
                    'exp_year'  => $request->get('cc_expiry_year'),
                    'cvc'       => $request->get('cvv_number'),
                ],
            ]);

            if (!isset($token['id'])) {
 
                return Response::json('The Stripe Token was not generated correctly',403);

            }
 
            $user = auth()->guard('customer')->user();

            $customer = $stripe->customers()->create(array(
						  'email' 	=> $user->email,
						  //'source'  => $token['id']
						));
			$card = $stripe->cards()->create($customer['id'], $token['id']);
            $charge = $stripe->charges()->create([
                'card' 			=> $card['id'],
                'currency' 		=> 'USD',
                'amount'   		=> $total_price,
                'description' 	=> 'Add in wallet',
                'customer' 		=> $customer['id'],
            ]);

			//dd($charge);

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
					'expirationMonth'=>$charge['outcome']['type']
				);
				
				$res = $this->payment_status($order_information,$request);

				

                return Response::json(['success'=>$res],200);

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
			
			$payment_method            				=   'stripe';
			 
			
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
			 
			foreach($cart as $products) {
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
			foreach($order as $data) {
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
			//$myVar = new AlertController();
			//$alertSetting = $myVar->orderAlert($ordersData);
			Event::fire(new SendProductOrderMail($ordersData));
			if(session('step')=='4'){
				session(['step' => array()]);
			}	
			
			//change status of cart products
			Basket::where('customers_id',session('customers_id'))->update(['is_order'=>'1']);			
			return true;
		} catch (Exception $e) {
            return Response::json($e->getMessage(),403);
        }
    }
}
