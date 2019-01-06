<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| front-end Controller Routes
|--------------------------------------------------------------------------
|
| This section contains all Routes of front-end content
| 
|
*/

/********* setting themes dynamically *********/
// default setting
$routeSetting = DB::table('settings')->get();
Theme::set($routeSetting[48]->value);

Route::get('welcome/{locale}', function ($locale) {
    App::setLocale($locale);
	print $locale;
    //
});

Route::group(['namespace' => 'Web'], function () {	
	
//language route
Route::post('/language-chooser', 'WebSettingController@changeLanguage');
Route::post('/language/', array(
	//'before' => 'csrf',
	'as' => 'language-chooser',
	'uses' => 'WebSettingController@changeLanguage'
	));
		
	Route::get('/setStyle', 'DefaultController@setStyle');
	Route::get('/settheme', 'DefaultController@settheme');
	Route::get('/page', 'DefaultController@page');
	Route::post('/subscribeNotification/', 'CustomersController@subscribeNotification');
	
	Route::get('/', 'DefaultController@index');
	Route::get('/index', 'DefaultController@index');
	
	Route::get('/contact-us', 'DefaultController@ContactUs');
	Route::post('/processContactUs', 'DefaultController@processContactUs');
	
	//news section
	// Route::get('/news', 'NewsController@news');
	// Route::get('/news-detail/{slug}', 'NewsController@newsDetail');
	// Route::post('/loadMoreNews', 'NewsController@loadMoreNews');	
	
	
	Route::get('/clear-cache', function() {
		$exitCode = Artisan::call('config:cache');
	});
	
	/*
	|--------------------------------------------------------------------------
	| categories / products Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains all Routes of categories page, products/shop page, product detail. 
	| 
	|
	*/
	
	Route::get('/shop', 'ProductsController@shop');
	Route::post('/shop', 'ProductsController@shop');
	Route::get('/product-detail/{slug}', 'ProductsController@productDetail');
	Route::post('/filterProducts', 'ProductsController@filterProducts');
	
	
	
	/*
	|--------------------------------------------------------------------------
	| Cart Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains customer cart products
	| 
	*/

	Route::get('/getCart', 'DataController@getCart');
	Route::post('/addToCart', 'CartController@addToCart');
	Route::post('/updatesinglecart', 'CartController@updatesinglecart');
	Route::get('/cartButton', 'CartController@cartButton');
	
	Route::get('/viewcart', 'CartController@viewcart');
	Route::get('/editcart', 'CartController@editcart');
	
	Route::post('/updateCart', 'CartController@updateCart');
	Route::get('/deleteCart', 'CartController@deleteCart');
	Route::post('/apply_coupon', 'CartController@apply_coupon');
	Route::get('/removeCoupon/{id}', 'CartController@removeCoupon');
	
	
	
	/*
	|--------------------------------------------------------------------------
	| customer registrations Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains all Routes of signup page, login page, forgot password 
	| facebook login , google login, shipping address etc.
	|
	*/
	
	Route::get('/login', 'CustomersController@login');
	Route::get('/signup', 'CustomersController@signup');
	Route::post('/process-login', 'CustomersController@processLogin');
	Route::get('/logout', 'CustomersController@logout');
	Route::post('/signupProcess', 'CustomersController@signupProcess');
	Route::get('/forgotPassword', 'CustomersController@forgotPassword');
	Route::get('/recoverPassword', 'CustomersController@recoverPassword');
	Route::post('/processPassword', 'CustomersController@processPassword');
	
	
	Route::get('login/{social}', 'CustomersController@socialLogin');
	Route::get('login/{social}/callback', 'CustomersController@handleSocialLoginCallback');
	Route::post('/commentsOrder', 'OrdersController@commentsOrder');
	
	//zones
	Route::post('/ajaxZones', 'ShippingAddressController@ajaxZones');
	
	//likeMyProduct
	Route::post('likeMyProduct', 'CustomersController@likeMyProduct');
	
	/*
	|--------------------------------------------------------------------------
	| WEbiste auth path Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains all Routes of After login 
	| 
	|
	*/
		
	Route::group(['middleware' => 'Customer'], function () {						
		Route::get('/wishlist', 'CustomersController@wishlist');
		Route::post('/loadMoreWishlist', 'CustomersController@loadMoreWishlist');
		Route::get('/profile', 'CustomersController@profile');
		Route::post('/updateMyProfile', 'CustomersController@updateMyProfile');
		Route::post('/updateMyPassword', 'CustomersController@updateMyPassword');		
		
		Route::get('/shipping-address', 'ShippingAddressController@shippingAddress');
		Route::post('/addMyAddress', 'ShippingAddressController@addMyAddress');
		Route::post('/myDefaultAddress', 'ShippingAddressController@myDefaultAddress');		
		
		Route::post('/update-address', 'ShippingAddressController@updateAddress');
		Route::post('/delete-address', 'ShippingAddressController@deleteAddress');
		
		Route::get('/checkout', 'OrdersController@checkout');	
		Route::post('/checkout_shipping_address', 'OrdersController@checkout_shipping_address');
		Route::post('/checkout_billing_address', 'OrdersController@checkout_billing_address');
		Route::post('/checkout_payment_method', 'OrdersController@checkout_payment_method');
		Route::post('/paymentComponent', 'OrdersController@paymentComponent');	
		Route::post('/place_order', 'OrdersController@place_order');	
		Route::get('/orders', 'OrdersController@orders');	
		Route::post('/myorders', 'OrdersController@myorders');	
		Route::get('/stripeForm', 'OrdersController@stripeForm');	
		Route::get('/view-order/{id}', 'OrdersController@viewOrder');
	});
});

