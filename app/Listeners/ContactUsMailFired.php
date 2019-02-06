<?php

namespace App\Listeners;

use App\Events\ContactUsMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Setting;
use Lang;
use Mail;
class ContactUsMailFired
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
     * @param  ContactUsMail  $event
     * @return void
     */
    public function handle(ContactUsMail $event)
    {
        $data = $event->data;

        $setting= Setting::get();
        $app_name = $setting[18]->value;    
        $admin_email = $setting[3]->value;

        Mail::send('/mail/contactUs', ['data' => $data,'app_name'=>$app_name], function($m) use ($data,$app_name,$admin_email){

            $m->to($admin_email)->subject($app_name.Lang::get("website.contact us title"))
                ->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');  

        });
    }
}