<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorException;
use Closure;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class ValidateInvitation
{
    /**
     * handle
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param $request
     * @param Closure $next
     * @param null $relative
     * @return mixed
     * @throws ErrorException
     */
    public function handle($request, Closure $next, $relative = null): mixed
    {
        if (!$this->hasCorrectLinkInvitation($request, $relative !== 'relative')) {
            throw (new ErrorException(__('The invitation is not valid.')))->withStatus(Response::HTTP_FORBIDDEN);
        }

        if (!$this->invitationHasNotExpired($request)) {
            throw (new ErrorException(__('The invitation has expired.')))->withStatus(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * hasCorrectLinkInvitation
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param $request
     * @param bool $absolute
     * @return bool
     */
    private function hasCorrectLinkInvitation($request, bool $absolute): bool
    {
        $url = env('URL_WEB_FRONTEND');
        $keyResolver = env('APP_KEY');
        $queryString = ltrim(
            preg_replace(
                '/(^|&)signature=[^&]+/',
                '',
                $request->server->get('QUERY_STRING')
            ),
            '&'
        );
        $signature = hash_hmac('sha256', rtrim($url . '/invitation?' . $queryString, '?'), $keyResolver);

        return hash_equals($signature, (string) $request->query('signature', ''));
    }

    /**
     * invitationHasNotExpired
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param $request
     * @return bool
     */
    private function invitationHasNotExpired($request): bool
    {
        $expires = (int) $request->query('expires');

        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
