<?php

namespace App\Http\Middleware;

use App\Services\CorsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * @var CorsService
     */
    private CorsService $service;

    /**
     * constructor
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param CorsService $service
     */
    public function __construct(CorsService $service)
    {
        $this->service = $service;
    }

    /**
     * handle
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$this->service->isCorsRequest($request)) {
            return $next($request);
        }

        if ($this->service->isPreflightRequest($request)) {
            return $this->service->handlePreflightRequest($request);
        }

        return $this->service->handleRequest($request, $next($request));
    }
}
