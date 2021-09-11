<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationsController extends AccessTokenController
{
    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function store(ServerRequestInterface $request): Response
    {
        return $this->issueToken($request)->setStatusCode(201);
    }

    /**
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        auth('merchant-api')->user()->token()->revoke();
        return json_response(status_code: 204);
    }
}
