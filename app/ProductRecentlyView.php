<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class ProductRecentlyView extends Model
{
    protected $guarded = ['product_recently_view_id']; 

	protected $primaryKey = 'product_recently_view_id'; 


	public static function   addToRecentlyViewedProducts($products_id) {

		if(empty(session('customers_id'))){
			$customers_id					=	'';
		}else{
			$customers_id					=	session('customers_id');
		}

		return  self::updateOrCreate(
												[	 
													'viewed_products_id'  => $products_id,
													'session_id'   => Session::getId(),
												 ],[	 
													
													'customers_id' => $customers_id,
													'viewed_products_id'  => $products_id,
													'session_id'   => Session::getId(),
												 ]);

	}

	public static function   recentlyViewedProducts() {

		if(empty(session('customers_id'))){
			$customers_id					=	'';
		}else{
			$customers_id					=	session('customers_id');
		}

		$recent_product_view =  self::orWhere(['customers_id'=>$customers_id,'session_id'=>Session::getId()])
					->join('products','products_id','viewed_products_id')
					->leftJoin('products_description','products_description.products_id','=','viewed_products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->where('status', '=', '1')
						->where('expires_date', '>', time());
					})
					->leftJoin('liked_products', function ($join) use ($customers_id) {  
						$join->on('liked_products.liked_products_id', 'viewed_products_id')
						->where('liked_customers_id',$customers_id);
					})
					->select('products.products_id','products.products_image',  'products_description.products_name','products.products_price','products.products_date_added','liked_customers_id')
					->limit(10)
					->groupBy('products.products_id')
					->get();
		//dd($recent_product_view);
		return $recent_product_view;

	}

	
}
