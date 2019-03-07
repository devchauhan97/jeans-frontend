<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
 
use Carbon;
use Session;
use Lang;
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
use App\Libraries\moneris\mpgTransaction;
use App\Libraries\moneris\mpgRequest;
use App\Libraries\moneris\mpgHttpsPost;
use App\Libraries\moneris\mpgCvdInfo;
use App\Libraries\moneris\mpgAvsInfo;


class PaymentController extends DataController
{
    public function payment(Request $request)
	{
		$title = array('pageTitle' => Lang::get('website.Payment'));
		$result = array();	
		$myVar = new CartController();
		$result['cart'] = $myVar->myCart($result);
		$result['commonContent'] = $this->commonContent();

		//$ord_details = $this->getOrderDetails($request->order_id);
		
		$result['orders_data']	 = @$ord_details['orders_data'][0];
		//$data1['order_price']	 = $ord_details['orders_data'][0]->order_price;
		
		$result['data_key'] = DB::table('data_key')
								->where([
									['CustID', '=', Auth::guard('customer')->user()->customers_id],
									['dataKey', '!=', '0'],
									['status', '=', 'Active']
								])->get();

		$request->session()->put('payment', 1);

		$result['p_sess'] = $request->session()->get('payment_' . 13);

		//return $data;

		return view('payment', $title)->with('result', $result);
		//return view('web.cart.payment', $data1);
	}
	
	private function getOrderDetails($order_id) {
		//send order email to user			
		$order = DB::table('orders')
			->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
			->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
			->where('orders.orders_id', '=', $order_id)->orderby('orders_status_history.date_added', 'DESC')->get();
			
		//foreach
		foreach($order as $data){
			$orders_id	 = $data->orders_id;
			$orders_products = DB::table('orders_products')
								->join('products', 'products.products_id','=', 'orders_products.products_id')
								->select('orders_products.*', 'products.products_image as image')
								->where('orders_products.orders_id', '=', $orders_id)->get();

			$i = 0;
			$total_price  = 0;
			$product = array();
			$subtotal = 0;
			foreach($orders_products as $orders_products_data){
				$product_attribute = DB::table('orders_products_attributes')
					->where([
						['orders_products_id', '=', $orders_products_data->orders_products_id],
						['orders_id', '=', $orders_products_data->orders_id],
					])
					->get();
					
				$orders_products_data->attribute = $product_attribute;
				$product[$i] = $orders_products_data;
				//$total_tax	 = $total_tax+$orders_products_data->products_tax;
				$total_price = $total_price+$orders_products[$i]->final_price;
				
				$subtotal += $orders_products[$i]->final_price;
				
				$i++;
			}
				
			$data->data = $product;
			$orders_data[] = $data;
		}
		
		return $ordersData;
	}

