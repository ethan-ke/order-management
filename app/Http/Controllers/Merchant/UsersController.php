<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers\Merchant
 */
class UsersController extends MainController
{
    /**
     * @return JsonResponse
     */
    public function mine(): JsonResponse
    {
        $user = $this->user();
        return json_response($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function password(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password' => 'required|string|min:8'
        ]);
        $user = $this->user()->update(['password' => \Hash::make($data['password'])]);
        return json_response($user);
    }
}
