<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class SuccessFullChargeEmail extends Notification
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

        $detail = Lang::get('Congratulations, we are pleased to inform you that the payment for your membership subscription could be made')
                  . ' ' . $data['plan_name'] . ', ' . Lang::get('which begins to be effective from the date') . ' ' . date('d/m/Y', $data['start_date'])
                  . ' ' . Lang::get('with a recurring payment with periodicity') . ' ' . Lang::get($data['periodicity']) . '.';

        $welcome = Lang::get('Dear') . ' ' . $contact['first_name'] . ' ' . $contact['last_name'] . '.';

        /* return (new MailMessage)
            ->subject(Lang::get('TumiSoft successful payment'))
            ->line($welcome)
            ->line($detail)
            ->line(Lang::get('Without further ado, we say goodbye without first showing our gratitude to you and your business.')); */
        
        

        return (new MailMessage)
            ->subject(Lang::get('TumiSoft successful payment'))
            ->view('mails.successFullChargeEmail', ['data' => $this->data]);
    }
}
