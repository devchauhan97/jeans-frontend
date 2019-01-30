<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use session;

class LikedProduct extends Model
{
    protected $guard = "customers";
	
	protected $table = 'liked_products';

	protected $guarded = ['like_id']; 

	protected $primaryKey = 'like_id'; 


	public function scopelikedProducts()
	{	

		$products = self::where('liked_customers_id','=', session('customers_id'))->get();	
		$result = array();
		$index = 0;
		foreach($products as $products_data){
			$result[$index++] = $products_data->liked_products_id;
		}

		return($result); 		

	}	
}
