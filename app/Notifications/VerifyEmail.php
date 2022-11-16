<?php

namespace App\Notifications;

use DateInterval;
use DateTimeInterface;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends VerifyEmailBase
{
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  string  $url
     * @return MailMessage
     */
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable): string
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable);
        }

        return $this->temporarySignedRoute(
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60))
        );
    }

    /**
     * temporary signed route
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $parameters
     * @param Carbon $expiration
     * @return string
     */
    private function temporarySignedRoute(array $parameters, Carbon $expiration): string
    {
        $key = env('APP_KEY');
        $parameters += ['expires' => $this->availableAt($expiration)];
        ksort($parameters);

        $urlVerification = env('URL_WEB_FRONTEND') . '/auth?' . http_build_query($parameters);

        return env('URL_WEB_FRONTEND') . '/auth?' . http_build_query(
            $parameters + ['signature' => hash_hmac('sha256', $urlVerification, $key)]
        );
    }

    /**
     * Get the "available at" UNIX timestamp.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @return int
     */
    protected function availableAt($delay = 0): int
    {
        $delay = $this->parseDateInterval($delay);

        return $delay instanceof DateTimeInterface
            ? $delay->getTimestamp()
            : Carbon::now()->addRealSeconds($delay)->getTimestamp();
    }

    /**
     * If the given value is an interval, convert it to a DateTime instance.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @return \DateTimeInterface|int
     */
    protected function parseDateInterval($delay)
    {
        if ($delay instanceof DateInterval) {
            $delay = Carbon::now()->add($delay);
        }

        return $delay;
    }
}
