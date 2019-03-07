<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use session;
use DB;
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

	public function scopelikedProductsDetail()
	{	

		$products = self::join('products','products_id','liked_products_id')
					->leftJoin('products_description','products_description.products_id','=','liked_products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'liked_products_id')->where('status', '=', '1')->where('expires_date', '>', time());
					})
					->where('liked_customers_id','=', session('customers_id'))
					->select('products.products_id','products.products_image',  'products_description.products_name','products.products_price', 'products.products_slug','specials.specials_new_products_price as discount_price','liked_products.liked_customers_id')->limit(5)
					->get();	
		 //dd($products);
		// IF(Obsolete = 'N' OR InStock = 'Y' ? 1 : 0) AS Saleable,
		return $products; 		

	}
	public function scopemostlikedProducts()
	{	

		$customers_id=session('customers_id')  ? session('customers_id') : '0' ;

		$products = self::
					whereRaw('liked_products_id not IN (select liked_products_id from liked_products where `liked_customers_id`='.$customers_id.')')

					->join('products','products_id','liked_products_id')
					
					->leftJoin('products_description','products_description.products_id','=','liked_products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'liked_products_id')
						->where('status', '=', '1')
						->where('expires_date', '>', time());
					})
					->select('products.products_id','products.products_image',  'products_description.products_name','products.products_price', 'products.products_slug','products.products_date_added','specials.specials_new_products_price as discount_price','liked_customers_id',DB::raw('count(*) as most_liked'))
					//DB::raw('CASE liked_products.liked_customers_id WHEN  40 THEn 1 END as liked_customers_id')
					->groupBy('liked_products_id')
					//->orderBy('most_likeds','DESC')
					//->orderByRaw('COUNT(*) DESC')
					->limit(10)
					->get();
 		//dd($products);
		return $products; 		

	}
}
