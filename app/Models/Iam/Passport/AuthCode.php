<?php

namespace App\Models\Iam\Passport;

use Laravel\Passport\AuthCode as AuthCodeAliasBase;

class AuthCode extends AuthCodeAliasBase
{
    /**
     * @var string
     */
    protected $table = 'iam_oauth_auth_code';
}
