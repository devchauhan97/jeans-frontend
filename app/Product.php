<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
class Product extends Model
{
    
  
	protected $table = 'products';

	protected $guarded = ['products_id'];

	//use user id of admin
	protected $primaryKey = 'products_id'; 



	public  function products_description()
	{

		return $this->hasOne(ProductsDescription::class,'products_id');
	}

	public  function category()
	{

		return $this->hasOne(Category::class,'categories_id');
	}

	public  function sub_category()
	{

		return $this->hasOne(Category::class,'categories_id');
	}
	public  function categories_description()
	{
 
		return $this->hasOne(CategoryDescription::class,'categories_id');
	}
	
	public  function products_to_categories() //used
	{
		return $this->hasMany(ProductsToCategory::class,'products_id');
	}

	public function default_products_attributes()
	{

		return $this->hasOne(ProductsAttribute::class,'products_id','products_id');
	}

	public  function special()
	{

		return $this->hasMany(Special::class,'products_id');
	}

	public  function scopeGetProduct($query)
	{

		return $query->where('products_quantity','>','0')
							->leftJoin('products_description','products_description.products_id','=','products.products_id');
	}
	public static function  homeProduct($type)
	{

		$list = self::with(['default_products_attributes'=> function ($join)  {  
						$join->where('is_default', '=', '1')->with(['default_products_option','default_products_options_values']);
					}])
					->join('products_description','products_description.products_id','=','products.products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'products.products_id')
						->where('specials.status', '=', '1')
						->where('specials.expires_date', '>', time());
					})
					->leftJoin('liked_products', function ($join)   {  
						$join->on('liked_products.liked_products_id', 'products.products_id')
						->where('liked_customers_id',session('customers_id'));
					}) 
					->select('products.*','products_description.products_name','specials.specials_new_products_price as discount_price',"liked_products.liked_customers_id")
					->where('products_quantity','>','0')
					->where('products.products_status', '=', 1)
					->where('products_description.language_id','=',1);

		switch ($type) {
			case 'featured':
				 $list =  $list->where('products.is_feature', '=', 1);
				break;
			
			case 'top_sellers':
				$list =   $list
				//->where('products.is_feature', '!=', 1)
					->orderBy('products.products_ordered', 'DESC');

				break;
			
			case 'top_deals':
				$list =   $list->where('products.is_feature', '!=', 1)
					->orderBy('specials.products_id', 'DESC');
				break;
			case 'top_deals':
				$list =   $list->where('products.is_feature', '!=', 1)
					->orderBy('specials.products_id', 'DESC');
				break;
		 
		}
		return  $list->groupBy('products.products_id')->limit(4);
	}

}
