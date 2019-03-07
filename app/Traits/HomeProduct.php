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
use App\Manufacturer;
use App\ProductsToCategory;
use App\ProductsImage;
use App\ProductsAttribute;
use App\ProductsOption;
use App\ProductsOptionsValue;
use App\LikedProduct;
use App\ProductsOptionsValuesToProductsOption;
use App\Special;

trait HomeProduct
{	

	public function productSpotLightSpecial( )
    {

    	$special = Special::homeSpotLight();
		// dd( $special->first() );
    	$products_data = $special->first();

		$detail = array();
		$result2 = array();
			
		$products_id = $products_data->products_id;
		//multiple images
		$products_images =  $products_data->other_images;

		
		// ******
		// *****like product
		// ***********
		if($products_data->liked_customers_id>0){
			$result['isLiked'] = '1';
		}else{
			$result['isLiked'] = '0';
		}

		// ******
		// *****list added arttribute to products
		// ***********
		$products_attribute = ProductsAttribute::with(['products_option.products_attribute'=> function ($query) use ($products_id) {			   $query->with('products_options_values');
				            $query->where('products_id','=', $products_id);
				        }])
						->where('products_id','=', $products_id)
						->groupBy('options_id')
						->get();
		//dd( $products_attribute);
		$products_attribute_list=	[];//$products_attribute->toArray()	;	
		foreach ($products_attribute as $key => $value) {

			if(count($value->products_option)) {
				foreach ($value->products_option->products_attribute as $key => $row) {
					if($row->is_default !=1) 
					$products_attribute_list[] = $request->{$value->products_option->products_options_name};
				}
			}

		}
		 //dd($products_attribute);
		$attributes_price = 0;
		$attributes = [];
		foreach ( $products_attribute as $key => $value) {

			$temp=array();
			if(count($value->products_option)) {

				foreach ($value->products_option->products_attribute as $key => $row) {

					//$p_o_v=ProductsOptionsValue::where('products_options_values_id',$row->options_values_id)->first();

					$temp[] =['value'=>$row->products_options_values->products_options_values_name,'id' => $row->options_values_id,'price'=>$row->options_values_price,'price_prefix'=>$row->price_prefix,'is_default'=>$row->is_default];

					if(in_array($row->options_values_id, $products_attribute_list)) {

						if($row->price_prefix == '+')
							$attributes_price += $row->options_values_price;
						else
							$attributes_price -= $products_option_value->options_values_price;
					}	
				}
				$attributes[]=['option'=>['name' => $value->products_option->products_options_name,'id' => $value->options_id],'values'=>$temp];
			}
		}
		$result['attributes'] =$attributes;
		// ******
		// ******Get attribute image 
		// ************* 
		if(count($products_attribute_list)) {

			$products_attributes_image = ProductsAttributesImage::select('image')->where('products_id','=', $products_id)
												->whereIn('options_values_id',$products_attribute_list)
												->get();	
 			if(count($products_attributes_image)) {

				$other_option_img =[];
				foreach ($products_attributes_image as $key => $value) {

					if($key == 0) {
						$products_data->products_image=$products_attributes_image[$key]->image;
					} else {
						$other_option_img[] = $products_attributes_image[$key];
					}

				}
				$products_images =  $other_option_img;

 			}
		}

		$result['product_images'] 	= $products_images;
		$result['attributes_price']	= $attributes_price;
		$result['product_data'][] =$products_data;

    	return  $result;
	}

     
 }
