<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotLightProduct extends Model
{
    protected $guarded = ['spot_light_id'];

	//use user id of admin
	protected $primaryKey = 'spot_light_id';

	public static function homeProductSpotLight()
	{
		
		return self::join('products','spot_light_products.products_id','products.products_id')
					->join('products_description','products_description.products_id','=','products.products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'products.products_id')
						->where('specials.status', '=', '1')
						->where('specials.expires_date', '>', time());
					})
					->select('products.*','products_description.products_name','products_description.products_description','products_description.sort_description','specials.specials_new_products_price as discount_price','spot_light_products.*')
					->where('products_quantity','>','0')
					->where('products.products_status', '=', 1)
					->where('products_description.language_id','=',1)
					->where('spot_light_products.spot_light_status', '=', 1)
					->groupBy('products.products_id')
					->orderBy('spot_light_products.spot_light_id', 'desc')
					->limit(10);
	}
}
