<?php

namespace App\Models\Iam\Passport;

use \Laravel\Passport\Client as ClientBase;

class Client extends ClientBase
{
    /**
     * @var string
     */
    protected $table = 'iam_oauth_clients';
}
