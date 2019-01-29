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
	function accessObjectArray($var)
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
							 where('products_slug',$request->slug)

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
		$categories = ProductsToCategory::LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id');
		$categories->where('products.products_id','=', $products->products_id);
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			->where('products_quantity','>','0');
		
		$categories->groupBy('products.products_id');
			
		//count
		$total_record = $categories->toSql();
		//dd($total_record);

		$products  = $categories->get();

		$detail = array();
		$result2 = array();
			
			//check if record exist
		if(count($products)>0) {
			$index = 0;	
			foreach ($products as $products_data){

				$products_id = $products_data->products_id;
				
				//multiple images
				 
				$products_images = ProductsImage::select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();	

				$products_data->images =  $products_images;
				/**Get attribute image */ 
				if(isset($request->Colors) || isset($request->Size)){
					$products_attributes_image = ProductsAttributesImage::select('image')->where('products_id','=', $products_id)
					->where(function($query) use ($request){
						$query->orWhere('options_values_id',$request->Colors);

						$query->orWhere('options_values_id',$request->Size);
					})
					->get();	
					//dd($products_attributes_image->toSql());
					if(count($products_attributes_image))
						$products_data->images =  $products_attributes_image;
				}
				array_push($detail,$products_data);
				$options = array();
				$attr = array();
			
			//like product
				if(!empty(session('customers_id'))){
					$liked_customers_id						=	session('customers_id');	
					$categories = LikedProduct::where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
					if(count($categories)>0){
						$detail[$index]->isLiked = '1';
					}else{
						$detail[$index]->isLiked = '0';
					}
				}else{
					$detail[$index]->isLiked = '0';						
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
									if(in_array($temp_i['id'], [$request->Colors,$request->Size])) {

										if($products_option_value->price_prefix == '+')
											$attributes_price += $products_option_value->options_values_price;
										else
											$attributes_price -= $products_option_value->options_values_price;
									}
									
									array_push($temp,$temp_i);

								}
								$attr[$index2]['values'] = $temp;
								$detail[$index]->attributes = 	$attr;	
								$index2++;
						}
					}
					//$attributes_price=0;
					$detail[$index]->attributes_price=$attributes_price;
				}else{
					$detail[$index]->attributes = 	array();	
				}

				$index++;
			}
		} 
		/* $products_attribute = ProductsAttribute::with(['products_option.products_attribute'=> function ($query) use ($products_id) {
            $query->where('products_id','=', $products_id);
        }])->where('products_id','=', $products_id)
		//$products_attribute = ProductsOption::with('products_attributes')
		 
		->groupBy('options_id')
		->get();
		
		foreach ($products_attribute as $key => $value) {

			$temp = array();
			$temp_option['id'] = $value->options_id;
			$temp_option['name'] = $value->products_options_name;
			$temp_option['is_default'] = $attribute_data->is_default;
			$temp=array();
			foreach ($value->products_option->products_attribute as $key => $products_attribute) {
			
				$temp[] =['name' => $value,'id' => $products_attribute->options_values_id,'price'=>$products_attribute->options_values_price,'price_prefix'=>$products_attribute->price_prefix];	
			}
			$result['attributes']['option'][]=$temp;
		}*/
		// ---------------------------
		$result['detail']['product_data'] =$detail;
		 // ["products_id" => 8,
   //    "categories_id" => 1,
   //    "created_at" => null,
   //    "updated_at" => null,
   //    "products_quantity" => 9995,
   //    "products_model" => null,
   //    "products_image" => "product_images/1502181584.pPOLO2-26008953_standard_v400.jpg",
   //    "products_price" => "125.50",
   //    "products_date_added" => "2017-08-08 08:39:44",
   //    "products_last_modified" => "2019-01-29 05:38:52",
   //    "products_date_available" => null,
   //    "products_weight" => "0.500",
   //    "products_weight_unit" => "Kilogram",
   //    "products_status" => 1,
   //    "products_tax_class_id" => 1,
   //    "manufacturers_id" => null,
   //    "products_ordered" => 8,
   //    "products_liked" => 3,
   //    "low_limit" => 0,
   //    "products_slug" => "standard-fit-cotton-popover",
   //    "is_feature" => null,
   //    "categories_description_id" => 1,
   //    "language_id" => 1,
   //    "categories_name" => "Men's Clothing",
   //    "manufacturers_name" => null,
   //    "manufacturers_image" => null,
   //    "date_added" => null,
   //    "last_modified" => null,
   //    "manufacturers_slug" => null,
   //    "languages_id" => null,
   //    "manufacturers_url" => null,
   //    "url_clicked" => null,
   //    "date_last_click" => null,
   //    "id" => 15,
   //    "products_name" => "STANDARD FIT COTTON POPOVER",
   //    "products_description" => "<p>Standard Fit: a comfortable, relaxed silhouette. If you favored our Classic Fit or Custom Fit, you will like this updated version. Size medium has a 30&quot;",
   //    "products_url" => null,
   //    "products_viewed" => 0
   //  ];

		

		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$detail[0]->categories_id, 'limit'=>5, 'min_price'=>0, 'max_price'=>0,'color'=>$request->Colors, 'size'=>$request->Size);
		$simliar_products = $this->products($data);

		$result['simliar_products'] = $simliar_products;
		
		$cart = '';
		//$myVar = new CartController();
		$result['cartArray'] = $result['commonContent']['cart']->pluck('products_id')->toArray();
		
		//liked products
		$result['liked_products'] = $this->likedProducts();	
		
		return view("product-detail", $title)->with('result', $result); 
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
