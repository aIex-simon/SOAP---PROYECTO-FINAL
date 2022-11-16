<?php

namespace App\Models\Iam\Passport;

use \Laravel\Passport\PersonalAccessClient as PersonalAccessClientBase;

class PersonalAccessClient extends PersonalAccessClientBase
{
    /**
     * @var string
     */
    protected $table = 'iam_oauth_personal_access_clients';
}
