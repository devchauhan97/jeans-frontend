<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;

class ProductsToCategory extends Model
{
    
  
	protected $table = 'products_to_categories';

	protected $guarded = ['products_id'];

	//use user id of admin
	protected $primaryKey = 'products_id';

	public function category() {
	    return $this->hasOne(Category::class, 'categories_id');
	}
		
	public function category_description(){ 

		return $this->hasMany(CategoryDescription::class,'categories_id','categories_id')->where('language_id',1);
	}

	public function other_images(){ 

		return $this->hasMany(ProductsImage::class,'products_id')->orderBy('sort_order', 'ASC');
	}
	public function products_attributes_images(){ 

		return $this->hasMany(ProductsAttributesImage::class,'products_id');
	}

	public static function simliar_products($categories_id) {

		$categories = self::LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id');
				
		$categories->LeftJoin('specials', function ($join)  {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>',time());
			})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'products_to_categories.categories_id', 'categories_description.*');


		$categories->where('products_to_categories.categories_id','=', $categories_id);
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			->where('products_quantity','>','0');
		
		$categories->groupBy('products.products_id');
			
		//count
		$total_record = $categories->toSql();
		//dd($total_record);

		$products  = $categories->take(5)->get();
		$result =[];

		if(count($products)>0) {
			$index = 0;	
			foreach ($products as $products_data){

				$products_id = $products_data->products_id;
				
				//multiple images
				$products_images = ProductsImage::select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();		
				$products_data->images =  $products_images;
				
				array_push($result,$products_data);
				$options = array();
				$attr = array();
			
			//like product
				if(!empty(session('customers_id'))){
					$liked_customers_id						=	session('customers_id');	
					$categories = LikedProduct::where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
					if(count($categories)>0){
						$result[$index]->isLiked = '1';
					}else{
						$result[$index]->isLiked = '0';
					}
				}else{
					$result[$index]->isLiked = '0';						
				}
			
			// fetch all options add join from products_options table for option name
				$products_attribute = ProductsAttribute::where('products_id','=', $products_id)->groupBy('options_id')->get();
				$attributes_price=0;
				if(count($products_attribute)) {
				$index2 = 0;
					foreach($products_attribute as $attribute_data){
						$option_name = ProductsOption::where('language_id','=', Session::get('language_id'))->where('products_options_id','=', $attribute_data->options_id)->get();
						
						if(count($option_name)>0){
							
							$temp = array();
							$temp_option['id'] = $attribute_data->options_id;
							$temp_option['name'] = $option_name[0]->products_options_name;
							$temp_option['is_default'] = $attribute_data->is_default;
							$attr[$index2]['option'] = $temp_option;

							// fetch all attributes add join from products_options_values table for option value name
							$attributes_value_query =  ProductsAttribute::where('products_id','=', $products_id)->where('options_id','=', $attribute_data->options_id)->get();
							$k = 0;
								foreach($attributes_value_query as $products_option_value){
									$option_value = ProductsOptionsValue::where('products_options_values_id','=', $products_option_value->options_values_id)->get();
									$temp_i['id'] = $products_option_value->options_values_id;
									$temp_i['value'] = $option_value[0]->products_options_values_name;
									$temp_i['price'] = $products_option_value->options_values_price;
									$temp_i['price_prefix'] = $products_option_value->price_prefix;
									if(in_array($temp_i['id'], [@$data['color'],@$data['size']])) {

										if($products_option_value->price_prefix == '+')
											$attributes_price += $products_option_value->options_values_price;
										else
											$attributes_price -= $products_option_value->options_values_price;
									}
									
									array_push($temp,$temp_i);

								}
								$attr[$index2]['values'] = $temp;
								$result[$index]->attributes = 	$attr;	
								$index2++;
						}
					}
					//$attributes_price=0;
					$result[$index]->attributes_price=$attributes_price;
				}else{
					$result[$index]->attributes = 	array();	
				}

				$index++;
			}
		}
		return ['product_data'=>$result];
	}
}
