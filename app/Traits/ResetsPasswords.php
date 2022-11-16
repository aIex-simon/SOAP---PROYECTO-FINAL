<?php

namespace App\Traits;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

trait ResetsPasswords
{
    /**
     * Reset the given user's password.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @author Marco Torres, <mtorres@tumi-soft.com>
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response === Password::INVALID_TOKEN) {
            return $this->sendResetFailedResponse($request, _('El token no es válido.'));
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response === Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, Lang::get('El password ha sido cambiado satisfactoriamente.'))
            : $this->sendResetFailedResponse($request, Lang::get('No se pudo actualizar el password.'));
    }

    /**
     * Get the password reset validation rules.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            //'password' => 'required|confirmed|min:6',
            'password' => 'required_with:password_confirmation|same:password_confirmation|string|min:6',
            'password_confirmation' => 'required|string|min:6'
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Set the user's password.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param  CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request, $response): JsonResponse|RedirectResponse
    {
        return new JsonResponse([
            'apiVersion' => config('bm.api_version'),
            'data' => [
                'message' => trans($response),
            ]
        ]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param  string  $response
     * @return JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return new JsonResponse([
            'apiVersion' => config('bm.api_version'),
            'error' => trans($response)
        ], 500);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
