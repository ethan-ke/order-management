<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\MainController;
use Illuminate\Http\JsonResponse;

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
}
