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

	public static function applyCoupon($coupon_code,$session_coupon_data,$session_coupon_ids)
	{
		
		$coupon = self::where('code', $coupon_code);
		// /dd($pev_coupon);

		$data = [];

		if($coupon->count()) {

			$coupons =$coupon->first();


			if(in_array($coupons->coupans_id,$session_coupon_ids)) 
				return  array('success'=>2, 'message'=>Lang::get("website.Coupon is already applied"));
		
			if(time() > strtotime($coupons->expiry_date)) 
				return ['success'=>2, 'message'=>Lang::get("website.Coupon expiry not exist")];
			
			if(!empty(auth()->guard('customer')->user()->email) && in_array(auth()->guard('customer')->user()->email , explode(',', $coupons->email_restrictions))) 
				return  ['success'=>2, 'message'=>Lang::get("website.You are not allowed to use this coupon")];
			
			if($coupons->usage_limit > 0 && $coupons->usage_limit <= $coupons->usage_count ) 
				return  array('success'=>2, 'message'=>Lang::get("website.This coupon has been reached to its maximum usage limit"));
			 
			if($coupons->individual_use == '1' && !empty( $session_coupon_ids)) 
				return  array('success'=>2, 'message'=>Lang::get("website.The coupon cannot be used in conjunction with other coupons"));
						
			

			$used_by = array_count_values (explode(',', $coupons->used_by));
			$used_by_user = isset($used_by[auth()->guard('customer')->user()->customers_id]);

			if($coupons->usage_limit_per_user > 0 && $coupons->usage_limit_per_user <= $used_by_user ) 							
				return $response = array('success'=>2, 'message'=>Lang::get("website.coupon is used limit"));
			

			$carts = Basket::getCart() ->get();

			$price = 0;
			$discount_price = 0;
			$price_of_sales_product = 0;
			$exclude_sale_items = array();

			foreach( $carts as $cart) {
				//cart price
				$price+= $cart->final_price * $cart->customers_basket_quantity;

				$cart_prod_ids_arr[]=$cart->products_id;

				$cart_prod_cat_ids_arr[] = $cart->categories->categories_id;

			    //if cart items are special product
				if($coupons->exclude_sale_items == 1) {
					if($cart->specials){
						$exclude_sale_items[] = $cart->products_id;;
						//price check is remaining if already an other coupon is applied and stored in session
						$price_of_sales_product += $cart->specials->specials_new_products_price;
					}
				}
			}
			//dd($cart_prod_cat_ids_arr);
			$total_special_items = count($exclude_sale_items);
			
			$cart_price = $price+0-$discount_price;
			 
			if($coupons->minimum_amount > 0 && $coupons->minimum_amount >= $cart_price)			
				return $response = array('success'=>2, 'message'=>Lang::get("website.Coupon amount limit is low than minimum price"));							
			
			if($coupons->maximum_amount > 0 && $coupons->maximum_amount <= $cart_price)
				return $response = array('success'=>2, 'message'=>Lang::get("website.Coupon amount limit is exceeded than maximum price"));
			
 
			if($coupons->exclude_sale_items == 1 && $total_special_items > 0)
				return $response = array('success'=>2, 'message'=>Lang::get("website.This coupon can not applied due to some special sales item in your cart."));
			

			$exclude_product_ids_arr = explode(',', $coupons->exclude_product_ids);
			if(!empty($coupons->exclude_product_ids) && count(array_intersect($exclude_product_ids_arr, $cart_prod_ids_arr)))
				return  ['success' => 2, 'message' => "This coupon can not applied due to some products in your cart."];
			/*
			*check cart have product categories
			*/
			$product_categories_arr = explode(',', $coupons->product_categories);
			if(!empty($coupons->product_categories) && !count(array_intersect($product_categories_arr, $cart_prod_cat_ids_arr)))
				 return ['success' => 2, 'message' => "This coupon can be applied on special product categories only."];
			/*
			*check cart have excluded product categories
			*/
			$excluded_product_categories_arr = explode(',', $coupons->excluded_product_categories);
			if(!empty($coupons->excluded_product_categories) && count(array_intersect($excluded_product_categories_arr, $cart_prod_cat_ids_arr)))
				return ['success' => 0, 'message' => "This coupon can not applied due to some product categories in your cart."];
			

			$cart_price = $cart_price - $price_of_sales_product;

			if($coupons->discount_type == 'fixed_cart') {
										
				if($coupons->free_shipping) {
					$coupon_discount = session('shipping_detail')->shipping_price;
					$coupon = $coupons;
				} else if($coupons->amount < $cart_price){
					//$total_price = $cart_price-$coupons[0]->amount;
					$coupon_discount = $coupons->amount;
					$coupon = $coupons;
				} else {
					return $response = array('success'=>2, 'message'=>Lang::get("website.Coupon amount is greater than total price"));
				}
				
				//session(['coupon' => $coupon]);
					
	
			} elseif($coupons->discount_type == 'percent') {
				
				$cart_price = $cart_price - ($coupons->amount/100 * $cart_price) ;
				//print 'percentage cart amount: '.$cart_price;
				if($coupons->free_shipping) {
					$coupon_discount =session('shipping_detail')->shipping_price;
					$coupon = $coupons;
				} else if($cart_price > 0) {
					//$total_price = $cart_price-$coupons[0]->amount;
					$coupon_discount = $coupons->amount/100 * $cart_price;
					$coupon = $coupons;
				} else {
					return $response = array('success'=>2, 'message'=>Lang::get("website.Coupon amount is greater than total price"));
				}
				
			}

			if(!in_array($coupons->coupans_id,$session_coupon_ids)) {

				$session_coupon_data[] = $coupon;
				session(['coupon_discount' => session('coupon_discount')+$coupon_discount]);	
				$response = array('success'=>'1', 'message'=>Lang::get("website.Couponisappliedsuccessfully"));
				
			}
			session(['coupon' => $session_coupon_data]);
			return $response;
		} else {
			
			return  array('success'=>'0', 'message'=>Lang::get("website.Coupon does not exist"));
		}
	}
}
