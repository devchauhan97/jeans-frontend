<?php

namespace App\Traits;
use Lang;
use URL;
use Session;
use Redirect;
use Input;
use App\User;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use App\Http\Requests\StripeRequest;
use Response;
use App\PaymentsSetting;
use App\Country;
use App\Zone;
use App\PaymentDescription;
use App\Order;
use App\OrdersStatusHistory;
use App\Setting;
use App\OrdersProductsAttribute;
use App\OrdersStatus;
use App\Basket;
use App\OrdersProduct;
use App\Product;
use App\ShippingMethod;
use App\ShippingDescription;
use App\UpsShipping;
use App\FlateRate;
use App\Events\SendProductOrderMail;
use Event;
use DB;
use App\Manufacturer;
use App\ProductsToCategory;
use App\ProductsImage;
use App\ProductsAttribute;
use App\ProductsOption;
use App\ProductsOptionsValue;
use App\LikedProduct;
use App\ProductsOptionsValuesToProductsOption;

trait ShopProducts
{	

	 
    public function listProducts( $data )
    {
		//dd($data);

		if(empty($data['page_number']) or $data['page_number'] == 0 ) {
			$skip								=   $data['page_number'].'0';
		} else {
			$skip								=   $data['limit']*$data['page_number'];
		}		
		
		$min_price	 							=   $data['min_price'];	
		$max_price	 							=   $data['max_price'];	
		$take									=   $data['limit'];
		$currentDate 							=   time();	
		$type									=	$data['type'];
		
		if($type=="atoz") {
			$sortby								=	"products_name";
			$order								=	"ASC";
		}elseif($type=="ztoa"){
			$sortby								=	"products_name";
			$order								=	"DESC";
		}elseif($type=="hightolow") {

			if(!empty($data['filters']['filter_type'])) {
				$sortby								=	"specials_new_products_price";
			} else {
				$sortby								=	"products_price";
			}

			$order								=	"DESC";

		}elseif($type=="lowtohigh") {

			if(!empty($data['filters']['filter_type'])) {
				$sortby								=	"specials_new_products_price";
			} else {
				$sortby								=	"products_price";
			}

			$order								=	"ASC";

		}elseif($type=="topseller"){
			$sortby								=	"products_ordered";
			$order								=	"DESC";
		}elseif($type=="mostliked"){
			$sortby								=	"products_liked";
			$order								=	"DESC";
			
		}elseif($type == "special" || $type == "deals"){ 
			$sortby = "specials.products_id";
			$order = "desc";
		} else {
			$sortby = "products.products_id";
			$order = "desc";
		}	
		
		$filterProducts = array();
		$eliminateRecord = array();
		$products_attribute_list=[];

		$categories = ProductsToCategory::LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id')
				->leftJoin('liked_products', function ($join)   {  
						$join->on('liked_products.liked_products_id', 'products.products_id')
						->where('liked_customers_id',session('customers_id'));
				})
				;

	    //dd($data['filters']);
		if(!empty($data['filters']) and empty($data['search'])){	

			$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id');

		}
			
		if(!empty($data['search'])) {

			$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id')
				->leftJoin('products_options','products_options.products_options_id','=','products_attributes.options_id')
				->leftJoin('products_options_values','products_options_values.products_options_values_id','=','products_attributes.options_values_id');

		}
		//wishlist customer id
		if($type == "wishlist") {

			$categories->LeftJoin('liked_products', 'liked_products.liked_products_id', '=', 'products.products_id');

		}//parameter special
		elseif($type == "special") {

			$categories->LeftJoin('specials', 'specials.products_id', '=', 'products_to_categories.products_id')
				->select('products.*', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price', 'categories_description.*','liked_products.liked_customers_id');
		} else{

			$categories->LeftJoin('specials', function ($join) use ($currentDate) {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
			})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'products_to_categories.categories_id', 'categories_description.*','liked_products.liked_customers_id');
		}
			
		if($type == "special"){ //deals special products

			$categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);

		}
		
		//get single category products
		if( !empty($data['categories_id']) ){

			$categories->where('products_to_categories.categories_id','=', $data['categories_id']);

		}
		
