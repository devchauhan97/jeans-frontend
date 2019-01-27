<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
class Basket extends Model
{
   
	//protected $guard = "customers";
	protected $table = 'customers_basket';

	protected $guarded = ['customers_basket_id'];

	protected $primaryKey = 'customers_basket_id';

	public  function scopeBasketCart($query) 
	{

		$cart = $query->join('products', 'products.products_id','=', 'customers_basket.products_id')
			->join('products_description', 'products_description.products_id','=', 'products.products_id')
			->select('customers_basket.*', 'products.products_model as model', 'products.products_image as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight_unit as unit' )->where('customers_basket.is_order', '=', '0')->where('products_description.language_id','=', Session::get('language_id') );
			
		if(empty(session('customers_id'))){
			$cart->where('customers_basket.session_id', '=', Session::getId());
		}else{
			$cart->where('customers_basket.customers_id', '=', session('customers_id'));
		}
		return	$cart;
	}
	public function scopegetCart(){

		return self::Join('specials', function($join){

				$join->on('specials.products_id','=', 'customers_basket.products_id');
				$join->where([['specials.status', '=', '1'],
									['specials.expires_date', '>', time()]]);
			})->join('products_to_categories','products_to_categories.products_id', 'customers_basket.products_id')
			->select('customers_basket.*', 'specials.*','products_to_categories.categories_id' )
				->where('customers_basket.is_order', '=', '0')
				->where(['customers_basket.is_order' => '0','customers_basket.customers_id' => session('customers_id')]);
		
	}
	public function scopegetCartTotalAmount(){

		$carts =self::where(['is_order' => '0','customers_id' => session('customers_id')])
					 
					 ->get();
		$price=0;
		foreach( $carts as $cart) {
			//cart price
			$price+= $cart->final_price * $cart->customers_basket_quantity;
		}
		return $price;
	}

	public function scopeMyBasketCart($query)
	{

		$cart = $query->join('products', 'products.products_id','=', 'customers_basket.products_id')
			->join('products_description', 'products_description.products_id','=', 'products.products_id')
			->select('customers_basket.*', 'products.products_model as model', 'products.products_image as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight_unit as unit', 'products.products_slug', 'products.products_quantity')->where([
						['customers_basket.is_order', '=', '0'],
						['products_description.language_id', '=', Session::get('language_id')],
					]);
			
		if(empty(session('customers_id'))){
			$cart->where('customers_basket.session_id', '=', Session::getId());
		}else{
			$cart->where('customers_basket.customers_id', '=', session('customers_id'));
		}
		 					
		
		$baskit = $cart->get();
					
		$total_carts = count($baskit);
		$result = array();
		$index = 0;

		if($total_carts > 0) {

			foreach($baskit as $cart_data){
				array_push($result, $cart_data);
				
				$attributes = BasketAttribute::leftjoin('products_options', 'products_options.products_options_id','=','customers_basket_attributes.products_options_id')
					->leftjoin('products_options_values', 'products_options_values.products_options_values_id','=','customers_basket_attributes.products_options_values_id')
					->leftjoin('products_attributes', function($join){
						$join->on('customers_basket_attributes.products_id', '=', 'products_attributes.products_id')->on('customers_basket_attributes.products_options_id', '=', 'products_attributes.options_id')->on('customers_basket_attributes.products_options_values_id', '=', 'products_attributes.options_values_id');						
					})
					->select('products_options.products_options_name as attribute_name', 'products_options_values.products_options_values_name as attribute_value', 'customers_basket_attributes.products_options_id as options_id', 'customers_basket_attributes.products_options_values_id as options_values_id', 'products_attributes.price_prefix as prefix', 'products_attributes.options_values_price as values_price' )
					
					->where('customers_basket_attributes.products_id', '=', $cart_data->products_id)
					->where('customers_basket_id', '=', $cart_data->customers_basket_id);					
				
					if(empty(session('customers_id'))){
						$attributes->where('customers_basket_attributes.session_id', '=', Session::getId());
					}else{
						$attributes->where('customers_basket_attributes.customers_id', '=', session('customers_id'));
					}
								
					$attributes_data = $attributes->get();
					$result2 = array();
					if(!empty($cart_data->coupon_id)){
						//coupon
						$coupons = explode(',', $cart_data->coupon_id);
						$index2 = 0;
						foreach($coupons as $coupons_data){
							$coupons =  DB::table('coupons')->where('coupans_id', '=', $coupons_data)->get();
							$result2[$index2++] = $coupons[0];
						}
						
					}
					$result[$index]->coupons = $result2;
					$result[$index]->attributes = $attributes_data;
					$index++;			
			}			
		}				
		return($result); 
	}
}
