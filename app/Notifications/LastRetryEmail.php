<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class LastRetryEmail extends Notification
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
     * Get last retry email notification mail message for the given URL.
     *
     * @return MailMessage
     */
    protected function buildMailMessage(): MailMessage
    {
        $data = $this->data;
        $contact = $data['contact_details'];

        $detail = Lang::get('We are having problems making the recurring payment of your membership')
            . ' ' . $data['plan_name'] . ', ' . Lang::get('which begins to be effective from the date') . ' ' . date('d/m/Y', $data['start_date'])
            . ' ' . Lang::get('with a recurring payment with periodicity') . ' ' . Lang::get($data['periodicity']) . '.';

        $greeting = Lang::get('Dear') . ' ' . $contact['first_name'] . ' ' . $contact['last_name'] . '.';

        return (new MailMessage)
            ->subject(Lang::get('TumiSoft last retry charge'))
            ->line($greeting)
            ->line($detail)
            ->line(Lang::get('Reason why we have moved you to freemium membership.'));
    }
}
