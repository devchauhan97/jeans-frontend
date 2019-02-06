<?php

namespace App\Listeners;

use App\Events\SendProductOrderMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;
use App\AlertSetting;
use App\Setting;
use Mail;
use Lang;
use App\OrdersStatusHistory;
use App\OrdersStatus;
use App\Order;
use App\OrdersProduct;
use App\OrdersProductsAttribute;

class SendProductOrderMailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendProductOrderMail  $event
     * @return void
     */
    public function handle(SendProductOrderMail $event)
    {
        $orders_id= $event->orders_id;

        $alertSetting = AlertSetting::get();

        //send order email to user          
        $order = Order::LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
            ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
            ->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
                
        //foreach
        foreach($order as $data) {

            $orders_id   = $data->orders_id;
            $orders_products =OrdersProduct::join('products', 'products.products_id','=', 'orders_products.products_id')
                ->select('orders_products.*', 'products.products_image as image')
                ->where('orders_products.orders_id', '=', $orders_id)->get();

                $i = 0;
                $total_price  = 0;
                $product = array();
                $subtotal = 0;

                foreach($orders_products as $orders_products_data) {

                    $product_attribute = OrdersProductsAttribute::leftjoin('products_attributes_images',function($q1){
                                        $q1->on('options_values_id','orders_products_attributes.products_options_values_id');
                                    })->where([
                            ['orders_products_id', '=', $orders_products_data->orders_products_id],
                            ['orders_id', '=', $orders_products_data->orders_id],
                        ])
                        ->get();
                        
                    if(count($product_attribute) > 0 &&  isset($product_attribute[0]->products_attributes_image_id) ) {
                         $image = $product_attribute[0]->image;
                    } else {
                        $image = $orders_products_data->image;
                    }
                    
                    $orders_products_data->image = $image; 

                    $orders_products_data->attribute = $product_attribute;
                    $product[$i] = $orders_products_data;
                    //$total_tax     = $total_tax+$orders_products_data->products_tax;
                    $total_price = $total_price+$orders_products[$i]->final_price;                  
                    $subtotal += $orders_products[$i]->final_price;                 
                    $i++;
                }
                
            $data->data = $product;
            $orders_data[] = $data;
        }

        $orders_status_history = OrdersStatusHistory::LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
                ->orderBy('orders_status_history.date_added', 'desc')
                ->where('orders_id', '=', $orders_id)->get();
                    
        $orders_status = OrdersStatus::get();
                
        $ordersData['orders_data']              =   $orders_data;
        $ordersData['total_price']              =   $total_price;
        $ordersData['orders_status']            =   $orders_status;
        $ordersData['orders_status_history']    =   $orders_status_history;
        $ordersData['subtotal']                 =   $subtotal;
        
       //dd($ordersData);
        //setting 
        $setting =  Setting::get();
        $ordersData['app_name'] = $setting[18]->value;

        $ordersData['orders_data'][0]->admin_email = $setting[70]->value;   
        
        if($alertSetting[0]->order_email == 1 ) {
            //admin email
            if(!empty($ordersData['orders_data'][0]->admin_email)) {

                Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
                    $m->to($ordersData['orders_data'][0]->admin_email)->subject($ordersData['app_name'].': An order has been placed')
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true')
                    ;   
                });
            }
            //customer email
            if(!empty($ordersData['orders_data'][0]->email)) {
                Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
                    $m->to($ordersData['orders_data'][0]->email)->subject($ordersData['app_name'].': Your order has been placed')
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true')
                    ;   
                });
            }       
        }
         
    }
}
