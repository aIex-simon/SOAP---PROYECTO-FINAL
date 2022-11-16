<?php

namespace App\Http\Middleware;

use App\Models\Iam\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  Request  $request
     * @param Closure $next
     * @param string|null $redirectToRoute
     *
     * @return Response|JsonResponse|RedirectResponse|null
     */
    public function handle($request, Closure $next, string $redirectToRoute = null)
    {
        /** @var User $user */
        $user = $request->user();
        if (!Auth::check() ||
            ($user instanceof MustVerifyEmail &&
            ! $user->hasVerifiedEmail())) {

            return new JsonResponse(
                [
                    'apiVersion' => config('bm.api_version'),
                    'context' => strtolower($request->getRealMethod()) . ': ' . $request->getRequestUri(),
                    'error' => [
                        'message' => __('Your email address is not verified.'),
                    ]
                ],
                ResponseCode::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
