<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
Version: 1.0
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
//for Carbon a value 
use Carbon;
use Session;
use Lang;
use App\Category;
use App\Basket;
use App\BasketAttribute;
use App\Coupon;
use App\Special;
use App\ProductsToCategory;
use Cache;
use Response;
use App\ProductsAttribute;
class CartController extends DataController
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
	
	//myCart 
	public function viewcart(Request $request)
	{
		
		$title = array('pageTitle' => Lang::get("website.View Cart"));
		$result = array();
		$data = array();	
		$result['commonContent'] = $this->commonContent();
		
		$result['cart'] = $this->myCart($data);
		
		//apply coupon
		if(count(session('coupon'))>0){
			$session_coupon_data = session('coupon');
			session(['coupon' => array()]);		
			$response = array();	
			if(!empty($session_coupon_data)){		
				foreach($session_coupon_data as $key=>$session_coupon){	
						$response = $this->common_apply_coupon($session_coupon->code);
				}
			}	
		}
		
		return view("viewcart", $title)->with('result', $result); 		
	}
	
	//eidtCart 
	public function editcart(Request $request){
		
		$title = array('pageTitle' => Lang::get("website.Edit Cart"));
		$result = array();
		$data = array();	
		$result['commonContent'] = $this->commonContent();
		$baskit_id = $request->id;
		
		$result['cart'] = $this->myCart($baskit_id);
		
		if(count($result['cart'])>0){
		
				
		//category		
		$category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$result['cart'][0]->products_id)->where('categories.parent_id',0)->where('language_id',Session::get('language_id'))->get();
		
		if(!empty($category) and count($category)>0){
			$category_slug = $category[0]->categories_slug;
			$category_name = $category[0]->categories_name;
		}else{
			$category_slug = '';
			$category_name = '';
		}
		$sub_category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$result['cart'][0]->products_id)->where('categories.parent_id','>',0)->where('language_id',Session::get('language_id'))->get();
		
		if(!empty($sub_category) and count($sub_category)>0){
			$sub_category_name = $sub_category[0]->categories_name;
			$sub_category_slug = $sub_category[0]->categories_slug;		
		}else{
			$sub_category_name = '';
			$sub_category_slug = '';	
		}
		
		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['sub_category_slug'] = $sub_category_slug;
		
		
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'', 'products_id'=>$result['cart'][0]->products_id, 'limit'=>'1', 'min_price'=>'', 'max_price'=>'');
		$detail = $myVar->products($data);
		$result['detail'] = $detail;
		
		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$result['detail']['product_data'][0]->categories_id, 'limit'=>'15', 'min_price'=>'', 'max_price'=>'');
		$simliar_products = $myVar->products($data);
		$result['simliar_products'] = $simliar_products;
		
		
		$cart = '';
		//$myVar = new CartController();
		$result['cartArray'] = $result['commonContent']['cart']->pluck('products_id')->toArray();
		
		//liked products
		$result['liked_products'] = $this->likedProducts();	
		
		return view("editcart", $title)->with('result', $result); 
		}else{
			return redirect('/viewcart');
		}
	}
	
	//deleteCart
	public function deleteCart(Request $request){
		
		$baskit_id = $request->id;
		
		Basket::where([
			['customers_basket_id', '=', $baskit_id],
		])->delete();
		
		BasketAttribute::where([
			['customers_basket_id', '=', $baskit_id],
		])->delete();
		
		//apply coupon
		if(!empty(session('coupon')) and count(session('coupon'))>0){
			$session_coupon_data = session('coupon');
			session(['coupon' => array()]);
			if(count($session_coupon_data)=='2'){		
				$response = array();	
				if(!empty($session_coupon_data)){		
					foreach($session_coupon_data as $key=>$session_coupon){	
							$response = $this->common_apply_coupon($session_coupon->code);
					}
				}	
			}
		}
			
		if(!empty($request->type) and $request->type=='header cart'){
			$result['commonContent'] = $this->commonContent();
			return view("cartButton")->with('result', $result);
		}else{
			$message = Lang::get("website.Cart item has been deleted successfully");
			return redirect()->back()->with('message', $message);
		}
	}
	
	
	//getCart
	public function cart($request){	
		
		$cart = Basket::join('products', 'products.products_id','=', 'customers_basket.products_id')
			->join('products_description', 'products_description.products_id','=', 'products.products_id')
			->select('customers_basket.*', 'products.products_model as model', 'products.products_image as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight_unit as unit' )->where('customers_basket.is_order', '=', '0')->where('products_description.language_id','=', Session::get('language_id') );
			
			if(empty(session('customers_id'))){
				$cart->where('customers_basket.session_id', '=', Session::getId());
			}else{
				$cart->where('customers_basket.customers_id', '=', session('customers_id'));
			}
		
		$baskit = $cart->get();
		return($baskit); 
		
	}
	
	//getCart
	public function cartIdArray($request){
		
		$cart = Basket::where('customers_basket.is_order', '=', '0');
			
		if(empty(session('customers_id'))){
			$cart->where('customers_basket.session_id', '=', Session::getId());
		}else{
			$cart->where('customers_basket.customers_id', '=', session('customers_id'));
		}
		
		$baskit = $cart->get();
		
		$result = array();
		$index = 0;
		foreach($baskit as $baskit_data){
			$result[$index++] = $baskit_data->products_id;
		}
		
		return($result); 
		
	}
	
	//updatesinglecart
	public function updatesinglecart(Request $request){
		
		$products_id            			=   $request->products_id;	
		$basket_id            				=   $request->cart_id;	
		
		if(empty(session('customers_id'))){
			$customers_id					=	'';
		}else{
			$customers_id					=	session('customers_id');
		}
		
		$session_id							=	Session::getId();		
		$customers_basket_date_added        =   date('Y-m-d H:i:s');
			
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		$data   = array( 'page_number'=>'0', 'type'=>'', 'products_id'=>$products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$myVar  = new DataController();
		$detail = $myVar->products($data);
		
		//price is not default
		$final_price = $request->products_price;	
				
		//quantity is not default
		$customers_basket_quantity          =   $request->quantity;	
		
		//update into cart
		Basket::where('customers_basket_id', '=', $basket_id)->update(
		[
			 'customers_id' => $customers_id,
			 'products_id'  => $products_id,
			 'session_id'   => $session_id,
			 'customers_basket_quantity' => $customers_basket_quantity,
			 'final_price' => $final_price,
			 'customers_basket_date_added' => $customers_basket_date_added,
		]);
		
		if(count($request->option_id)>0){
			foreach($request->option_id as $option_id){
				
				BasketAttribute::where([
					['customers_basket_id', '=', $basket_id],
					['products_id', '=', $products_id],
					['products_options_id', '=', $option_id],
				])->update(
				[
					 'customers_id' => $customers_id,
					 'products_options_values_id'  =>  $request->$option_id,
					 'session_id' => $session_id,
				]);
			 }
							 
		}
				
			
			
			
		//apply coupon
		if(count(session('coupon'))>0){
			$session_coupon_data = session('coupon');
			session(['coupon' => array()]);		
			$response = array();	
			if(!empty($session_coupon_data)){		
				foreach($session_coupon_data as $key=>$session_coupon){	
						$response = $this->common_apply_coupon($session_coupon->code);
				}
			}	
		}
						
		
		
		$result['commonContent'] = $this->commonContent();
		return view("cartButton")->with('result', $result);
	}	
	
	//addToCart
	public function addToCart(Request $request)
	{
		
		if(count($request->option_id)>0){
			foreach($request->option_id as $option_id){
				if(!$request->$option_id) { 	
					return Response::json('Please select option.',403);
				}
			 }
		}

		$products_id            				=   $request->products_id;		
		
		if(empty(session('customers_id'))){
			$customers_id					=	'';
		}else{
			$customers_id					=	session('customers_id');
		}
		
		$session_id							=	Session::getId();		
		$customers_basket_date_added        =   date('Y-m-d H:i:s');
			
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		$data   = array( 'page_number'=>'0', 'type'=>'', 'products_id'=>$products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$myVar  = new DataController();
		$detail = $myVar->products($data);
		
		if( empty($customers_id) ){
			
			$exist = Basket::where([
					['session_id', '=', $session_id],
					['products_id', '=', $products_id],
					['is_order', '=', 0],
				])->get();
			
		} else {
			
			$exist = Basket::where([
					['customers_id', '=', $customers_id],
					['products_id', '=', $products_id],
					['is_order', '=', 0],
				])->get();
			
		}		
		
		//price is not default
		if(!empty($request->products_price)) {
			$final_price = $request->products_price;
		} elseif(!empty($detail['product_data'][0]->discount_price)) {
			$final_price = $detail['product_data'][0]->discount_price;
		} else {
			$final_price = $detail['product_data'][0]->products_price;
		}
		
		//quantity is not default
		if(empty($request->quantity)) {
			$customers_basket_quantity          =   1;
		} else {
			$customers_basket_quantity          =   $request->quantity;
		}
		
		//insert into cart
		if( count($exist) == 0 ) {					

			$customers_basket_id = Basket::insertGetId(
											[
												 'customers_id' => $customers_id,
												 'products_id'  => $products_id,
												 'session_id'   => $session_id,
												 'customers_basket_quantity' => $customers_basket_quantity,
												 'final_price' => $final_price,
												 'customers_basket_date_added' => $customers_basket_date_added,
											]);
				
			if(count($request->option_id)>0){

				foreach($request->option_id as $option_id){
					
					BasketAttribute::insert(
					[
						 'customers_id' => $customers_id,
						 'products_id'  => $products_id,
						 'products_options_id' =>$option_id,
						 'products_options_values_id'  =>  $request->$option_id,
						 'session_id' => $session_id,
						 'customers_basket_id'=>$customers_basket_id,
					]);
					
				 }
			 
			} else if(!empty($detail['product_data'][0]->attributes)){
				 
				foreach($detail['product_data'][0]->attributes as $attribute){	

					BasketAttribute::insert(
					[
						 'customers_id' => $customers_id,
						 'products_id'  => $products_id,
						 'products_options_id' =>$attribute['option']['id'],
						 'products_options_values_id'  =>  $attribute['values'][0]['id'],
						 'session_id' => $session_id,
						 'customers_basket_id'=>$customers_basket_id,
					]);
				}
			} else {
				 
				$products_attribute = ProductsAttribute::where([
						 'is_default' 	=> 1,
						 'products_id'  => $products_id])->first();

					BasketAttribute::insert(
					[
						 'customers_id' => $customers_id,
						 'products_id'  => $products_id,
						 'products_options_id' =>$products_attribute->options_id,
						 'products_options_values_id'  => $products_attribute->options_values_id,
						 'session_id' => $session_id,
						 'customers_basket_id'=>$customers_basket_id,
					]);
			}


		} else {
			
			$existAttribute = '0';
			$totalAttribute = '0';
			$basket_id 		= '0';
			
			if(!empty($request->option_id)){
				
				if(count($request->option_id)>0){
					
					foreach($exist as $exists){
						$totalAttribute = '0';
						foreach($request->option_id as $option_id){
							$checkexistAttributes = BasketAttribute::where([
									['customers_basket_id', '=', $exists->customers_basket_id],
									['products_id', '=', $products_id],
									['products_options_id', '=', $option_id],
									['customers_id', '=', $customers_id],
									['products_options_values_id', '=', $request->$option_id],
									['session_id', '=', $session_id],
								])->get();
						$totalAttribute++;	
						if(count($checkexistAttributes)>0){
							$existAttribute++;
						}else{
							$existAttribute=0;
						}
							
						}
						
						if($totalAttribute==$existAttribute){
							$basket_id = $exists->customers_basket_id;
						}
					}
					
					
				}else if(!empty($detail['product_data'][0]->attributes)){
				
					foreach($exist as $exists){
						$totalAttribute = '0';
						foreach($detail['product_data'][0]->attributes as $attribute){
							$checkexistAttributes = BasketAttribute::where([									
									['customers_basket_id', '=', $exists->customers_basket_id],
									['products_id', '=', $products_id],
									['products_options_id', '=', $attribute['option']['id']],
									['customers_id', '=', $customers_id],
									['products_options_values_id', '=', $attribute['values'][0]['id']],
									['products_options_id', '=', $option_id],
								])->get();
						$totalAttribute++;	
						if(count($checkexistAttributes)>0){
							$existAttribute++;
						}else{
							$existAttribute=0;
						}
							if($totalAttribute==$existAttribute){
								$basket_id = $exists->customers_basket_id;
							}
						}
					}	
				}
				//attribute exist
				if($basket_id==0){
					
					$customers_basket_id = Basket::insertGetId(
					[
						 'customers_id' => $customers_id,
						 'products_id'  => $products_id,
						 'session_id'   => $session_id,
						 'customers_basket_quantity' => $customers_basket_quantity,
						 'final_price' => $final_price,
						 'customers_basket_date_added' => $customers_basket_date_added,
					]);
					
					if(count($request->option_id)>0){
						foreach($request->option_id as $option_id){
							
							BasketAttribute::insert(
							[
								 'customers_id' => $customers_id,
								 'products_id'  => $products_id,
								 'products_options_id' =>$option_id,
								 'products_options_values_id'  =>  $request->$option_id,
								 'session_id' => $session_id,
								 'customers_basket_id'=>$customers_basket_id,
							]);
							
						 }
					 
					}else if(!empty($detail['product_data'][0]->attributes)){
						
						foreach($detail['product_data'][0]->attributes as $attribute){	
		
							BasketAttribute::insert(
							[
								 'customers_id' => $customers_id,
								 'products_id'  => $products_id,
								 'products_options_id' =>$attribute['option']['id'],
								 'products_options_values_id'  =>  $attribute['values'][0]['id'],
								 'session_id' => $session_id,
								 'customers_basket_id'=>$customers_basket_id,
							]);
						}
					}
						
				}
				else{
					//update into cart
					Basket::where('customers_basket_id', '=', $basket_id)->update(
					[
						 'customers_id' => $customers_id,
						 'products_id'  => $products_id,
						 'session_id'   => $session_id,
						 'customers_basket_quantity' => $customers_basket_quantity,
						 'final_price' => $final_price,
						 'customers_basket_date_added' => $customers_basket_date_added,
					]);
					
					if(count($request->option_id)>0){
						foreach($request->option_id as $option_id){
							
							BasketAttribute::where([
								['customers_basket_id', '=', $basket_id],
								['products_id', '=', $products_id],
								['products_options_id', '=', $option_id],
							])->update(
							[
								 'customers_id' => $customers_id,
								 'products_options_values_id'  =>  $request->$option_id,
								 'session_id' => $session_id,
							]);
						 }
						 				 
					}else if(!empty($detail['product_data'][0]->attributes)){
						
						foreach($detail['product_data'][0]->attributes as $attribute){	
		
							BasketAttribute::where([
								['customers_basket_id', '=', $basket_id],
								['products_id', '=', $products_id],
								['products_options_id', '=', $option_id],
							])->update(
							[
								 'customers_id' => $customers_id,
								 'products_id'  => $products_id,
								 'products_options_id' =>$attribute['option']['id'],
								 'products_options_values_id'  =>  $attribute['values'][0]['id'],
								 'session_id' => $session_id,
								 'customers_basket_id'=>$customers_basket_id,
							]);
						}
					}
					
				}
			
			}else{
				//update	
				//update into cart
				Basket::where('customers_basket_id', '=', $exist[0]->customers_basket_id)->update(
				[
					 'customers_id' => $customers_id,
					 'products_id'  => $products_id,
					 'session_id'   => $session_id,
					 'customers_basket_quantity' => $customers_basket_quantity,
					 'final_price' => $final_price,
					 'customers_basket_date_added' => $customers_basket_date_added,
				]);
			}

			//apply coupon
			if(count(session('coupon'))>0){
				$session_coupon_data = session('coupon');
				session(['coupon' => array()]);		
				$response = array();	
				if(!empty($session_coupon_data)){		
					foreach($session_coupon_data as $key=>$session_coupon){	
							$response = $this->common_apply_coupon($session_coupon->code);
					}
				}	
			}

						
		}
	
		
		$result['commonContent'] = $this->commonContent();
		return view("cartButton")->with('result', $result);
	}	
	//updateCart
	public function updateCart(Request $request)
	{
		
		if(empty(session('customers_id'))) {
			$customers_id	=	'';
		} else {
			$customers_id	=	session('customers_id');
		}
						
		$session_id	= Session::getId();
		
		foreach($request->cart as $key=>$customers_basket_id) {

			$product = Basket::select('customers_basket.products_id','products.products_quantity','products_description.products_name')->LeftJoin('products', function ($join) {
					$join->on('products.products_id', '=', 'customers_basket.products_id');
				 })
				->LeftJoin('products_description', function ($join) {
					$join->on('products_description.products_id', '=', 'products.products_id');
				 })
				->where('customers_basket_id', $customers_basket_id)->first();
			 
			if($product['products_quantity'] > $request->quantity[$key]) {

				Basket::where('customers_basket_id', '=', $customers_basket_id)->update(
				[
					 'customers_id' => $customers_id,
					 'session_id'   => $session_id,
					 'customers_basket_quantity' => $request->quantity[$key],
				]);

			} else {
				$message = $product['products_name'] .' has  quantity exceeded';
				return redirect()->back()->with('error', $message);
			}
			
		}
		
		$message = Lang::get("website.Cart has been updated successfully");

		return redirect()->back()->with('message', $message);

	}	
		
	//mycart
	public function myCart($baskit_id)
	{		
		$cart = Basket::join('products', 'products.products_id','=', 'customers_basket.products_id')
			->join('products_description', 'products_description.products_id','=', 'products.products_id')
			->select('customers_basket.*', 'products.products_model as model', 'products.products_image as image', 'products_description.products_name as products_name', 'products.products_quantity as quantity', 'products.products_price as price', 'products.products_weight as weight', 'products.products_weight_unit as unit', 'products.products_slug', 'products.products_quantity')->where([
						['customers_basket.is_order', '=', '0'],
						['products_description.language_id', '=', Session::get('language_id')],
					]);
			
			if(empty(session('customers_id'))){
				$cart->where('customers_basket.session_id', '=', Session::getId());
			}else{
				$cart->where('customers_basket.customers_id', '=', session('customers_id'));
			}
			
			if(!empty($baskit_id)){
				$cart->where('customers_basket.customers_basket_id', '=', $baskit_id);
			}						
		
		$baskit = $cart->get();
					
		$total_carts = count($baskit);
		$result = array();
		$index = 0;
		if($total_carts > 0){
			foreach($baskit as $cart_data){
				array_push($result, $cart_data);
				
				$attributes = BasketAttribute::leftjoin('products_options', 'products_options.products_options_id','=','customers_basket_attributes.products_options_id')
					->leftjoin('products_options_values', 'products_options_values.products_options_values_id','=','customers_basket_attributes.products_options_values_id')
					->leftjoin('products_attributes', function($join){
						$join->on('customers_basket_attributes.products_id', '=', 'products_attributes.products_id')->on('customers_basket_attributes.products_options_id', '=', 'products_attributes.options_id')->on('customers_basket_attributes.products_options_values_id', '=', 'products_attributes.options_values_id');						
					})
					->select('products_options.products_options_name as attribute_name', 'products_options_values.products_options_values_name as attribute_value', 'customers_basket_attributes.products_options_id as options_id', 'customers_basket_attributes.products_options_values_id as options_values_id', 'products_attributes.price_prefix as prefix', 'products_attributes.options_values_price as values_price' )
					
					->where('customers_basket_attributes.products_id', '=', $cart_data->products_id)
					->where('customers_basket_id', '=', $cart_data->customers_basket_id);					
				
					if(empty(session('customers_id'))){
						$attributes->where('customers_basket_attributes.session_id', '=', Session::getId());
					}else{
						$attributes->where('customers_basket_attributes.customers_id', '=', session('customers_id'));
					}
								
					$attributes_data = $attributes->get();
					$result2 = array();
					if(!empty($cart_data->coupon_id)){
						//coupon
						$coupons = explode(',', $cart_data->coupon_id);
						$index2 = 0;
						foreach($coupons as $coupons_data){
							$coupons =  DB::table('coupons')->where('coupans_id', '=', $coupons_data)->get();
							$result2[$index2++] = $coupons[0];
						}
						
					}
					$result[$index]->coupons = $result2;
					$result[$index]->attributes = $attributes_data;
					$index++;			
			}			
		}				
		return($result); 
	}
	
		
	//apply_coupon
	public function apply_coupon(Request $request){
		
		$result = array();
		$coupon_code = $request->coupon_code;	
			
		
		$carts = $this->myCart(array());
		if(count($carts)>0){
			$response = $this->common_apply_coupon($coupon_code);
		}else{
			$response = array('success'=>'0', 'message'=>Lang::get("website.Coupon can not be apllied to empty cart"));
		}
			print_r(json_encode($response));
	}
	
	
	//removeCoupon
	public function removeCoupon(Request $request)
	{
		$coupons_id = $request->id;
		
		$session_coupon_data = session('coupon');
		session(['coupon' => array()]);	
		session(['coupon_discount' => 0]);	
		$response = array();	
		if(!empty($session_coupon_data)){		
			foreach($session_coupon_data as $key=>$session_coupon){	
				if($session_coupon->coupans_id != $coupons_id){
					//$session_coupons_data[] = $session_coupon;
					$response = $this->common_apply_coupon($session_coupon->code);
					//$response = $this->common_apply_coupon('product_discount_fixed');
				}
			}
		}	
		
		$message = Lang::get("website.Coupon has been removed successfully");
		return redirect()->back()->with('message', $message);
	
	}
	
	//apply_coupon
	public function common_apply_coupon($coupon_code)
	{
		$result = array();
							
		//current date
		$currentDate		=	date('Y-m-d 00:00:00',time());
		
		
		if(session('coupon')=='' or count(session('coupon'))==0) {
			session(['coupon' => array()]);
			session(['coupon_discount' => 0]);
		}
		
		$session_coupon_ids = array();
		$session_coupon_data = array();
		//$free_shipping=false;
		if(!empty(session('coupon'))) {	
			$pev_coupon = session('coupon')[0]['individual_use'];
			if($pev_coupon == 1) {
				return  array('success'=>2, 'message'=>Lang::get("website.The coupon cannot be used in .Becouse conjunction coupons already exists"));
			}
			foreach(session('coupon') as $session_coupon) {	
				array_push($session_coupon_data, $session_coupon);				
				$session_coupon_ids[] = $session_coupon->coupans_id;
							
			}
		}
		return Coupon::applyCoupon($coupon_code,$session_coupon_data,$session_coupon_ids) ;
	}
}