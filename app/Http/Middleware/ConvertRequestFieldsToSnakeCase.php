<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ConvertRequestFieldsToSnakeCase
{
    /**
     * handle
     *
     * Handle an incoming request.
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->replace($this->convertRecursive($request->all()));

        return $next($request);
    }

    /**
     * convertRecursive
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $data
     *
     * @return array
     */
    private function convertRecursive(array $data): array
    {
        $replaced = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertRecursive($value);
            }
            $replaced[Str::snake($key)] = $value;
        }
        return $replaced;
    }
}