		//get single products
		if(!empty($data['products_id']) && $data['products_id']!=""){

			$categories->where('products.products_id','=', $data['products_id']);

		}
		
		//for min and maximum price
		if(!empty($max_price)){
			if(!empty($data['filters']['filter_type'])) {

				$categories->whereBetween('specials.specials_new_products_price', [$min_price, $max_price]);

			} else {

				$categories->whereBetween('products.products_price', [$min_price, $max_price]);
			}

		}
			
		if(!empty($data['search'])) {
				
			$searchValue = $data['search'];
			$categories->where('products_options.products_options_name', 'LIKE', '%'.$searchValue.'%');
				
			$categories->orWhere('products_options_values.products_options_values_name', 'LIKE', '%'.$searchValue.'%');		
			
			$categories->orWhere('products_name', 'LIKE', '%'.$searchValue.'%');				
			 
			$categories->orWhere('products_model', 'LIKE', '%'.$searchValue.'%');
			 				
     	}
						
		if( !empty($data['filters']['options']) ) {	

			$products_attribute_list = explode(',',$data['filters']['option_value']);	

			$categories->whereIn('products_attributes.options_id', explode(',',$data['filters']['options']))	           
				->whereIn('products_attributes.options_values_id', explode(',',$data['filters']['option_value']))
				;
				
             
		}
		//echo "<pre>";
		//print_r($data['filters']);

		/*if(!empty($data['filters']['filter_categories']) ) {

			$filter_categories = $data['filters']['filter_categories'];
			//dd($filter_categories);
			$categories->whereIn('products_to_categories.categories_id',$data['filters']['filter_categories']);


							
		}
		if(!empty($data['filters']['filter_occasion_category'])) {
 		
			$categories->whereIn('products_to_categories.categories_id',$data['filters']['filter_occasion_category']);	 	 
							
		}
*/
			
		if(!empty($data['filters']['filter_product_tags'])) {
			
			$filter_product_tags =implode('|', $data['filters']['filter_product_tags']);
			 
			$categories->whereRaw('CONCAT(",", `products_tags_id`, ",") REGEXP ",('.$filter_product_tags.'),"'); 
							
		}
		if(!empty($data['filters']['brand'])){

			$categories->whereIn('products.manufacturers_id',[$data['filters']['brand']]);		
			//dd($dd);
			$data['filters']['brand'];
			//die;
							
		}

