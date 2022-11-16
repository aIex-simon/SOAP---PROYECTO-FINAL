<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorException;
use App\Models\Bm\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LicenseMiddleware
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
        if (!License::query()->where([
            ['tumisoft_product_id', '=', $request->route('license')],
            ['status', '=', License::ACTIVE]])->exists()
        ) {
            throw (new ErrorException('The License does not belong to the user.'))->withStatus(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
