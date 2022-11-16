<?php

namespace App\Services;

use App\Services\Contracts\CorsServiceContract;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsService implements CorsServiceContract
{
    /**
     * @var array
     */
    private array $allowOrigins = [];

    /**
     * @var array
     */
    private array $allowMethods = [];

    /**
     * @var array
     */
    private array $allowHeaders = [];

    /**
     * @var bool
     */
    private bool $allowCredentials = false;

    /**
     * @var array
     */
    private array $exposeHeaders = [];

    /**
     * @var int
     */
    private int $maxAge = 0;

    /**
     * constructor
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (isset($config['allow_origins'])) {
            $this->allowOrigins = $config['allow_origins'];
        }

        if (isset($config['allow_headers'])) {
            $this->setAllowHeaders($config['allow_headers']);
        }

        if (isset($config['allow_methods'])) {
            $this->setAllowMethods($config['allow_methods']);
        }

        if (isset($config['allow_credentials'])) {
            $this->allowCredentials = $config['allow_credentials'];
        }

        if (isset($config['expose_headers'])) {
            $this->setExposeHeaders($config['expose_headers']);
        }

        if (isset($config['max_age'])) {
            $this->setMaxAge($config['max_age']);
        }
    }

    /**
     * is cors request
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return bool
     */
    public function isCorsRequest(Request $request): bool
    {
        return $request->headers->has('Origin');
    }

    /**
     * is preflight request
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return bool
     */
    public function isPreflightRequest(Request $request): bool
    {
        return $this->isCorsRequest($request) &&
            $request->isMethod('OPTIONS') &&
            $request->headers->has('Access-Control-Request-Method');
    }

    /**
     * handle preflight request
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return Response
     */
    public function handlePreflightRequest(Request $request): Response
    {
        $response = new Response();

        // Do not set any headers if the origin is not allowed
        if ($this->isOriginAllowed($request->headers->get('Origin'))) {
            $response = $this->setAccessControlAllowOriginHeader($request, $response);

            if ($this->allowCredentials) {
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }

            if ($this->maxAge) {
                $response->headers->set('Access-Control-Max-Age', (string)$this->maxAge);
            }

            $allowMethods = $this->isAllMethodsAllowed()
                ? strtoupper($request->headers->get('Access-Control-Request-Method'))
                : implode(', ', $this->allowMethods);

            $response->headers->set('Access-Control-Allow-Methods', $allowMethods);

            $allowHeaders = $this->isAllHeadersAllowed()
                ? strtolower($request->headers->get('Access-Control-Request-Headers'))
                : implode(', ', $this->allowHeaders);

            $response->headers->set('Access-Control-Allow-Headers', $allowHeaders);
        }

        return $response;
    }

    /**
     * handle request
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handleRequest(Request $request, Response $response): Response
    {
        if ($this->isOriginAllowed($request->headers->get('Origin'))) {
            $response = $this->setAccessControlAllowOriginHeader($request, $response);

            // Set Vary unless all origins are allowed
            if (!$this->isAllOriginsAllowed()) {
                $vary = $request->headers->has('Vary') ? $request->headers->get('Vary') . ', Origin' : 'Origin';
                $response->headers->set('Vary', $vary);
            }

            if ($this->allowCredentials) {
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }

            if (!empty($this->exposeHeaders)) {
                $response->headers->set('Access-Control-Expose-Headers', implode(', ', $this->exposeHeaders));
            }
        }

        return $response;
    }

    /**
     * set access control allow origin header
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    protected function setAccessControlAllowOriginHeader(Request $request, Response $response): Response
    {
        $origin = $request->headers->get('Origin');

        if ($this->isAllOriginsAllowed()) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        } elseif ($this->isOriginAllowed($origin)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        return $response;
    }

    /**
     * is origin allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string|null $origin
     * @return bool
     */
    protected function isOriginAllowed(?string $origin): bool
    {
        if ($this->isAllOriginsAllowed()) {
            return true;
        }

        return Str::is($this->allowOrigins, $origin);
    }

    /**
     * is method allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string|null $method
     * @return bool
     */
    protected function isMethodAllowed(?string $method): bool
    {
        if ($this->isAllMethodsAllowed()) {
            return true;
        }

        return in_array(strtoupper($method), $this->allowMethods, true);
    }

    /**
     * is header allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string|null $header
     * @return bool
     */
    protected function isHeaderAllowed(?string $header): bool
    {
        if ($this->isAllHeadersAllowed()) {
            return true;
        }

        return in_array(strtolower($header), $this->allowHeaders, true);
    }

    /**
     * is all origins allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return bool
     */
    protected function isAllOriginsAllowed(): bool
    {
        return in_array('*', $this->allowOrigins, true);
    }

    /**
     * is all methods allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return bool
     */
    protected function isAllMethodsAllowed(): bool
    {
        return in_array('*', $this->allowMethods, true);
    }

    /**
     * is all headers allowed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return bool
     */
    protected function isAllHeadersAllowed(): bool
    {
        return in_array('*', $this->allowHeaders, true);
    }

    /**
     * set allow methods
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $allowMethods
     * @return $this
     */
    protected function setAllowMethods(array $allowMethods): self
    {
        $this->allowMethods = array_map('strtoupper', $allowMethods);

        return $this;
    }

    /**
     * set allow headers
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $allowHeaders
     * @return $this
     */
    protected function setAllowHeaders(array $allowHeaders): self
    {
        $this->allowHeaders = array_map('strtolower', $allowHeaders);

        return $this;
    }

    /**
     * set expose headers
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $exposeHeaders
     * @return $this
     */
    protected function setExposeHeaders(array $exposeHeaders): self
    {
        $this->exposeHeaders = array_map('strtolower', $exposeHeaders);

        return $this;
    }

    /**
     * set max age
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param int $maxAge
     * @return $this
     */
    protected function setMaxAge(int $maxAge): self
    {
        if ($maxAge < 0) {
            throw new InvalidArgumentException('Max age must be a positive number or zero.');
        }

        $this->maxAge = $maxAge;

        return $this;
    }
}
