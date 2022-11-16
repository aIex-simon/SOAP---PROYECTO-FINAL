<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;

trait ApiResponse
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var string
     */
    protected string $apiVersion;

    /**
     * setApiVersion
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * constructor
     */
    public function __construct()
    {
        $this->setApiVersion(config('bm.api_version'));
    }

    /**
     * getModel
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * setModel
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Model $model
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * getApiVersion
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * setApiVersion
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $apiVersion
     */
    public function setApiVersion(string $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * json response success
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param $message
     * @param array $body
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($message, array $body = [], int $code = 200): JsonResponse
    {
        $data = [
            'apiVersion' => $this->getApiVersion(),
            'context' =>  $this->getContext(),
        ];

        if (is_array($message)) {
            $data['data'] = array_merge($message, $body);
        } elseif (is_string($message)) {
            $data['message'] = __($message);

            if ($body) {
                $data['data'] = $body;
            }
        }

        return response()->json($this->convertArrayKeysToCamelCase($data), $code);
    }

    /**
     * errorResponseCustom
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $msg
     * @param int $code
     * @param array $details
     * @return JsonResponse
     */
    protected function errorResponseCustom(string $msg = '', int $code = Response::HTTP_BAD_REQUEST, array $details = []): JsonResponse
    {
        $message = __($msg);
        if (empty($message)) {
            Log::error('Mensaje sin Traducción: ' . $msg);
            app()->setLocale('en');
            $message = __($msg);
        }

        $res = [
            'apiVersion' => $this->getApiVersion(),
        ];

        if ($details) {
            $res['message'] = $message ?: __('An internal error has occurred, please try again later.');
            $res['details'] = $details;
        } else {
            $res['error'] = $message ?: __('An internal error has occurred, please try again later.');
        }

        return response()->json($res, $code);
    }

    /**
     * fail response
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $message
     * @param int $code
     * @param Validator|null $validatorError
     * @return JsonResponse
     */
    protected function failResponse(string $message, int $code, Validator $validatorError = null): JsonResponse
    {
        $error = [
            'code' => $code,
            'message' => __($message),
        ];

        if ($validatorError) {
            $error['errors'] = $validatorError->errors()->messages();
        }

        return response()->json([
            'apiVersion' => $this->getApiVersion(),
            'context' =>  strtolower($this->method())  . $this->getRequestUri(),
            'error' => $error
        ], $code);
    }

    /**
     * Muestra el listado basado en Google JSON Style Guide
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $columns
     * @param array $resources
     * @param array $where
     * @return JsonResponse
     */
    public function showAll(array $columns = ['*'], array $resources = [], array $where = []): JsonResponse
    {
        $size = (int) $this->get('size', 10);
        $page = (int) $this->get('page', 0);

        $entity = (new ReflectionClass($this->model))->getShortName();
        $query = $this->model::query();

        if (!empty($resources)) {
            $query = $query->with($resources);
        }

        if (!empty($where)) {
            $query = $query->where($where);
        }

        $paginator = $query->paginate($size, $columns, __('page'), $page);

        return $this->successResponse([
            'kind' => $entity,
            'totalItems' => $paginator->total(),
            'startIndex' => $paginator->currentPage(),
            'itemsPerPage' => $paginator->perPage(),
            'previousLink' => $paginator->previousPageUrl() ?? '',
            'nextLink' => $paginator->nextPageUrl() ?? '',
            'items' => $paginator->items()
        ]);
    }

    /**
     * Muestra el detalle basado en Google JSON Style Guide
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return JsonResponse
     */
    public function showOne(): JsonResponse
    {
        $entity = (new ReflectionClass($this->model))->getShortName();
        return $this->successResponse(array_merge(['kind' => $entity], $this->model->toArray()));
    }

    /**
     * success
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return JsonResponse
     */
    public function success(): JsonResponse
    {
        $verb = match ($this->method()) {
            'POST' => 'created',
            'PUT', 'PATCH' => 'updated',
            'DELETE' => 'deleted'
        };

        $entity = (new ReflectionClass($this->model))->getShortName();
        return $this->successResponse(
            __('The ' . $entity . ' was ' . $verb. ' successfully.'),
            array_merge(['kind' => $entity], $this->model->toArray())
        );
    }

    /**
     * convierte llaves de un arreglo en camelCase
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param array $array
     * @return array
     */
    public function convertArrayKeysToCamelCase(array $array): array
    {
        $replaced = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertArrayKeysToCamelCase($value);
            } elseif ($value instanceof Model) {
                $value = $this->convertArrayKeysToCamelCase($value->toArray());
            }

            $replaced[Str::camel($key)] = $value;
        }

        return $replaced;
    }

    /**
     * show list
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Builder $queryBuilder
     * @param array $columns
     * @return JsonResponse
     */
    public function showList(Builder $queryBuilder, array $columns): JsonResponse
    {
        $size = (int) $this->get('size', 10);
        $page = (int) $this->get('page', 0);
        $kindEntity = (new ReflectionClass($queryBuilder->getModel()))->getShortName();
        $paginator = $queryBuilder->paginate($size, $columns, __('page'), $page);

        return $this->successResponse([
            'kind' => $kindEntity,
            'totalItems' => $paginator->total(),
            'startIndex' => $paginator->currentPage(),
            'itemsPerPage' => $paginator->perPage(),
            'previousLink' => $paginator->previousPageUrl() ?? '',
            'nextLink' => $paginator->nextPageUrl() ?? '',
            'items' => $paginator->items()
        ]);
    }
}
