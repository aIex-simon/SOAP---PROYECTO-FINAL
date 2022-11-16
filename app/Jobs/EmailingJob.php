<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleNoteMail;

class EmailingJob extends Job
{
    /** @var string  */
    public $queue = 'send-email-sale-note';

    /**
     * @var string
     */
    protected string $customerEmail;

    /**
     * constructor
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @param string $userEmail
     */
    public function __construct(string $customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    /**
     * handle
     *
     * @author Gabriel Capcha, <gcapcha@tumi-soft.com>
     * @return void
     */
    public function handle()
    {
        if (empty($this->customerEmail)
            || !filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)
        ) {
            Log::error('Correo de venta NO Enviado: ' . $this->customerEmail);
            return;
        }

        Mail::to($this->customerEmail)->send(new SaleNoteMail());
        Log::info('Correo de verificación enviado: ' . $this->customerEmail);
    }
}
