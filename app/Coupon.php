<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lang;

class Coupon extends Model
{
   
  
	protected $table = 'coupons';

	protected $guarded = ['coupans_id'];

	//use user id of admin
	protected $primaryKey = 'coupans_id'; 

	public static function checkCoupon($coupon_code){
		
		$coupon = self::where('code', $coupon_code);
		$data = [];
		if($coupon->count()){
			$customer = Auth::guard('customer')->user();
			$customers_email = $customer->customers_email_address;
			$customers_id = $customer->customers_id;
			$coupon =$coupon->first();
			$cart_total = CustomerBasket::getCartTotalAmount();

			$used_by_arr = explode(',', $coupon->used_by);
			$used_by_arr_count_val = array_count_values($used_by_arr);
			$used_count_by_cust = !empty($used_by_arr_count_val[$customers_id]) ? $used_by_arr_count_val[$customers_id] : 0;

			$cart_prod_ids_arr = CustomerBasket::getCartItemsProductIdArr();
			$product_ids_arr = explode(',', $coupon->product_ids);
			$exclude_product_ids_arr = explode(',', $coupon->exclude_product_ids);

			$cart_prod_cat_ids_arr = CustomerBasket::getCartItemsProductCatIdArr();
			$product_categories_arr = explode(',', $coupon->product_categories);
			$excluded_product_categories_arr = explode(',', $coupon->excluded_product_categories);

			$special_prod_ids_arr = CustomerBasket::getSpecialProdIdsArr();

			if(time() > strtotime($coupon->expiry_date)){
				$data = ['status' => 0, 'msg' => 'Coupon has expired.'];
			}
			else if($cart_total < $coupon->minimum_amount && $coupon->minimum_amount > 0){
				$data = ['status' => 0, 'msg' => "Cart amount must be greater than or equal to {$coupon->minimum_amount} to apply this coupon."];
			}
			else if($cart_total > $coupon->maximum_amount && $coupon->maximum_amount > 0){
				$data = ['status' => 0, 'msg' => "Cart amount must be less than or equal to {$coupon->maximum_amount} to apply this coupon."];
			}
			else if(!empty($coupon->email_restrictions) && $customers_email != $coupon->email_restrictions){
				$data = ['status' => 0, 'msg' => "Invalid coupon."];
			}
			else if($coupon->usage_limit > 0 && count($used_by_arr) >= $coupon->usage_limit){
				$data = ['status' => 0, 'msg' => "Coupon usage limit exceeds."];
			}
			else if($coupon->usage_limit_per_user > 0 && $used_count_by_cust >= $coupon->usage_limit_per_user){
				$data = ['status' => 0, 'msg' => "Coupon usage limit exceeds."];
			}
			else if(!empty($coupon->product_ids) && !count(array_intersect($product_ids_arr, $cart_prod_ids_arr))){
				$data = ['status' => 0, 'msg' => "This coupon can be applied on special products only."];
			}
			else if(!empty($coupon->exclude_product_ids) && count(array_intersect($exclude_product_ids_arr, $cart_prod_ids_arr))){
				$data = ['status' => 0, 'msg' => "This coupon can not applied due to some products in your cart."];
			}
			else if(!empty($coupon->product_categories) && !count(array_intersect($product_categories_arr, $cart_prod_cat_ids_arr))){
				$data = ['status' => 0, 'msg' => "This coupon can be applied on special product categories only."];
			}
			else if(!empty($coupon->excluded_product_categories) && count(array_intersect($excluded_product_categories_arr, $cart_prod_cat_ids_arr))){
				$data = ['status' => 0, 'msg' => "This coupon can not applied due to some product categories in your cart."];
			}
			else if($coupon->exclude_sale_items == 1 && count(array_intersect($cart_prod_ids_arr, $special_prod_ids_arr))){
				$data = ['status' => 0, 'msg' => "This coupon can not applied due to some special sales item in your cart."];
			}
			else{
				// coupon valid
				$data = ['status' => 1, 'msg' => 'success', 'discount_type' => $coupon->discount_type, 'amount' => $coupon->amount];
			}
		}
		else{
			$data = ['status' => 0, 'msg' => 'Coupon not found.'];
		}

		return $data;
	}

