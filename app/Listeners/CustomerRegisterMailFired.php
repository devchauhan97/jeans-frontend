<?php

namespace App\Listeners;

use App\Events\CustomerRegisterMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AlertSetting;
use App\Setting;
use Mail;
use Lang;
class CustomerRegisterMailFired
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
    public function handle(CustomerRegisterMail $event)
    {
        $customer = $event->customer;

        $alertSetting = AlertSetting::get();

        $setting = Setting::where(['name'=>'app_name'])->first(['value']); //get app name
        $app_name = $setting->value;
        
        if( $alertSetting[0]->create_customer_email==1 and !empty($customer->email) ) { 

            Mail::send('/mail/createAccount', ['userData' => $customer,'app_name'=>$app_name], function($m) use ($customer,$app_name){

                $m->to($customer->email)->subject(Lang::get("labels.Welcometo").$app_name)
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');   

            });

        }

    }
}
