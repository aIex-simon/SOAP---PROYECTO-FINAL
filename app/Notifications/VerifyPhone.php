<?php

namespace App\Notifications;

use App\Channels\Sms\SmsChannel;
use App\Channels\Sms\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VerifyPhone extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $message;

    /**
     * constructor
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        $this->message = 'TumiSoft: Tú código de verificación es el siguiente: ' . $this->code;
    }

    /**
     * Get the notification's channels.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param mixed $notifiable
     *
     * @return SmsMessage
     */
    public function toSms($notifiable)
    {
        return new SmsMessage($this->message);
    }
}
