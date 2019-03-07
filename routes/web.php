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
Theme::set('libaas');

// Route::get('welcome/{locale}', function ($locale) {
//     App::setLocale($locale);
// 	print $locale;
//     //
// });

Route::group(['namespace' => 'Web'], function () {	
	
	//language route
	Route::post('/language-chooser', 'WebSettingController@changeLanguage');
	Route::post('/language/', array( //'before' => 'csrf',
		'as' => 'language-chooser',
		'uses' => 'WebSettingController@changeLanguage'
		));
		
	Route::get('404', ['as' => '404', 'uses' => 'ErrorController@notFound']);
	Route::get('500', ['as' => '500', 'uses' => 'ErrorController@fatal']);
	Route::get('/setStyle', 'DefaultController@setStyle');
	Route::get('/settheme', 'DefaultController@settheme');
	Route::get('/page', 'DefaultController@page');
	Route::post('/subscribeNotification/', 'CustomersController@subscribeNotification');
	
	Route::get('/', 'DefaultController@index');
	Route::get('/index', 'DefaultController@index');
	
	Route::get('/contact-us', 'ContactController@contactUs');
	Route::post('/contact', 'ContactController@processContactUs');
	
	Route::get('/blog', 'BlogController@index');
	Route::get('/blog/{blogs_id}', 'BlogController@getDetail');
	//news section
	// Route::get('/news', 'NewsController@news');
	// Route::get('/news-detail/{slug}', 'NewsController@newsDetail');
	// Route::post('/loadMoreNews', 'NewsController@loadMoreNews');	
	
	Route::post('/news/letter','NewsLetterController@addEmail');
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
	//Route::post('/filterProducts', 'ProductsController@moreProducts');

	/*
	|--------------------------------------------------------------------------
	| Cart Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains customer cart products
	| 
	*/

	Route::get('/getCart', 'DataController@getCart');
	Route::post('/add/cart', 'CartController@addToCart');
	Route::post('/updatesinglecart', 'CartController@updatesinglecart');
	Route::get('/cartButton', 'CartController@cartButton');
	
	Route::get('/viewcart', 'CartController@viewcart');
	Route::get('/editcart', 'CartController@editcart');
	
	Route::post('/update/cart', 'CartController@updateCart');
	Route::get('/deleteCart', 'CartController@deleteCart');
	Route::post('/apply_coupon', 'CartController@apply_coupon');
	Route::get('/removeCoupon/{id}', 'CartController@removeCoupon');
	
	
	/*
	|--------------------------------------------------------------------------
	| customer login Controller Routes
	|--------------------------------------------------------------------
	*/
	
	Route::get('/login', 'CustomersController@login');
	Route::post('/customer/login', 'CustomersController@customerLogin');
	Route::get('/logout', 'CustomersController@logout');
	/*
	|--------------------------------------------------------------------------
	| customer registrations Controller Routes
	|--------------------------------------------------------------------------
	|
	| This section contains all Routes of signup page, login page, forgot password 
	| facebook login , google login, shipping address etc.
	|
	*/
	Route::get('/signup', 'CustomersController@signup');
	Route::post('/customer/signup', 'CustomersController@store');

	Route::get('login/{social}', 'CustomersController@socialLogin');
	Route::get('callback/{social}', 'CustomersController@handleSocialLoginCallback');
	Route::post('/order/comments/', 'OrdersController@commentsOrder');
	/*
	|--------------------------------------------------------------------------
	| customer forget password and reset password
	|
	*/
	Route::get('/forgot/password', 'CustomerForgotPasswordController@forgotPwd');
	Route::post('/password/email', 'CustomerForgotPasswordController@passwordEmail');
	Route::get('/password/reset/{token}', 'CustomerForgotPasswordController@passwordResetToken');
	Route::post('update/reset/password', 'CustomerForgotPasswordController@updateResetPassword');
	/*
	|------------------------------------------------------------------- 
	| zones
	|
	*/
	Route::get('/zones', 'ShippingAddressController@ajaxZones');

	//likeMyProduct
	Route::post('likeMyProduct', 'CustomersController@likeMyProduct');
	
	/*
	|--------------------------------------------------------------------
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
		Route::post('/updateprofile', 'CustomersController@updateProfile');
		Route::post('/updatepassword', 'CustomersController@updatePassword');		
		
		Route::get('/shipping/address', 'ShippingAddressController@shippingAddress');
		Route::get('/exiting/address/{address_id}', 'ShippingAddressController@selectExitingAddress');
		Route::post('add/address', 'ShippingAddressController@addAddress');

		Route::post('/default/address', 'ShippingAddressController@myDefaultAddress');		
		
		Route::post('/update/address', 'ShippingAddressController@updateAddress');
		Route::post('/delete/address', 'ShippingAddressController@deleteAddress');
		
		Route::get('/checkout', 'OrdersController@checkout');	
		Route::post('/checkout/shipping/address', 'OrdersController@checkoutShippingAddress');
		Route::post('/checkout/billing/address', 'OrdersController@checkoutBillingAddress');
		Route::post('/checkout/payment/method', 'OrdersController@checkoutPaymentMethod');
		Route::post('/paymentComponent', 'OrdersController@paymentComponent');	
		Route::post('/place_order', 'OrdersController@place_order');	
		Route::get('/orders', 'OrdersController@orders');	
		Route::post('/myorders', 'OrdersController@myorders');	
		Route::get('/stripeForm', 'OrdersController@stripeForm');	
		Route::get('/view/order/{id}', 'OrdersController@viewOrder');
		Route::get('/stripe', 'StripeController@payWithStripe');
		Route::post('/strip/order', 'StripeController@postPaymentWithStripe');
		Route::get('/payment', 'PaymentController@payment')->name('payment');
		Route::post('/new-card-payment', 'PaymentController@newCardPayment')->name('newCardPayment');
		Route::post('/saved-card-payment', 'PaymentController@savedCardPayment')->name('savedCardPayment');

	});
	
});

Route::get('/cache', 'CacheController@index');