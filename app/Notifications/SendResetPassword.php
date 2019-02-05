<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Setting;
use Lang;
class SendResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $url = url('/password/reset/'.$notifiable->token);
        $setting = Setting::where(['name'=>'app_name'])->first(['value']); //get app name
        $app_name = $setting->value;
        return (new MailMessage)
                ->subject($app_name.Lang::get("labels.fogotPasswordEmailTitle"))
                ->markdown('mail.recoverPassword',['user'=> $this->users,'url' => $url,'app_name' => $setting->value]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
