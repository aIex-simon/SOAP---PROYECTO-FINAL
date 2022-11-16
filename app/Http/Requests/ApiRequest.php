<?php

namespace App\Http\Requests;

use App\Models\Bm\Enterprise;
use App\Models\Iam\User;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
    use ApiResponse;

    /**
     * get context
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return string
     */
    public function getContext(): string
    {
        return strtolower($this->method()) . ': ' . $this->getRequestUri();
    }

    /**
     * failed validation
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Validator $validator     *
     * @return void
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, $this->errorResponse());
    }

    /**
     * rules default
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get all inputs, files and params for the request.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  array  $keys
     * @return array
     */
    public function all($keys = null): array
    {
        $input = array_replace_recursive($this->input(), $this->allFiles());

        if (is_object($this->route()) && ($paramsRoute = $this->route()->parameters())) {
            $input = array_merge($paramsRoute, $input);
        }

        if (! $keys) {
            return $input;
        }

        $results = [];

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            Arr::set($results, $key, Arr::get($input, $key));
        }

        return $results;
    }

    /**
     * errorResponse default
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return JsonResponse|null
     */
    protected function errorResponse(): ?JsonResponse
    {
        return response()->json([
            'apiVersion' => $this->getApiVersion(),
            'context' =>  $this->getContext(),
            'error' => [
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $this->validator->errors()->messages()
            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * fail response
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param string $message
     * @param int $code
     * @param Validator|null $validatorError
     *
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
            'context' => $this->getContext(),
            'error' => $error
        ], $code);
    }

    /**
     * create token for user
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param User $user
     * @return array
     */
    public function createTokenForUser(User $user): array
    {
        $tokenResult = $user->createToken('Token ' . $user->getAttribute('email'));
        $token = $tokenResult->token;
        $token->setAttribute('expires_at', Carbon::now()->addYear());
        $expires = $token->getAttribute('expires_at');
        $token->save();

        return [
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($expires)->toDateTimeString()
        ];
    }

    /**
     * is registration completed
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param User $user
     * @return bool
     */
    public function isRegistrationCompleted(User $user): bool
    {
        if (!$user->account()->first()) {
            return false;
        }

        return (bool) $user->account()->first()->flag_initial_config;
    }

    /**
     * getValuesFromAccount
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param User $user
     * @return array
     */
    public function getValuesFromAccount(User $user): array
    {
        $result = [
            'accountId' => 0,
            'enterprises' => [],
        ];

        if (!$user->account()->first()) {
            return $result;
        }

        $result['accountId'] = $user->getAccountId();
        $result['enterprises'] = Enterprise::query()
            ->select(['id', 'name'])
            ->with(['terminals'])
            ->where([
                ['account_id', '=', $result['accountId']],
                ['status', '=', Enterprise::ACTIVE]
            ])
            ->get()
            ->toArray();

        return $result;
    }
}
