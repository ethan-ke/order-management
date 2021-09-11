<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Merchant;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @var int $perPage Per Page.
     */
    public int $perPage;

    /**
     * @var string $guard
     */
    public string $guard;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->guard = $request->get('guard') ?? '';
        Auth()->shouldUse($this->guard);
        $this->perPage = $request->limit ?? 15;
    }

    /**
     * @return Authenticatable|Merchant|Admin
     */
    public function user(): Merchant|Admin|Authenticatable
    {
        return Auth::user();
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60,
        ]);
    }
}
