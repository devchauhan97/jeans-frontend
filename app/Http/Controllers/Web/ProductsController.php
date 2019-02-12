<?php

namespace App\Http\Controllers\Web;
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
use App\ProductsOption;
use App\Manufacturer;
use App\ProductsOptionsValue;
use App\Product;
use App\ProductsToCategory;
use App\ProductsAttribute;

//email
use Illuminate\Support\Facades\Mail;
use App\ProductsAttributesImage;
use App\ProductsImage;
use App\LikedProduct;
class ProductsController extends DataController
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
			
	//shop 
	public function shop(Request $request)
	{
		//dd($request);
		$title = array('pageTitle' => Lang::get('website.Shop'));
		$result = array();
		
		$result['commonContent'] = $this->commonContent();

		if(!empty($request->page)) {
			$page_number = $request->page;
		} else {
			$page_number = 0;
		}
		
		if(!empty($request->limit)) {
			$limit = $request->limit;
		} else {
			$limit = 15;
		}
		
		if(!empty($request->type)) {
			$type = $request->type;
		} else {
			$type = '';
		}
		
		//min_price
		if(!empty($request->min_price)) {
			$min_price = $request->min_price;
		} else {
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)) {
			$max_price = $request->max_price;
		} else {
			$max_price = '';
		}	
		
		//category		
		if(!empty($request->category) and $request->category!='all') {
			$category =Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();
			
			$categories_id = $category[0]->categories_id;
			//for main
			if($category[0]->parent_id==0) {
				$category_name = $category[0]->categories_name;
				$sub_category_name = '';
				$category_slug = '';
			} else {
			//for sub
				$main_category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories.categories_id',$category[0]->parent_id)->where('language_id',Session::get('language_id'))->get();
				
				$category_slug = $main_category[0]->categories_slug;
				$category_name = $main_category[0]->categories_name;
				$sub_category_name = $category[0]->categories_name;
			}
			
		} else {
			$categories_id = '';
			$category_name = '';
			$sub_category_name = '';
			$category_slug = '';
		}
		
		
		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		 
		//search value
		if(!empty($request->search)) {
			$search = $request->search;
		} else {
			$search = '';
		}	
		
		
		$filters = array();
		if(!empty($request->filters_applied) and $request->filters_applied==1) {
			$index = 0;
			$options = array();
			$option_values = array();
			$option = ProductsOption::get(); 
			if(!empty($request->brand))
				  {
				  	 $index2=0;
				  	 $values=array();				  	
				  	$brand = Manufacturer::whereIN('manufacturers_name',$request->brand)->get();
				      foreach ($brand as $value) {
				      	$brandid[]=$value->manufacturers_id;
				      }
				       $brandid=array_unique($brandid);
				       $result['filter_attribute']['brand'] = $brandid;
				       $filters['brand'] = implode($brandid,',');
				    }
				    	

			foreach($option as $key=>$options_data) {				
				$option_name = $options_data->products_options_name;				 		
				if(!empty($request->$option_name)){
					$index2 = 0;
					$values = array();
					foreach($request->$option_name as $value)
					{
						$value = ProductsOptionsValue::where('products_options_values_name',$value)->get();
						$option_values[]=$value[0]->products_options_values_id;
					}
					$options[] = $options_data->products_options_id;
				}					
			}
			
			$filters['options_count'] = count($option_values);
			$filters['options'] = implode($options,',');		  
			$filters['option_value'] = implode($option_values, ',');
			$result['filter_attribute']['options'] = $options;
			$result['filter_attribute']['option_values'] = $option_values;

			
		}
		//print_r($filters);
		//$myVar = new DataController();	
		$data = array('page_number'=>$page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
			//dd($data);
		
		$products =$this->products($data);
		//dd($products);
		$result['products'] = $products;
		
		$data = array('limit'=>$limit, 'categories_id'=>$categories_id );
		$filters = $this->filters($data);
		//dd($data);
		$result['filters'] = $filters;
		
		$cart = '';
		//$myVar = new CartController();
		$result['cartArray'] = $result['commonContent']['cart']->pluck('products_id')->toArray();		
		
		if($limit > $result['products']['total_record']){		
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}
		
		//liked products
		$result['liked_products'] = $this->likedProducts();		
		return view("shopj", $title)->with('result', $result); 
		
	}
	
	//access object for custom pagination
	public function accessObjectArray($var)
	{
	  return $var;
	}
	//productDetail 
	public function productDetail(Request $request)
	{
		
		$title 			= 	array('pageTitle' => Lang::get('website.Product Detail'));
		$result 		= 	array();
		$result['commonContent'] = $this->commonContent();
		
		
		$products = Product::
							//with('products_to_categories.category_description')
							 where(['products_slug'=>$request->slug,'products_status'=>1])
							//->where('products_quantity','>',0)
							->first();
							 
		if(!count($products))
			 return response(redirect(url('/404')), 404);

		//category		
		// $category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$products->products_id)->where('categories.parent_id',0)->where('language_id',Session::get('language_id'))->first();

		// $category_slug = '';
		// $category_name = '';

		// if(!empty($category) and count($category)>0){
		// 	$category_slug = $category->categories_slug;
		// 	$category_name = $category->categories_name;
		// } 

		// $sub_category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->leftJoin('products_to_categories','products_to_categories.categories_id','=','categories.categories_id')->where('products_to_categories.products_id',$products->products_id)->where('categories.parent_id','>',0)->where('language_id',Session::get('language_id'))->first();

		// $sub_category_name = '';
		// $sub_category_slug = '';
		// if(!empty($sub_category) and count($sub_category)>0){
		// 	$sub_category_name = $sub_category->categories_name;
		// 	$sub_category_slug = $sub_category->categories_slug;		
		// } 
		
		// $result['category_name'] = $category_name;
		// $result['category_slug'] = $category_slug;
		// $result['sub_category_name'] = $sub_category_name;
		// $result['sub_category_slug'] = $sub_category_slug;
		
		//$myVar = new DataController();
		// $data = array('page_number'=>'0', 'type'=>'', 'products_id'=>$products[0]->products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price,'color'=>$request->Colors, 'size'=>$request->Size);
		// $detail = $this->products($data);

		// ----------------------------------
		$categories = ProductsToCategory::with('other_images')->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id')
				->leftJoin('liked_products',function($q){
					$q->on('liked_products.liked_products_id','=','products.products_id');
					$q->where('liked_products.liked_customers_id', '=', session('customers_id'));
				});

		$categories->LeftJoin('specials', function ($join)   {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>', time());
			})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'products_to_categories.categories_id', 'categories_description.*','liked_products.*');

		$categories->where('products.products_id','=', $products->products_id);
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			//->where('products_quantity','>','0')
			;
		
		$categories->groupBy('products.products_id');
			
		//count
		$total_record = $categories->toSql();
		//dd($total_record);
		$products_data  = $categories->first();
		//dd($products_data );
		$detail = array();
		$result2 = array();
			
		$products_id = $products_data->products_id;
		//multiple images
		$products_images =  $products_data->other_images;

		//array_push($detail,$products_data);
 	
		// ******
		// *****like product
		// ***********
		if($products_data->liked_customers_id>0){
			$result['isLiked'] = '1';
		}else{
			$result['isLiked'] = '0';
		}
		// ******
		// *****list added arttribute to products
		// ***********
		$products_attribute = ProductsAttribute::with(['products_option.products_attribute'=> function ($query) use ($products_id) {
				            $query->where('products_id','=', $products_id);
				        }])
						->where('products_id','=', $products_id)
						->groupBy('options_id')
						->get();
		//dd( $products_attribute);
		$products_attribute_list=	[];//$products_attribute->toArray()	;	
		foreach ($products_attribute as $key => $value) {

			if(count($value->products_option)) {
				foreach ($value->products_option->products_attribute as $key => $row) {
					if($row->is_default !=1) 
					$products_attribute_list[] = $request->{$value->products_option->products_options_name};
				}
			}

		}
		 //dd($products_attribute);
		$attributes_price = 0;
		$attributes = [];
		foreach ( $products_attribute as $key => $value) {

			$temp=array();
			if(count($value->products_option)) {

				foreach ($value->products_option->products_attribute as $key => $row) {

					$p_o_v=ProductsOptionsValue::where('products_options_values_id',$row->options_values_id)->first();

					$temp[] =['value'=>$p_o_v->products_options_values_name,'id' => $row->options_values_id,'price'=>$row->options_values_price,'price_prefix'=>$row->price_prefix,'is_default'=>$row->is_default];

					if(in_array($row->options_values_id, $products_attribute_list)) {

						if($row->price_prefix == '+')
							$attributes_price += $row->options_values_price;
						else
							$attributes_price -= $products_option_value->options_values_price;
					}	
				}
				$attributes[]=['option'=>['name' => $value->products_option->products_options_name,'id' => $value->options_id],'values'=>$temp];
			}
		}
		$result['attributes'] =$attributes;
		// ******
		// ******Get attribute image 
		// ************* 
		if(count($products_attribute_list)) {

			$products_attributes_image = ProductsAttributesImage::select('image')->where('products_id','=', $products_id)
												->whereIn('options_values_id',$products_attribute_list)
												->get();	
 			if(count($products_attributes_image)) {

				$other_option_img =[];
				foreach ($products_attributes_image as $key => $value) {

					if($key == 0) {
						$products_data->products_image=$products_attributes_image[$key]->image;
					} else {
						$other_option_img[] = $products_attributes_image[$key];
					}

				}
				$products_images =  $other_option_img;

 			}
		}

		$result['product_images'] 	= $products_images;
		$result['attributes_price']	= $attributes_price;
		$result['detail']['product_data'][] =$products_data; 
		// ******
		// ******Get simliar products******
		$result['simliar_products'] = $this->simliar_products($products_data['categories_id']);
		
		//$myVar = new CartController();
		$result['cartArray'] = $result['commonContent']['cart']->pluck('products_id')->toArray();
		//dd($result['commonContent']);
		//liked products
		//$result['liked_products'] = LikedProduct::likedProducts();		
		
	//	return view("product-detail", $title)->with('result', $result); 
		return view("product-detail-cloudzoom", $title)->with('result', $result); 
	}
	// ******
	// ******make category wise simliar products
	// ******
	public function simliar_products($categories_id) {

		$categories = ProductsToCategory::LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id');
				
		$categories->LeftJoin('specials', function ($join)  {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>',time());
			})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'products_to_categories.categories_id', 'categories_description.*');


		$categories->where('products_to_categories.categories_id','=', $categories_id);
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			->where('products_quantity','>','0');
		
		$categories->groupBy('products.products_id');
			
		//count
		$total_record = $categories->toSql();
		//dd($total_record);

		$products  = $categories->take(4)->get();
		$result =[];

		if(count($products)>0) {
			$index = 0;	
			foreach ($products as $products_data){

				$products_id = $products_data->products_id;
				
				//multiple images
				$products_images = ProductsImage::select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();		
				$products_data->images =  $products_images;
				
				array_push($result,$products_data);
				$options = array();
				$attr = array();
			
			//like product
				if(!empty(session('customers_id'))){
					$liked_customers_id						=	session('customers_id');	
					$categories = LikedProduct::where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
					if(count($categories)>0){
						$result[$index]->isLiked = '1';
					}else{
						$result[$index]->isLiked = '0';
					}
				}else{
					$result[$index]->isLiked = '0';						
				}
			
			// fetch all options add join from products_options table for option name
				$products_attribute = ProductsAttribute::where('products_id','=', $products_id)->groupBy('options_id')->get();
				$attributes_price=0;
				if(count($products_attribute)) {
				$index2 = 0;
					foreach($products_attribute as $attribute_data){
						$option_name = ProductsOption::where('language_id','=', Session::get('language_id'))->where('products_options_id','=', $attribute_data->options_id)->get();
						
						if(count($option_name)>0){
							
							$temp = array();
							$temp_option['id'] = $attribute_data->options_id;
							$temp_option['name'] = $option_name[0]->products_options_name;
							$temp_option['is_default'] = $attribute_data->is_default;
							$attr[$index2]['option'] = $temp_option;

							// fetch all attributes add join from products_options_values table for option value name
							$attributes_value_query =  ProductsAttribute::where('products_id','=', $products_id)->where('options_id','=', $attribute_data->options_id)->get();
							$k = 0;
								foreach($attributes_value_query as $products_option_value){
									$option_value = ProductsOptionsValue::where('products_options_values_id','=', $products_option_value->options_values_id)->get();
									$temp_i['id'] = $products_option_value->options_values_id;
									$temp_i['value'] = $option_value[0]->products_options_values_name;
									$temp_i['price'] = $products_option_value->options_values_price;
									$temp_i['price_prefix'] = $products_option_value->price_prefix;
									if(in_array($temp_i['id'], [@$data['color'],@$data['size']])) {

										if($products_option_value->price_prefix == '+')
											$attributes_price += $products_option_value->options_values_price;
										else
											$attributes_price -= $products_option_value->options_values_price;
									}
									
									array_push($temp,$temp_i);

								}
								$attr[$index2]['values'] = $temp;
								$result[$index]->attributes = 	$attr;	
								$index2++;
						}
					}
					//$attributes_price=0;
					$result[$index]->attributes_price=$attributes_price;
				}else{
					$result[$index]->attributes = 	array();	
				}

				$index++;
			}
		}
		return ['product_data'=>$result];
	}
	
	public function filterProducts(Request $request)
	{
		$result['commonContent'] = $this->commonContent();
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		} else {
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		} else {
			$max_price = '';
		}	
				
		if(!empty($request->limit)){
			$limit = $request->limit;
		} else {
			$limit = 15;
		}
		
		if(!empty($request->type)){
			$type = $request->type;
		} else {
			$type = '';
		}
		
		//if(!empty($request->category_id)){
		if(!empty($request->category) and $request->category!='all') {
			$category = Category::leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();
			
			$categories_id = $category[0]->categories_id;
		} else {
			$categories_id = '';
		}
		
		//search value
		if(!empty($request->search)) {
			$search = $request->search;
		} else {
			$search = '';
		}
		
		//min_price
		if(!empty($request->min_price)) {
			$min_price = $request->min_price;
		} else {
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$filters['options_count'] = count($request->options_value);
			$filters['options'] = $request->options;
			$filters['option_value'] = $request->options_value;
		}else{
			$filters = array();
		}	
						
		// /$myVar = new DataController();
		$data = array('page_number'=>$request->page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$products = $this->products($data);
		$result['products'] = $products;	
			
		$cart = '';
		//$myVar = new CartController();
		$result['cartArray'] =  $result['commonContent']['cart']->pluck('products_id')->toArray();
		$result['limit'] = $limit;
		return view("filterproducts")->with('result', $result);			
		
	}
	
	//filters
	public function filters($data){
		
		$categories_id      =   $data['categories_id'];				
		$currentDate		=	time();		
				
		$price = ProductsToCategory::join('products', 'products.products_id', '=', 'products_to_categories.products_id');
						if(isset($categories_id) and !empty($categories_id)){
							$price->where('products_to_categories.categories_id','=', $categories_id);
						}
						
		$priceContent 	=	$price->max('products_price');			
		if(!empty($priceContent) and count($priceContent)>0){
			$maxPrice = round($priceContent+1);	
		}else{
			$maxPrice = '';
		}
		
		$product = ProductsToCategory::join('products', 'products.products_id', '=', 'products_to_categories.products_id')
			->leftJoin('products_description','products_description.products_id','=','products.products_id')
			->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
			->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
			->LeftJoin('specials', function ($join) use ($currentDate) {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
			})
			
			->select('products_to_categories.*', 'products.*','products_description.*','manufacturers.*','manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price')
			->where('products_description.language_id','=', Session::get('language_id'));
			
			if(isset($categories_id) and !empty($categories_id)){
				$product->where('products_to_categories.categories_id','=', $categories_id);
			}
			
		$products = $product->get();
		  //dd($products);
		$index = 0;
		$optionsIdArray = array();
		$valueIdArray = array();
		foreach($products as $products_data){
			$option_name = ProductsAttribute::where('products_id', '=', $products_data->products_id)->get();
			foreach($option_name as $option_data){
				
				if(!in_array($option_data->options_id, $optionsIdArray)){
					$optionsIdArray[] = $option_data->options_id;
				}
				
				if(!in_array($option_data->options_values_id, $valueIdArray)){
					$valueIdArray[] = $option_data->options_values_id;
				}
			}
		}

		
		if(!empty($optionsIdArray)){
			
			$index3 = 0;
			$result = array();
			//for brand
		$brandArry=array();
		$pridArry=array();
		foreach ($products as $brand) {
			if($brand->manufacturers_id!=0 && $brand->manufacturers_id!=''){
				$brandArry[]= $brand->manufacturers_id;		   
						       
			    }
				}
				$brandArry=array_unique($brandArry);
		        $brand=Manufacturer::Select('manufacturers_id','manufacturers_name')->whereIn('manufacturers_id',$brandArry)->get();
	        //dd($brand);

			foreach($optionsIdArray as $optionsIdArray){
				$option_name = ProductsOption::where('language_id', Session::get('language_id'))->where('products_options_id', $optionsIdArray)->get();
				if(count($option_name)>0){
					$attribute_opt_val = DB::table('products_options_values_to_products_options')->where('products_options_id', $optionsIdArray)->get();			
					if(count($attribute_opt_val)>0){
					$temp = array();
					$temp_name['name'] = $option_name[0]->products_options_name;
					$attr[$index3]['option'] = $temp_name;
					
					foreach($attribute_opt_val as $attribute_opt_val_data){
					
						$attribute_value = DB::table('products_options_values')->where('products_options_values_id', $attribute_opt_val_data->products_options_values_id )->get();
						
						foreach($attribute_value as $attribute_value_data){
							
							if(in_array($attribute_value_data->products_options_values_id,$valueIdArray)){
								$temp_value['value'] = $attribute_value_data->products_options_values_name;
								$temp_value['value_id'] = $attribute_value_data->products_options_values_id;
								
								array_push($temp, $temp_value);
							}
						}
							$attr[$index3]['values'] = $temp;
					}
					$index3++;
					}
					$response = array('success'=>'1', 'attr_data'=>$attr,'brand'=>$brand ,'message'=> Lang::get('website.Returned all filters successfully'), 'maxPrice'=>$maxPrice);
				}
			
			}
			
		}else{
			$response = array('success'=>'0', 'attr_data'=>array(),'brand'=>array(), 'message'=>Lang::get('website.Filter is empty for this category'), 'maxPrice'=>$maxPrice);
		}
		//dd($response);
		return($response);
		}
	
}
