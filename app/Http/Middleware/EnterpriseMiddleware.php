<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorException;
use App\Models\Iam\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnterpriseMiddleware
{
    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ErrorException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User $user */
        $user = $request->user();
        $account = $user->account()->with(['enterprises'])->first()->toArray();
        $enterprises = $account['enterprises'] ?? [];
        $enterprises = array_column($enterprises, 'id');

        if (empty($enterprises)
            || !in_array((int) $request->route('enterprise'), $enterprises)
        ) {
            throw (new ErrorException('The enterprise does not belong to the user.'))->withStatus(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
