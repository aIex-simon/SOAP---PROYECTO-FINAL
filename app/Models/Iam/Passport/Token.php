<?php

namespace App\Models\Iam\Passport;

use \Laravel\Passport\Token as TokenBase;

class Token extends TokenBase
{
    /**
     * @var string
     */
    protected $table = 'iam_oauth_access_tokens';
}