	public function newCardPayment(Request $request) {

		//$ord_details = $this->getOrderDetails($request->order_id);
		
		//$ord_det = $ord_details['orders_data'][0];
		$user = Auth::guard('customer')->user();
		$cust_id = $user->customers_id;

		// if($ord_det->customers_id != $cust_id) {
		// 	return redirect('/');
		// }

		if($request->session()->get('payment') == 1 ) {
			// Include the moneris library
			require app_path('Libraries/moneris/mpgClasses.php');

			$store_id = env('MONERIS_STORE_ID');
			$api_token = env('MONERIS_API_TOKEN');
			$monerisGatewayMode = env('MONERIS_GATEWAY_MODE');

			if(!empty($request->cardAccptance) && $request->cardAccptance =='Yes'){
				$cardAccptance = $request->cardAccptance;
			}
			else{
				$cardAccptance = 'No';
			}

			$pan = $request->pan;
			$expiry_date = $request->expiry_date;
			$avs_street_number = $request->avs_street_number;
			$avs_street_name = $request->avs_street_name;
			$avs_zipcode = $request->avs_zipcode;
			$phone = $user->customers_telephone;
			$email = $user->customers_email_address;
			$note = '';

			if(!empty($cardAccptance) && $cardAccptance=='Yes')
			{
				$type='res_add_cc';
				$crypt_type='1';	

				// Transactional Associative Array

				$txnArray=array('type' => $type,
					'cust_id' => $cust_id,
					'phone' => $phone,
					'email' => $email,
					'note' => $note,
					'pan' => $pan,
					'expdate' => $expiry_date,
					'crypt_type' => $crypt_type
				);

				
				// AVS Associative Array

				$avsTemplate = array(
							'avs_street_number' => $avs_street_number,
							'avs_street_name' => $avs_street_name,
							'avs_zipcode' => $avs_zipcode
						);

				// AVS Object

				$mpgAvsInfo = new mpgAvsInfo($avsTemplate);
				// Transaction Object

				$mpgTxn = new mpgTransaction($txnArray);				
				$mpgTxn->setAvsInfo($mpgAvsInfo);
				// Request Object

				$mpgRequest = new mpgRequest($mpgTxn);
				$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
				$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

				// HTTPS Post Object

				$mpgHttpPost  = new mpgHttpsPost($store_id, $api_token, $mpgRequest);

				// Response

				$mpgResponse = $mpgHttpPost->getMpgResponse();	

				//print_r($mpgResponse);exit;
				if($mpgResponse->getResSuccess() == true){

					$data_key	= $mpgResponse->getDataKey();
					$orderid 	= $ord_det->unique_order_id;
					$amount 	= $ord_det->order_price;
					$custid 	= $cust_id;
					$crypt_type = '1';
					$expdate 	= $request->expiry_date;

					$txnArray=array(
						'type' 		=>'res_purchase_cc',
						'data_key'	=> $data_key,
				        'order_id'	=> $orderid,
				        'cust_id'	=> $custid,
				        'amount'	=> $amount,
				        'crypt_type'=> $crypt_type,
						'expdate'	=> $expdate,
						'dynamic_descriptor'=>'12484'
					   );
					   
					   // CVD Variables

					$cvd_indicator = '1';
					$cvd_value = $request->cvd_value;

					// CVD Associative Array

					$cvdTemplate = array(
							'cvd_indicator' => $cvd_indicator,
							'cvd_value' => $cvd_value
					);

					$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);

					$mpgTxn = new mpgTransaction($txnArray);
					$mpgTxn->setCvdInfo($mpgCvdInfo);
					
					$mpgRequest = new mpgRequest($mpgTxn);
					$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
					$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

					$mpgHttpPost  = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

					$mpgResponse = '';
					$mpgResponse = $mpgHttpPost->getMpgResponse();

					$addCardData = array(
							'cardAccptance' => $cardAccptance,
							'dataKey' 		=> $mpgResponse->getDataKey(),
							'ReceiptId' 	=> $mpgResponse->getReceiptId(),
							'ReferenceNum' 	=> $mpgResponse->getReferenceNum(),
							'ResponseCode' 	=> $mpgResponse->getResponseCode(),
							'ISO' 			=> $mpgResponse->getISO(),
							'AuthCode' 		=> $mpgResponse->getAuthCode(),
							'Message' 		=> $mpgResponse->getMessage(),
							'TransDate' 	=> $mpgResponse->getTransDate(),
							'TransTime' 	=> $mpgResponse->getTransTime(),
							'TransType' 	=> $mpgResponse->getTransType(),
							'Complete' 		=> $mpgResponse->getComplete(),
							'TransAmount' 	=> $mpgResponse->getTransAmount(),
							'CardType' 		=> $mpgResponse->getCardType(),
							'TxnNumber' 	=> $mpgResponse->getTxnNumber(),
							'TimedOut' 		=> $mpgResponse->getTimedOut(),
							'AVSResponse' 	=> $mpgResponse->getAvsResultCode(),
							'ResSuccess' 	=> $mpgResponse->getResSuccess(),
							'PaymentType' 	=> $mpgResponse->getPaymentType(),
							'CustID' 		=> $mpgResponse->getResDataCustId(),
							'Phone' 		=> $mpgResponse->getResDataPhone(),
							'Email' 		=> $mpgResponse->getResDataEmail(),
							'Note' 			=> $mpgResponse->getResDataNote(),
							'MaskedPan' 	=> $mpgResponse->getResDataMaskedPan(),
							'ExpDate' 		=> $mpgResponse->getResDataExpDate(),
							'CryptType' 	=> $mpgResponse->getResDataCryptType(),
							'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
							'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
							'AvsZipcode' 	=> $mpgResponse->getResDataAvsZipcode(),
							'CvdResultCode' => $mpgResponse->getCvdResultCode(),
							'IsVisaDebit' 	=> '',
							'Ticket' 		=> '',
							'StatusCode' 	=> '',
							'StatusMessage' => '',
							'payment_method' => 'Vault',
						);

					//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

					if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {
						$data_key_row_count = DB::table('data_key')
												->where('MaskedPan', $addCardData['MaskedPan'])
												->where('CustID', $cust_id)->count();
						if(!$data_key_row_count){
							DB::table('data_key')->insert($addCardData);
						}
						//$this->paymentSuccessUpdate($addCardData);
						$request->session()->put('payment_status', 1);
						//return redirect()->route('paymentSuccessVault', [base64_encode($addCardData['ReceiptId'])]);
					}
					else{
						$addCardData['unique_order_id'] = $ord_det->unique_order_id;
						//$this->paymentFailedUpdate($addCardData);
						$request->session()->put('payment_status', 0);
						//return redirect()->route('paymentFailureVault', [base64_encode($ord_det->unique_order_id)]);
					}
				}
				else{
					$addCardData['unique_order_id'] = $ord_det->unique_order_id;
					$request->session()->put('payment_status', 0);
					//$this->paymentFailedUpdate($addCardData);
					//return redirect()->route('paymentFailureVault', [base64_encode($ord_det->unique_order_id)]);
				}

			} else { 

				$type = 'purchase';
				$order_id = $ord_det->unique_order_id;
				$amount=$ord_det->order_price;
				$crypt='7';
				$dynamic_descriptor='123';
				$status_check = 'false';

				// Transactional Associative Array

				$txnArray=array('type'=>$type,
				     		    'order_id'=>$order_id,
				     		    'cust_id'=>$cust_id,
				    		    'amount'=>$amount,
				   			    'pan'=>$pan,
				   			    'expdate'=>$expiry_date,
				   			    'crypt_type'=>$crypt,
				   			    'dynamic_descriptor'=>$dynamic_descriptor
				   		       );
				
				// CVD Variables

				$cvd_indicator = '1';
				$cvd_value = $request->cvd_value;

				// CVD Associative Array

				$cvdTemplate = array(
									'cvd_indicator' => $cvd_indicator,
									'cvd_value' => $cvd_value
								);

				$mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
				// AVS Associative Array

				$avsTemplate = array(
									'avs_street_number' => $avs_street_number,
									'avs_street_name' 	=> $avs_street_name,
									'avs_zipcode' 		=> $avs_zipcode
								);

				// AVS Object

				$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);
				// Transaction Object

				$mpgTxn = new mpgTransaction($txnArray);
				$mpgTxn->setCvdInfo($mpgCvdInfo);
				$mpgTxn->setAvsInfo($mpgAvsInfo);

				// Request Object

				$mpgRequest = new mpgRequest($mpgTxn);
				$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
				$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

				// HTTPS Post Object

				$mpgHttpPost  = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

				// Response

				$mpgResponse = $mpgHttpPost->getMpgResponse();

				$addCardData = array(
						'cardAccptance' => $cardAccptance,
						'dataKey' => '0',
						'ReceiptId' => $mpgResponse->getReceiptId(),
						'ReferenceNum' => $mpgResponse->getReferenceNum(),
						'ResponseCode' => $mpgResponse->getResponseCode(),
						'ISO' => $mpgResponse->getISO(),
						'AuthCode' => $mpgResponse->getAuthCode(),
						'Message' => $mpgResponse->getMessage(),
						'TransDate' => $mpgResponse->getTransDate(),
						'TransTime' => $mpgResponse->getTransTime(),
						'TransType' => $mpgResponse->getTransType(),
						'Complete' => $mpgResponse->getComplete(),
						'TransAmount' => $mpgResponse->getTransAmount(),
						'CardType' => $mpgResponse->getCardType(),
						'TxnNumber' => $mpgResponse->getTxnNumber(),
						'TimedOut' => $mpgResponse->getTimedOut(),
						'AVSResponse' => $mpgResponse->getAvsResultCode(),
						'ResSuccess' => '',
						'PaymentType' => '',
						'CustID' => '',
						'Phone' => '',
						'Email' => '',
						'Note' => '',
						'MaskedPan' => $mpgResponse->getResDataMaskedPan(),
						'ExpDate' => $mpgResponse->getResDataExpDate(),
						'CryptType' => $mpgResponse->getResDataCryptType(),
						'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
						'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
						'AvsZipcode' => $mpgResponse->getResDataAvsZipcode(),
						'CvdResultCode' => $mpgResponse->getCvdResultCode(),
						'IsVisaDebit' => $mpgResponse->getIsVisaDebit(),
						'Ticket' => $mpgResponse->getTicket(),
						'StatusCode' => $mpgResponse->getStatusCode(),
						'StatusMessage' => $mpgResponse->getStatusMessage(),
						'payment_method' => 'Credit Card',
					);	

				//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

				if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {
					$this->paymentSuccessUpdate($addCardData);
					$request->session()->put('payment_status', 1);
					//return redirect()->route('paymentSuccessVault', [base64_encode($addCardData['ReceiptId'])]);
				}
				else{
					$addCardData['unique_order_id'] = $ord_det->unique_order_id;
					$this->paymentFailedUpdate($addCardData);
					$request->session()->put('payment_status', 0);
					//return redirect()->route('paymentFailureVault', [base64_encode($ord_det->unique_order_id)]);
				}
			}
			$request->session()->forget('payment');
			$request->session()->forget('payment_' . $request->order_id);
		}

		$data['unique_order_id'] = $ord_det->unique_order_id;
		if($request->session()->get('payment_status') == 1){
			return view('payment-success', $data);
		}
		else{
			return view('payment-failure', $data);
		}
	}

	public function paymentSuccessVault($id){
		$data['unique_order_id'] = base64_decode($id);
		return view('web.order.payment-success', $data);
	}

	public function paymentFailureVault($id){
		$data['unique_order_id'] = base64_decode($id);
		return view('web.order.payment-failure', $data);
	}

	public function paymentSuccessUpdate($req) { 

		$data['unique_order_id'] = $req['ReceiptId'];
		$data['date_stamp'] = $req['TransDate'];
		$data['time_stamp'] = $req['TransTime'];		
		$data['bank_transaction_id'] = $req['ReferenceNum'];
		$data['TxnNumber'] = $req['TxnNumber'];
		$data['charge_total'] = $req['TransAmount'];
		$data['bank_approval_code'] = $req['AuthCode'];
		$data['response_code'] = $req['ResponseCode'];
		$data['iso_code'] = $req['ISO'];
		$data['message'] = $req['Message'];
		$data['trans_name'] = 'purchase';
		//$data['cardholder'] = $req['cardholder'];
		$data['card_num'] = $req['MaskedPan'];
		$data['card'] = $req['CardType'];
		$data['expiry_date'] = $req['ExpDate'];
		//$data['result'] = $req['result'];
		$data['payment_status'] = 'Success';
		$data['payment_method'] = $req['payment_method'];
		$data['timestamp'] = date('H:i:s m/d/y');

		$order_row = DB::table('orders')
							->where('unique_order_id', $req['ReceiptId'])
							->first();

		if($req['ResponseCode'] < 50) {

			DB::table('orders')
				->where('unique_order_id', $req['ReceiptId'])
				->update(['payment_status' => 'Success']);

			 
                     
		}		
		
	}

	public function paymentFailedUpdate($req){
		$data['unique_order_id'] = $req['unique_order_id'];
		$data['date_stamp'] = $req['TransDate'];
		$data['time_stamp'] = $req['TransTime'];
		$data['bank_transaction_id'] = $req['ReferenceNum'];
		$data['TxnNumber'] = $req['TxnNumber'];
		$data['charge_total'] = $req['TransAmount'];
		$data['bank_approval_code'] = $req['AuthCode'];
		$data['response_code'] = $req['ResponseCode'];
		$data['iso_code'] = $req['ISO'];
		$data['message'] = $req['Message'];
		$data['trans_name'] = 'purchase';
		//$data['cardholder'] = $req['cardholder'];
		$data['card_num'] = $req['MaskedPan'];
		$data['card'] = $req['CardType'];
		$data['expiry_date'] = $req['ExpDate'];
		//$data['result'] = $req['result'];
		$data['payment_status'] = 'Failed';
		$data['payment_method'] = $req['payment_method'];
		$data['timestamp'] = date('H:i:s m/d/y');
		$getOrderData = DB::table('payment_history')
						->where([['unique_order_id', '=', $data['unique_order_id']]])
						->first();
		if(!count($getOrderData)){
			$orders_id = DB::table('payment_history')->insert($data);
		}
	
		//return view('web.order.payment-failure', $data);
	}

	public function savedCardPayment(Request $request)
	{
		$ord_details = $this->getOrderDetails($request->order_id);
		
		$ord_det = $ord_details['orders_data'][0];

		$custid = Auth::guard('customer')->user()->customers_id;

		$data_key_row = DB::table('data_key')
						->where([
							['CustID', '=', $custid],
							['dataKey', '!=', '0'],
							['status', '=', 'Active'],
							['id', '=', $request->card_id]
						])->first();

		if(!count($data_key_row) || $ord_det->customers_id != $custid){
			return redirect('/');
		}

		if($request->session()->get('payment') == 1){
			// Include the moneris library
			require app_path('Libraries/moneris/mpgClasses.php');

			$store_id = env('MONERIS_STORE_ID');
			$api_token = env('MONERIS_API_TOKEN');
			$monerisGatewayMode = env('MONERIS_GATEWAY_MODE');
			$data_key	= $data_key_row->dataKey;
			$orderid 	= $ord_det->unique_order_id;
			$amount 	= $ord_det->order_price;
			$expdate 	= $data_key_row->ExpDate;
			$crypt_type = '1';

			$txnArray = array(
							'type' 		=>'res_purchase_cc',
							'data_key'	=> $data_key,
						    'order_id'	=> $orderid,
						    'cust_id'	=> $custid,
						    'amount'	=> $amount,
						    'crypt_type'=> $crypt_type,
							'expdate'	=> $expdate,
							'dynamic_descriptor'=>'12484'
					    );

			$mpgTxn = new mpgTransaction($txnArray);		

			$mpgRequest = new mpgRequest($mpgTxn);
			$mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
			$mpgRequest->setTestMode($monerisGatewayMode); //false or comment out this line for production transactions

			$mpgHttpPost  = new mpgHttpsPost($store_id, $api_token, $mpgRequest);

			$mpgResponse = $mpgHttpPost->getMpgResponse();

			//print_r($mpgResponse);exit;

			$addCardData = array(
								'cardAccptance' => 'No',
								//'dataKey' 		=> $mpgResponse->getDataKey(),
								'dataKey' 		=> '0',
								'ReceiptId' 	=> $mpgResponse->getReceiptId(),
								'ReferenceNum' 	=> $mpgResponse->getReferenceNum(),
								'ResponseCode' 	=> $mpgResponse->getResponseCode(),
								'ISO' 			=> $mpgResponse->getISO(),
								'AuthCode' 		=> $mpgResponse->getAuthCode(),
								'Message' 		=> $mpgResponse->getMessage(),
								'TransDate' 	=> $mpgResponse->getTransDate(),
								'TransTime' 	=> $mpgResponse->getTransTime(),
								'TransType' 	=> $mpgResponse->getTransType(),
								'Complete' 		=> $mpgResponse->getComplete(),
								'TransAmount' 	=> $mpgResponse->getTransAmount(),
								'CardType' 		=> $mpgResponse->getCardType(),
								'TxnNumber' 	=> $mpgResponse->getTxnNumber(),
								'TimedOut' 		=> $mpgResponse->getTimedOut(),
								'AVSResponse' 	=> $mpgResponse->getAvsResultCode(),
								'ResSuccess' 	=> $mpgResponse->getResSuccess(),
								'PaymentType' 	=> $mpgResponse->getPaymentType(),
								'CustID' 		=> $mpgResponse->getResDataCustId(),
								'Phone' 		=> $mpgResponse->getResDataPhone(),
								'Email' 		=> $mpgResponse->getResDataEmail(),
								'Note' 			=> $mpgResponse->getResDataNote(),
								'MaskedPan' 	=> $mpgResponse->getResDataMaskedPan(),
								'ExpDate' 		=> $mpgResponse->getResDataExpDate(),
								'CryptType' 	=> $mpgResponse->getResDataCryptType(),
								'AvsStreetNumber' => $mpgResponse->getResDataAvsStreetNumber(),
								'AvsStreetName' => $mpgResponse->getResDataAvsStreetName(),
								'AvsZipcode' 	=> $mpgResponse->getResDataAvsZipcode(),
								'CvdResultCode' => $mpgResponse->getCvdResultCode(),
								'IsVisaDebit' 	=> '',
								'Ticket' 		=> '',
								'StatusCode' 	=> '',
								'StatusMessage' => '',
								'payment_method' => 'Vault',
							);

			//CustomHelper::generateLog($ord_det->unique_order_id . '=>' . json_encode($addCardData));

			if ($mpgResponse->getResponseCode() < 50 && $mpgResponse->getResponseCode() != 'null') {
				$this->paymentSuccessUpdate($addCardData);
				$request->session()->put('payment_status', 1);
				//return redirect()->route('paymentSuccessVault', [base64_encode($addCardData['ReceiptId'])]);
			}
			else{
				$addCardData['unique_order_id'] = $ord_det->unique_order_id;
				$this->paymentFailedUpdate($addCardData);
				$request->session()->put('payment_status', 0);
				//return redirect()->route('paymentFailureVault', [base64_encode($ord_det->unique_order_id)]);
			}

			$request->session()->forget('payment');
			$request->session()->forget('payment_' . $request->order_id);
		}

		$data['unique_order_id'] = $ord_det->unique_order_id;
		if($request->session()->get('payment_status') == 1){
			return view('web.order.payment-success', $data);
		}
		else{
			return view('web.order.payment-failure', $data);
		}
	}

	 
}