	public static function applyCoupon($coupon_code,$session_coupon_data,$session_coupon_ids){
		
		$coupon = self::where('code', $coupon_code);
		$data = [];


		if($coupon->count()){

			$coupons =$coupon->first();

			if(in_array($coupons->coupans_id,$session_coupon_ids)){
			
				return  array('success'=>'2', 'message'=>Lang::get("website.Coupon is already applied"));
			}

			if(time() > strtotime($coupons->expiry_date)){
				return ['success'=>'2', 'message'=>Lang::get("website.Coupon expiry not exist")];
			}

			if(!empty(auth()->guard('customer')->user()->email) and in_array(auth()->guard('customer')->user()->email , explode(',', $coupons->email_restrictions))){
				return  ['success'=>'2', 'message'=>Lang::get("website.You are not allowed to use this coupon")];
			}
			if($coupons->usage_limit > 0 and $coupons->usage_limit <= $coupons->usage_count ) {
				return  array('success'=>'2', 'message'=>Lang::get("website.This coupon has been reached to its maximum usage limit"));
			} 
			if($coupons->individual_use == '1' and !empty( $session_coupon_ids)){
				return  array('success'=>'2', 'message'=>Lang::get("website.The coupon cannot be used in conjunction with other coupons"));
						
			}

			$used_by = array_count_values (explode(',', $coupons->used_by));
			
			$used_by_user = isset($used_by[auth()->guard('customer')->user()->customers_id]);

			if($coupons->usage_limit_per_user > 0 and $coupons->usage_limit_per_user <= $used_by_user ){							
				return $response = array('success'=>'2', 'message'=>Lang::get("website.coupon is used limit"));
			}

			$carts = Basket::getCart() ->get();

			$total_cart_items = count($carts);
			$price = 0;
			$discount_price = 0;
			$used_by_user = 0;
			$price_of_sales_product = 0;
			$exclude_sale_items = array();
			$currentDate = time();
			foreach( $carts as $cart) {
				//cart price
				$price+= $cart->final_price * $cart->customers_basket_quantity;
				$cart_prod_ids_arr[]=$cart->products_id;
				$cart_prod_cat_ids_arr[] = $cart->categories_id;
			    //if cart items are special product
				if($coupons->exclude_sale_items == 1) {
					if($cart->specials_id){
						$exclude_sale_items[] = $cart->products_id;;
						//price check is remaining if already an other coupon is applied and stored in session
						$price_of_sales_product += $cart->specials_new_products_price;
					}
				}
			}
			
			$total_special_items = count($exclude_sale_items);
			
			$cart_price = $price+0-$discount_price;

			if($coupons->minimum_amount > 0 and $coupons->minimum_amount >= $cart_price){							
				return $response = array('success'=>'2', 'message'=>Lang::get("website.Coupon amount limit is low than minimum price"));							
			}
			if($coupons->maximum_amount > 0 and $coupons->maximum_amount <= $cart_price){
				return $response = array('success'=>'2', 'message'=>Lang::get("website.Coupon amount limit is exceeded than maximum price"));
			}

			if($coupons->exclude_sale_items == 1 and $total_special_items == $total_cart_items){

				return $response = array('success'=>'2', 'message'=>Lang::get("website.This coupon can not applied due to some special sales item in your cart."));
			}

			$exclude_product_ids_arr = explode(',', $coupons->exclude_product_ids);
			if(!empty($coupons->exclude_product_ids) && count(array_intersect($exclude_product_ids_arr, $cart_prod_ids_arr))){
				return  ['success' => 2, 'message' => "This coupon can not applied due to some products in your cart."];
			}
			
			$product_categories_arr = explode(',', $coupons->product_categories);
			$excluded_product_categories_arr = explode(',', $coupons->excluded_product_categories);

			if(!empty($coupon->product_categories) && !count(array_intersect($product_categories_arr, $cart_prod_cat_ids_arr))){
				 return ['success' => 2, 'message' => "This coupon can be applied on special product categories only."];
			}
			if(!empty($coupon->excluded_product_categories) && count(array_intersect($excluded_product_categories_arr, $cart_prod_cat_ids_arr))){
				return ['success' => 0, 'message' => "This coupon can not applied due to some product categories in your cart."];
			}

			$cart_price = $cart_price - $price_of_sales_product;

			if($coupons->discount_type == 'fixed_cart'){
										
				if($coupons->amount < $cart_price){
				
					//$total_price = $cart_price-$coupons[0]->amount;
					$coupon_discount = $coupons->amount;
					$coupon = $coupons;
				
				}else{
					return $response = array('success'=>'2', 'message'=>Lang::get("website.Coupon amount is greater than total price"));
				}
				
				//session(['coupon' => $coupon]);
					
	
			}elseif($coupons->discount_type=='percent'){
				
				$cart_price = $cart_price - ($coupons->amount/100 * $cart_price) ;
				//print 'percentage cart amount: '.$cart_price;
				
				if($cart_price > 0){
				
					//$total_price = $cart_price-$coupons[0]->amount;
					$coupon_discount = $coupons->amount/100 * $cart_price;
					$coupon = $coupons;
				
				}else{
					return $response = array('success'=>'2', 'message'=>Lang::get("website.Coupon amount is greater than total price"));
				}
				
			}


			if(!in_array($coupons->coupans_id,$session_coupon_ids)){
				$session_coupon_data[] = $coupon;
				session(['coupon_discount' => session('coupon_discount')+$coupon_discount]);	
				$response = array('success'=>'1', 'message'=>Lang::get("website.Couponisappliedsuccessfully"));
				
			}
			
			session(['coupon' => $session_coupon_data]);

			return $response;

		}else{
			
			return  array('success'=>'0', 'message'=>Lang::get("website.Coupon does not exist"));
		}
	}
}
