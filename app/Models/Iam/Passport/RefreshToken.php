<?php

namespace App\Models\Iam\Passport;

use \Laravel\Passport\RefreshToken as RefreshTokenBase;

class RefreshToken extends RefreshTokenBase
{
    /**
     * @var string
     */
    protected $table = 'iam_oauth_refresh_tokens';
}
