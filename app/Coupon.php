<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
