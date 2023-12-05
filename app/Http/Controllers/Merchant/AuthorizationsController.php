<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\Merchant\AuthorizationRequest;
use App\Models\Merchant;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationsController extends AccessTokenController
{

    /**
     * @param ServerRequestInterface $request
     * @return Response|object
     * @throws AuthenticationException
     */
    public function store(ServerRequestInterface $request)
    {
        $credentials = $request->getParsedBody();
        $user = Merchant::where('username', $credentials['username'])->first();

        if (!$user->validatePassword($credentials['password'])) {
            throw new AuthenticationException();
        }
        $user->tokens()->delete();
        return $this->issueToken($request)->setStatusCode(201);
    }

    /**
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        Auth::guard('merchant-api')->user()->tokens()->delete();
        return json_response(status_code: 204);
    }
}
