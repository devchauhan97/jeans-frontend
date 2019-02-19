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
use App\Category;
use App\PageSection;
use App\Blog;
use App\BlogDescription;
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
	public function setStyle(Request $request)
	{	
	  	session(['homeStyle' => $request->style]);		
		return redirect('/');
	}
	
	//
	public function settheme(Request $request)
	{		
		session(['theme' => $request->theme]);		
		return redirect('/');
	}
	//index 
	public function index(Request $request)
	{
		$title = array('pageTitle' => Lang::get("website.Home"));
		$result = [];	
		$result['commonContent'] = $this->commonContent();

		$result['slides'] =  SlidersImage::homeSilder()->get();

		// $result['cat_slides'] =  Category::homeCatSilder()->get();

		// $result['occasion_slides'] =  Category::homeOccasionSilder(1)->get();
 
	 //    $result['bridal_lehengas'] = Category::homeCatProduct(1)->get();
	    //dd($result['bridal_lehengas'] );
		$result['featured'] = Product::homeProduct('featured')->get();
		//dd($result['featured']);							
		/*get top seller product*/
		$result['top_sellers'] = Product::homeProduct('top_sellers')->get();
		//special products
		$result['special'] = Product::homeProduct('top_deals')->get();
					 			
		//current time
		// $currentDate = Carbon::now()->toDateTimeString();
		// $chave = 'slides_'.Carbon::now()->toDateString();
		
	    
 
		$result['page_section_top'] = PageSection::pageSectionTop()
													->get();
		$result['page_section_center'] = PageSection::pageSectionCenter()
													->get();
		$result['page_section_bottom'] = PageSection::pageSectionBottom()
													->get();

		$result['blogs'] = Blog::blogDescriptions()->get();
		 
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
 
	 
}