<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Session;
class Special extends Model
{
   protected $table = 'specials';

	protected $guarded = ['specials_id'];

	//use user id of admin
	protected $primaryKey = 'specials_id'; 

	public static function homeSpotLight(){

		$pr = self::join('products','products.products_id','specials.products_id')
						->join('products_description','products_description.products_id','=','products.products_id')
						->leftJoin('liked_products',function($q){
							$q->on('liked_products.liked_products_id','=','products.products_id');
							$q->where('liked_products.liked_customers_id', '=', session('customers_id'));
						})->select('products.*','products_description.*',  'specials.specials_new_products_price as discount_price', 'liked_products.*')
						->where('specials.status', '=', '1')
						->where('products_status', '=', '1')
						->where('specials.expires_date', '>', time()) ;
		return $pr;
	}
}
