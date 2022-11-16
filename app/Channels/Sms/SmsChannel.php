<?php

namespace App\Channels\Sms;

use Aws\Credentials\Credentials;
use Aws\Result;
use Aws\Sns\SnsClient;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    /**
     * The SNS client instance.
     *
     * @var SnsClient
     */
    private SnsClient $sns;


    /**
     * constructor
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     */
    public function __construct()
    {
        $this->sns = new SnsClient([
            'version' => '2010-03-31',
            'credentials' => new Credentials(
                config('services.sns.key'),
                config('services.sns.secret'),
                config('services.sns.token')
            ),
            'region' => config('services.sns.region'),
        ]);
    }

    /**
     * Send the given notification.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return Result|void
     */
    public function send(mixed $notifiable, Notification $notification)
    {
        if (!($to = $notifiable->routeNotificationFor('sms', $notification))) {
            Log::error(__FUNCTION__ . ', routeNotificationFor');

            return;
        }

        if (!($result = $this->sns->checkIfPhoneNumberIsOptedOut(['phoneNumber' => $to]))) {
            Log::error(__FUNCTION__ . ', checkIfPhoneNumberIsOptedOut: ' . $to);

            return;
        }

        if ($result['isOptedOut']) {
            Log::error(__FUNCTION__ . ', routeNotificationFor: ' . print_r([$result], true));

            return;
        }

        $message = $notification->toSms($notifiable);

        if (is_string($message)) {
            $message = new SmsMessage($message);
        }

        return $this->sns->publish([
            'Message' => $message->content,
            'PhoneNumber' => $to,
            'MessageAttributes' => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'TumiSoft',
                ]
            ]
        ]);
    }
}
