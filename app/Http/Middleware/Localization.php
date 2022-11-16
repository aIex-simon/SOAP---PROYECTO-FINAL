<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * handle
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request  $request
     * @param Closure $next
     *
     * @return Response|RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        $local = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'en';
        app()->setLocale($local);

        return $next($request);
    }
}
