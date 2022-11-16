<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class FailedChargeEmail extends Notification
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
    protected function buildMailMessage(): MailMessage
    {
        $data = $this->data;
        $contact = $data['contact_details'];

        $welcome = $contact['first_name'] . ' ' . $contact['last_name'] . Lang::get(' we are having trouble processing your payment. We ask that you review the payment details and check if there are funds in the associated account');

        return (new MailMessage)
            ->subject(Lang::get('TumiSoft failed payment'))
            ->greeting(Lang::get("We couldn't process your last payment!"))
            ->line($welcome)
            ->salutation(Lang::get('Regards.'));
    }
}
