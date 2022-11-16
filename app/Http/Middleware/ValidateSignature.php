<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignature
{
    /**
     * handle
     *
     * @author Marco Torres, <mtorres
     * @param Request $request
     * @param Closure $next
     * @param null $relative
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $relative = null)
    {
        if (!$this->hasCorrectSignature($request, $relative !== 'relative')) {
            return new JsonResponse(
                [
                    'apiVersion' => config('bm.api_version'),
                    'context' => strtolower($request->getRealMethod()) . $request->getRequestUri(),
                    'error' => [
                        'code' => Response::HTTP_FORBIDDEN,
                        'message' => __('The link is not valid.'),
                    ]
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        if (!$this->signatureHasNotExpired($request)) {
            return new JsonResponse(
                [
                    'apiVersion' => config('bm.api_version'),
                    'context' => strtolower($request->getRealMethod()) . $request->getRequestUri(),
                    'error' => [
                        'code' => Response::HTTP_FORBIDDEN,
                        'message' => __('The link has expired.'),
                    ]
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }

    /**
     * has correct signature
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param bool $absolute
     *
     * @return bool
     */
    private function hasCorrectSignature(Request $request, bool $absolute)
    {
        $url = config('iam.url_web_frontend');
        $keyResolver = config('iam.app_key');
        $queryString = ltrim(
            preg_replace(
                '/(^|&)signature=[^&]+/',
                '',
                $request->server->get('QUERY_STRING')
            ),
            '&'
        );
        $signature = hash_hmac('sha256', rtrim($url . '/auth?' . $queryString, '?'), $keyResolver);
        return hash_equals($signature, (string) $request->query('signature', ''));
    }

    /**
     * signature has not expired
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     *
     * @return bool
     */
    private function signatureHasNotExpired(Request $request): bool
    {
        $expires = (int) $request->query('expires');
        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
