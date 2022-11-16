<?php

namespace App\Traits;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait SendsPasswordResetEmails
{
    /**
     * Send a reset link to the given user.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $credentials = $this->credentials($request);
        $broker =  $this->broker();
        $response = $broker->sendResetLink($credentials);

        return $response === Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return new JsonResponse([
            'apiVersion' => config('bm.api_version'),
            'data' => [
                'message' => Lang::get('Password reset email sent')
            ],
        ]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @author Marco Torres, <mtorres@tumi-soft.com>
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return new JsonResponse(
            [
                'apiVersion' => config('bm.api_version'),
                'error' => Lang::get('Email could not be sent to this email address')
            ],
            Response::HTTP_NOT_ACCEPTABLE
        );
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
}
