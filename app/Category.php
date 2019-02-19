<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
class Category extends Model
{
    
	
	protected $table = 'categories';
	
	protected $guarded = ['categories_id'];

	protected $primaryKey = 'categories_id'; 

	public function parent() 
	{
	    return $this->belongsTo(self::class, 'parent_id');
	}

	public function sub_categories() 
	{
	    return $this->hasMany(self::class, 'parent_id','categories_id');//->where('categories_status',1);
	}

	public function categories_description() 
	{
	    return $this->hasOne(CategoryDescription::class,'categories_id');
	}

	public function products() 
	{
	    return $this->hasMany(Product::class, 'categories_id');
	}

	public function products_to_categories() 
	{
	    return $this->hasMany(ProductsToCategory::class, 'categories_id');
	}
	public function scopehomeCatSilder() 
	{
		return self::where('categories_status','=',1)->join('categories_description','categories_description.categories_id','categories.categories_id');
	}

	public static function  homeOccasionSilder($catId)
	{
		return self:: join('categories_description','categories_description.categories_id','categories.categories_id')
					//->where('categories_status','=',1)
					->where('categories.parent_id','=',$catId);
	}

	public static function  homeCatProduct($catId)
	{
		 
		return self::join('products_to_categories','products_to_categories.categories_id','categories.categories_id')
					->join('products','products.products_id','products_to_categories.products_id') 
					->join('products_description','products_description.products_id','=','products.products_id') 
					->LeftJoin('specials', function ($join)  {  
							$join->on('specials.products_id', '=', 'products.products_id')
							->where('specials.status', '=', '1')
							->where('specials.expires_date', '>', time());
						})
					->select('products.*','categories.*','products_description.products_name','specials.specials_new_products_price as discount_price')
					->where('products_quantity','>','0')
					->where('products.products_status', '=', 1)
					->where('products_description.language_id','=',1) 
					->where('categories.categories_id','=',$catId)
					->groupBy('products.products_id')->limit(4);			
	}

}
