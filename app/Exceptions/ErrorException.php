<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ErrorException extends Exception
{
    /**
     * Any extra data to send with the response.
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * The status code to use for the response.
     *
     * @var integer
     */
    protected int $status = 422;

    /**
     * Create a new exception instance.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * render
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param $request
     *
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        Log::error(__FUNCTION__ . ': ' . $this->getMessage() . ', Track: ' . $this->getTraceAsString());

        return response()->json([
            'apiVersion' => config('bm.api_version'),
            'context' =>  strtolower($request->method()) . ': ' . $request->getRequestUri(),
            'error' => [
                'code' => $this->status,
                'message' => $this->getMessage(),
                'errors' => $this->errors
            ]
        ], $this->status);
    }

    /**
     * Set the extra $errors to send with the response.
     *
     * @param array $errors
     *
     * @return $this
     */
    public function withErrors(array $errors): static
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param int $status
     *
     * @return $this
     */
    public function withStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }
}
