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
        //
       // $user = Customer::where(['customers_id'=>$event->userId])->first();

        $alertSetting = AlertSetting::get();
        $ordersData=$event->ordersData;
       //dd($ordersData);
        //setting 
        $setting =  Setting::get();
        $ordersData['app_name'] = $setting[18]->value;  
        $ordersData['orders_data'][0]->admin_email = $setting[70]->value;   
        
        if($alertSetting[0]->order_email==1){
            
            //admin email
            if(!empty($ordersData['orders_data'][0]->admin_email)){
                Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
                    $m->to($ordersData['orders_data'][0]->admin_email)->subject('Ecommerce App: An order has been placed')
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true')
                    ;   
                });
            }
            
            //customer email
            if(!empty($ordersData['orders_data'][0]->email)){
                Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
                    $m->to($ordersData['orders_data'][0]->email)->subject('Ecommerce App: Your order has been placed')
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true')
                    ;   
                });
            }       
        }
        
        if($alertSetting[0]->order_notification==1){    
                
            /*$title = Lang::get("labels.OrderTitle");
            $message = Lang::get("labels.OrderDetail").$setting[18]->value;
            
            $sendData = array
                  (
                    'body'  => $message,
                    'title' => $title ,
                            'icon'  => 'myicon', 
                            'sound' => 'mySound', 
                            'image' => '',
                  );
                  
            if($setting[54]->value=='fcm'){
                $functionName = 'fcmNotification';  
            }elseif($setting[54]->value=='onesignal'){
                $functionName = 'onesignalNotification';
            }*/
            
            //get device id
            //$device_id = $this->userDevice($ordersData['orders_data'][0]->customers_id);    
            
            // if(!empty($device_id)){ 
            //     $response = $this->$functionName($device_id, $sendData);    
            // }
        }
    }
}