		if(!empty($data['filters']['filter_type'])){ //deals special products
 
			$categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);

		}
		//wishlist customer id
		if( $type == "wishlist") {
			$categories->where('liked_customers_id', '=', session('customers_id'));
		}
		
		//wishlist customer id
		if( $type == "is_feature") {
			$categories->where('products.is_feature', '=', 1);
		}
			
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			->where('products_quantity','>','0')
			->orderBy($sortby, $order);
		
		$categories->groupBy('products.products_id');
		//count
		// $total_record = $categories->toSql();
		// dd($total_record);

		$products  = $categories->skip( $skip )->take( $take )->get();
		//dd($products);
		$total_record = $categories->paginate($skip/$take)->total();
	    //$products  = $categories->skip($skip)->take($take)->toSql();
		 
		$result = array();
		$result2 = array();
			
		//check if record exist
		if( count( $products ) >0 ) {
			$index = 0;	
			foreach ( $products as $products_data ) {

				$products_id = $products_data->products_id;
				
				//multiple images
				// $products_images = ProductsImage::select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();		
				// $products_data->images =  $products_images;

				array_push($result,$products_data);	

				$options = array();
				$attr = array();
			
			//like product
				// if( !empty( session('customers_id') ) ) {
				// 	$liked_customers_id						=	session('customers_id');	
				// 	$categories = LikedProduct::where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
				// 	if( count($categories)>0 ) {
				// 		$result[$index]->isLiked = '1';
				// 	} else {
				// 		$result[$index]->isLiked = '0';
				// 	}
				// } else {
				// 	$result[$index]->isLiked = '0';						
				// } 

				$products_attribute = ProductsAttribute::with(['products_option.products_attribute'=> function ($query) use ($products_id) {
							$query->with('products_options_values');
				            $query->where('products_id','=', $products_id);
				        //    $query->where('is_default','=',1);
				        }])
						->where('products_id','=', $products_id)
						->groupBy('options_id')
						->get();
				 
				$attributes = [];
				$attributes_price=0;
				$option_fillter_seleted=[];
				foreach ( $products_attribute as $key => $value) {

					$temp=array();
					if(count($value->products_option)) {

						foreach ($value->products_option->products_attribute as $key => $row) {

							$filter_selected = false;

							if( count($products_attribute_list) == 0 && $row->is_default == 1 ) {

								$option_fillter_seleted[$value->products_option->products_options_name]=$row->options_values_id;

							} else if( in_array($row->options_values_id, $products_attribute_list) ) {

								// if($row->price_prefix == '+')
								// 	$attributes_price += $row->options_values_price;
								// else
								// 	$attributes_price -= $products_option_value->options_values_price;
								$filter_selected = true;

								$option_fillter_seleted[$value->products_option->products_options_name]=$row->options_values_id;
							}
							
							$temp[] =['value'=>$row->products_options_values->products_options_values_name,'id' => $row->options_values_id,'price'=>$row->options_values_price,'price_prefix'=>$row->price_prefix,'is_default'=>$row->is_default,'filter_selected' =>$filter_selected];
						}
						$attributes[]=['option'=>['name' => $value->products_option->products_options_name,'id' => $value->options_id],'values'=>$temp];
					}
				}

				$param = count($option_fillter_seleted) ? '?'. makeQueryParameter($option_fillter_seleted) :'' ;

				$result[$index]->products_slug=$products_data->products_slug.$param;

				$result[$index]->attributes_price=$attributes_price;
				
				$result[$index]->attributes =$attributes;
				$index++;
			}
			//dd($result);
			$responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>$total_record);
		} else {
				$responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>$total_record);
		}	

		return($responseData);
	
	}


	public function shopFilters($data)
	{
		
		$categories_id      =   $data['categories_id'];				
		$currentDate		=	time();		
				
		$price = ProductsToCategory::join('products', 'products.products_id', '=', 'products_to_categories.products_id');

		if(isset($categories_id) and !empty($categories_id)) {
			$price->where('products_to_categories.categories_id','=', $categories_id);
		}
		
		if(isset($data['filter_type']) and !empty($data['filter_type'])) {
			
			$priceContent = $price->LeftJoin('specials', 'specials.products_id', '=', 'products_to_categories.products_id')->max('specials_new_products_price');
		} else {

			$priceContent = $price->max('products_price');
		}


		if(!empty($priceContent) and count($priceContent)>0) {
			$maxPrice = round($priceContent+1);	
		} else {
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
			
		if(isset($categories_id) and !empty($categories_id)) {
			$product->where('products_to_categories.categories_id','=', $categories_id);
		}
			
		$products = $product->get();
		  //dd($products);
		$index = 0;
		$optionsIdArray = array();
		$valueIdArray = array();

		foreach($products as $products_data) {

			$option_name = ProductsAttribute::where('products_id', '=', $products_data->products_id)->get();

			foreach($option_name as $option_data) {
				
				if(!in_array($option_data->options_id, $optionsIdArray)) {
					$optionsIdArray[] = $option_data->options_id;
				}
				
				if(!in_array($option_data->options_values_id, $valueIdArray)) {
					$valueIdArray[] = $option_data->options_values_id;
				}
			}
		}

		
		if( !empty($optionsIdArray) ) {
			
			$index3 = 0;
			$result = array();
			//for brand
			$brandArry=array();
			$pridArry=array();
			foreach ($products as $brand) {

				if($brand->manufacturers_id!=0 && $brand->manufacturers_id!='') {
					$brandArry[]= $brand->manufacturers_id;		   
							       
			    }

			}
			$brandArry=array_unique($brandArry);
	        $brand=Manufacturer::Select('manufacturers_id','manufacturers_name')->whereIn('manufacturers_id',$brandArry)->get();
		        //dd($brand);
	        $products_option = ProductsOption::
	        //with('products_options_values_to_products_options.products_options_values')
	        with(['products_options_values_to_products_options'=>function($query) use ($valueIdArray) {
	        		
	        		$query->with('products_options_values');
	        		$query->whereIn('products_options_values_id',$valueIdArray);
	       		 }])->where('language_id', Session::get('language_id'))
	        	->whereIn('products_options_id', $optionsIdArray)->get();
	        //dd($products_option);
			foreach($products_option as $products_option_val) {

				// $option_name = ProductsOption::where('language_id', Session::get('language_id'))->where('products_options_id', $optionsIdArray)->get();
				//if(count($products_option_val)>0) {

					// $attribute_opt_val = DB::table('products_options_values_to_products_options')->where('products_options_id', $optionsIdArray)->get();

					// if(count($products_option_val->products_options_values_to_products_options)>0){

						$temp = array();
						$temp_name['name'] = $products_option_val->products_options_name;
						$attr[$index3]['option'] = $temp_name;
						
						foreach($products_option_val->products_options_values_to_products_options as $attribute_opt_val_data){
						//dd($attribute_opt_val_data->products_options_values);
							// $attribute_value = DB::table('products_options_values')->where('products_options_values_id', $attribute_opt_val_data->products_options_values_id )->get();
							
							// foreach($attribute_opt_val_data->products_options_values as $attribute_value_data){
								
								// if(count($attribute_opt_val_data->products_options_values)){

									$temp_value['value'] = $attribute_opt_val_data->products_options_values->products_options_values_name;
									$temp_value['value_id'] = $attribute_opt_val_data->products_options_values->products_options_values_id;
									
									array_push($temp, $temp_value);
								// }
							// }
								$attr[$index3]['values'] = $temp;
						}
						$index3++;
					//}
					// $response = array('success'=>'1', 'attr_data'=>$attr,'brand'=>$brand ,'message'=> Lang::get('website.Returned all filters successfully'), 'maxPrice'=>$maxPrice);
				//}
			
			}

			$response = array('success'=>'1', 'attr_data'=>$attr,'brand'=>$brand ,'message'=> Lang::get('website.Returned all filters successfully'), 'maxPrice'=>$maxPrice);
		}else{
			$response = array('success'=>'0', 'attr_data'=>array(),'brand'=>array(), 'message'=>Lang::get('website.Filter is empty for this category'), 'maxPrice'=>$maxPrice);
		}
		//dd($response);
		return($response);
	}
	public function loadMoreProducts($request)
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
		$products = $this->listMoreproducts($data);
		$result['products'] = $products;	
			
		$cart = '';
		//$myVar = new CartController();
		$result['cartArray'] =  $result['commonContent']['cart']->pluck('products_id')->toArray();
		$result['limit'] = $limit;
		return view("filterproducts")->with('result', $result);			
		
	}

	public function listMoreProducts( $data )
    {
		//dd($data);

		if(empty($data['page_number']) or $data['page_number'] == 0 ) {
			$skip								=   $data['page_number'].'0';
		} else {
			$skip								=   $data['limit']*$data['page_number'];
		}		
		
		$min_price	 							=   $data['min_price'];	
		$max_price	 							=   $data['max_price'];	
		$take									=   $data['limit'];
		$currentDate 							=   time();	
		$type									=	$data['type'];
		
		if($type=="atoz") {
			$sortby								=	"products_name";
			$order								=	"ASC";
		}elseif($type=="ztoa"){
			$sortby								=	"products_name";
			$order								=	"DESC";
		}elseif($type=="hightolow"){
			$sortby								=	"products_price";
			$order								=	"DESC";
		}elseif($type=="lowtohigh"){
			$sortby								=	"products_price";
			$order								=	"ASC";
		}elseif($type=="topseller"){
			$sortby								=	"products_ordered";
			$order								=	"DESC";
		}elseif($type=="mostliked"){
			$sortby								=	"products_liked";
			$order								=	"DESC";
			
		}elseif($type == "special" || $type == "deals"){ 
			$sortby = "specials.products_id";
			$order = "desc";
		}else{
			$sortby = "products.products_id";
			$order = "desc";
		}	
		
		$filterProducts = array();
		$eliminateRecord = array();
		$products_attribute_list=[];
		$categories = ProductsToCategory::LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id')
				->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
				->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
				->leftJoin('products_description','products_description.products_id','=','products.products_id');
			  //dd($data['filters']);
		if(!empty($data['filters']) and empty($data['search'])){			
			$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id');
		}
			
		if(!empty($data['search'])){
			$categories->leftJoin('products_attributes','products_attributes.products_id','=','products.products_id')
				->leftJoin('products_options','products_options.products_options_id','=','products_attributes.options_id')
				->leftJoin('products_options_values','products_options_values.products_options_values_id','=','products_attributes.options_values_id');
		}
		//wishlist customer id
		if($type == "wishlist"){
			$categories->LeftJoin('liked_products', 'liked_products.liked_products_id', '=', 'products.products_id');
		}
		//parameter special
		elseif($type == "special") {
			$categories->LeftJoin('specials', 'specials.products_id', '=', 'products_to_categories.products_id')
				->select('products.*', 'products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price', 'categories_description.*');
		} else{
			$categories->LeftJoin('specials', function ($join) use ($currentDate) {  
				$join->on('specials.products_id', '=', 'products_to_categories.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
			})->select('products.*','products_description.*', 'manufacturers.*', 'manufacturers_info.manufacturers_url', 'specials.specials_new_products_price as discount_price', 'products_to_categories.categories_id', 'categories_description.*');
		}
			
			
		if($type == "special"){ //deals special products
			$categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);
		}
		
		//get single category products
		if(!empty($data['categories_id'])){
			$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
		}
		
		//get single products
		if(!empty($data['products_id']) && $data['products_id']!=""){
			$categories->where('products.products_id','=', $data['products_id']);
		}
		
		
		//for min and maximum price
		if(!empty($max_price)){
			$categories->whereBetween('products.products_price', [$min_price, $max_price]);
		}
			
		if(!empty($data['search'])) {
				
			$searchValue = $data['search'];
			$categories->where('products_options.products_options_name', 'LIKE', '%'.$searchValue.'%');
							
			if(!empty($data['categories_id'])){
				$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
			}
			
			if(!empty($data['filters'])){			
				$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
					->whereIn('products_attributes.options_values_id', [$data['filters']['option_value']])
					->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);					
			}				
				
			$categories->orWhere('products_options_values.products_options_values_name', 'LIKE', '%'.$searchValue.'%');				
			if(!empty($data['categories_id'])){
				$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
			}
			
			if(!empty($data['filters'])){			
				$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
					->whereIn('products_attributes.options_values_id', [$data['filters']['option_value']])
					->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);					
			}	
			
			$categories->orWhere('products_name', 'LIKE', '%'.$searchValue.'%');				
			if(empty($data['search']) and !empty($data['categories_id'])){
				$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
			}
			
			if(!empty($data['filters'])){			
				$categories->whereIn('products_attributes.options_id', [$data['filters']['options']])
					->whereIn('products_attributes.options_values_id', [$data['filters']['option_value']])
					->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count']);					
			}	
			
			$categories->orWhere('products_model', 'LIKE', '%'.$searchValue.'%');
			
			if(!empty($data['categories_id'])){
				$categories->where('products_to_categories.categories_id','=', $data['categories_id']);
			}
			
			if(!empty($data['filters'])) {	

				$products_attribute_list = explode(',',$data['filters']['option_value']);			
				$categories->whereIn('products_attributes.options_id', explode(',',$data['filters']['options']))	           
				->whereIn('products_attributes.options_values_id', explode(',',$data['filters']['option_value']))	
					// ->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count'])
					;	
									
			}					
     	}
						
		if( !empty($data['filters']) ) {	
			$products_attribute_list = explode(',',$data['filters']['option_value']);		
			$categories->whereIn('products_attributes.options_id', explode(',',$data['filters']['options']))	           
				->whereIn('products_attributes.options_values_id', explode(',',$data['filters']['option_value']))			
				// ->where(DB::raw('(select count(*) from `products_attributes` where `products_attributes`.`products_id` = `products`.`products_id` and `products_attributes`.`options_id` in ('.$data['filters']['options'].') and `products_attributes`.`options_values_id` in ('.$data['filters']['option_value'].'))'),'>=',$data['filters']['options_count'])
				;
				
             
		}
		//echo "<pre>";
		//print_r($data['filters']);
		if(!empty($data['filters']['brand'])){

			$categories->whereIn('products.manufacturers_id',[$data['filters']['brand']]);		
			//dd($dd);
			$data['filters']['brand'];
			//die;
							
		}

		//wishlist customer id
		if( $type == "wishlist") {
			$categories->where('liked_customers_id', '=', session('customers_id'));
		}
		
		//wishlist customer id
		if( $type == "is_feature") {
			$categories->where('products.is_feature', '=', 1);
		}
			
		$categories->where('products_description.language_id','=',Session::get('language_id'))
			->where('categories_description.language_id','=',Session::get('language_id'))
			->where('products_quantity','>','0')
			->orderBy($sortby, $order);
		
		$categories->groupBy('products.products_id');
		//count
		$total_record = $categories->toSql();
		//dd($total_record);
		$products  = $categories->skip( $skip )->take( $take )->get();
	     //$products  = $categories->skip($skip)->take($take)->toSql();
		 //dd($products);
		$result = array();
		$result2 = array();
			
		//check if record exist
		if( count( $products ) >0 ) {
			$index = 0;	
			foreach ( $products as $products_data ) {

				$products_id = $products_data->products_id;
				
				//multiple images
				// $products_images = ProductsImage::select('image')->where('products_id','=', $products_id)->orderBy('sort_order', 'ASC')->get();		
				// $products_data->images =  $products_images;

				array_push($result,$products_data);	

				$options = array();
				$attr = array();
			
			//like product
				if( !empty( session('customers_id') ) ) {
					$liked_customers_id						=	session('customers_id');	
					$categories = LikedProduct::where('liked_products_id', '=', $products_id)->where('liked_customers_id', '=', $liked_customers_id)->get();
					
					if( count($categories)>0 ) {
						$result[$index]->isLiked = '1';
					} else {
						$result[$index]->isLiked = '0';
					}
				} else {
					$result[$index]->isLiked = '0';						
				} 

				$products_attribute = ProductsAttribute::with(['products_option.products_attribute'=> function ($query) use ($products_id) {
							$query->with('products_options_values');
				            $query->where('products_id','=', $products_id);
				        //    $query->where('is_default','=',1);
				        }])
						->where('products_id','=', $products_id)
						->groupBy('options_id')
						->get();
				 
				$attributes = [];
				$attributes_price=0;
				$option_fillter_seleted=[];
				foreach ( $products_attribute as $key => $value) {

					$temp=array();
					if(count($value->products_option)) {

						foreach ($value->products_option->products_attribute as $key => $row) {

							$filter_selected = false;

							if( count($products_attribute_list) == 0 && $row->is_default == 1 ) {

								$option_fillter_seleted[$value->products_option->products_options_name]=$row->options_values_id;

							} else if( in_array($row->options_values_id, $products_attribute_list) ) {

								// if($row->price_prefix == '+')
								// 	$attributes_price += $row->options_values_price;
								// else
								// 	$attributes_price -= $products_option_value->options_values_price;
								$filter_selected = true;

								$option_fillter_seleted[$value->products_option->products_options_name]=$row->options_values_id;
							}
							
							$temp[] =['value'=>$row->products_options_values->products_options_values_name,'id' => $row->options_values_id,'price'=>$row->options_values_price,'price_prefix'=>$row->price_prefix,'is_default'=>$row->is_default,'filter_selected' =>$filter_selected];
						}
						$attributes[]=['option'=>['name' => $value->products_option->products_options_name,'id' => $value->options_id],'values'=>$temp];
					}
				}

				$param = count($option_fillter_seleted) ? '?'. makeQueryParameter($option_fillter_seleted) :'' ;

				$result[$index]->products_slug=$products_data->products_slug.$param;

				$result[$index]->attributes_price=$attributes_price;
				
				$result[$index]->attributes =$attributes;
				$index++;
			}
			//dd($result);
			$responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));
		} else {
				$responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
		}	

		return($responseData);
	
	}
 }
