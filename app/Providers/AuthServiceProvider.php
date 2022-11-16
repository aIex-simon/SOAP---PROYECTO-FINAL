<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Iam\Passport\AuthCode;
use App\Models\Iam\Passport\Client;
use App\Models\Iam\Passport\PersonalAccessClient;
use App\Models\Iam\Passport\RefreshToken;
use App\Models\Iam\Passport\Token;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return void
     */
    public function boot()
    {
        //$this->registerPolicies();

        Passport::routes();
        Passport::enableImplicitGrant();
        Passport::loadKeysFrom(storage_path('oauth'));
        Passport::personalAccessTokensExpireIn(Date::now()->addDays(365));

        Passport::useTokenModel(Token::class);
        Passport::useClientModel(Client::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);
        Passport::useRefreshTokenModel(RefreshToken::class);

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('iam.url_web_frontend'). '/auth?'  . http_build_query([
                    'token' => $token,
                    'email' => $user->email,
                ]);
        });
    }
}
