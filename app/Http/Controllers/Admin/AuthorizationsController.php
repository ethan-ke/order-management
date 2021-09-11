<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;
use App\Http\Requests\Admin\AuthorizationRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Auth;

class AuthorizationsController extends MainController
{
    /**
     * @param AuthorizationRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function store(AuthorizationRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (!$token = Auth::attempt($credentials)) {
            throw new AuthenticationException(trans('auth.failed'));
        }
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function update()
    {
        $token = Auth::refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        Auth::logout();
        return response(null, 204);
    }
}
