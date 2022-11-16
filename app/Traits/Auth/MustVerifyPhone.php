<?php

namespace App\Traits\Auth;

use App\Notifications\VerifyPhone;
use Illuminate\Notifications\Notification;

trait MustVerifyPhone
{
    /**
     * Return the SMS notification routing information.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Notification|null $notication
     * @return mixed
     */
    public function routeNotificationForSms(?Notification $notication = null)
    {
        return $this->getAttribute('phone');
    }

    /**
     * Send the phone verification notification.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return string
     */
    public function sendPhoneVerificationNotification(): string
    {
        $notification  = new VerifyPhone();
        $this->notify($notification);

        return $notification->code;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return (isset($this->phone_verified_at) && !is_null($this->phone_verified_at));
    }
}
