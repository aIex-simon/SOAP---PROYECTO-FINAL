<?php

namespace App\Channels\Sms;

class SmsMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public string $content;

    /**
     * Create a new message instance.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = trim($content);

        return $this;
    }
}
