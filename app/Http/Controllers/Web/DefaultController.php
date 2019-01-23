<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 3.0
*/
namespace App\Http\Controllers\Web;
//use Mail;
//validator is builtin class in laravel
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lang;
//for Carbon a value 
use Carbon\Carbon;

//email
use Illuminate\Support\Facades\Mail;
use Session;
use App\SlidersImage;
use App\OrdersProduct;
use App\Page;
use App\Order;
use App\Product;
use App\ProductsToCategory;
use Cache;

class DefaultController extends DataController
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	
	//setStyle
	public function setStyle(Request $request){	
	 //echo "ok0";die;	
		session(['homeStyle' => $request->style]);		
		return redirect('/');
	}
	
	//
	public function settheme(Request $request){		
		session(['theme' => $request->theme]);		
		return redirect('/');
	}
	
	//index 
	public function index(Request $request)
	{
		
		$title = array('pageTitle' => Lang::get("website.Home"));
		$result = array();	
		$result['commonContent'] = $this->commonContent();
		//dd($result['commonContent']);
		/*get top featured product*/
		//$result['featured'] =$this->getFeaturedProduct();
		$pr = Product::select('products.*','products_description.products_name','specials.specials_new_products_price as discount_price')
				->join('products_description','products_description.products_id','=','products.products_id')
				->LeftJoin('specials', function ($join)  {  
					$join->on('specials.products_id', '=', 'products.products_id')
					->where('specials.status', '=', '1')
					->where('specials.expires_date', '>', time());
				})
				->where('products_quantity','>','0')
				->where('products.products_status', '=', 1)
				->where('products_description.language_id','=',Session::get('language_id'))
				;
		
		$featured   =	clone $pr;
		$top_seller =	clone $pr;
		$top_deals  =	clone $pr;
 	    
		$result['featured'] = Cache::remember('cache_featured', 3600, function()  use ($featured){ 
									return $featured->where('products.is_feature', '=', 1)
									->groupBy('products.products_id')
									->limit(4)
									->get();
								});
		/*get top seller product*/
		$result['top_seller'] =  Cache::remember('cache_top_seller', 3600, function() use ($top_seller){ 
									return  $top_seller->where('products.is_feature', '=', 0)
											->orderBy('products.products_ordered', 'DESC')
											->groupBy('products.products_id')
											->limit(4)
											->get();
								});
		//special products
		$result['special'] = Cache::remember('cache_special', 3600, function()  use ($top_deals){ 
										return $top_deals->where('products.is_feature', '=', 0)
												->orderBy('specials.products_id', 'DESC')
												->groupBy('products.products_id')
												->limit(4)
												->get();
					 				});
		//current time
		$currentDate = Carbon::now()->toDateTimeString();
		$chave = 'slides_'.Carbon::now()->toDateString();
		
	    $result['slides'] = Cache::remember($chave, 3600, function() use ($currentDate) { 
								return SlidersImage::select('sliders_id as id', 'sliders_title as title', 'sliders_url as url', 'sliders_image as image', 'type', 'sliders_title as title')
								   ->where('status', '=', '1')
								   ->where('languages_id', '=', session('language_id'))
								   ->where('expires_date', '>', $currentDate)
					   			   ->get();
							 });

	 
		
		//cart array
		$result['cartArray'] = $result['commonContent']['cart']->pluck('products_id')->toArray();

		return view("index", $title)->with('result', $result); 
		
	}
	
	
	
	//page
	public function page(Request $request)
	{
		
		$pages = Page::leftJoin('pages_description','pages_description.page_id','=','pages.page_id')
					->where([['pages.status','1'],['type',2],['pages_description.language_id',session('language_id')],['pages.slug',$request->name]])->get();
		
		if(count($pages)>0) {

			$title = array('pageTitle' => $pages[0]->name);
			$result['commonContent'] = $this->commonContent();
			$result['pages'] = $pages;			
			return view("page", $title)->with('result', $result);
		
		} else {

			return redirect()->intended('/') ;
		
		}
	}

	public function getFeaturedProduct($data =[])
	{
		
		return  Product::where('products_quantity','>','0')
					->where('products.products_status', '=', 1)
					->where('products.is_feature', '=', 1)
					->leftJoin('products_description','products_description.products_id','=','products.products_id')
					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')
						->where('expires_date', '>', time());
					})->select('products.*','products_description.products_name','specials.specials_new_products_price as discount_price')
			
				->where('products_description.language_id','=',Session::get('language_id'))
				->groupBy('products.products_id')
				->limit(4)
				->get();
			  

	}
	public function getTopDealsProduct($data = [])
	{
		 
		$top_deals = Product::where('products_quantity','>','0')
					->where('products.products_status', '=', 1)
					->where('products.is_feature', '!=', 1)
					->leftJoin('products_description','products_description.products_id','=','products.products_id')

					->LeftJoin('specials', function ($join)  {  
						$join->on('specials.products_id', '=', 'products.products_id')->where('specials.status', '=', '1')
						->where('expires_date', '>', time());
					})
					->select('products.*',   'products_description.products_name', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price')
			 
					->where('products_quantity','>','0')
					->orderBy('specials.products_id', 'DESC')
					->groupBy('products.products_id')
					->limit(4)
					->get();
		 
		return $top_deals;			
	}
	public function getTopSellerProduct($data =[])
	{
		
	  	$top_seller = Product::where('products_quantity','>','0')
							->where('products.products_status', '=', 1)
							->where('products.is_feature', '!=', 1)
							->leftJoin('products_description','products_description.products_id','=','products.products_id')
							->LeftJoin('specials', function ($join)  {  
								$join->on('specials.products_id', '=', 'products.products_id')
								->where('status', '=', '1')->where('expires_date', '>', time());
							})->select('products.*','products_description.products_name','specials.specials_new_products_price as discount_price')
									 ->where('products_description.language_id','=',Session::get('language_id'))

							->orderBy('products_ordered', 'DESC')
							->groupBy('products.products_id')
							->limit(4)
							->get();

		return  $top_seller;  
	}
 	
}