<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;

class DeclinedChargeEmail extends Notification
{
    protected $data = [];

    public function __construct($data)
    {
        $this->data = $data;
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
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return $this->buildMailMessage();
    }

    /**
     * Get successfully email notification mail message for the given URL.
     *
     * @return MailMessage
     */
    protected function buildMailMessage()
    {
        $data = $this->data;
        $contact = $data['contact_details'];
        $url = Config::get('iam.url_web_frontend', 'https://tumisoft.cloud');
        $urlVerification = $url . '/auth';

        $detail = Lang::get('We have not been able to process your payment, this payment is necessary to be able to change you to the membership')
            . ' ' . $data['plan_name'] . ', ' . Lang::get('which begins to be effective from the date') . ' ' . date('d/m/Y', $data['start_date'])
            . ' ' . Lang::get('with a recurring payment with periodicity') . ' ' . Lang::get($data['periodicity']) . '.';

        $greeting = Lang::get('Dear') . ' ' . $contact['first_name'] . ' ' . $contact['last_name'] . '.';

        return (new MailMessage)
            ->subject(Lang::get('TumiSoft declined charge'))
            ->greeting(Lang::get('Failed payment!'))
            ->line(Lang::get('There was a problem processing your payment. Contact your bank or try another payment method.'))
            ->action(Lang::get('Update payment method'), $urlVerification)
            ->salutation(Lang::get('Regards.'));
    }
}
