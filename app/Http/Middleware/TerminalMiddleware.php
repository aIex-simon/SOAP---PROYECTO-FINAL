<?php

namespace App\Http\Middleware;

use App\Exceptions\ErrorException;
use App\Models\Bm\Terminal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TerminalMiddleware
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
        if (!Terminal::query()->where([
            ['id', '=', $request->route('terminal')],
            ['enterprise_id', '=', $request->route('enterprise')],
            ['status', '=', Terminal::ACTIVE]])->exists()
        ) {
            throw (new ErrorException('The terminal does not belong to the user.'))->withStatus(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
